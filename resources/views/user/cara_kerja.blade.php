@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:py-12 px-3.5 sm:px-6 lg:px-8">
    
    <!-- Header Halaman -->
    <div class="text-center mb-16">
        <span class="inline-block px-4 py-1.5 rounded-full text-xs font-extrabold tracking-wider bg-gray-100 text-gray-800 uppercase mb-3">Panduan Operasional</span>
        <h1 class="text-3xl sm:text-4xl font-black text-gray-900 tracking-tight uppercase">Alur Kerja Sistem Peminjaman</h1>
        <div class="w-24 h-1.5 bg-pili-primary mx-auto mt-4 rounded-full"></div>
        <p class="text-base sm:text-lg text-gray-500 mt-4 max-w-2xl mx-auto font-medium">Panduan langkah demi langkah proses peminjaman hingga pengembalian aset di Yayasan PILI.</p>
    </div>

    <!-- Container Grid: Di HP Menurun, Di Laptop/Desktop Kesamping (5 Kolom) -->
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-6 relative">

        <!-- LANGKAH 1: Pengajuan -->
        <div class="bg-white rounded-2xl shadow-xl p-6 border-t-4 border-pili-primary flex flex-col justify-between transform transition duration-300 hover:-translate-y-2 hover:shadow-2xl relative">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-gray-50 flex items-center justify-center text-pili-primary shadow-inner">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    </div>
                    <span class="text-2xl font-black text-gray-200">01</span>
                </div>
                <h3 class="mb-2 font-black text-gray-900 text-lg">Pilih & Ajukan Aset</h3>
                <p class="text-xs sm:text-sm leading-relaxed text-gray-600 font-medium">Staf/User memilih aset dari katalog dan mengisi form pengajuan peminjaman (5W) beserta jadwal penggunaannya.</p>
            </div>
            <div class="mt-6 pt-4 border-t border-gray-100 flex items-center text-xs font-bold text-pili-primary">
                <span>Langkah Pertama</span>
            </div>
        </div>

        <!-- LANGKAH 2: Persetujuan Atasan -->
        <div class="bg-white rounded-2xl shadow-xl p-6 border-t-4 border-yellow-500 flex flex-col justify-between transform transition duration-300 hover:-translate-y-2 hover:shadow-2xl relative">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-yellow-50 flex items-center justify-center text-yellow-600 shadow-inner">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <span class="text-2xl font-black text-gray-200">02</span>
                </div>
                <h3 class="mb-2 font-black text-gray-900 text-lg">Review & Otorisasi</h3>
                <p class="text-xs sm:text-sm leading-relaxed text-gray-600 font-medium">Admin Persetujuan meninjau form. Jika disetujui, status berubah menjadi <span class="font-bold text-yellow-600">Disetujui</span> ke Gudang.</p>
            </div>
            <div class="mt-6 pt-4 border-t border-gray-100 flex items-center text-xs font-bold text-yellow-600">
                <span>Verifikasi Manajer</span>
            </div>
        </div>

        <!-- LANGKAH 3: Pengambilan Gudang -->
        <div class="bg-white rounded-2xl shadow-xl p-6 border-t-4 border-indigo-600 flex flex-col justify-between transform transition duration-300 hover:-translate-y-2 hover:shadow-2xl relative">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600 shadow-inner">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                    </div>
                    <span class="text-2xl font-black text-gray-200">03</span>
                </div>
                <h3 class="mb-2 font-black text-gray-900 text-lg">Pengambilan Aset</h3>
                <p class="text-xs sm:text-sm leading-relaxed text-gray-600 font-medium">User mengambil fisik barang ke gudang. Admin Gudang menyerahkan aset dan status menjadi <span class="font-bold text-indigo-600">Sedang Digunakan</span>.</p>
            </div>
            <div class="mt-6 pt-4 border-t border-gray-100 flex items-center text-xs font-bold text-indigo-600">
                <span>Serah Terima Fisik</span>
            </div>
        </div>

        <!-- LANGKAH 4: Pengembalian -->
        <div class="bg-white rounded-2xl shadow-xl p-6 border-t-4 border-orange-500 flex flex-col justify-between transform transition duration-300 hover:-translate-y-2 hover:shadow-2xl relative">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-orange-50 flex items-center justify-center text-orange-600 shadow-inner">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    </div>
                    <span class="text-2xl font-black text-gray-200">04</span>
                </div>
                <h3 class="mb-2 font-black text-gray-900 text-lg">Upload Bukti Kembali</h3>
                <p class="text-xs sm:text-sm leading-relaxed text-gray-600 font-medium">Selesai pakai, User klik "Ajukan Pengembalian" dan wajib mengunggah <span class="font-bold">Foto Bukti Fisik</span> aset.</p>
            </div>
            <div class="mt-6 pt-4 border-t border-gray-100 flex items-center text-xs font-bold text-orange-600">
                <span>Pelaporan Selesai</span>
            </div>
        </div>

        <!-- LANGKAH 5: Selesai -->
        <div class="bg-white rounded-2xl shadow-xl p-6 border-t-4 border-green-500 flex flex-col justify-between transform transition duration-300 hover:-translate-y-2 hover:shadow-2xl relative md:col-span-3 lg:col-span-1">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-green-50 flex items-center justify-center text-green-600 shadow-inner">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <span class="text-2xl font-black text-gray-200">05</span>
                </div>
                <h3 class="mb-2 font-black text-gray-900 text-lg">Pengecekan & Selesai</h3>
                <p class="text-xs sm:text-sm leading-relaxed text-gray-600 font-medium">Admin Gudang mengecek foto bukti dan fisik barang. Transaksi <span class="font-bold text-green-600">Selesai</span> & stok otomatis kembali.</p>
            </div>
            <div class="mt-6 pt-4 border-t border-gray-100 flex items-center text-xs font-bold text-green-600">
                <span>Finalisasi Transaksi</span>
            </div>
        </div>

    </div>

    <!-- Banner Unduh Dokumen Word Resmi -->
    <div class="mt-12 max-w-2xl mx-auto bg-gradient-to-r from-blue-50 to-emerald-50 border border-blue-200 rounded-2xl p-6 flex flex-col sm:flex-row items-center justify-between gap-4 shadow-sm">
        <div class="flex items-center gap-3 text-left">
            <div class="w-12 h-12 rounded-xl bg-blue-600 text-white flex items-center justify-center shrink-0 shadow-md">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <div>
                <h4 class="font-extrabold text-gray-900 text-sm sm:text-base">Buku Panduan Resmi Yayasan PILI</h4>
                <p class="text-xs text-gray-500">Tersedia dokumen Word (.doc) lengkap untuk Staf, Admin Pengelola, dan Admin Persetujuan.</p>
            </div>
        </div>
        <a href="{{ asset('documents/Panduan_Resmi_Sistem_Peminjaman_Aset_PILI_Green_Network.doc') }}" download class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs sm:text-sm px-5 py-3 rounded-xl shadow transition shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            <span>Unduh File Word</span>
        </a>
    </div>

    <!-- Tombol CTA -->
    <div class="mt-8 text-center">
        @auth
            <a href="{{ route('peminjaman.katalog') }}" class="inline-flex items-center gap-2 bg-gray-900 hover:bg-black text-white font-extrabold px-8 py-4 rounded-xl shadow-lg transition transform hover:-translate-y-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                Mulai Peminjaman Sekarang
            </a>
        @else
            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 bg-gray-900 hover:bg-black text-white font-extrabold px-8 py-4 rounded-xl shadow-lg transition transform hover:-translate-y-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                Login untuk Meminjam Aset
            </a>
        @endauth
    </div>

</div>
@endsection