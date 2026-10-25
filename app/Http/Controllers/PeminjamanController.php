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
    public function index(Request $request)
    {
        $query = Barang::query();
        
        if ($request->has('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('kode_inventaris', 'like', '%' . $request->search . '%')
                  ->orWhere('kategori', 'like', '%' . $request->search . '%');
        }

        $kategoriBarang = $query->get()->groupBy('kategori');

        return view('user.peminjaman.index', compact('kategoriBarang'));
    }

    // Menampilkan halaman cara kerja
    public function caraKerja()
    {
        return view('user.cara_kerja');
    }

    // Menampilkan riwayat peminjaman untuk user yang sedang login
    public function riwayat()
    {
        $peminjamans = Peminjaman::where('user_id', auth()->id())
                        ->orderBy('created_at', 'desc')
                        ->get();

        return view('user.peminjaman.riwayat', compact('peminjamans'));
    }

    // Menampilkan form permohonan & keranjang barang
    public function create()
    {
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
            'items' => 'required|array', 
            'items.*.barang_id' => 'required|exists:barangs,id',
            'items.*.jumlah' => 'required|integer|min:1'
        ]);

        $batasWaktu = Carbon::now()->addWeekdays(2);

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

        foreach ($request->items as $item) {
            $barang = Barang::find($item['barang_id']);
            PeminjamanItem::create([
                'peminjaman_id' => $peminjaman->id,
                'barang_id' => $barang->id,
                'jumlah' => $item['jumlah'],
                'kategori_saat_pinjam' => $barang->kategori, 
            ]);
        }

        return redirect()->route('peminjaman.index')
                         ->with('success', 'Permohonan berhasil diajukan dan menunggu persetujuan.');
    }

    // Menampilkan detail pengajuan (Preview)
    public function show(Peminjaman $peminjaman)
    {
        if ($peminjaman->user_id !== Auth::id()) {
            abort(403);
        }
        
        return view('user.peminjaman.show', compact('peminjaman'));
    }

    // Proses User menerima aset
    public function terimaAset(Request $request, Peminjaman $peminjaman)
    {
        $request->validate([
            'ttd_digital_peminjam' => 'required|string', 
            'konfirmasi_checkbox' => 'accepted'
        ]);

        $peminjaman->basta()->update([
            'ttd_digital_peminjam' => $request->ttd_digital_peminjam,
            'konfirmasi_checkbox_peminjam' => true
        ]);

        $peminjaman->update(['status_keseluruhan' => 'Sedang Digunakan']);

        return back()->with('success', 'Aset berhasil diterima.');
    }

    // Menampilkan halaman Katalog Barang
    public function katalog()
    {
        $kategoriBarang = Barang::all()->groupBy('kategori');
        
        return view('user.peminjaman.katalog', compact('kategoriBarang'));
    }

    // Menampilkan Form Pengembalian
    public function formPengembalian(Peminjaman $peminjaman)
    {
        if (!in_array($peminjaman->status_keseluruhan, ['Aset Diserahkan', 'Sedang Digunakan', 'Terlambat'])) {
            return redirect()->route('peminjaman.index')->with('error', 'Status peminjaman belum valid untuk dikembalikan.');
        }

        return view('user.peminjaman.pengembalian', compact('peminjaman'));
    }

    // Proses Submit Pengembalian oleh User
    public function prosesPengembalian(Request $request, Peminjaman $peminjaman)
    {
        $request->validate([
            'foto_kondisi_akhir' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'catatan_kondisi_peminjam' => 'required|string'
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto_kondisi_akhir')) {
            $file = $request->file('foto_kondisi_akhir');
            $filename = time() . '_kembali_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/pengembalian'), $filename);
            $fotoPath = 'uploads/pengembalian/' . $filename;
        }

        \App\Models\Pengembalian::create([
            'peminjaman_id' => $peminjaman->id,
            'tanggal_pengembalian' => now(), 
            'foto_kondisi_akhir' => $fotoPath,
            'catatan_kondisi_peminjam' => $request->catatan_kondisi_peminjam,
        ]);
        
        $peminjaman->update(['status_keseluruhan' => 'Menunggu Konfirmasi Pengembalian']);

        return redirect()->route('peminjaman.index')->with('success', 'Pengembalian diajukan. Menunggu pengecekan Admin.');
    }
}