<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('peminjamans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->text('tujuan_peminjaman'); 
            $table->string('nama_kegiatan');
            $table->string('lokasi_penggunaan'); 
            $table->dateTime('tanggal_mulai'); 
            $table->dateTime('tanggal_selesai'); 
            $table->string('dokumen_pendukung')->nullable();
            
            $table->enum('status_pengelola', ['Pending', 'Disetujui', 'Ditolak'])->default('Pending');
            $table->enum('status_persetujuan', ['Pending', 'Disetujui', 'Ditolak'])->default('Pending');
            $table->dateTime('batas_waktu_approval'); 
            
            $table->enum('status_keseluruhan', [
                'Menunggu Persetujuan', 
                'Disetujui', 
                'Ditolak', 
                'Aset Diserahkan', 
                'Sedang Digunakan', 
                'Menunggu Konfirmasi Pengembalian', 
                'Terlambat', 
                'Selesai'
            ])->default('Menunggu Persetujuan');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjamans');
    }
};