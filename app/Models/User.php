<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * The model's default values for attributes.
     * Mencegah error 'Field jabatan doesn't have a default value'
     *
     * @var array
     */
    protected $attributes = [
        'jabatan' => 'Staff', 
        'divisi_program' => 'Umum',
        'no_hp' => '-',// Anda bisa mengubah teks 'Staff' sesuai default yang diinginkan
    ];

    /**
     * Relasi: 1 User bisa melakukan banyak Peminjaman.
     */
    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class);
    }

    /**
     * Relasi: 1 User bisa menjadi PIC untuk banyak Barang (saat sedang dipinjam).
     */
    public function barangYangDipegang()
    {
        return $this->hasMany(Barang::class, 'pic_saat_ini');
    }
}