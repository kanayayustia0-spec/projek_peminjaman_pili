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
    // 1. Halaman Beranda (Daftar Barang + Search)
    public function index(\Illuminate\Http\Request $request)
    {
        $query = \App\Models\Barang::query();

        // Logika Fitur Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('nama', 'like', "%{$search}%")
                  ->orWhere('kode_inventaris', 'like', "%{$search}%")
                  ->orWhere('kategori', 'like', "%{$search}%");
        }

        // Ambil barang dan kelompokkan per kategori
        $kategoriBarang = $query->get()->groupBy('kategori');

        return view('user.peminjaman.index', compact('kategoriBarang'));
    }

    // 2. Halaman Cara Kerja
    public function caraKerja()
    {
        return view('user.cara_kerja');
    }

    // 3. Halaman Riwayat Peminjaman
    public function riwayat()
    {
        $peminjamans = \App\Models\Peminjaman::with(['items.barang', 'basta', 'pengembalian'])
                            ->where('user_id', \Illuminate\Support\Facades\Auth::id())
                            ->orderBy('created_at', 'desc')
                            ->get();
                            
        return view('user.peminjaman.riwayat', compact('peminjamans'));
    }
}