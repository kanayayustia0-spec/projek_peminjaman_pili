@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-gray-900">Form Pengajuan Peminjaman</h1>
        <p class="text-gray-500 text-sm mt-1">Lengkapi data 5W (Who, What, Why, Where, When) dan masukkan barang ke keranjang.</p>
    </div>

    <form action="{{ route('peminjaman.store') }}" method="POST" x-data="{ 
        cart: {}, 
        tambah(id, max) { 
            if(!this.cart[id]) this.cart[id] = 0; 
            if(this.cart[id] < max) this.cart[id]++; 
        },
        kurang(id) { 
            if(this.cart[id] > 0) this.cart[id]--; 
            if(this.cart[id] === 0) delete this.cart[id]; 
        }
    }">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Kolom Kiri: Keranjang Shopee (Semua Kategori) -->
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-2xl shadow-soft p-6 border border-gray-100">
                    <h3 class="font-extrabold text-lg text-gray-900 border-b pb-3 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-pili-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        Pilih Aset (Keranjang)
                    </h3>
                    
                    <div class="h-96 overflow-y-auto space-y-3 pr-2">
                        @foreach(\App\Models\Barang::where('jumlah_tersedia', '>', 0)->get() as $b)
                            <div class="flex items-center justify-between p-3 border border-gray-100 rounded-xl bg-gray-50 hover:bg-white transition">
                                <div class="w-3/5">
                                    <p class="text-[9px] font-bold text-gray-400 uppercase truncate">{{ $b->kategori }}</p>
                                    <h4 class="text-sm font-bold text-gray-800 leading-tight">{{ $b->nama }}</h4>
                                    <p class="text-xs text-gray-500 mt-0.5">Sisa: {{ $b->jumlah_tersedia }}</p>
                                </div>
                                
                                <!-- Tombol Plus Minus -->
                                <div class="flex items-center gap-2 bg-white border border-gray-200 rounded-lg p-1 shadow-sm">
                                    <button type="button" @click="kurang({{ $b->id }})" class="w-6 h-6 flex items-center justify-center bg-gray-100 text-gray-600 rounded hover:bg-gray-200 font-bold">-</button>
                                    <span class="w-4 text-center text-xs font-bold" x-text="cart[{{ $b->id }}] || 0"></span>
                                    <button type="button" @click="tambah({{ $b->id }}, {{ $b->jumlah_tersedia }})" class="w-6 h-6 flex items-center justify-center bg-pili-light text-pili-primary rounded hover:bg-pili-primary hover:text-white font-bold">+</button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Form Data Kegiatan 5W -->
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-soft p-6 md:p-8 border border-gray-100">
                <h3 class="font-extrabold text-lg text-gray-900 border-b pb-3 mb-6">Detail Kegiatan & Peminjam</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Nama Kegiatan (What)</label>
                        <input type="text" name="nama_kegiatan" required class="w-full border-gray-300 rounded-lg p-3 bg-gray-50 focus:ring-pili-primary focus:border-pili-primary border">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Lokasi Penggunaan (Where)</label>
                        <input type="text" name="lokasi_penggunaan" required class="w-full border-gray-300 rounded-lg p-3 bg-gray-50 focus:ring-pili-primary focus:border-pili-primary border">
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 mb-1">Tujuan / Urgensi (Why)</label>
                    <textarea name="tujuan_peminjaman" required rows="2" class="w-full border-gray-300 rounded-lg p-3 bg-gray-50 focus:ring-pili-primary focus:border-pili-primary border"></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Tanggal Mulai (When)</label>
                        <input type="datetime-local" name="tanggal_mulai" required class="w-full border-gray-300 rounded-lg p-3 bg-gray-50 focus:ring-pili-primary focus:border-pili-primary border">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Tanggal Selesai (When)</label>
                        <input type="datetime-local" name="tanggal_selesai" required class="w-full border-gray-300 rounded-lg p-3 bg-gray-50 focus:ring-pili-primary focus:border-pili-primary border">
                    </div>
                </div>

                <!-- Input Tersembunyi untuk Data Keranjang Shopee -->
                <template x-for="(qty, id) in cart" :key="id">
                    <div>
                        <input type="hidden" name="barang_id[]" :value="id">
                        <input type="hidden" name="jumlah[]" :value="qty">
                    </div>
                </template>

                <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 mb-6">
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" required class="mt-1 w-5 h-5 text-pili-primary rounded border-gray-300 focus:ring-pili-primary">
                        <span class="text-sm text-blue-900 font-medium leading-relaxed">
                            Saya (Peminjam) bertanggung jawab penuh atas aset yang dipinjam. Jika terjadi kerusakan/kehilangan, saya siap mematuhi prosedur yang berlaku di Yayasan PILI.
                        </span>
                    </label>
                </div>

                <button type="submit" class="w-full bg-pili-primary hover:bg-pili-dark text-white font-extrabold py-4 px-6 rounded-xl shadow-lg transition transform hover:-translate-y-1 text-lg">
                    AJUKAN PEMINJAMAN SEKARANG
                </button>
            </div>
        </div>
    </form>
</div>
@endsection