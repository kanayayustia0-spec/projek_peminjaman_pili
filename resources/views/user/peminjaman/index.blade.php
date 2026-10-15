@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-10" x-data="{ showModal: false, modalData: {} }">
    
    <!-- HERO SECTION -->
    <div class="relative bg-pili-primary text-white rounded-3xl p-8 md:p-12 shadow-xl overflow-hidden">
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="max-w-2xl">
                <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight mb-4">
                    Katalog Aset Yayasan PILI
                </h1>
                <p class="text-pili-light text-lg mb-8 leading-relaxed">
                    Cari dan pilih aset yang Anda butuhkan untuk keperluan lapangan maupun operasional.
                </p>
            </div>
        </div>
    </div>

    <!-- FITUR SEARCH BAR -->
    <form action="{{ route('dashboard') }}" method="GET" class="flex max-w-3xl mx-auto -mt-16 relative z-20">
        <div class="flex w-full bg-white rounded-full shadow-lg border-2 border-pili-light overflow-hidden">
            <div class="flex items-center pl-6 pr-3">
                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama barang, kode inventaris, atau kategori..." class="w-full py-4 px-2 text-gray-700 outline-none font-medium">
            <button type="submit" class="bg-pili-primary hover:bg-pili-dark text-white font-bold py-4 px-8 transition">
                Cari Aset
            </button>
        </div>
    </form>

    <!-- KETERANGAN HASIL PENCARIAN -->
    @if(request('search'))
        <div class="flex justify-between items-center bg-blue-50 border border-blue-200 p-4 rounded-xl">
            <p class="text-blue-800 font-medium">Menampilkan hasil pencarian untuk: <strong>"{{ request('search') }}"</strong></p>
            <a href="{{ route('dashboard') }}" class="text-sm font-bold text-red-500 hover:underline">Reset Pencarian &times;</a>
        </div>
    @endif

    <!-- DAFTAR BARANG (Per Kategori) -->
    <div class="space-y-12 pb-10">
        @forelse($kategoriBarang as $kategori => $barangs)
            <div>
                <div class="flex items-center justify-between border-b-2 border-gray-200 pb-2 mb-6">
                    <h2 class="text-xl font-extrabold text-gray-800 uppercase tracking-wide flex items-center gap-2">
                        <span class="w-3 h-3 bg-pili-primary rounded-full"></span>
                        {{ $kategori }}
                    </h2>
                </div>
                
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
                    @foreach($barangs as $barang)
                        <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-soft hover:shadow-md transition flex flex-col h-full">
                            
                            <!-- Foto -->
                            <div class="w-full h-32 bg-gray-100 rounded-xl mb-3 flex items-center justify-center overflow-hidden">
                                @if($barang->foto)
                                    <img src="{{ asset($barang->foto) }}" alt="{{ $barang->nama }}" class="object-cover w-full h-full">
                                @else
                                    <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                @endif
                            </div>
                            
                            <!-- Info Singkat -->
                            <div class="flex-grow">
                                <p class="text-[10px] font-bold text-gray-400 uppercase">{{ $barang->kode_inventaris }}</p>
                                <h3 class="font-bold text-gray-800 text-sm leading-tight mb-2">{{ $barang->nama }}</h3>
                                
                                <div class="flex justify-between items-center mb-3">
                                    <span class="text-[11px] font-bold {{ $barang->kondisi == 'Baik' ? 'text-green-600' : 'text-red-500' }}">{{ $barang->kondisi }}</span>
                                    <span class="text-[11px] bg-blue-50 text-blue-700 px-2 py-0.5 rounded font-bold border border-blue-100">Stok: {{ $barang->jumlah_tersedia }}</span>
                                </div>
                            </div>

                            <!-- Tombol Modal Detail -->
                            <button @click="modalData = { nama: '{{ addslashes($barang->nama) }}', kode: '{{ $barang->kode_inventaris }}', deskripsi: '{{ addslashes($barang->deskripsi ?? 'Tidak ada deskripsi rinci.') }}', stok: '{{ $barang->jumlah_tersedia }}', kondisi: '{{ $barang->kondisi }}' }; showModal = true" class="w-full bg-gray-50 hover:bg-gray-100 text-gray-700 border border-gray-200 font-bold py-2 rounded-lg text-xs transition">
                                Detail Barang
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="text-center p-12 bg-white rounded-2xl border-2 border-dashed border-gray-200">
                <p class="text-gray-500 font-bold text-lg">Tidak ada aset yang ditemukan.</p>
            </div>
        @endforelse
    </div>

    <!-- MODAL DETAIL BARANG (Sama seperti sebelumnya) -->
    <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div x-show="showModal" @click="showModal = false" class="fixed inset-0 bg-gray-900 bg-opacity-50 backdrop-blur-sm"></div>
            <div x-show="showModal" class="relative bg-white rounded-2xl w-full max-w-lg p-6 shadow-xl">
                <div class="flex justify-between items-center mb-4 border-b pb-3">
                    <h3 class="text-xl font-extrabold text-gray-900" x-text="modalData.nama"></h3>
                    <button @click="showModal = false" class="text-gray-400 hover:text-red-500 font-bold text-xl">&times;</button>
                </div>
                <div class="mb-4 space-y-2">
                    <p class="text-sm"><span class="font-bold text-gray-700">Kode:</span> <span x-text="modalData.kode" class="bg-gray-100 px-2 py-0.5 rounded text-xs font-mono"></span></p>
                    <p class="text-sm"><span class="font-bold text-gray-700">Kondisi:</span> <span x-text="modalData.kondisi" class="text-green-600 font-bold"></span></p>
                    <p class="text-sm"><span class="font-bold text-gray-700">Stok:</span> <span x-text="modalData.stok" class="font-bold"></span> Unit</p>
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-700 mb-1">Deskripsi:</p>
                    <p class="text-sm text-gray-600 bg-gray-50 p-3 rounded-xl border border-gray-100" x-text="modalData.deskripsi"></p>
                </div>
                <div class="mt-6 flex justify-end">
                    <a href="{{ route('peminjaman.create') }}" class="bg-pili-primary hover:bg-pili-dark text-white px-6 py-2 rounded-xl font-bold transition">Buka Form Pinjam</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection