@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-5 sm:py-8 px-3.5 sm:px-6 lg:px-8 space-y-6 sm:space-y-8" x-data="{ showModal: false, modalData: {} }">
    
    <!-- NOTIFIKASI SUCCESS / ERROR -->
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 sm:p-5 rounded-2xl flex items-start sm:items-center gap-3 shadow-sm">
            <div class="w-8 h-8 rounded-xl bg-emerald-100 flex items-center justify-center text-emerald-600 font-bold shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <div>
                <p class="font-bold text-sm">Berhasil!</p>
                <p class="text-xs text-emerald-700 leading-relaxed">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-rose-50 border border-rose-200 text-rose-800 p-4 sm:p-5 rounded-2xl flex items-start sm:items-center gap-3 shadow-sm">
            <div class="w-8 h-8 rounded-xl bg-rose-100 flex items-center justify-center text-rose-600 font-bold shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </div>
            <div>
                <p class="font-bold text-sm">Perhatian</p>
                <p class="text-xs text-rose-700 leading-relaxed">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <!-- HERO SECTION PROFESIONAL & RESPONSIVE HP -->
    <div class="relative bg-gradient-to-r from-[#0F3963] via-[#1A6BB8] to-[#2585DE] text-white rounded-2xl sm:rounded-3xl p-5 sm:p-8 lg:p-12 shadow-xl overflow-hidden border border-blue-400/20">
        <!-- Dekorasi Gradien Halus -->
        <div class="absolute -right-16 -bottom-16 w-60 sm:w-80 h-60 sm:h-80 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
        <div class="absolute right-1/4 -top-12 w-36 sm:w-48 h-36 sm:h-48 bg-emerald-400/10 rounded-full blur-xl pointer-events-none"></div>

        <div class="relative z-10 max-w-3xl space-y-3 sm:space-y-4">
            <div class="inline-flex items-center gap-2 px-3 py-1 sm:px-3.5 sm:py-1.5 rounded-full text-[10px] sm:text-xs font-bold tracking-wide bg-white/15 text-blue-50 backdrop-blur-md border border-white/20">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>PILI Green Network • Asset Portal</span>
            </div>

            <h1 class="text-2xl sm:text-3xl lg:text-5xl font-black tracking-tight leading-tight">
                Selamat Datang di Website Peminjaman PILI
            </h1>

            <p class="text-blue-100 text-xs sm:text-sm lg:text-base leading-relaxed font-normal">
                Sistem Informasi Terpadu peminjaman inventaris dan operasional Yayasan Pusat Informasi Lingkungan Indonesia. Memfasilitasi efisiensi operasional kegiatan konservasi, riset lapangan, multimedia, dan program kerja dengan transparansi dan akuntabilitas.
            </p>

            <!-- Tombol Aksi Cepat (Responsif Mobile) -->
            <div class="pt-2 sm:pt-4 flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5 sm:gap-3">
                <a href="{{ route('peminjaman.create') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-white text-pili-dark hover:bg-blue-50 font-extrabold px-5 py-3 sm:py-3.5 rounded-xl shadow-lg transition duration-200 text-xs sm:text-sm text-center">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-pili-primary shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                    <span>Ajukan Pinjam Aset</span>
                </a>

                <a href="{{ route('peminjaman.riwayat') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-white/15 hover:bg-white/25 text-white font-bold px-4 py-3 sm:py-3.5 rounded-xl backdrop-blur-md border border-white/20 transition text-xs sm:text-sm text-center">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>Riwayat & Struk Saya</span>
                </a>

                <a href="{{ route('peminjaman.export') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-emerald-600/90 hover:bg-emerald-600 text-white font-bold px-4 py-3 sm:py-3.5 rounded-xl backdrop-blur-md border border-emerald-400/30 transition text-xs sm:text-sm text-center">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span>Export Excel</span>
                </a>
            </div>
        </div>
    </div>

    <!-- STATISTIK KARTU METRIK (RESPONSIF HP: 2 KOLOM RAPI) -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-5">
        <!-- Kartu 1: Aset Tersedia -->
        <div class="bg-white rounded-2xl p-3.5 sm:p-5 border border-gray-100 shadow-soft">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-gray-400">Siap Pinjam</span>
                <div class="w-7 h-7 sm:w-9 sm:h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="flex items-baseline gap-1.5">
                <span class="text-2xl sm:text-3xl font-extrabold text-gray-900">{{ $stats['total_tersedia'] ?? 0 }}</span>
                <span class="text-[10px] sm:text-xs font-bold text-emerald-600">Unit</span>
            </div>
            <p class="text-[10px] text-gray-400 mt-1 truncate">Tersedia saat ini</p>
        </div>

        <!-- Kartu 2: Aset Habis / Terpakai -->
        <div class="bg-white rounded-2xl p-3.5 sm:p-5 border border-gray-100 shadow-soft">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-gray-400">Habis / Dipinjam</span>
                <div class="w-7 h-7 sm:w-9 sm:h-9 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
            </div>
            <div class="flex items-baseline gap-1.5">
                <span class="text-2xl sm:text-3xl font-extrabold text-gray-900">{{ $stats['total_habis'] ?? 0 }}</span>
                <span class="text-[10px] sm:text-xs font-bold text-rose-600">Unit</span>
            </div>
            <p class="text-[10px] text-gray-400 mt-1 truncate">Tidak dapat diajukan</p>
        </div>

        <!-- Kartu 3: Total Kategori SOP -->
        <div class="bg-white rounded-2xl p-3.5 sm:p-5 border border-gray-100 shadow-soft">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-gray-400">Kategori Aset</span>
                <div class="w-7 h-7 sm:w-9 sm:h-9 rounded-xl bg-blue-50 text-pili-primary flex items-center justify-center font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
            </div>
            <div class="flex items-baseline gap-1.5">
                <span class="text-2xl sm:text-3xl font-extrabold text-gray-900">{{ $stats['total_kategori'] ?? 6 }}</span>
                <span class="text-[10px] sm:text-xs font-bold text-pili-primary">Kategori</span>
            </div>
            <p class="text-[10px] text-gray-400 mt-1 truncate">Kategori A s/d F</p>
        </div>

        <!-- Kartu 4: Peminjaman Aktif Saya -->
        <div class="bg-white rounded-2xl p-3.5 sm:p-5 border border-gray-100 shadow-soft">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-gray-400">Peminjaman Aktif</span>
                <div class="w-7 h-7 sm:w-9 sm:h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </div>
            </div>
            <div class="flex items-baseline gap-1.5">
                <span class="text-2xl sm:text-3xl font-extrabold text-gray-900">{{ $stats['pinjaman_aktif_saya'] ?? 0 }}</span>
                <span class="text-[10px] sm:text-xs font-bold text-indigo-600">Transaksi</span>
            </div>
            <p class="text-[10px] text-gray-400 mt-1 truncate">
                <a href="{{ route('peminjaman.riwayat') }}" class="text-pili-primary font-bold hover:underline">Lihat Riwayat &rarr;</a>
            </p>
        </div>
    </div>

    <!-- PENGINGAT REGULASI PEMINJAMAN (MAX 2 HARI) -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200/80 rounded-2xl p-4 sm:p-5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 sm:gap-4">
        <div class="flex items-start gap-3">
            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center font-bold shrink-0 shadow-sm mt-0.5 sm:mt-0">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <h4 class="text-xs sm:text-sm font-extrabold text-blue-950">Ketentuan Durasi Peminjaman Aset PILI</h4>
                <p class="text-[11px] sm:text-xs text-blue-800/90 mt-0.5 leading-relaxed">
                    Sesuai Standar Operasional Prosedur (SOP), setiap peminjaman berlaku <strong>maksimal 2 hari (48 jam)</strong>. Pengajuan dapat menggabungkan berbagai kategori barang (A s/d F) sekaligus.
                </p>
            </div>
        </div>
        <a href="{{ route('cara-kerja') }}" class="w-full sm:w-auto text-center shrink-0 text-xs font-bold bg-white text-pili-primary hover:bg-pili-light border border-blue-200 px-4 py-2.5 rounded-xl transition shadow-sm">
            Lihat Alur & SOP &rarr;
        </a>
    </div>

    <!-- SECTION KATALOG BARANG DENGAN FILTER & SEARCH BAR -->
    <div class="space-y-4 sm:space-y-6 pt-1">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h2 class="text-xl sm:text-2xl font-black text-gray-900 tracking-tight">Katalog Aset & Inventaris</h2>
                <p class="text-gray-500 text-xs">Pilih dan masukkan aset yang dibutuhkan ke formulir peminjaman.</p>
            </div>

            <!-- SEARCH BAR (RESPONSIF MOBILE) -->
            <form action="{{ route('dashboard') }}" method="GET" class="flex items-center gap-2 w-full sm:w-auto sm:min-w-[320px]">
                <div class="relative w-full">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama aset, kode inventaris..." class="w-full bg-white border border-gray-300 rounded-xl pl-9 pr-3 py-2 sm:py-2.5 text-xs text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-pili-primary outline-none transition shadow-sm">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-2.5 sm:top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                @if(request('search') || request('kategori'))
                    <a href="{{ route('dashboard') }}" class="shrink-0 px-3 py-2 sm:py-2.5 text-xs font-bold text-rose-600 bg-rose-50 hover:bg-rose-100 rounded-xl transition">
                        Reset
                    </a>
                @endif
                <button type="submit" class="shrink-0 bg-pili-primary hover:bg-pili-dark text-white font-bold px-4 py-2 sm:py-2.5 rounded-xl text-xs transition shadow-sm">
                    Cari
                </button>
            </form>
        </div>

        <!-- FILTER TAB KATEGORI A - F (SMOOTH HORIZONTAL SCROLL DI HP) -->
        <div class="overflow-x-auto pb-2 -mx-3.5 px-3.5 sm:mx-0 sm:px-0 scrollbar-none flex items-center gap-2">
            <a href="{{ route('dashboard') }}" class="px-3.5 py-1.5 rounded-xl text-xs font-bold whitespace-nowrap transition shrink-0 {{ !request('kategori') ? 'bg-pili-primary text-white shadow-sm' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50' }}">
                Semua Kategori
            </a>
            @foreach(\App\Models\Barang::KATEGORI_LIST as $kode => $label)
                <a href="{{ route('dashboard', array_merge(request()->query(), ['kategori' => $kode])) }}" class="px-3.5 py-1.5 rounded-xl text-xs font-bold whitespace-nowrap transition shrink-0 {{ request('kategori') == $kode ? 'bg-pili-primary text-white shadow-sm' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <!-- DAFTAR ASET PER KATEGORI -->
        <div class="space-y-6 sm:space-y-8">
            @forelse($kategoriBarang as $kategoriName => $barangs)
                <div class="bg-white rounded-2xl sm:rounded-3xl p-4 sm:p-6 border border-gray-100 shadow-soft space-y-4">
                    <!-- Heading Kategori -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-gray-100 pb-3 gap-1.5">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-md bg-pili-primary shrink-0"></span>
                            <h3 class="text-base sm:text-lg font-black text-gray-900 tracking-tight">{{ $kategoriName }}</h3>
                            <span class="text-[10px] sm:text-[11px] font-bold bg-blue-50 text-pili-primary px-2 py-0.5 rounded-full border border-blue-100">
                                {{ count($barangs) }} Item
                            </span>
                        </div>
                        <span class="text-[11px] text-gray-400">
                            {{ \App\Models\Barang::KATEGORI_DESKRIPSI[substr($kategoriName, 9, 1)] ?? 'Inventaris Aset PILI' }}
                        </span>
                    </div>

                    <!-- Grid Kartu Barang (Responsif HP: 1 kolom di layar sempit / 2 kolom di tablet / 4 kolom di desktop) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3.5 sm:gap-4">
                        @foreach($barangs as $b)
                            @php
                                $isHabis = ($b->jumlah_tersedia <= 0 || $b->status !== 'Tersedia');
                            @endphp
                            <div class="group border border-gray-100 rounded-2xl p-3.5 sm:p-4 hover:border-blue-200 hover:shadow-md transition flex flex-col justify-between bg-white relative">
                                <div>
                                    <!-- Badge Status Stok -->
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-[10px] font-mono font-bold text-gray-400 uppercase tracking-wider">{{ $b->kode_inventaris }}</span>
                                        @if($isHabis)
                                            <span class="text-[10px] font-extrabold bg-rose-50 text-rose-600 border border-rose-200 px-2 py-0.5 rounded-full">
                                                Stok Habis
                                            </span>
                                        @else
                                            <span class="text-[10px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200 px-2 py-0.5 rounded-full">
                                                Tersedia: {{ $b->jumlah_tersedia }}
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Gambar / Placeholder -->
                                    <div class="w-full h-32 sm:h-36 bg-gray-50 rounded-xl mb-3 flex items-center justify-center overflow-hidden border border-gray-100">
                                        @if($b->foto)
                                            <img src="{{ asset($b->foto) }}" alt="{{ $b->nama }}" class="object-cover w-full h-full group-hover:scale-105 transition duration-300">
                                        @else
                                            <div class="text-center p-3">
                                                <svg class="w-8 h-8 text-gray-300 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                <span class="text-[10px] text-gray-400 font-medium">Inventaris PILI</span>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Nama & Info -->
                                    <h4 class="font-bold text-gray-900 text-xs sm:text-sm leading-snug line-clamp-2 mb-1 group-hover:text-pili-primary transition">
                                        {{ $b->nama }}
                                    </h4>
                                    <p class="text-[11px] text-gray-500 line-clamp-2 mb-2 sm:mb-3">
                                        {{ $b->deskripsi ?? 'Aset operasional Yayasan PILI.' }}
                                    </p>
                                </div>

                                <div class="space-y-2 pt-2 border-t border-gray-50">
                                    <div class="flex items-center justify-between text-[11px] text-gray-500">
                                        <span>Kondisi: <strong class="text-gray-700">{{ $b->kondisi }}</strong></span>
                                        <span>Total: {{ $b->jumlah_total }}</span>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <!-- Tombol Detail -->
                                        <button type="button" @click="modalData = { nama: '{{ addslashes($b->nama) }}', kode: '{{ $b->kode_inventaris }}', kategori: '{{ addslashes($b->kategori_label) }}', stok: '{{ $b->jumlah_tersedia }}', total: '{{ $b->jumlah_total }}', kondisi: '{{ $b->kondisi }}', lokasi: '{{ addslashes($b->lokasi_penyimpanan ?? 'Gudang PILI') }}', deskripsi: '{{ addslashes($b->deskripsi ?? 'Tidak ada deskripsi rinci.') }}', foto: '{{ $b->foto ? asset($b->foto) : '' }}' }; showModal = true" class="w-1/3 bg-gray-50 hover:bg-gray-100 text-gray-700 font-bold py-2 rounded-xl text-xs transition border border-gray-200 text-center">
                                            Detail
                                        </button>

                                        <!-- Tombol Pinjam -->
                                        @if($isHabis)
                                            <button type="button" disabled class="w-2/3 bg-gray-100 text-gray-400 cursor-not-allowed font-bold py-2 rounded-xl text-xs border border-gray-200 flex items-center justify-center gap-1">
                                                <span>Habis</span>
                                            </button>
                                        @else
                                            <a href="{{ route('peminjaman.create') }}" class="w-2/3 bg-pili-primary hover:bg-pili-dark text-white font-bold py-2 rounded-xl text-xs transition shadow-sm flex items-center justify-center gap-1 text-center">
                                                <span>+ Pinjam</span>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="text-center p-8 sm:p-12 bg-white rounded-3xl border border-gray-200 shadow-sm space-y-3 max-w-md mx-auto">
                    <div class="w-12 h-12 bg-gray-50 text-gray-400 rounded-2xl flex items-center justify-center mx-auto">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <h3 class="text-base font-extrabold text-gray-800">Aset tidak ditemukan</h3>
                    <p class="text-xs text-gray-500">Kata kunci atau filter kategori yang Anda masukkan tidak menghasilkan data.</p>
                    <a href="{{ route('dashboard') }}" class="inline-block bg-pili-primary text-white text-xs font-bold px-4 py-2 rounded-xl">
                        Reset Pencarian
                    </a>
                </div>
            @endforelse
        </div>
    </div>

    <!-- MODAL DETAIL BARANG POPUP (RESPONSIF HP: SCROLLABLE DI LAYAR KECIL) -->
    <div x-show="showModal" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto bg-black/60 backdrop-blur-sm flex items-center justify-center p-3 sm:p-4" style="display: none;">
        
        <div class="bg-white rounded-3xl max-w-lg w-full max-h-[90vh] overflow-y-auto p-5 sm:p-6 shadow-2xl border border-gray-100 space-y-4 relative" @click.away="showModal = false">
            <button @click="showModal = false" class="absolute top-4 right-4 text-gray-400 hover:text-gray-700 bg-gray-50 hover:bg-gray-100 w-8 h-8 rounded-full flex items-center justify-center transition text-lg font-bold">
                &times;
            </button>

            <div class="pr-6">
                <span class="text-[10px] font-mono font-bold text-pili-primary uppercase tracking-wider" x-text="modalData.kode"></span>
                <h3 class="text-base sm:text-lg font-black text-gray-900 leading-tight" x-text="modalData.nama"></h3>
                <p class="text-xs text-gray-500 mt-0.5" x-text="modalData.kategori"></p>
            </div>

            <div class="bg-gray-50 rounded-2xl p-3.5 sm:p-4 text-xs space-y-2 border border-gray-100">
                <div class="flex justify-between">
                    <span class="text-gray-500 font-medium">Stok Tersedia:</span>
                    <span class="font-extrabold text-gray-900" x-text="modalData.stok + ' unit (Total ' + modalData.total + ' unit)'"></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500 font-medium">Kondisi Fisik:</span>
                    <span class="font-extrabold text-emerald-700" x-text="modalData.kondisi"></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500 font-medium">Lokasi Simpan:</span>
                    <span class="font-extrabold text-gray-900" x-text="modalData.lokasi"></span>
                </div>
            </div>

            <div>
                <h5 class="text-xs font-bold text-gray-700 mb-1">Deskripsi & Spesifikasi:</h5>
                <p class="text-xs text-gray-600 leading-relaxed bg-gray-50/50 p-3 border border-gray-100 rounded-xl" x-text="modalData.deskripsi"></p>
            </div>

            <div class="pt-2">
                <a href="{{ route('peminjaman.create') }}" class="w-full bg-pili-primary hover:bg-pili-dark text-white font-extrabold py-3 rounded-xl text-center text-xs transition shadow-md block">
                    Buka Keranjang & Pinjam Aset
                </a>
            </div>
        </div>
    </div>

</div>
@endsection