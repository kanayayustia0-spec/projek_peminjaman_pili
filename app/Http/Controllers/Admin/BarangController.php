<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
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
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->all();
        $data['jumlah_tersedia'] = $request->jumlah_total;

        // Simpan langsung ke folder public/uploads
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/barang_fotos'), $filename);
            $data['foto'] = 'uploads/barang_fotos/' . $filename;
        }

        Barang::create($data);

        return redirect()->route('admin.barang.index')->with('success', 'Barang baru berhasil ditambahkan!');
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
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->except('foto');

        // Perbaikan kode penghitungan selisih stok yang sebelumnya terpotong
        $selisih = $request->jumlah_total - $barang->jumlah_total;
        $data['jumlah_tersedia'] = $barang->jumlah_tersedia + $selisih;

        // Simpan langsung ke folder public/uploads
        if ($request->hasFile('foto')) {
            // Hapus foto fisik yang lama jika ada
            if ($barang->foto && file_exists(public_path($barang->foto))) {
                unlink(public_path($barang->foto));
            }
            
            // Upload foto baru
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/barang_fotos'), $filename);
            $data['foto'] = 'uploads/barang_fotos/' . $filename;
        }

        $barang->update($data);

        return redirect()->route('admin.barang.index')->with('success', 'Data Aset berhasil diperbarui!');
    }
}