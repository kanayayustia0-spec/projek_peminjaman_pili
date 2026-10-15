<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengembalian extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'tanggal_pengembalian' => 'datetime',
        'checklist_kondisi_akhir' => 'array',
        'kelengkapan_kembali' => 'array',
    ];

    // Relasi: Pengembalian ini milik transaksi Peminjaman yang mana?
    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class);
    }
}