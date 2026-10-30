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
        Schema::create('incomes', function (Blueprint $table) {
            $table->id();
            // Menambahkan foreign key ke tabel clients
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            // Kolom untuk nominal uang
            $table->decimal('amount', 15, 2);
            // Kolom untuk keterangan pemasukan
            $table->string('description');
            // Kolom tanggal transaksi
            $table->date('date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incomes');
    }
};
