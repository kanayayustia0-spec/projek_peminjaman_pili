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
            $table->dateTime('tanggal_pengembalian');
            $table->json('checklist_kondisi_akhir');
            $table->json('kelengkapan_kembali');
            $table->string('foto_kondisi_akhir');
            $table->text('catatan_kondisi_peminjam');
            
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