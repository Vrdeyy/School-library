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
        $request->validate([
            'qr_code' => 'nullable|string',
            'nis' => 'nullable|string',
            'pin' => 'nullable|string',
        ]);

        $user = null;

        // 1. Check QR Code (signed or raw NIS for backward compatibility)
        if ($request->qr_code) {
            $user = User::verifyQrSignature($request->qr_code);
        } 
        // 2. Check Manual Login (NIS/Email + PIN)
        elseif ($request->nis && $request->pin) {
            $user = User::where(function($query) use ($request) {
                        $query->where('nis', $request->nis)
                              ->orWhere('email', $request->nis);
                    })->first();

            // Note: Since User model uses 'password' for hashed pass, 
            // but we have a 'pin' field. Let's check how PIN is stored.
            // If it's the password field, use Hash::check. 
            // If it's a separate 'pin' field, we'll check that.
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

        // Create Sanctum Token for the session
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
    }

    /**
     * Borrow Book via QR
     */
    public function borrow(Request $request)
    {
        $request->validate(['book_qr' => 'required|string']);

        $user = auth()->user();

        if (!$user->canBorrow()) {
            return response()->json([
                'success' => false,
                'message' => 'Borrow limit reached or account suspended.',
            ], 403);
        }

        // Verify Book QR (Signed) or Raw Code
        $bookItem = BookItem::verifyQrSignature($request->book_qr);
        
        if (!$bookItem) {
            // Fallback: Check raw code
            $bookItem = BookItem::where('code', $request->book_qr)->first();
        }

        if (!$bookItem) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid QR Code or Book Code not found.',
            ], 400);
        }

        if ($bookItem->status !== 'available') {
            return response()->json([
                'success' => false,
                'message' => 'Book is currently ' . $bookItem->status,
            ], 400);
        }

        try {
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
                'message' => 'Borrow request submitted. Please ask Admin to approve.',
            ]);

        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'success' => false,
                'message' => 'System error.',
            ], 500);
        }
    }

    /**
     * Return Book via QR
     */
    public function returnBook(Request $request)
    {
        $request->validate(['book_qr' => 'required|string']);

        $user = auth()->user();

        // Verify Book QR
        $bookItem = BookItem::verifyQrSignature($request->book_qr);

        if (!$bookItem) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid QR Code signature.',
            ], 400);
        }

        // Find active borrow for this book belongs to this user
        $borrow = Borrow::where('book_item_id', $bookItem->id)
            ->where('user_id', $user->id)
            ->whereIn('status', ['approved', 'borrowed'])
            ->first();

        if (!$borrow) {
            return response()->json([
                'success' => false,
                'message' => 'No active borrow record found for this book and user.',
            ], 404);
        }

        try {
            DB::transaction(function () use ($borrow, $bookItem, $user, $request) {
                // Set to 'returning' - pending admin approval
                $borrow->update([
                    'status' => 'returning', 
                ]);
                
                // Set to maintenance until admin approves
                $bookItem->update(['status' => 'maintenance']);

                // AUDIT LOG
                AuditLog::create([
                    'user_id' => $user->id,
                    'action' => 'kiosk_return_request',
                    'details' => 'Item Code: ' . $bookItem->code . ' - Pending admin approval',
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Pengembalian tercatat! Silahkan tunggu admin approve dan letakkan buku di tempat pengembalian.',
            ]);

        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'success' => false,
                'message' => 'System error.',
            ], 500);
        }
    }
}
