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
        Schema::table('extrakulikulers', function (Blueprint $table) {
           $table->string('hari')->after('ekstrakulikuler')->nullable();
           $table->time('jam_mulai')->after('ekstrakulikuler')->nullable();
           $table->time('jam_selesai')->after('ekstrakulikuler')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('extrakulikulers', function (Blueprint $table) {
            $table->dropColumn('hari');
            $table->dropColumn('jam_mulai');
            $table->dropColumn('jam_selesai');
        });
    }
};
