<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NotifikasiAdmin extends Notification
{
    use Queueable;

    // Tambahkan property jika ingin membawa data pesan dinamis
    public $pesan;
    public $url;

    public function __construct($pesan, $url)
    {
        $this->pesan = $pesan;
        $this->url = $url;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        // UBAH DARI ['mail'] MENJADI ['database']
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     * Ini adalah data yang akan disimpan di tabel 'notifications' kolom 'data'
     */
    public function toArray(object $notifiable): array
    {
        return [
            'pesan' => $this->pesan,
            'url'   => $this->url,
        ];
    }
}
