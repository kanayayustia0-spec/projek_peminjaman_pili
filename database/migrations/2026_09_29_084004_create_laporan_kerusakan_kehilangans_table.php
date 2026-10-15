<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('laporan_kerusakan_kehilangans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peminjaman_id')->constrained('peminjamans')->cascadeOnDelete();
            $table->foreignId('barang_id')->constrained('barangs')->cascadeOnDelete();
            $table->dateTime('tanggal_kejadian');
            $table->string('lokasi');
            $table->enum('jenis_kejadian', ['Kerusakan', 'Kehilangan', 'Force Majeure']);
            $table->text('kronologi');
            $table->enum('penyebab', [
                'Pemakaian Normal', 
                'Kelalaian', 
                'Pihak Ketiga', 
                'Force Majeure', 
                'Belum Diketahui'
            ]);
            $table->json('bukti_pendukung')->nullable(); 
            $table->text('keputusan_admin_persetujuan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_kerusakan_kehilangans');
    }
};