<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;

class AdminVerificationController extends Controller
{
    public function verify($id)
    {
        // 1. Cari user
        $user = User::findOrFail($id);

        // 2. Update status verifikasi
        $user->update(['phone_verified_at' => now()]);

        // 3. Kirim pesan konfirmasi ke user
        WhatsAppService::send($user->phone, "Halo {$user->name}, pendaftaran Anda di Posbakum telah berhasil diverifikasi.");

        // 4. Feedback ke layar Admin
        return "<h1>Verifikasi Berhasil!</h1><p>Status user {$user->name} sudah diaktifkan.</p>";
    }
}
