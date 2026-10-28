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
    // Halaman Beranda & Dashboard User
    public function index(Request $request)
    {
        $userId = Auth::id();

        // 1. Statistik Cepat untuk Dashboard
        $stats = [
            'total_tersedia' => Barang::where('status', 'Tersedia')->where('jumlah_tersedia', '>', 0)->count(),
            'total_habis' => Barang::where('jumlah_tersedia', '<=', 0)->orWhere('status', '!=', 'Tersedia')->count(),
            'total_kategori' => count(Barang::KATEGORI_LIST),
            'pinjaman_aktif_saya' => Peminjaman::where('user_id', $userId)
                ->whereIn('status_keseluruhan', ['Menunggu Persetujuan', 'Disetujui', 'Aset Diserahkan', 'Sedang Digunakan'])
                ->count(),
            'riwayat_selesai_saya' => Peminjaman::where('user_id', $userId)
                ->where('status_keseluruhan', 'Selesai')
                ->count(),
        ];

        // 2. Transaksi Aktif / Berjalan User untuk Widget Dashboard
        $transaksiAktif = Peminjaman::with(['items.barang'])
            ->where('user_id', $userId)
            ->whereIn('status_keseluruhan', ['Menunggu Persetujuan', 'Disetujui', 'Aset Diserahkan', 'Sedang Digunakan', 'Menunggu Konfirmasi Pengembalian'])
            ->latest()
            ->take(3)
            ->get();

        // 3. Query Katalog Barang
        $query = Barang::query();

        if ($request->filled('search')) {
            $keyword = trim($request->search);
            $query->where(function ($q) use ($keyword) {
                $q->where('nama', 'like', "%{$keyword}%")
                  ->orWhere('kode_inventaris', 'like', "%{$keyword}%")
                  ->orWhere('kategori', 'like', "%{$keyword}%")
                  ->orWhere('deskripsi', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('kategori') && $request->kategori !== 'all') {
            $query->where('kategori', 'like', '%' . $request->kategori . '%');
        }

        // Ambil semua barang dan urutkan
        $allBarangs = $query->orderBy('kategori')->orderBy('nama')->get();
        $kategoriBarang = $allBarangs->groupBy('kategori_label');

        return view('user.peminjaman.index', compact('kategoriBarang', 'stats', 'transaksiAktif'));
    }

    // Menampilkan halaman cara kerja
    public function caraKerja()
    {
        return view('user.cara_kerja');
    }

    // Menampilkan riwayat peminjaman untuk user yang sedang login
    public function riwayat()
    {
        $peminjamans = Peminjaman::with(['items.barang', 'basta', 'pengembalian'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.peminjaman.riwayat', compact('peminjamans'));
    }

    // Menampilkan form permohonan & keranjang barang
    public function create()
    {
        // Ambil semua barang untuk pemilihan multi-kategori di keranjang
        $barangs = Barang::orderBy('kategori')->orderBy('nama')->get();

        return view('user.peminjaman.create', compact('barangs'));
    }

    // Memproses pengajuan peminjaman dari form
    public function store(Request $request)
    {
        $request->validate([
            'tujuan_peminjaman' => 'required|string',
            'nama_kegiatan' => 'required|string',
            'lokasi_penggunaan' => 'required|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date',
        ]);

        // 1. Ekstraksi Item Keranjang (Mendukung format items array maupun barang_id & jumlah array)
        $itemsData = [];
        if ($request->has('items') && is_array($request->items)) {
            foreach ($request->items as $row) {
                if (!empty($row['barang_id']) && !empty($row['jumlah']) && (int)$row['jumlah'] > 0) {
                    $itemsData[] = [
                        'barang_id' => (int)$row['barang_id'],
                        'jumlah'    => (int)$row['jumlah'],
                    ];
                }
            }
        } elseif ($request->has('barang_id') && is_array($request->barang_id)) {
            $bIds = $request->barang_id;
            $bQtys = $request->jumlah ?? [];
            foreach ($bIds as $idx => $bId) {
                $qty = isset($bQtys[$idx]) ? (int)$bQtys[$idx] : 0;
                if ($bId && $qty > 0) {
                    $itemsData[] = [
                        'barang_id' => (int)$bId,
                        'jumlah'    => $qty,
                    ];
                }
            }
        }

        if (empty($itemsData)) {
            return back()->withInput()->with('error', 'Keranjang peminjaman masih kosong! Silakan pilih minimal 1 barang dengan jumlah minimal 1 unit.');
        }

        // 2. VALIDASI MAKSIMAL DURASI PEMINJAMAN 2 HARI (SOP PILI)
        $mulai = Carbon::parse($request->tanggal_mulai);
        $selesai = Carbon::parse($request->tanggal_selesai);

        if ($selesai->lessThanOrEqualTo($mulai)) {
            return back()->withInput()->with('error', 'Waktu selesai peminjaman harus lebih dari waktu mulai.');
        }

        // Cek durasi dalam jam (48 jam = 2 hari). Berikan toleransi margin 2 jam (50 jam) jika acara berakhir sore hari
        $diffHours = $mulai->diffInHours($selesai);
        if ($diffHours > 50) {
            return back()->withInput()->with('error', 'Pengajuan peminjaman maksimal adalah 2 hari (48 jam). Mohon sesuaikan tanggal & jam selesai.');
        }

        // 3. VALIDASI STOK: Tidak dapat meminjam jika stok habis atau tidak mencukupi
        foreach ($itemsData as $item) {
            $barang = Barang::find($item['barang_id']);
            if (!$barang) {
                return back()->withInput()->with('error', 'Barang yang dipilih tidak ditemukan dalam sistem.');
            }

            if ($barang->status !== 'Tersedia' || $barang->jumlah_tersedia <= 0) {
                return back()->withInput()->with('error', "Maaf, stok barang '{$barang->nama}' ({$barang->kode_inventaris}) saat ini sedang HABIS dan tidak dapat dipinjam.");
            }

            if ($item['jumlah'] > $barang->jumlah_tersedia) {
                return back()->withInput()->with('error', "Permintaan untuk '{$barang->nama}' ({$item['jumlah']} unit) melebihi stok yang tersedia saat ini ({$barang->jumlah_tersedia} unit).");
            }
        }

        // 4. Simpan Peminjaman Baru
        $batasWaktu = Carbon::now()->addWeekdays(2);

        $peminjaman = Peminjaman::create([
            'user_id' => Auth::id(),
            'tujuan_peminjaman' => $request->tujuan_peminjaman,
            'nama_kegiatan' => $request->nama_kegiatan,
            'lokasi_penggunaan' => $request->lokasi_penggunaan,
            'tanggal_mulai' => $mulai,
            'tanggal_selesai' => $selesai,
            'batas_waktu_approval' => $batasWaktu,
            'status_pengelola' => 'Pending',
            'status_persetujuan' => 'Pending',
            'status_keseluruhan' => 'Menunggu Persetujuan'
        ]);

        // 5. Simpan Item Peminjaman (Multi-Kategori)
        foreach ($itemsData as $item) {
            $barang = Barang::find($item['barang_id']);
            
            PeminjamanItem::create([
                'peminjaman_id' => $peminjaman->id,
                'barang_id' => $barang->id,
                'jumlah' => $item['jumlah'],
                'kategori_saat_pinjam' => $barang->kategori_kode, // 'A', 'B', 'C', 'D', 'E', atau 'F'
            ]);
        }

        // Redirect langsung ke struk peminjaman
        return redirect()->route('peminjaman.struk', $peminjaman->id)
                         ->with('success', 'Pengajuan peminjaman berhasil dibuat! Silakan simpan atau cetak Struk Bukti Peminjaman berikut.');
    }

    // Menampilkan detail pengajuan
    public function show(Peminjaman $peminjaman)
    {
        if ($peminjaman->user_id !== Auth::id() && !Auth::guard('admin')->check()) {
            abort(403);
        }
        
        $peminjaman->load(['items.barang', 'basta', 'pengembalian', 'user']);
        return view('user.peminjaman.show', compact('peminjaman'));
    }

    // Menampilkan Struk / Bukti Peminjaman Aset
    public function struk(Peminjaman $peminjaman)
    {
        if ($peminjaman->user_id !== Auth::id() && !Auth::guard('admin')->check()) {
            abort(403);
        }

        $peminjaman->load(['items.barang', 'basta', 'pengembalian', 'user']);
        return view('user.peminjaman.struk', compact('peminjaman'));
    }

    // Export Riwayat Peminjaman User ke Format Excel
    public function exportExcel()
    {
        $peminjamans = Peminjaman::with(['user', 'items.barang'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        $namaUser = preg_replace('/[^A-Za-z0-9_-]/', '_', Auth::user()->name);
        $filename = 'Riwayat_Peminjaman_' . $namaUser . '_' . date('Ymd_His') . '.xls';

        return response()->view('user.peminjaman.export_excel', compact('peminjamans'))
            ->header('Content-Type', 'application/vnd.ms-excel; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Cache-Control', 'max-age=0');
    }

    // Proses User menerima aset
    public function terimaAset(Request $request, Peminjaman $peminjaman)
    {
        $request->validate([
            'ttd_digital_peminjam' => 'required|string', 
            'konfirmasi_checkbox' => 'accepted'
        ]);

        if ($peminjaman->basta) {
            $peminjaman->basta()->update([
                'ttd_digital_peminjam' => $request->ttd_digital_peminjam,
                'konfirmasi_checkbox_peminjam' => true
            ]);
        }

        $peminjaman->update(['status_keseluruhan' => 'Sedang Digunakan']);

        return back()->with('success', 'Aset berhasil diterima dan status telah diperbarui.');
    }

    // Konfirmasi Barang Sudah Diambil di Gudang oleh Peminjam
    public function konfirmasiAmbil(Peminjaman $peminjaman)
    {
        if ($peminjaman->user_id !== Auth::id()) {
            abort(403, 'Akses tidak sah.');
        }

        if (!in_array($peminjaman->status_keseluruhan, ['Disetujui', 'Aset Diserahkan'])) {
            return redirect()->route('peminjaman.riwayat')->with('error', 'Status peminjaman tidak valid untuk pengambilan barang.');
        }

        // Kurangi stok barang dan pasang PIC jika belum diupdate
        foreach ($peminjaman->items as $item) {
            $barang = $item->barang;
            if ($barang) {
                if ($barang->pic_saat_ini !== $peminjaman->user_id) {
                    $kurang = min($item->jumlah, $barang->jumlah_tersedia);
                    if ($kurang > 0) {
                        $barang->decrement('jumlah_tersedia', $kurang);
                    }
                    $barang->update([
                        'pic_saat_ini' => $peminjaman->user_id,
                        'status' => 'Dipinjam'
                    ]);
                }
            }
        }

        $peminjaman->update([
            'status_keseluruhan' => 'Sedang Digunakan'
        ]);

        return redirect()->route('peminjaman.riwayat')
            ->with('success', 'Konfirmasi berhasil! Barang telah diambil dari gudang. Status peminjaman kini Sedang Digunakan. Anda dapat mengajukan pengembalian aset setelah selesai.');
    }

    // Menampilkan halaman Katalog Barang
    public function katalog()
    {
        $kategoriBarang = Barang::all()->groupBy('kategori_label');
        return view('user.peminjaman.katalog', compact('kategoriBarang'));
    }

    // Menampilkan Form Pengembalian
    public function formPengembalian(Peminjaman $peminjaman)
    {
        if ($peminjaman->user_id !== Auth::id()) {
            abort(403, 'Akses tidak sah.');
        }

        if (!in_array($peminjaman->status_keseluruhan, ['Aset Diserahkan', 'Sedang Digunakan', 'Terlambat'])) {
            return redirect()->route('peminjaman.riwayat')->with('error', 'Status peminjaman belum valid untuk dikembalikan.');
        }

        $peminjaman->load(['items.barang', 'user']);
        return view('user.peminjaman.pengembalian', compact('peminjaman'));
    }

    // Proses Submit Pengembalian oleh User
    public function prosesPengembalian(Request $request, Peminjaman $peminjaman)
    {
        if ($peminjaman->user_id !== Auth::id()) {
            abort(403, 'Akses tidak sah.');
        }

        if (!in_array($peminjaman->status_keseluruhan, ['Aset Diserahkan', 'Sedang Digunakan', 'Terlambat'])) {
            return redirect()->route('peminjaman.riwayat')->with('error', 'Status peminjaman tidak valid untuk diajukan pengembalian.');
        }

        $request->validate([
            'tanggal_pengembalian' => 'required|date',
            'foto_kondisi_akhir' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            'catatan_kondisi_peminjam' => 'required|string|min:3',
            'konfirmasi_lengkap' => 'accepted',
        ], [
            'tanggal_pengembalian.required' => 'Tanggal pengembalian wajib diisi sesuai waktu barang dikembalikan.',
            'tanggal_pengembalian.date' => 'Format tanggal pengembalian tidak valid.',
            'foto_kondisi_akhir.required' => 'Wajib melampirkan foto fisik kondisi barang saat dikembalikan.',
            'foto_kondisi_akhir.image' => 'File bukti kondisi harus berupa gambar (JPG, PNG, WEBP).',
            'foto_kondisi_akhir.max' => 'Ukuran foto maksimal 5MB.',
            'catatan_kondisi_peminjam.required' => 'Catatan kondisi barang wajib diisi.',
            'konfirmasi_lengkap.accepted' => 'Anda wajib mencentang konfirmasi bahwa seluruh item barang dikembalikan lengkap.',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto_kondisi_akhir')) {
            $file = $request->file('foto_kondisi_akhir');
            $filename = time() . '_kembali_' . preg_replace('/[^A-Za-z0-9_\.-]/', '', $file->getClientOriginalName());
            
            // Simpan ke storage public agar konsisten
            $fotoPath = $file->storeAs('pengembalian_fotos', $filename, 'public');

            // Salin juga ke public/uploads/pengembalian sebagai fallback
            $uploadDir = public_path('uploads/pengembalian');
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            @copy(storage_path('app/public/' . $fotoPath), $uploadDir . '/' . $filename);
        }

        \App\Models\Pengembalian::updateOrCreate(
            ['peminjaman_id' => $peminjaman->id],
            [
                'tanggal_pengembalian' => $request->tanggal_pengembalian, 
                'foto_kondisi_akhir' => $fotoPath,
                'catatan_kondisi_peminjam' => $request->catatan_kondisi_peminjam,
                'kelengkapan_kembali' => $request->items_kembali ?? [],
                'checklist_kondisi_akhir' => [
                    'konfirmasi_lengkap' => true,
                    'kondisi_umum' => $request->kondisi_umum ?? 'Baik',
                    'catatan' => $request->catatan_kondisi_peminjam,
                ],
            ]
        );
        
        $peminjaman->update(['status_keseluruhan' => 'Menunggu Konfirmasi Pengembalian']);

        return redirect()->route('peminjaman.riwayat')->with('success', 'Pengajuan pengembalian berhasil diajukan secara lengkap! Menunggu verifikasi fisik oleh Admin Pengelola di gudang.');
    }
}