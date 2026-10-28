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
        Schema::table('bastas', function (Blueprint $table) {
            $table->longText('ttd_digital_pengelola')->change();
            $table->longText('ttd_digital_peminjam')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bastas', function (Blueprint $table) {
            $table->string('ttd_digital_pengelola')->change();
            $table->string('ttd_digital_peminjam')->change();
        });
    }
};
