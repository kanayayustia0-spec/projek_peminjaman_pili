<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PersetujuanController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(function ($request, $next) {
                $admin = Auth::guard('admin')->user();
                if ($admin && $admin->role !== 'persetujuan') {
                    return redirect()->route('admin.dashboard')->with('error', 'Akses ditolak: Antrean Persetujuan hanya dapat diakses oleh Admin Persetujuan.');
                }
                return $next($request);
            })
        ];
    }
    // Menampilkan daftar pengajuan dengan tab filter (Pending, Disetujui, Ditolak, Semua)
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'pending');

        $query = Peminjaman::with(['user', 'items.barang']);

        if ($tab === 'pending') {
            $query->whereIn('status_persetujuan', ['Pending', 'Menunggu Persetujuan'])
                  ->where('status_keseluruhan', '!=', 'Ditolak');
        } elseif ($tab === 'disetujui') {
            $query->where('status_persetujuan', 'Disetujui')
                  ->where('status_keseluruhan', '!=', 'Ditolak');
        } elseif ($tab === 'ditolak') {
            $query->where(function($q) {
                $q->where('status_persetujuan', 'Ditolak')
                  ->orWhere('status_keseluruhan', 'Ditolak')
                  ->orWhere('status_pengelola', 'Ditolak');
            });
        }

        $peminjamans = $query->orderBy('created_at', 'desc')->get();

        // Hitung count untuk tab badges
        $counts = [
            'pending'   => Peminjaman::whereIn('status_persetujuan', ['Pending', 'Menunggu Persetujuan'])->where('status_keseluruhan', '!=', 'Ditolak')->count(),
            'disetujui' => Peminjaman::where('status_persetujuan', 'Disetujui')->where('status_keseluruhan', '!=', 'Ditolak')->count(),
            'ditolak'   => Peminjaman::where(function($q) {
                               $q->where('status_persetujuan', 'Ditolak')
                                 ->orWhere('status_keseluruhan', 'Ditolak')
                                 ->orWhere('status_pengelola', 'Ditolak');
                           })->count(),
            'semua'     => Peminjaman::count(),
        ];

        return view('admin.persetujuan.index', compact('peminjamans', 'tab', 'counts'));
    }

    // Menampilkan detail form dan keranjang untuk di-review
    public function show($id)
    {
        $peminjaman = Peminjaman::with(['user', 'items.barang'])->findOrFail($id);
        return view('admin.persetujuan.show', compact('peminjaman'));
    }

    // Logika Tombol Setuju
    public function approve(Request $request, $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->status_persetujuan = 'Disetujui';
        $peminjaman->alasan_penolakan = null;
        
        // Jika pengelola juga sudah setuju atau admin memilih setujui langsung
        if ($peminjaman->status_pengelola == 'Disetujui' || $request->has('setujui_langsung')) {
            $peminjaman->status_pengelola = 'Disetujui';
            $peminjaman->status_keseluruhan = 'Disetujui';
        } else {
            $peminjaman->status_keseluruhan = 'Menunggu Persetujuan';
        }
        
        $peminjaman->save();

        return redirect()->route('admin.persetujuan.index')->with('success', 'Pengajuan #' . str_pad($peminjaman->id, 5, '0', STR_PAD_LEFT) . ' berhasil DISETUJUI.');
    }

    // Logika Tombol Tolak
    public function reject(Request $request, $id)
    {
        $request->validate([
            'alasan' => 'nullable|string|max:500'
        ]);

        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->status_persetujuan = 'Ditolak';
        $peminjaman->status_keseluruhan = 'Ditolak';
        $peminjaman->alasan_penolakan = $request->input('alasan') ?: 'Pengajuan ditolak oleh Admin Persetujuan.';
        $peminjaman->save();

        return redirect()->route('admin.persetujuan.index')->with('error', 'Pengajuan #' . str_pad($peminjaman->id, 5, '0', STR_PAD_LEFT) . ' telah DITOLAK.');
    }
}