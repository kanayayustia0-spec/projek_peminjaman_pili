@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4">
    <div class="flex items-center mb-6 space-x-4">
        <a href="{{ route('admin.barang.index') }}" class="text-gray-500 hover:text-pili-primary font-bold">&larr; Kembali</a>
        <h1 class="text-2xl font-bold text-pili-primary">Tambah Aset Baru</h1>
    </div>

    <!-- Menampilkan pesan error validasi jika ada -->
    @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.barang.store') }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-soft rounded-2xl p-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Kode Inventaris -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Kode Inventaris (Wajib & Unik)</label>
                <input type="text" name="kode_inventaris" value="{{ old('kode_inventaris') }}" required class="w-full border border-gray-300 rounded-lg p-2 mt-1 focus:ring-pili-primary focus:border-pili-primary">
                <p class="text-xs text-gray-500 mt-1">Contoh: CAM-001, LPT-015</p>
            </div>

            <!-- Nama Barang -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Nama Barang</label>
                <input type="text" name="nama" value="{{ old('nama') }}" required class="w-full border border-gray-300 rounded-lg p-2 mt-1">
            </div>

            <!-- Kategori -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Kategori (Sesuai SOP PILI)</label>
                <select name="kategori" required class="w-full border border-gray-300 rounded-lg p-2 mt-1">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach(\App\Models\Barang::KATEGORI_LIST as $code => $label)
                        <option value="{{ $label }}" {{ old('kategori') == $label ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Kondisi -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Kondisi Saat Ini</label>
                <select name="kondisi" required class="w-full border border-gray-300 rounded-lg p-2 mt-1">
                    <option value="Baik" {{ old('kondisi') == 'Baik' ? 'selected' : '' }}>Baik</option>
                    <option value="Rusak Ringan" {{ old('kondisi') == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                    <option value="Rusak Berat" {{ old('kondisi') == 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                </select>
            </div>

            <!-- Jumlah Total -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Jumlah Aset (Qty)</label>
                <input type="number" name="jumlah_total" value="{{ old('jumlah_total', 1) }}" min="1" required class="w-full border border-gray-300 rounded-lg p-2 mt-1">
            </div>

            <!-- Lokasi Penyimpanan -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Lokasi Penyimpanan</label>
                <input type="text" name="lokasi_penyimpanan" value="{{ old('lokasi_penyimpanan', 'Gudang PILI') }}" required class="w-full border border-gray-300 rounded-lg p-2 mt-1">
            </div>
        </div>

        <!-- Deskripsi -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700">Deskripsi Detail & Spesifikasi</label>
            <textarea name="deskripsi" rows="3" required class="w-full border border-gray-300 rounded-lg p-2 mt-1">{{ old('deskripsi') }}</textarea>
        </div>

        <!-- Upload Gambar -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700">Foto Aset (Opsional)</label>
            <input type="file" name="foto" accept="image/png, image/jpeg" class="w-full border border-gray-300 rounded-lg p-2 mt-1 bg-gray-50">
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-pili-primary text-white font-bold py-3 px-6 rounded-xl hover:bg-pili-dark transition shadow">
                Simpan ke Database
            </button>
        </div>
    </form>
</div>
@endsection