<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('consultation_reports', function (Blueprint $table) {
            $table->id();
            // Menghubungkan ke tabel clients
            $table->foreignId('client_id')->constrained()->onDelete('cascade');

            // Header & Info Dasar Dokumen
            $table->string('no_dokumen')->nullable(); // No. Dok
            $table->integer('revisi')->default(0);    // Revisi
            $table->string('no_reg')->nullable();    // No. Reg (dari klien)
            $table->string('nama_klien')->nullable(); // Nama Klien (dari klien)

            // Info Pertemuan
            $table->date('tanggal');                  // Tanggal Pertemuan
            $table->time('waktu');                    // Waktu
            $table->string('tempat');                 // Tempat
            $table->integer('pertemuan_ke')->default(1); // Pertemuan ke
            $table->string('kasus')->nullable();      // Kolom Kasus (Textarea besar)

            // Tim Hukum
            $table->string('advokat_nama')->nullable(); // Advokat
            $table->enum('konsultan_tipe', ['Pusat', 'Daerah', 'Cabang'])->nullable();
            $table->string('paralegal_nama')->nullable(); // Isian nama cabang/daerah
            $table->string('supervisor_nama')->nullable(); // Supervisor
            $table->string('koordinator_nama')->nullable(); // Koordinator Hukum

            // Isi Laporan (Semua Textarea)
            $table->text('permasalahan_klien')->nullable();
            $table->text('harapan_klien')->nullable();
            $table->text('legal_advice')->nullable();
            $table->text('rencana_tindak_lanjut')->nullable();
            $table->text('catatan_supervisi')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('consultation_reports');
    }
};
