<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanKerusakanKehilangan extends Model
{
    use HasFactory;

    protected $table = 'laporan_kerusakan_kehilangans';
    protected $guarded = ['id'];

    protected $casts = [
        'tanggal_kejadian' => 'datetime',
        'bukti_pendukung' => 'array',
    ];

    // Relasi: Laporan ini terjadi pada saat transaksi Peminjaman apa?
    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class);
    }

    // Relasi: Barang apa yang rusak/hilang?
    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}