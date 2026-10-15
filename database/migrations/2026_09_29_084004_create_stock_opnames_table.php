<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('stock_opnames', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('lokasi');
            $table->string('tim_pemeriksa');
            $table->json('detail_hasil'); 
            $table->integer('jumlah_terdaftar');
            $table->integer('jumlah_ditemukan');
            $table->integer('jumlah_rusak');
            $table->integer('jumlah_hilang');
            $table->text('rekomendasi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_opnames');
    }
};