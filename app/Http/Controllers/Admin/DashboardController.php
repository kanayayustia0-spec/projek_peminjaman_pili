<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Barang; // Tambahkan ini untuk memanggil model Barang
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Ambil data admin yang sedang login
        $admin = Auth::guard('admin')->user();

        // 2. Ambil semua transaksi (tabel) beserta relasi user dan item barangnya
        $peminjamans = Peminjaman::with(['user', 'items.barang'])
                        ->orderByRaw("
                            CASE 
                                WHEN status_keseluruhan = 'Terlambat' THEN 1
                                WHEN status_keseluruhan = 'Menunggu Konfirmasi Pengembalian' THEN 2
                                WHEN status_keseluruhan = 'Menunggu Persetujuan' THEN 3
                                WHEN status_keseluruhan = 'Disetujui' THEN 4
                                ELSE 5
                            END
                        ")
                        ->orderBy('created_at', 'desc')
                        ->get();

        // ========================================================
        // 3. STATISTIK UNTUK ADMIN PERSETUJUAN
        // ========================================================
        if ($admin->role == 'persetujuan') {
            // Hitung jumlah status
            $menunggu = Peminjaman::where('status_persetujuan', 'Menunggu Persetujuan')->count();
            
            $disetujuiBulanIni = Peminjaman::where('status_persetujuan', 'Disetujui')
                                    ->whereMonth('created_at', date('m'))
                                    ->count();
                                    
            $ditolak = Peminjaman::where('status_persetujuan', 'Ditolak')->count();

            // Ambil 5 antrean paling lama yang belum disetujui
            $antreanTerbaru = Peminjaman::with('user')
                                ->where('status_persetujuan', 'Menunggu Persetujuan')
                                ->orderBy('created_at', 'asc')
                                ->take(5)
                                ->get();

            // Return ke view admin.dashboard.index dengan data spesifik Persetujuan
            return view('admin.dashboard.index', compact('peminjamans', 'admin', 'menunggu', 'disetujuiBulanIni', 'ditolak', 'antreanTerbaru'));
        }

        // ========================================================
        // 4. STATISTIK UNTUK ADMIN PENGELOLA
        // ========================================================
        $totalBarang = Barang::count(); 
        $peminjamanAktif = Peminjaman::whereIn('status_keseluruhan', ['Disetujui', 'Aset Diserahkan', 'Sedang Digunakan'])->count();
        
        // Return ke view admin.dashboard.index dengan data spesifik Pengelola
        return view('admin.dashboard.index', compact('peminjamans', 'admin', 'totalBarang', 'peminjamanAktif'));
    }

    public function rekapPeminjaman(Request $request)
    {
        $query = Peminjaman::with(['user', 'items.barang', 'basta', 'pengembalian']);

        // Filter Rentang Waktu
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00', 
                $request->end_date . ' 23:59:59'
            ]);
        }

        // Filter Status
        if ($request->filled('status')) {
            $query->where('status_keseluruhan', $request->status);
        }

        $peminjamans = $query->orderBy('created_at', 'desc')->get();

        return view('admin.dashboard.rekap', compact('peminjamans'));
    }
}