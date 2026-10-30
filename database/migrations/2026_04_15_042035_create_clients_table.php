<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Jalankan perintah: php artisan migrate:fresh jika ingin mereset tabel
     */
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();

            // Kolom Relasi & Identitas Sistem
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('reg_code')->unique(); // Contoh: PBK-2026-001

            // Biodata Klien
            $table->string('nama');
            $table->string('nik');
            $table->string('email')->nullable();
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['LAKI-LAKI', 'PEREMPUAN']);
            $table->text('alamat_ktp');
            $table->text('alamat_domisili');
            $table->string('phone');
            $table->string('status_perkawinan');
            $table->string('agama');
            $table->string('pendidikan');
            $table->string('pekerjaan');

            // Konten Perkara (Masalah Hukum)
            $table->text('uraian_masalah');
            $table->text('harapan_klien');

            // Manajemen Layanan & Berkas (Kategori PRODEO/UMUM)
            $table->enum('kategori_layanan', ['UMUM', 'PRODEO'])->default('UMUM');
            $table->string('ktp_path')->nullable(); // Foto KTP
            $table->string('sktm_path')->nullable(); // Wajib jika Prodeo (Gratis)
            $table->string('bukti_bayar_path')->nullable(); // Bukti transfer jika Umum (Berbayar)

            // Status Tracking Progres
            $table->string('status')->default('Menunggu Verifikasi');
            $table->text('catatan_admin')->nullable(); // <-- BARIS BARU DITAMBAHKAN DI SINI
            $table->string('tahapan')->default('Verifikasi Berkas');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
