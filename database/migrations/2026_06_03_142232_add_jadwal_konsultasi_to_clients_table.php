<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            // Menambahkan kolom jadwal_konsultasi
            // nullable() digunakan agar tidak error jika klien belum memiliki jadwal
            $table->timestamp('jadwal_konsultasi')->nullable()->after('tahapan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            // Menghapus kolom jika migrasi di-rollback
            $table->dropColumn('jadwal_konsultasi');
        });
    }
};
