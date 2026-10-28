<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    public const KATEGORI_LIST = [
        'A' => 'Kategori A - Aset Strategis',
        'B' => 'Kategori B - Peralatan Teknis Lapangan',
        'C' => 'Kategori C - Peralatan Multimedia',
        'D' => 'Kategori D - Peralatan Operasional',
        'E' => 'Kategori E - Kendaraan Operasional',
        'F' => 'Kategori F - Inventaris Kantor',
    ];

    public const KATEGORI_DESKRIPSI = [
        'A' => 'Aset bernilai tinggi / risiko tinggi (Kamera DSLR, Drone, Laptop Spek Tinggi)',
        'B' => 'Peralatan teknis survei & riset lapangan (GPS, Kompas, Tenda, Teropong)',
        'C' => 'Peralatan dokumentasi audio-visual (Tripod, Mic Wireless, Lighting, Proyektor)',
        'D' => 'Peralatan pendukung operasional umum (HT, Genset, Toolkit, Kabel Roll)',
        'E' => 'Kendaraan dinas dan mobilisasi lapangan (Mobil 4x4, Motor Trail)',
        'F' => 'Perlengkapan inventaris kantor & administrasi (Pointer, Speaker Portable, Meja Lipat)',
    ];

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

    // Helper untuk mengekstrak kode kategori huruf A, B, C, D, E, F
    public function getKategoriKodeAttribute(): string
    {
        $kat = trim($this->kategori ?? '');
        if (isset(self::KATEGORI_LIST[$kat])) {
            return $kat;
        }

        // Cari pola Kategori A/B/C/D/E/F atau kode di awal
        if (preg_match('/(?:Kategori\s+)?([A-F])/i', $kat, $matches)) {
            return strtoupper($matches[1]);
        }

        return 'A';
    }

    // Label lengkap kategori (misal: "Kategori A - Aset Strategis")
    public function getKategoriLabelAttribute(): string
    {
        $kode = $this->kategori_kode;
        return self::KATEGORI_LIST[$kode] ?? $this->kategori;
    }

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