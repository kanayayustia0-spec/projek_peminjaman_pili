@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    
    <div class="mb-6 flex items-center gap-4">
        <a href="{{ route('admin.barang.index') }}" class="text-gray-500 hover:text-pili-primary font-bold">&larr; Kembali</a>
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Edit Data Aset</h1>
    </div>
    
    <!-- Form Edit Data Barang -->
    <form action="{{ route('admin.barang.update', $barang->id) }}" method="POST" enctype="multipart/form-data" 
          x-data="{ 
              photoPreview: '{{ $barang->foto ? asset('storage/'.$barang->foto) : '' }}',
              updatePreview(event) {
                  const reader = new FileReader();
                  reader.onload = (e) => { this.photoPreview = e.target.result; };
                  reader.readAsDataURL(event.target.files[0]);
              }
          }" 
          class="bg-white shadow-soft rounded-2xl p-6 md:p-8 border border-gray-100">
        
        @csrf
        @method('PUT')

        @if ($errors->any())
            <div class="bg-red-50 text-red-700 p-4 rounded-xl mb-6">
                <ul class="list-disc pl-5 font-medium text-sm space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            
            <!-- Kolom Kiri: Upload Foto -->
            <div class="col-span-1 flex flex-col items-center">
                <label class="block text-sm font-bold text-gray-700 mb-2 w-full text-center">Foto Barang (Opsional)</label>
                
                <!-- Kotak Preview -->
                <div class="w-full aspect-square bg-gray-50 rounded-2xl border-2 border-dashed border-gray-300 flex items-center justify-center overflow-hidden mb-4 relative hover:bg-gray-100 transition">
                    
                    <template x-if="!photoPreview">
                        <div class="text-center p-4">
                            <svg class="mx-auto h-12 w-12 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span class="text-xs text-gray-500 font-medium">Belum ada foto</span>
                        </div>
                    </template>
                    
                    <template x-if="photoPreview">
                        <img :src="photoPreview" class="object-cover w-full h-full">
                    </template>

                    <!-- Tombol overlay untuk klik file -->
                    <input type="file" name="foto" accept="image/*" @change="updatePreview" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                </div>
                <p class="text-[10px] text-gray-400 text-center">Format: JPG, PNG (Maks 2MB). Klik kotak untuk mengubah foto.</p>
            </div>

            <!-- Kolom Kanan: Form Data -->
            <div class="col-span-1 md:col-span-2 space-y-5">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Kode Inventaris</label>
                        <input type="text" name="kode_inventaris" value="{{ old('kode_inventaris', $barang->kode_inventaris) }}" required class="w-full border-gray-300 rounded-lg p-2.5 bg-gray-50 focus:ring-pili-primary focus:border-pili-primary border">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Kategori Barang</label>
                        <select name="kategori" required class="w-full border-gray-300 rounded-lg p-2.5 bg-gray-50 focus:ring-pili-primary focus:border-pili-primary border">
                            @foreach(['Alat Elektronik & Dokumentasi', 'Alat Navigasi & Survei Lapangan', 'Perlengkapan Logistik & Camping', 'Perlengkapan Komunikasi & Keselamatan', 'Perlengkapan Sosialisasi & Edukasi', 'Kendaraan Operasional'] as $kat)
                                <option value="{{ $kat }}" {{ $barang->kategori == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Nama Aset / Barang</label>
                    <input type="text" name="nama" value="{{ old('nama', $barang->nama) }}" required placeholder="Contoh: Kamera Canon EOS 60D" class="w-full border-gray-300 rounded-lg p-2.5 bg-gray-50 focus:ring-pili-primary focus:border-pili-primary border">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Total Stok Keseluruhan</label>
                        <input type="number" name="jumlah_total" value="{{ old('jumlah_total', $barang->jumlah_total) }}" min="1" required class="w-full border-gray-300 rounded-lg p-2.5 bg-gray-50 focus:ring-pili-primary focus:border-pili-primary border">
                        <p class="text-xs text-blue-500 mt-1 font-bold">Stok yang sedang di gudang saat ini: {{ $barang->jumlah_tersedia }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Kondisi Barang</label>
                        <select name="kondisi" required class="w-full border-gray-300 rounded-lg p-2.5 bg-gray-50 focus:ring-pili-primary focus:border-pili-primary border">
                            <option value="Baik" {{ $barang->kondisi == 'Baik' ? 'selected' : '' }}>Baik</option>
                            <option value="Rusak Ringan" {{ $barang->kondisi == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                            <option value="Rusak Berat" {{ $barang->kondisi == 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Deskripsi Tambahan</label>
                    <textarea name="deskripsi" rows="3" class="w-full border-gray-300 rounded-lg p-2.5 bg-gray-50 focus:ring-pili-primary focus:border-pili-primary border" placeholder="Tuliskan spesifikasi kelengkapan, dll...">{{ old('deskripsi', $barang->deskripsi) }}</textarea>
                </div>

            </div>
        </div>

        <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end">
            <button type="submit" class="bg-pili-primary text-white font-extrabold py-3 px-8 rounded-xl shadow-md hover:bg-pili-dark transition transform hover:-translate-y-0.5">
                Simpan Perubahan Data
            </button>
        </div>
    </form>
</div>
@endsection