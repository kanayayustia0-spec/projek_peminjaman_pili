<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bastas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peminjaman_id')->constrained('peminjamans')->cascadeOnDelete();
            $table->date('tanggal_serah');
            $table->time('jam_serah');
            $table->string('lokasi_serah');
            $table->json('checklist_kondisi_awal');
            $table->json('kelengkapan');
            $table->string('foto_kondisi_awal');
            
            $table->longText('ttd_digital_pengelola');
            $table->boolean('konfirmasi_checkbox_pengelola')->default(false);
            $table->longText('ttd_digital_peminjam');
            $table->boolean('konfirmasi_checkbox_peminjam')->default(false);
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bastas');
    }
};