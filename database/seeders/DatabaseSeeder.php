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
        Admin::updateOrCreate(
            ['username' => 'admin_pengelola'],
            [
                'password' => Hash::make('utamaKelola99'),
                'role'     => 'pengelola',
            ]
        );

        Admin::updateOrCreate(
            ['username' => 'adminpengelola1'],
            [
                'password' => Hash::make('kelolaPili01'),
                'role'     => 'pengelola',
            ]
        );

        Admin::updateOrCreate(
            ['username' => 'adminpengelola2'],
            [
                'password' => Hash::make('asetPili02'),
                'role'     => 'pengelola',
            ]
        );

        // Admin: persetujuan
        Admin::updateOrCreate(
            ['username' => 'admin_persetujuan'],
            [
                'password' => Hash::make('utamaSetuju88'),
                'role'     => 'persetujuan',
            ]
        );

        Admin::updateOrCreate(
            ['username' => 'adminpersetujuan3'],
            [
                'password' => Hash::make('setujuPili03'),
                'role'     => 'persetujuan',
            ]
        );

        Admin::updateOrCreate(
            ['username' => 'adminpersetujuan4'],
            [
                'password' => Hash::make('izinPili04'),
                'role'     => 'persetujuan',
            ]
        );

        Admin::updateOrCreate(
            ['username' => 'adminpersetujuan5'],
            [
                'password' => Hash::make('verifPili05'),
                'role'     => 'persetujuan',
            ]
        );

        // User peminjam
        User::firstOrCreate(
            ['email' => 'peminjam@pili.com'],
            [
                'name'          => 'Peminjam Test',
                'username'      => 'peminjam_test',
                'password'      => Hash::make('peminjam123'),
                'jabatan'       => 'Staff',
                'divisi_program'=> 'Umum',
                'no_hp'         => '08123456789',
            ]
        );
    }
}
