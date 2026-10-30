<?php

namespace App\Observers;

use App\Models\Client;
use App\Notifications\NotifikasiAdmin;

class ClientObserver
{
    /**
     * Handle the Client "updated" event.
     */
    public function updated(Client $client)
    {
        // Mengecek apakah kolom 'status' berubah
        if ($client->isDirty('status')) {
            // Mengirim notifikasi ke user pemilik perkara
            $client->user->notify(new NotifikasiAdmin(
                'Status perkara Anda berubah menjadi: ' . $client->status,
                url('/dashboard') // Link saat diklik
            ));
        }
    }
}
