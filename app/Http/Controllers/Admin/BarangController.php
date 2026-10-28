<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class BarangController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(function ($request, $next) {
                $admin = Auth::guard('admin')->user();
                if ($admin && $admin->role !== 'pengelola') {
                    return redirect()->route('admin.dashboard')->with('error', 'Akses ditolak: Fitur Register Aset hanya dapat diakses oleh Admin Pengelola.');
                }
                return $next($request);
            })
        ];
    }

    // Menampilkan daftar semua aset
    public function index()
    {
        $barangs = Barang::orderBy('created_at', 'desc')->get();
        return view('admin.barang.index', compact('barangs'));
    }

    // Menampilkan form tambah aset baru
    public function create()
    {
        return view('admin.barang.create');
    }

    // Menyimpan aset baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'kode_inventaris' => 'required|unique:barangs',
            'nama' => 'required',
            'kategori' => 'required',
            'jumlah_total' => 'required|integer|min:1',
            'kondisi' => 'required',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'deskripsi' => 'required',
            'lokasi_penyimpanan' => 'nullable'
        ]);

        $data = $request->all();
        $data['jumlah_tersedia'] = $request->jumlah_total;

        // PERBAIKAN: Simpan langsung ke folder public/uploads dengan penamaan aman
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            // Menghasilkan nama file acak untuk mencegah error karena spasi/simbol
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('uploads/barang_fotos');

            // Membuat folder secara otomatis jika belum ada di server
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $file->move($destinationPath, $filename);
            $data['foto'] = 'uploads/barang_fotos/' . $filename;
        }

        Barang::create($data);

        return redirect()->route('admin.barang.index')->with('success', 'Barang baru berhasil ditambahkan!');
    }

    // Menampilkan detail atau form edit aset
    public function show($id)
    {
        $barang = Barang::findOrFail($id);
        return view('admin.barang.edit', compact('barang'));
    }

    // Menampilkan form edit aset
    public function edit($id)
    {
        $barang = Barang::findOrFail($id);
        return view('admin.barang.edit', compact('barang'));
    }

    // Menyimpan perubahan aset ke database
    public function update(Request $request, $id)
    {
        $barang = Barang::findOrFail($id);

        $request->validate([
            'kode_inventaris' => 'required|unique:barangs,kode_inventaris,'.$id,
            'nama' => 'required',
            'kategori' => 'required',
            'jumlah_total' => 'required|integer|min:1',
            'kondisi' => 'required',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'deskripsi' => 'required'
        ]);

        $data = $request->except('foto');

        // Perbaikan penghitungan selisih stok
        $selisih = $request->jumlah_total - $barang->jumlah_total;
        $data['jumlah_tersedia'] = $barang->jumlah_tersedia + $selisih;

        // PERBAIKAN: Simpan langsung ke folder public/uploads dengan penamaan aman
        if ($request->hasFile('foto')) {
            // Hapus foto fisik yang lama jika ada untuk menghemat penyimpanan
            if ($barang->foto && file_exists(public_path($barang->foto))) {
                unlink(public_path($barang->foto));
            }
            
            // Upload foto baru
            $file = $request->file('foto');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('uploads/barang_fotos');

            // Membuat folder secara otomatis jika belum ada di server
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $file->move($destinationPath, $filename);
            $data['foto'] = 'uploads/barang_fotos/' . $filename;
        }

        $barang->update($data);

        return redirect()->route('admin.barang.index')->with('success', 'Data Aset berhasil diperbarui!');
    }
    
    // Menghapus aset dari database beserta file fotonya
    public function destroy($id)
    {
        $barang = \App\Models\Barang::findOrFail($id);

        // Hapus foto fisik dari folder public jika file tersebut ada
        if ($barang->foto && file_exists(public_path($barang->foto))) {
            unlink(public_path($barang->foto));
        }

        // Hapus data dari database
        $barang->delete();

        return redirect()->route('admin.barang.index')->with('success', 'Data Aset dan fotonya berhasil dihapus permanen!');
    }
}