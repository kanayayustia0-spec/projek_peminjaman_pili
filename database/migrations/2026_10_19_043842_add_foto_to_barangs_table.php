<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('barangs', function (Blueprint $table) {
            // Menambahkan kolom foto setelah kolom deskripsi
            $table->string('foto')->nullable()->after('deskripsi');
        });
    }

    public function down(): void
    {
        Schema::table('barangs', function (Blueprint $table) {
            // Menghapus kolom foto jika migrasi dibatalkan
            $table->dropColumn('foto');
        });
    }
};