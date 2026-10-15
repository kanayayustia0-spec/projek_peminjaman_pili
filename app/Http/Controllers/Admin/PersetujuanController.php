<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;

class PersetujuanController extends Controller
{
    // Menampilkan daftar pengajuan yang butuh persetujuan
    public function index()
    {
        $peminjamans = Peminjaman::with('user')
            ->where('status_persetujuan', 'Menunggu Persetujuan')
            ->orderBy('created_at', 'asc')
            ->get();

        return view('admin.persetujuan.index', compact('peminjamans'));
    }

    // Menampilkan detail form 5W dan Keranjang untuk di-review
    public function show($id)
    {
        $peminjaman = Peminjaman::with(['user', 'items.barang'])->findOrFail($id);
        return view('admin.persetujuan.show', compact('peminjaman'));
    }

    // Logika Tombol Setuju
    public function approve($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->status_persetujuan = 'Disetujui';
        
        // Jika pengelola juga sudah setuju, maka status keseluruhan berubah jadi disetujui
        if ($peminjaman->status_pengelola == 'Disetujui') {
            $peminjaman->status_keseluruhan = 'Disetujui';
        }
        $peminjaman->save();

        return redirect()->route('admin.persetujuan.index')->with('success', 'Pengajuan berhasil DISETUJUI.');
    }

    // Logika Tombol Tolak
    public function reject(Request $request, $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->status_persetujuan = 'Ditolak';
        $peminjaman->status_keseluruhan = 'Ditolak';
        
        // (Opsional) Jika di database Anda ada kolom alasan_penolakan, hapus tanda komentar di bawah ini:
        // $peminjaman->alasan_penolakan = $request->alasan;
        
        $peminjaman->save();

        return redirect()->route('admin.persetujuan.index')->with('error', 'Pengajuan telah DITOLAK.');
    }
}