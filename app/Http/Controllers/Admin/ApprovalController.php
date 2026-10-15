<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Illuminate\Http\Request;

class ApprovalController extends Controller
{
    // A. Proses Approval oleh Admin Pengelola
    public function approvePengelola(Request $request, Peminjaman $peminjaman)
    {
        $request->validate([
            'status' => 'required|in:Disetujui,Ditolak'
        ]);

        $peminjaman->update(['status_pengelola' => $request->status]);
        
        $this->cekStatusKeseluruhan($peminjaman);

        return back()->with('success', 'Status pengelola berhasil diperbarui.');
    }

    // B. Proses Approval oleh Admin Persetujuan
    public function approvePersetujuan(Request $request, Peminjaman $peminjaman)
    {
        $request->validate([
            'status' => 'required|in:Disetujui,Ditolak'
        ]);

        $peminjaman->update(['status_persetujuan' => $request->status]);
        
        $this->cekStatusKeseluruhan($peminjaman);

        return back()->with('success', 'Keputusan persetujuan berhasil diperbarui.');
    }

    // C. Logika Pengecekan Kombinasi Approval
    private function cekStatusKeseluruhan(Peminjaman $peminjaman)
    {
        // Jika salah satu menolak, transaksi langsung dibatalkan
        if ($peminjaman->status_pengelola === 'Ditolak' || $peminjaman->status_persetujuan === 'Ditolak') {
            $peminjaman->update(['status_keseluruhan' => 'Ditolak']);
            return;
        }

        // Jika keduanya menyetujui, transaksi berlanjut ke tahap BASTA
        if ($peminjaman->status_pengelola === 'Disetujui' && $peminjaman->status_persetujuan === 'Disetujui') {
            $peminjaman->update(['status_keseluruhan' => 'Disetujui']);
            return;
        }
    }

    // Menampilkan Halaman Crosscheck Pengembalian (Untuk Admin Pengelola)
    public function formVerifikasiPengembalian(Peminjaman $peminjaman)
    {
        // Pastikan ada data pengembalian yang diajukan user
        if (!$peminjaman->pengembalian) {
            abort(404, 'Data pengembalian belum diajukan oleh peminjam.');
        }

        return view('admin.pengembalian.verifikasi', compact('peminjaman'));
    }

    // Memproses Hasil Pemeriksaan Admin Pengelola
    public function verifikasiPengembalian(Request $request, Peminjaman $peminjaman)
    {
        $request->validate([
            'hasil_pemeriksaan' => 'required|in:Sesuai,Ada Kerusakan,Ada Kehilangan,Perlu Tindak Lanjut'
        ]);

        // Update data pengembalian
        $peminjaman->pengembalian()->update([
            'hasil_pemeriksaan' => $request->hasil_pemeriksaan,
            'checklist_kondisi_akhir' => $request->checklist_kondisi_akhir ?? [], // dari form
            'kelengkapan_kembali' => $request->kelengkapan_kembali ?? [], // dari form
        ]);

        // Jika AMAN (Sesuai) -> Status Selesai & Stok Kembali
        if ($request->hasil_pemeriksaan === 'Sesuai') {
            $peminjaman->update(['status_keseluruhan' => 'Selesai']);

            foreach ($peminjaman->items as $item) {
                $barang = $item->barang;
                $barang->increment('jumlah_tersedia', $item->jumlah); // Kembalikan stok
                $barang->update([
                    'pic_saat_ini' => null, // Hapus PIC karena sudah di gudang
                    'status' => 'Tersedia'
                ]);
            }
            return redirect()->route('admin.dashboard')->with('success', 'Pengembalian diverifikasi. Transaksi Selesai dan stok barang telah dikembalikan.');
        } 
        
        // Jika ADA MASALAH -> Ganti status
        else {
            $peminjaman->update(['status_keseluruhan' => 'Perlu Tindak Lanjut']);
            return redirect()->route('admin.dashboard')->with('warning', 'Pemeriksaan mencatat ada kendala. Silakan buat Laporan Kerusakan/Kehilangan.');
        }
        
    }
    // Keputusan akhir dari Admin Persetujuan untuk Laporan Kerusakan/Kehilangan
    public function keputusanLaporan(Request $request, \App\Models\LaporanKerusakanKehilangan $laporan)
    {
        $request->validate([
            'keputusan' => 'required|string'
        ]);

        // Simpan keputusan
        $laporan->update([
            'keputusan_admin_persetujuan' => $request->keputusan
        ]);

        // Tutup transaksi karena masalah sudah diputuskan/diselesaikan
        $laporan->peminjaman->update(['status_keseluruhan' => 'Selesai']);

        return back()->with('success', 'Keputusan berhasil dicatat dan transaksi ditutup.');
    }
    
} 
