<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('daftar_poli', function (Blueprint $table) {
            // Menambahkan status_periksa dengan default 'belum_diperiksa'
            $table->enum('status_periksa', ['belum_diperiksa', 'sudah_diperiksa'])->default('belum_diperiksa')->after('no_antrian');
        });
    }

    public function down(): void
    {
        Schema::table('daftar_poli', function (Blueprint $table) {
            $table->dropColumn('status_periksa');
        });
    }
};