<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Basta extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Casting field JSON agar otomatis menjadi array di PHP
    protected $casts = [
        'checklist_kondisi_awal' => 'array',
        'kelengkapan' => 'array',
        'konfirmasi_checkbox_pengelola' => 'boolean',
        'konfirmasi_checkbox_peminjam' => 'boolean',
    ];

    // Relasi: BASTA ini milik transaksi Peminjaman yang mana?
    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class);
    }
}