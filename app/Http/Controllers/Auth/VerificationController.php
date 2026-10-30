<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Exception;

class VerificationController extends Controller
{
    public function showForm()
    {
        if (!Session::has('verifying_user_id')) {
            return redirect()->route('register');
        }
        return view('auth.verify-otp');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $userId = Session::get('verifying_user_id');
        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('register')->withErrors(['otp' => 'Sesi kadaluarsa.']);
        }

        // 1. CEK KADALUARSA
        if ($user->otp_expires_at && now()->greaterThan($user->otp_expires_at)) {
            return back()->withErrors(['otp' => 'Kode OTP sudah kadaluarsa! Silakan daftar ulang.']);
        }

        // 2. CEK KECOCOKAN KODE
        if ($user->otp_code == $request->otp) {
            $updateData = ['otp_code' => null, 'otp_expires_at' => null];

            if (!empty($user->email) && empty($user->phone)) {
                $updateData['email_verified_at'] = now();
            } else {
                $updateData['phone_verified_at'] = now();
            }

            $user->update($updateData);

            // --- TAMBAHAN: NOTIFIKASI KE ADMIN ---
            try {
                // Pastikan format nomor diawali 62 (contoh: 62812...)
                $adminPhone = '6281234567890';
                $msg = "🔔 Klien Baru Terverifikasi!\nNama: {$user->name}\nHP: {$user->phone}\nStatus: Menunggu tindakan admin.";

                WhatsAppService::send($adminPhone, $msg);
            } catch (Exception $e) {
                Log::error("Gagal kirim WA ke admin: " . $e->getMessage());
            }
            // ------------------------------------

            Session::forget('verifying_user_id');
            Auth::login($user);

            return redirect()->route('login')->with('status', 'Verifikasi berhasil!');
        }

        return back()->withErrors(['otp' => 'Kode OTP yang Anda masukkan salah.']);
    }
}
