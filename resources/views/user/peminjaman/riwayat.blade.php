@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="mb-6 flex justify-between items-end">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900">Riwayat Peminjaman</h1>
            <p class="text-gray-500 text-sm mt-1">Pantau status pengajuan dan pengembalian aset Anda.</p>
        </div>
        <a href="{{ route('peminjaman.create') }}" class="bg-pili-primary text-white font-bold py-2.5 px-6 rounded-xl shadow-md hover:bg-pili-dark transition">+ Pinjam Baru</a>
    </div>
    
    <!-- Paste bagian <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"> (Daftar Card Riwayat) dari file index yang lama ke sini -->
    <div class="bg-white p-10 text-center rounded-2xl border border-gray-200">
        <h3 class="font-bold text-gray-700">Halaman ini diisi dengan daftar Card Riwayat Peminjaman (Seperti kode sebelumnya)</h3>
    </div>
</div>
@endsection