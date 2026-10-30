<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class WhatsAppService
{
    /**
     * Mengirim pesan via API Fonnte
     *
     * @param string $target
     * @param string $message
     * @return mixed
     */
    public static function send($target, $message)
    {
        // Mengambil konfigurasi dari file .env
        $apiKey = env('FONNTE_TOKEN');
        $apiUrl = env('FONNTE_URL');

        // Optimasi: Pastikan nomor dimulai dengan 62
        $target = trim($target);

        // Jika diawali 0, ganti jadi 62
        if (strpos($target, '0') === 0) {
            $target = '62' . substr($target, 1);
        }
        // Jika diawali 8 (langsung angka 8), tambahkan 62
        elseif (strpos($target, '8') === 0) {
            $target = '62' . $target;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $apiKey,
            ])->post($apiUrl, [
                'target'  => $target,
                'message' => $message,
            ]);

            // Cek respon API
            if ($response->successful()) {
                Log::info("WA Terkirim ke " . $target . ": " . $response->body());
            } else {
                // Menangkap error dari sisi server Fonnte
                Log::error("Fonnte API Error: " . $response->body());
            }

            return $response->json();

        } catch (Exception $e) {
            Log::error("Exception pada WhatsAppService: " . $e->getMessage());
            return false;
        }
    }
}
