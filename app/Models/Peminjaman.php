<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjamans';
    protected $guarded = ['id'];
    
    // Casting ke datetime agar mudah dimanipulasi dengan Carbon
    protected $casts = [
        'tanggal_mulai' => 'datetime',
        'tanggal_selesai' => 'datetime',
        'batas_waktu_approval' => 'datetime',
    ];

    // Accessor kode transaksi rapi
    public function getNomorTransaksiAttribute(): string
    {
        return 'TRX-PILI-' . $this->created_at->format('Ym') . '-' . str_pad($this->id, 4, '0', STR_PAD_LEFT);
    }

    // Accessor durasi peminjaman (hari)
    public function getDurasiHariAttribute(): int
    {
        if ($this->tanggal_mulai && $this->tanggal_selesai) {
            return max(1, (int) ceil($this->tanggal_mulai->floatDiffInDays($this->tanggal_selesai)));
        }
        return 1;
    }

    // Relasi: Peminjaman ini milik 1 User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi: 1 Peminjaman berisi banyak Item/Barang
    public function items()
    {
        return $this->hasMany(PeminjamanItem::class);
    }

    // Relasi: 1 Peminjaman hanya punya 1 BASTA
    public function basta()
    {
        return $this->hasOne(Basta::class);
    }

    // Relasi: 1 Peminjaman hanya punya 1 Form Pengembalian
    public function pengembalian()
    {
        return $this->hasOne(Pengembalian::class);
    }

    // Relasi: 1 Peminjaman bisa memiliki lebih dari 1 laporan kerusakan (jika ada beberapa barang rusak)
    public function laporanKerusakan()
    {
        return $this->hasMany(LaporanKerusakanKehilangan::class);
    }

    // Relasi: 1 Peminjaman bisa memiliki banyak log pengingat (jika admin telat approve berkali-kali)
    public function reminderLogs()
    {
        return $this->hasMany(ReminderLog::class);
    }
}