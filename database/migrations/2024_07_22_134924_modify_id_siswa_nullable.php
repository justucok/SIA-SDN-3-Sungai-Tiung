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
        Schema::table('raport_siswas', function (Blueprint $table) {
            $table->dropForeign(['id_siswa']);
                  
            $table->unsignedBigInteger('id_siswa')->nullable()->change();
                   
            $table->foreign('id_siswa')->references('id')->on('tbl_data_siswa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('raport_siswas', function (Blueprint $table) {
            $table->dropForeign(['id_siswa']);
                  
            $table->unsignedBigInteger('id_siswa')->nullable(false)->change();
                   
            $table->foreign('id_siswa')->references('id')->on('tbl_data_siswa');
        });
    }
};