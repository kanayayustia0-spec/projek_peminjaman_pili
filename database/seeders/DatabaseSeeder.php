<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin: pengelola (akses penuh)
        Admin::firstOrCreate(
            ['username' => 'admin_pengelola'],
            [
                'password' => Hash::make('pengelola123'),
                'role'     => 'pengelola',
            ]
        );

        // Admin: persetujuan
        Admin::firstOrCreate(
            ['username' => 'admin_persetujuan'],
            [
                'password' => Hash::make('persetujuan123'),
                'role'     => 'persetujuan',
            ]
        );

        // User peminjam
        User::firstOrCreate(
            ['email' => 'peminjam@pili.com'],
            [
                'name'          => 'Peminjam Test',
                'password'      => Hash::make('peminjam123'),
                'jabatan'       => 'Staff',
                'divisi_program'=> 'Umum',
                'no_hp'         => '08123456789',
            ]
        );
    }
}
