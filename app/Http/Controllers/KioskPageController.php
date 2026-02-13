<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class KioskPageController extends Controller
{
    /**
     * Display the kiosk page.
     * This page starts with a Welcome/Admin Login screen.
     */
    public function index()
    {
        return view('kiosk.index');
    }

    /**
     * Specialized Admin Login for Kiosk Mode.
     */
    public function adminLogin(Request $request)
    {
        try {
            $request->validate([
                'qr_code' => 'nullable|string',
                'id_pengenal_siswa' => 'nullable|string', // Support Email or ID
                'pin' => 'nullable|string',
            ]);

            $user = null;

            // 1. Check QR Code for Admin
            if ($request->qr_code) {
                $user = User::verifyQrSignature($request->qr_code);
            } 
            // 2. Check Manual Login for Admin
            elseif ($request->id_pengenal_siswa && $request->pin) {
                $user = \App\Models\User::where(function($query) use ($request) {
                            $query->where('id_pengenal_siswa', $request->id_pengenal_siswa)
                                  ->orWhere('email', $request->id_pengenal_siswa);
                        })->first();

                if ($user && $user->pin !== $request->pin) {
                    $user = null;
                }
            }

            if (!$user || $user->role !== 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya admin yang dapat mengaktifkan mode kiosk.',
                ], 403);
            }

            // Optional: Start a real Laravel session if needed, 
            // but for a kiosk, we can just return success and let the frontend handle the state.
            // If they are already an admin in another tab, this just confirms it for the kiosk view.
            
            return response()->json([
                'success' => true,
                'admin' => [
                    'name' => $user->name,
                ],
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
