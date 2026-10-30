<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->string('tujuan_rujuk')->nullable()->after('catatan_admin');
            $table->text('alamat_tujuan')->nullable()->after('tujuan_rujuk');
        });
    }

    public function down()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn(['tujuan_rujuk', 'alamat_tujuan']);
        });
    }
};
