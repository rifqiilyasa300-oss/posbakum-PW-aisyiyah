<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',              // Untuk nomor WA
        'keahlian',           // Untuk keahlian advokat
        'otp_code',           // WAJIB DITAMBAHKAN
        'otp_expires_at',
        'payment_status',
        'payment_proof',
        'email_verified_at',
        'phone_verified_at',  // WAJIB DITAMBAHKAN
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'phone_verified_at' => 'datetime',
            'otp_expires_at'    => 'datetime', // Casting agar jadi objek Carbon (tanggal)
            'password' => 'hashed',
        ];
    }

    /**
     * RELASI: Menghubungkan Advokat dengan kasus yang ditanganinya.
     */
    public function cases()
    {
        return $this->hasMany(Client::class, 'advokat_id');
    }

    /**
     * Check Role
     */
    public function hasRole($role)
    {
        return $this->role === $role;
    }
}
