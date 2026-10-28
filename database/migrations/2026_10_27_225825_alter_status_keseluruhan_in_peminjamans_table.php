<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE `peminjamans` MODIFY COLUMN `status_keseluruhan` VARCHAR(50) NOT NULL DEFAULT 'Menunggu Persetujuan'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE `peminjamans` MODIFY COLUMN `status_keseluruhan` ENUM('Menunggu Persetujuan','Disetujui','Ditolak','Aset Diserahkan','Sedang Digunakan','Menunggu Konfirmasi Pengembalian','Terlambat','Selesai') NOT NULL DEFAULT 'Menunggu Persetujuan'");
    }
};
