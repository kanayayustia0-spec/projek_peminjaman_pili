<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Peminjaman;
use App\Models\PeminjamanItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PeminjamanController extends Controller
{
    // Menampilkan riwayat peminjaman user (Dashboard User)
public function index()
    {
        // 1. Ambil riwayat peminjaman user yang sedang login
        $peminjamans = \App\Models\Peminjaman::with(['items.barang', 'basta', 'pengembalian'])
                            ->where('user_id', \Illuminate\Support\Facades\Auth::id())
                            ->orderBy('created_at', 'desc')
                            ->get();
        
        // 2. Ambil sampel barang yang stoknya masih ada untuk ditampilkan di Katalog Beranda (Misal tampilkan 8 barang)
        $barangs = \App\Models\Barang::where('jumlah_tersedia', '>', 0)->inRandomOrder()->take(8)->get();

        return view('user.peminjaman.index', compact('peminjamans', 'barangs'));
    }

    // Menampilkan form permohonan & keranjang barang
    public function create()
    {
        // Katalog barang dikirim ke view (dikelompokkan per kategori)
        $barangs = Barang::where('status', 'Tersedia')
                         ->where('jumlah_tersedia', '>', 0)
                         ->get()
                         ->groupBy('kategori');

        return view('user.peminjaman.create', compact('barangs'));
    }

    // Memproses pengajuan peminjaman dari form
    public function store(Request $request)
    {
        $request->validate([
            'tujuan_peminjaman' => 'required|string',
            'nama_kegiatan' => 'required|string',
            'lokasi_penggunaan' => 'required|string',
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'items' => 'required|array', // Data keranjang dari frontend
            'items.*.barang_id' => 'required|exists:barangs,id',
            'items.*.jumlah' => 'required|integer|min:1'
        ]);

        // Hitung SLA batas waktu approval (2 hari kerja)
        $batasWaktu = Carbon::now()->addWeekdays(2);

        // 1. Simpan Header Transaksi
        $peminjaman = Peminjaman::create([
            'user_id' => Auth::id(),
            'tujuan_peminjaman' => $request->tujuan_peminjaman,
            'nama_kegiatan' => $request->nama_kegiatan,
            'lokasi_penggunaan' => $request->lokasi_penggunaan,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'batas_waktu_approval' => $batasWaktu,
            'status_keseluruhan' => 'Menunggu Persetujuan'
        ]);

        

        // 2. Simpan Daftar Barang (Items)
        foreach ($request->items as $item) {
            $barang = Barang::find($item['barang_id']);
            PeminjamanItem::create([
                'peminjaman_id' => $peminjaman->id,
                'barang_id' => $barang->id,
                'jumlah' => $item['jumlah'],
                'kategori_saat_pinjam' => $barang->kategori, // Snapshot kategori
            ]);
        }

        return redirect()->route('peminjaman.index')
                         ->with('success', 'Permohonan berhasil diajukan dan menunggu persetujuan.');
    }

    // Menampilkan detail pengajuan (Preview)
    public function show(Peminjaman $peminjaman)
    {
        // Pastikan hanya pemilik yang bisa melihat
        if ($peminjaman->user_id !== Auth::id()) abort(403);
        
        return view('user.peminjaman.show', compact('peminjaman'));
    }

    // Proses User menerima aset (Tanda tangan digital & Checkbox BASTA)
    public function terimaAset(Request $request, Peminjaman $peminjaman)
    {
        $request->validate([
            'ttd_digital_peminjam' => 'required|string', // Base64 dari canvas signature_pad
            'konfirmasi_checkbox' => 'accepted'
        ]);

        $peminjaman->basta()->update([
            'ttd_digital_peminjam' => $request->ttd_digital_peminjam,
            'konfirmasi_checkbox_peminjam' => true
        ]);

        // Update status keseluruhan
        $peminjaman->update(['status_keseluruhan' => 'Sedang Digunakan']);

        return back()->with('success', 'Aset berhasil diterima.');
    }

    // Menampilkan halaman Katalog Barang
    public function katalog()
    {
        // Mengambil semua barang, dikelompokkan berdasarkan kategori
        $kategoriBarang = \App\Models\Barang::all()->groupBy('kategori');
        
        return view('user.peminjaman.katalog', compact('kategoriBarang'));
    }

    // Menampilkan Form Pengembalian (Wajib foto & catatan)
    public function formPengembalian(Peminjaman $peminjaman)
    {
        return view('user.peminjaman.pengembalian', compact('peminjaman'));
    }

    // Proses Submit Pengembalian oleh User
    public function prosesPengembalian(Request $request, Peminjaman $peminjaman)
    {
        $request->validate([
            'foto_kondisi_akhir' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'catatan_kondisi_peminjam' => 'required|string'
        ]);

        $path = $request->file('foto_kondisi_akhir')->store('pengembalian_fotos', 'public');

        $peminjaman->pengembalian()->create([
            'tanggal_pengembalian' => now(),
            'foto_kondisi_akhir' => $path,
            'catatan_kondisi_peminjam' => $request->catatan_kondisi_peminjam,
            'checklist_kondisi_akhir' => [], // Nanti diisi admin saat crosscheck
            'kelengkapan_kembali' => [], // Nanti diisi admin saat crosscheck
        ]);

        $peminjaman->update(['status_keseluruhan' => 'Menunggu Konfirmasi Pengembalian']);

        return redirect()->route('peminjaman.index')->with('success', 'Pengembalian diajukan. Menunggu pengecekan Admin.');
    }
}