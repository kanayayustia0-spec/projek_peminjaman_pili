<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeminjamanItem extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Relasi: Item ini bagian dari transaksi Peminjaman yang mana?
    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class);
    }

    // Relasi: Item ini merujuk ke data Barang fisik yang mana?
    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}