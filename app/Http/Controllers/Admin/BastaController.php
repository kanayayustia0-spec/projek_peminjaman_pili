<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Basta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BastaController extends Controller
{
    // Menampilkan form BASTA untuk diisi Admin Pengelola
    public function create(Peminjaman $peminjaman)
    {
        // Pastikan status sudah disetujui kedua belah pihak
        if ($peminjaman->status_pengelola !== 'Disetujui' || $peminjaman->status_persetujuan !== 'Disetujui') {
            abort(403, 'Peminjaman belum disetujui sepenuhnya.');
        }

        return view('admin.basta.create', compact('peminjaman'));
    }

    // Memproses form BASTA dan Tanda Tangan Digital
    public function store(Request $request, Peminjaman $peminjaman)
    {
        $request->validate([
            'tanggal_serah' => 'required|date',
            'jam_serah' => 'required',
            'lokasi_serah' => 'required|string',
            'foto_kondisi_awal' => 'required|image|max:5120', // Maks 5MB
            
            // JSON Arrays
            'checklist_kondisi_awal' => 'required|array',
            'kelengkapan' => 'required|array',
            
            // Tanda tangan (Base64 dari canvas)
            'ttd_digital_pengelola' => 'required|string',
            'ttd_digital_peminjam' => 'required|string',
            
            // Checkbox
            'konfirmasi_checkbox_pengelola' => 'accepted',
            'konfirmasi_checkbox_peminjam' => 'accepted',
        ]);

        // Simpan foto kondisi awal ke storage/app/public/basta_fotos
        $fotoPath = $request->file('foto_kondisi_awal')->store('basta_fotos', 'public');

        // Simpan data BASTA
        Basta::create([
            'peminjaman_id' => $peminjaman->id,
            'tanggal_serah' => $request->tanggal_serah,
            'jam_serah' => $request->jam_serah,
            'lokasi_serah' => $request->lokasi_serah,
            'checklist_kondisi_awal' => $request->checklist_kondisi_awal,
            'kelengkapan' => $request->kelengkapan,
            'foto_kondisi_awal' => $fotoPath,
            'ttd_digital_pengelola' => $request->ttd_digital_pengelola, // Disimpan dalam bentuk base64 text
            'ttd_digital_peminjam' => $request->ttd_digital_peminjam,   // Disimpan dalam bentuk base64 text
            'konfirmasi_checkbox_pengelola' => true,
            'konfirmasi_checkbox_peminjam' => true,
        ]);

        // Kurangi stok barang yang dipinjam & Update PIC
        foreach ($peminjaman->items as $item) {
            $barang = $item->barang;
            $barang->decrement('jumlah_tersedia', $item->jumlah);
            
            // Update PIC barang ke peminjam saat ini dan statusnya
            $barang->update([
                'pic_saat_ini' => $peminjaman->user_id,
                'status' => 'Dipinjam'
            ]);
        }

        // Update status keseluruhan transaksi
        $peminjaman->update(['status_keseluruhan' => 'Aset Diserahkan']);

        return redirect()->route('admin.dashboard')->with('success', 'BASTA berhasil dibuat dan aset telah diserahkan.');
    }
}