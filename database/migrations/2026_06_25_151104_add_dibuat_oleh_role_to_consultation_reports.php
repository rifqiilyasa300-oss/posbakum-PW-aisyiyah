<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('consultation_reports', function (Blueprint $table) {
            $table->string('dibuat_oleh_role', 30)->nullable()->after('catatan_supervisi');
        });
    }

    public function down()
    {
        Schema::table('consultation_reports', function (Blueprint $table) {
            $table->dropColumn('dibuat_oleh_role');
        });
    }
};
