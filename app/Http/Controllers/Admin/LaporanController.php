<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\LaporanKerusakanKehilangan;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    // 1. (Admin Pengelola) Form Buat Laporan
    public function create(Peminjaman $peminjaman)
    {
        // Pastikan status peminjaman memang butuh tindak lanjut
        if ($peminjaman->status_keseluruhan !== 'Perlu Tindak Lanjut') {
            abort(403, 'Transaksi ini tidak dalam status Perlu Tindak Lanjut.');
        }

        return view('admin.laporan.create', compact('peminjaman'));
    }

    // 2. (Admin Pengelola) Simpan Laporan
    public function store(Request $request, Peminjaman $peminjaman)
    {
        $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'tanggal_kejadian' => 'required|date',
            'lokasi' => 'required|string',
            'jenis_kejadian' => 'required|in:Kerusakan,Kehilangan,Force Majeure',
            'kronologi' => 'required|string',
            'penyebab' => 'required|in:Pemakaian Normal,Kelalaian,Pihak Ketiga,Force Majeure,Belum Diketahui',
            'bukti_pendukung.*' => 'nullable|image|max:5120' // Array file max 5MB
        ]);

        // Proses multiple upload file bukti
        $buktiPaths = [];
        if ($request->hasFile('bukti_pendukung')) {
            foreach ($request->file('bukti_pendukung') as $file) {
                $buktiPaths[] = $file->store('bukti_laporan', 'public');
            }
        }

        LaporanKerusakanKehilangan::create([
            'peminjaman_id' => $peminjaman->id,
            'barang_id' => $request->barang_id,
            'tanggal_kejadian' => $request->tanggal_kejadian,
            'lokasi' => $request->lokasi,
            'jenis_kejadian' => $request->jenis_kejadian,
            'kronologi' => $request->kronologi,
            'penyebab' => $request->penyebab,
            'bukti_pendukung' => $buktiPaths, // Disimpan sebagai array JSON (otomatis di-cast di Model)
        ]);

        // Update status barang di Master Inventaris
        $barang = Barang::find($request->barang_id);
        $barang->update([
            'status' => $request->jenis_kejadian === 'Kehilangan' ? 'Hilang' : 'Rusak',
            // Note: Stok tidak dikembalikan karena barangnya rusak/hilang
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Laporan berhasil dibuat. Menunggu keputusan Admin Persetujuan.');
    }
}