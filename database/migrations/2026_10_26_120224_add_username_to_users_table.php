<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tambahkan kolom tanpa aturan unique dan dibuat nullable (boleh kosong)
        // Ini dijamin 100% aman untuk data yang sudah ada
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'username')) {
                $table->string('username')->nullable()->after('id');
            }
        });

        // 2. Isi otomatis username untuk data lama yang masih kosong berdasarkan ID-nya
        $users = DB::table('users')->whereNull('username')->orWhere('username', '')->get();
        foreach ($users as $user) {
            DB::table('users')->where('id', $user->id)->update([
                'username' => 'user_' . $user->id
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('username');
        });
    }
};