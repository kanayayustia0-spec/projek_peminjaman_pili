<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('barangs', function (Blueprint $table) {
            $table->id();
            $table->string('kode_inventaris')->unique();
            $table->string('qr_code')->nullable();
            $table->string('nama');
            $table->text('deskripsi'); 
            $table->string('merek_spesifikasi')->nullable();
            $table->string('no_seri')->nullable();
            $table->string('kategori');
            $table->year('tahun_perolehan')->nullable();
            $table->string('sumber_dana')->nullable();
            $table->string('gambar')->nullable();
            $table->integer('jumlah_total');
            $table->integer('jumlah_tersedia');
            $table->enum('kondisi', ['Baik', 'Rusak Ringan', 'Rusak Berat']);
            $table->string('lokasi_penyimpanan');
            $table->foreignId('pic_saat_ini')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['Tersedia', 'Dipinjam', 'Rusak', 'Hilang'])->default('Tersedia');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barangs');
    }
};