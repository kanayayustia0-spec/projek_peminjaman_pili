<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;
    protected $fillable = [
        'kode_inventaris',
        'nama',
        'kategori',
        'jumlah_total',
        'jumlah_tersedia',
        'kondisi',
        'lokasi_penyimpanan',
        'deskripsi',
        'foto', 
    ];
    
    // Relasi: Barang ini sedang dipegang oleh siapa?
    public function pic()
    {
        return $this->belongsTo(User::class, 'pic_saat_ini');
    }

    // Relasi: Barang ini pernah ada di permohonan peminjaman apa saja?
    public function peminjamanItems()
    {
        return $this->hasMany(PeminjamanItem::class);
    }

    // Relasi: Barang ini pernah dilaporkan rusak/hilang berapa kali?
    public function laporanKerusakanKehilangan()
    {
        return $this->hasMany(LaporanKerusakanKehilangan::class);
    }
}