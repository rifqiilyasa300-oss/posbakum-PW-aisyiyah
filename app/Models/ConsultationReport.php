<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsultationReport extends Model
{
    use HasFactory;

    protected $guarded = []; // Mengizinkan semua kolom diisi

    /**
     * Relasi ke Klien (Setiap laporan milik satu klien)
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Relasi ke User (Siapa yang membuat laporan ini: Admin atau Advokat)
     * Tambahan ini penting agar Anda bisa menampilkan nama pembuat laporan di dashboard
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
