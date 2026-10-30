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
        // Tambahkan kolom advokat_id setelah user_id
        // nullable() artinya boleh kosong saat klien pertama kali daftar
        $table->unsignedBigInteger('advokat_id')->nullable()->after('user_id');

        // Opsional: Bikin relasi resmi ke tabel users
        $table->foreign('advokat_id')->references('id')->on('users')->onDelete('set null');
    });
}

public function down(): void
{
    Schema::table('clients', function (Blueprint $table) {
        // Untuk hapus foreign key dan kolomnya jika migration di-rollback
        $table->dropForeign(['advokat_id']);
        $table->dropColumn('advokat_id');
    });
}

};
