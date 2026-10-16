<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
{
    Schema::create('pengembalians', function (Blueprint $table) {
        $table->id();
        $table->foreignId('peminjaman_id')->constrained('peminjamans')->cascadeOnDelete();
        
        // 1. Bagian dari panduan (Diisi oleh Peminjam)
        $table->dateTime('tanggal_pengembalian')->nullable(); 
        $table->string('foto_kondisi_akhir')->nullable(); 
        $table->text('catatan_kondisi_peminjam')->nullable();

        // 2. Bagian sistem asli Anda (Diisi oleh Admin)
        $table->json('checklist_kondisi_akhir')->nullable();
        $table->json('kelengkapan_kembali')->nullable();
        $table->enum('hasil_pemeriksaan', [
            'Sesuai', 
            'Ada Kerusakan', 
            'Ada Kehilangan', 
            'Perlu Tindak Lanjut'
        ])->nullable();

        $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('pengembalians');
    }
};