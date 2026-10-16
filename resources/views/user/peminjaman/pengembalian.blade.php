@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    
    <div class="mb-6 flex items-center gap-4">
        <a href="{{ route('peminjaman.riwayat') }}" class="text-gray-500 hover:text-pili-primary font-bold">&larr; Kembali ke Riwayat</a>
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Form Pengembalian Aset</h1>
    </div>

    <!-- Alert Edukasi -->
    <div class="bg-blue-50 border-l-4 border-pili-primary p-4 mb-8 rounded-r-xl">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-pili-primary" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" /></svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-blue-700 font-bold">Instruksi Pengembalian</p>
                <p class="text-xs text-blue-600 mt-1">Pastikan Anda telah membersihkan/merapikan aset sebelum dikembalikan. Silakan ambil foto keseluruhan aset bersama perlengkapannya sebagai bukti kondisi.</p>
            </div>
        </div>
    </div>

    <form action="{{ route('peminjaman.pengembalian.proses', $peminjaman->id) }}" method="POST" enctype="multipart/form-data" 
          x-data="{ 
              photoPreview: '',
              updatePreview(event) {
                  const reader = new FileReader();
                  reader.onload = (e) => { this.photoPreview = e.target.result; };
                  reader.readAsDataURL(event.target.files[0]);
              }
          }" 
          class="bg-white shadow-soft rounded-2xl p-6 md:p-8 border border-gray-100 grid grid-cols-1 md:grid-cols-2 gap-8">
        
        @csrf

        <!-- Kolom Kiri: Daftar Aset & Catatan -->
        <div class="space-y-6">
            <div>
                <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-2">Aset yang Dikembalikan:</h3>
                <ul class="space-y-2 bg-gray-50 p-4 rounded-xl border border-gray-100">
                    @foreach($peminjaman->items as $item)
                        <li class="flex justify-between items-center text-sm">
                            <span class="font-bold text-gray-800">{{ $item->barang->nama }}</span>
                            <span class="bg-white px-2 py-0.5 rounded border border-gray-200 font-bold">x{{ $item->jumlah }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Catatan Kondisi Barang (Opsional)</label>
                <textarea name="catatan_user" rows="4" class="w-full border-gray-300 rounded-xl p-3 bg-gray-50 focus:ring-pili-primary focus:border-pili-primary" placeholder="Tulis di sini jika ada kerusakan ringan saat penggunaan, kelengkapan yang hilang, dll..."></textarea>
            </div>
        </div>

        <!-- Kolom Kanan: Upload Foto -->
        <div class="flex flex-col">
            <label class="block text-sm font-bold text-gray-700 mb-2 text-center">Upload Foto Bukti Pengembalian <span class="text-red-500">*</span></label>
            
            <div class="w-full h-64 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-300 flex items-center justify-center overflow-hidden relative hover:bg-gray-100 transition group">
                
                <template x-if="!photoPreview">
                    <div class="text-center p-4">
                        <svg class="mx-auto h-12 w-12 text-gray-300 mb-2 group-hover:text-pili-primary transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <span class="text-sm font-bold text-gray-600 block mb-1">Ambil Gambar / Pilih File</span>
                        <span class="text-xs text-gray-400">Pastikan pencahayaan terang. Maks 2MB</span>
                    </div>
                </template>
                
                <template x-if="photoPreview">
                    <img :src="photoPreview" class="object-cover w-full h-full">
                </template>

                <input type="file" name="foto_bukti" accept="image/*" @change="updatePreview" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
            </div>

            <button type="submit" class="mt-auto w-full bg-orange-500 hover:bg-orange-600 text-white font-extrabold py-4 rounded-xl shadow-lg transition transform hover:-translate-y-1 flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Kirim Bukti Pengembalian
            </button>
        </div>
    </form>
</div>
@endsection