@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-8" x-data="{ showModal: false, modalData: {} }">
    
    <!-- HERO SECTION & SEARCH BAR WRAPPER (Dibuat Lebih Ramping/Kecil) -->
    <div class="relative bg-gradient-to-br from-pili-primary to-pili-dark text-white rounded-2xl p-6 sm:p-8 shadow-lg overflow-hidden">
        <!-- Dekorasi Background Tipis -->
        <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-white/10 rounded-full blur-xl pointer-events-none"></div>

        <div class="relative z-10 max-w-2xl mx-auto text-center space-y-2">
            <span class="inline-block px-3 py-1 rounded-full text-[10px] font-extrabold tracking-wider bg-white/20 text-white uppercase backdrop-blur-md">
                Inventaris & Aset Yayasan
            </span>
            <h1 class="text-2xl sm:text-3xl font-black tracking-tight">
                Katalog Aset Yayasan PILI
            </h1>
            <p class="text-white/80 text-xs sm:text-sm max-w-lg mx-auto font-medium">
                Cari, temukan, dan ajukan peminjaman aset dengan mudah dan cepat.
            </p>
        </div>

        <!-- FITUR SEARCH BAR (Compact / Lebih Kecil) -->
        <div class="max-w-xl mx-auto mt-6 relative z-20">
            <form action="{{ route('dashboard') }}" method="GET" class="bg-white rounded-xl sm:rounded-full shadow-lg p-1.5 sm:p-2 flex flex-col sm:flex-row items-center gap-2 border border-white/40">
                <div class="flex items-center w-full pl-3 pr-2 py-1.5 sm:py-0">
                    <svg class="w-4 h-4 text-gray-400 shrink-0 mr-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama barang, kode, atau kategori..." class="w-full text-gray-800 placeholder-gray-400 bg-transparent outline-none font-medium text-xs sm:text-sm">
                </div>
                
                <div class="flex items-center gap-2 w-full sm:w-auto">
                    @if(request('search'))
                        <a href="{{ route('dashboard') }}" class="hidden sm:inline-flex items-center justify-center px-3 py-2 text-xs font-bold text-red-500 hover:bg-red-50 rounded-full transition">
                            Reset
                        </a>
                    @endif
                    <button type="submit" class="w-full sm:w-auto bg-pili-primary hover:bg-pili-dark text-white font-bold py-2.5 px-6 rounded-lg sm:rounded-full transition shadow text-xs sm:text-sm tracking-wide flex items-center justify-center">
                        <span>Cari</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- KETERANGAN HASIL PENCARIAN -->
    @if(request('search'))
        <div class="flex flex-col sm:flex-row justify-between items-center gap-3 bg-blue-50 border border-blue-200 px-4 py-3 rounded-xl shadow-sm">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center text-blue-600 font-bold shrink-0 text-xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <p class="text-blue-900 text-xs sm:text-sm font-medium">Hasil pencarian untuk: <strong class="font-bold underline decoration-blue-300">"{{ request('search') }}"</strong></p>
            </div>
            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-1 text-xs font-bold text-red-600 hover:bg-red-100/60 bg-red-50 px-3 py-1.5 rounded-lg transition">
                <span>Reset Pencarian</span>
                <span class="text-sm">&times;</span>
            </a>
        </div>
    @endif

    <!-- DAFTAR BARANG (Per Kategori) -->
    <div class="space-y-10 pb-10">
        @forelse($kategoriBarang as $kategori => $barangs)
            <div class="space-y-4">
                <!-- Judul Kategori -->
                <div class="flex items-center justify-between border-b border-gray-200 pb-2.5">
                    <h2 class="text-lg sm:text-xl font-extrabold text-gray-900 tracking-tight flex items-center gap-2.5">
                        <span class="w-3 h-3 bg-pili-primary rounded-md shadow-sm"></span>
                        <span>{{ $kategori }}</span>
                        <span class="text-[11px] font-bold bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full">{{ count($barangs) }} Unit</span>
                    </h2>
                </div>
                
                <!-- Grid Produk Responsif -->
                <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                    @foreach($barangs as $barang)
                        <div class="group bg-white rounded-2xl p-3.5 border border-gray-100 shadow-soft hover:shadow-xl transition-all duration-300 flex flex-col justify-between h-full transform hover:-translate-y-1">
                            
                            <div>
                                <!-- Foto Barang -->
                                <div class="w-full h-32 sm:h-36 bg-gray-50 rounded-xl mb-3 flex items-center justify-center overflow-hidden relative border border-gray-100">
                                    @if($barang->foto)
                                        <img src="{{ asset($barang->foto) }}" alt="{{ $barang->nama }}" class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-500">
                                    @else
                                        <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    @endif

                                    <!-- Badge Stok di atas gambar -->
                                    <div class="absolute top-2 right-2">
                                        <span class="text-[10px] font-extrabold bg-white/90 backdrop-blur-md text-gray-800 px-2 py-0.5 rounded-md shadow-sm border border-gray-200/60">
                                            Stok: {{ $barang->jumlah_tersedia }}
                                        </span>
                                    </div>
                                </div>
                                
                                <!-- Info Singkat -->
                                <div class="space-y-0.5 mb-3">
                                    <p class="text-[10px] font-mono font-bold text-gray-400 uppercase tracking-wider">{{ $barang->kode_inventaris }}</p>
                                    <h3 class="font-bold text-gray-900 text-xs sm:text-sm leading-snug line-clamp-2 group-hover:text-pili-primary transition-colors">{{ $barang->nama }}</h3>
                                </div>
                            </div>

                            <div class="space-y-2.5 pt-2 border-t border-gray-50">
                                <!-- Kondisi -->
                                <div class="flex justify-between items-center text-[11px]">
                                    <span class="text-gray-400 font-medium">Kondisi</span>
                                    <span class="font-bold px-1.5 py-0.5 rounded {{ $barang->kondisi == 'Baik' ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-rose-50 text-rose-600 border border-rose-100' }}">
                                        {{ $barang->kondisi }}
                                    </span>
                                </div>

                                <!-- Tombol Modal Detail -->
                                <button @click="modalData = { nama: '{{ addslashes($barang->nama) }}', kode: '{{ $barang->kode_inventaris }}', deskripsi: '{{ addslashes($barang->deskripsi ?? 'Tidak ada deskripsi rinci.') }}', stok: '{{ $barang->jumlah_tersedia }}', kondisi: '{{ $barang->kondisi }}' }; showModal = true" class="w-full bg-gray-50 hover:bg-pili-primary hover:text-white text-gray-700 border border-gray-200 hover:border-transparent font-bold py-2 rounded-xl text-xs transition shadow-sm flex items-center justify-center gap-1">
                                    <span>Detail</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </button>
                            </div>

                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="text-center p-10 bg-white rounded-2xl border-2 border-dashed border-gray-200 shadow-sm space-y-3 max-w-md mx-auto">
                <div class="w-12 h-12 bg-gray-50 text-gray-400 rounded-xl flex items-center justify-center mx-auto shadow-inner">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                </div>
                <div class="space-y-1">
                    <p class="text-gray-900 font-extrabold text-base">Tidak ada aset ditemukan</p>
                    <p class="text-gray-500 text-xs">Coba gunakan kata kunci pencarian yang lain.</p>
                </div>
                <div>
                    <a href="{{ route('dashboard') }}" class="inline-block bg-pili-primary hover:bg-pili-dark text-white font-bold px-4 py-2 rounded-xl text-xs transition shadow">
                        Muat Ulang
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- MODAL DETAIL BARANG -->
    <div x-show="showModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        
        <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
            <!-- Backdrop -->
            <div x-show="showModal" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="showModal = false" 
                 class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity"></div>

            <!-- Modal Box -->
            <div x-show="showModal" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative bg-white rounded-3xl w-full max-w-lg p-6 sm:p-7 shadow-2xl text-left overflow-hidden transform transition-all my-8">
                
                <div class="flex justify-between items-start mb-4 border-b border-gray-100 pb-3">
                    <div>
                        <span class="text-[11px] font-mono font-bold text-gray-400 uppercase tracking-wider" x-text="modalData.kode"></span>
                        <h3 class="text-lg sm:text-xl font-extrabold text-gray-900 mt-0.5" x-text="modalData.nama"></h3>
                    </div>
                    <button @click="showModal = false" class="w-7 h-7 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-500 hover:text-gray-800 flex items-center justify-center font-bold transition">&times;</button>
                </div>

                <div class="grid grid-cols-2 gap-3 mb-4">
                    <div class="bg-gray-50 p-3 rounded-xl border border-gray-100">
                        <p class="text-[11px] text-gray-400 font-medium mb-0.5">Kondisi Barang</p>
                        <p class="text-xs sm:text-sm font-bold text-emerald-600" x-text="modalData.kondisi"></p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-xl border border-gray-100">
                        <p class="text-[11px] text-gray-400 font-medium mb-0.5">Stok Tersedia</p>
                        <p class="text-xs sm:text-sm font-bold text-gray-900"><span x-text="modalData.stok"></span> Unit</p>
                    </div>
                </div>

                <div class="space-y-1 mb-6">
                    <p class="text-xs font-bold text-gray-700 uppercase tracking-wider">Deskripsi Rinci:</p>
                    <p class="text-xs sm:text-sm text-gray-600 bg-gray-50 p-3 rounded-xl border border-gray-100 leading-relaxed min-h-[70px]" x-text="modalData.deskripsi"></p>
                </div>

                <div class="flex flex-col sm:flex-row gap-2.5 justify-end pt-1">
                    <button @click="showModal = false" class="w-full sm:w-auto px-4 py-2.5 rounded-xl border border-gray-200 text-gray-700 font-bold text-xs sm:text-sm hover:bg-gray-50 transition">
                        Tutup
                    </button>
                    <a href="{{ route('peminjaman.create') }}" class="w-full sm:w-auto bg-pili-primary hover:bg-pili-dark text-white px-5 py-2.5 rounded-xl font-bold text-xs sm:text-sm transition shadow-md hover:shadow-lg text-center flex items-center justify-center gap-1.5">
                        <span>Buka Form Pinjam</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection