<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            // Tambahkan 2 kolom baru di bagian paling bawah
            $table->string('nama_penandatangan')->nullable();
            $table->string('nip_penandatangan')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            // Jika ingin membatalkan, hapus kolom ini
            $table->dropColumn(['nama_penandatangan', 'nip_penandatangan']);
        });
    }
};
