<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    use HasFactory;

    /**
     * Kolom yang dapat diisi secara massal (Mass Assignment).
     */
    protected $fillable = [
        'client_id',
        'amount',
        'description',
        'date',
    ];

    /**
     * RELASI: Setiap pemasukan dimiliki oleh satu klien.
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
