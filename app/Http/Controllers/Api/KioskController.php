<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\BookItem;
use App\Models\Borrow;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class KioskController extends Controller
{
    /**
     * Kiosk Login
     * Supports both QR (nis only) and Manual (nis + pin)
     */
    public function login(Request $request)
    {
        try {
            Log::info('Kiosk Login Attempt', $request->all());
            
            $request->validate([
                'qr_code' => 'nullable|string',
                'id_pengenal_siswa' => 'nullable|string',
                'pin' => 'nullable|string',
            ]);

            $user = null;

            // 1. Check QR Code
            if ($request->qr_code) {
                $user = User::verifyQrSignature($request->qr_code);
            } 
            // 2. Check Manual Login
            elseif ($request->id_pengenal_siswa && $request->pin) {
                $user = \App\Models\User::where(function($query) use ($request) {
                            $query->where('id_pengenal_siswa', $request->id_pengenal_siswa)
                                  ->orWhere('email', $request->id_pengenal_siswa);
                        })->first();

                if ($user && $user->pin !== $request->pin) {
                    $user = null;
                }
            }

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found or invalid credentials.',
                ], 404);
            }

            if ($user->is_suspended) {
                return response()->json([
                    'success' => false,
                    'message' => 'User is suspended.',
                ], 403);
            }

            // Create Sanctum Token
            $token = $user->createToken('kiosk-session')->plainTextToken;

            // AUDIT LOG
            AuditLog::create([
                'user_id' => $user->id,
                'action' => 'kiosk_login',
                'details' => 'Login via ' . ($request->qr_code ? 'QR' : 'Manual'),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json([
                'success' => true,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'token' => $token,
                ],
            ]);
        } catch (\Throwable $e) {
            Log::error('Kiosk Login Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Server Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Borrow Book via QR
     */
    public function borrow(Request $request)
    {
        try {
            Log::info('Kiosk Borrow Attempt', ['user' => auth()->id(), 'data' => $request->all()]);
            $request->validate(['book_qr' => 'required|string']);

            $user = auth()->user();

            if (!$user->canBorrow()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Batas peminjaman tercapai atau akun ditangguhkan.',
                ], 403);
            }

            // Verify Book QR (Signed) or Raw Code
            $bookItem = BookItem::verifyQrSignature($request->book_qr);
            if (!$bookItem) {
                $bookItem = BookItem::where('code', $request->book_qr)->first();
            }

            if (!$bookItem) {
                // Check if it's actually a member card QR (better error message)
                if (User::verifyQrSignature($request->book_qr)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Ini adalah kartu member. Silahkan scan QR yang ada di buku.',
                    ], 400);
                }

                return response()->json([
                    'success' => false,
                    'message' => 'Kode buku tidak valid atau tidak ditemukan.',
                ], 400);
            }

            if ($bookItem->status !== 'available') {
                return response()->json([
                    'success' => false,
                    'message' => 'Buku sedang ' . $bookItem->status,
                ], 400);
            }

            DB::transaction(function () use ($user, $bookItem, $request) {
                // Create Pending Borrow
                Borrow::create([
                    'user_id' => $user->id,
                    'book_item_id' => $bookItem->id,
                    'status' => 'pending',
                ]);

                // AUDIT LOG
                AuditLog::create([
                    'user_id' => $user->id,
                    'action' => 'kiosk_borrow_request',
                    'details' => 'Item Code: ' . $bookItem->code,
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Permintaan peminjaman berhasil dibuat. Silahkan bawa buku ke pustakawan.',
            ]);
        } catch (\Throwable $e) {
            Log::error('Kiosk Borrow Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Server Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Return Book via QR
     */
    public function returnBook(Request $request)
    {
        try {
            Log::info('Kiosk Return Attempt', ['user' => auth()->id(), 'data' => $request->all()]);
            $request->validate(['book_qr' => 'required|string']);

            $user = auth()->user();

            // Verify Book QR (Signed) or Raw Code
            $bookItem = BookItem::verifyQrSignature($request->book_qr);
            if (!$bookItem) {
                $bookItem = BookItem::where('code', $request->book_qr)->first();
            }

            if (!$bookItem) {
                // Check if it's actually a member card QR (better error message)
                if (User::verifyQrSignature($request->book_qr)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Ini adalah kartu member. Silahkan scan QR yang ada di buku.',
                    ], 400);
                }

                return response()->json([
                    'success' => false,
                    'message' => 'Kode buku tidak valid atau tidak ditemukan.',
                ], 400);
            }

            // Find Active Borrow
            $borrow = Borrow::where('book_item_id', $bookItem->id)
                ->where('user_id', $user->id)
                ->whereIn('status', ['approved', 'pending'])
                ->first();

            if (!$borrow) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kamu tidak memiliki pinjaman aktif untuk buku ini.',
                ], 400);
            }

            DB::transaction(function () use ($user, $bookItem, $borrow, $request) {
                // Mark for return (pending approval)
                $borrow->update(['status' => 'returning']);
                $bookItem->update(['status' => 'returning']);

                // AUDIT LOG
                AuditLog::create([
                    'user_id' => $user->id,
                    'action' => 'kiosk_return_request',
                    'details' => 'Item Code: ' . $bookItem->code,
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Permintaan pengembalian dikirim. Silahkan taruh buku di loket pengembalian.',
            ]);
        } catch (\Throwable $e) {
            Log::error('Kiosk Return Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Server Error: ' . $e->getMessage()], 500);
        }
    }
}
