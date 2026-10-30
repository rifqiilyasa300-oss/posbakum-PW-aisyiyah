<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    // Tambahkan ini agar bisa simpan data lewat Controller
    protected $fillable = ['title', 'image'];
}
