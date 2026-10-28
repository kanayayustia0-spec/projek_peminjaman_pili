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
            'status' => 'required|in:Disetujui,Ditolak',
            'alasan' => 'nullable|string|max:500'
        ]);

        $peminjaman->status_pengelola = $request->status;

        if ($request->status === 'Ditolak') {
            $peminjaman->status_keseluruhan = 'Ditolak';
            $peminjaman->status_persetujuan = 'Ditolak';
            $peminjaman->alasan_penolakan = $request->input('alasan') ?: 'Pengajuan ditolak oleh Admin Pengelola.';
            $peminjaman->save();
            return back()->with('error', 'Pengajuan telah ditolak oleh Admin Pengelola.');
        }

        $peminjaman->alasan_penolakan = null;
        $this->cekStatusKeseluruhan($peminjaman);
        $peminjaman->save();

        return back()->with('success', 'Status pengelola berhasil diperbarui.');
    }

    // B. Proses Approval oleh Admin Persetujuan
    public function approvePersetujuan(Request $request, Peminjaman $peminjaman)
    {
        $request->validate([
            'status' => 'required|in:Disetujui,Ditolak',
            'alasan' => 'nullable|string|max:500'
        ]);

        $peminjaman->status_persetujuan = $request->status;

        if ($request->status === 'Ditolak') {
            $peminjaman->status_keseluruhan = 'Ditolak';
            $peminjaman->alasan_penolakan = $request->input('alasan') ?: 'Pengajuan ditolak oleh Admin Persetujuan.';
            $peminjaman->save();
            return back()->with('error', 'Pengajuan telah ditolak oleh Admin Persetujuan.');
        }

        $peminjaman->alasan_penolakan = null;
        $this->cekStatusKeseluruhan($peminjaman);
        $peminjaman->save();

        return back()->with('success', 'Keputusan persetujuan berhasil diperbarui.');
    }

    // C. Logika Pengecekan Kombinasi Approval
    private function cekStatusKeseluruhan(Peminjaman $peminjaman)
    {
        // Jika salah satu menolak, transaksi langsung dibatalkan
        if ($peminjaman->status_pengelola === 'Ditolak' || $peminjaman->status_persetujuan === 'Ditolak') {
            $peminjaman->status_keseluruhan = 'Ditolak';
            return;
        }

        // Jika keduanya menyetujui, transaksi berlanjut ke tahap BASTA
        if ($peminjaman->status_pengelola === 'Disetujui' && $peminjaman->status_persetujuan === 'Disetujui') {
            $peminjaman->status_keseluruhan = 'Disetujui';
            return;
        }

        // Jika belum keduanya disetujui
        $peminjaman->status_keseluruhan = 'Menunggu Persetujuan';
    }

    // D. Persetujuan Sepenuhnya / Langsung (Bypass jika diperlukan oleh Admin)
    public function approveLangsung(Peminjaman $peminjaman)
    {
        $peminjaman->update([
            'status_pengelola' => 'Disetujui',
            'status_persetujuan' => 'Disetujui',
            'status_keseluruhan' => 'Disetujui',
            'alasan_penolakan' => null
        ]);

        return back()->with('success', 'Pengajuan berhasil DISETUJUI SEPENUHNYA. Aset siap diserahterimakan (BASTA).');
    }

    // E. Reset Approval kembali ke Pending
    public function resetApproval(Peminjaman $peminjaman)
    {
        $peminjaman->update([
            'status_pengelola' => 'Pending',
            'status_persetujuan' => 'Pending',
            'status_keseluruhan' => 'Menunggu Persetujuan',
            'alasan_penolakan' => null
        ]);

        return back()->with('success', 'Status pengajuan berhasil di-reset kembali ke Pending.');
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
