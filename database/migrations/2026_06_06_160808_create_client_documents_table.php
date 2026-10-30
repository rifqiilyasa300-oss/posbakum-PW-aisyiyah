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
        Schema::create('client_documents', function (Blueprint $table) {
            $table->id();
            // Menghubungkan ke tabel clients
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            // Menyimpan nama file unik (misal: 1717654321_xYzA1_ijazah.pdf)
            $table->string('file_path');
            // Menyimpan nama asli file (misal: ijazah.pdf)
            $table->string('file_name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_documents');
    }
};
