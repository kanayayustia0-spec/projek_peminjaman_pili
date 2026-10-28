@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8" x-data="{ showModal: false, modalData: {} }">
    
    <!-- Tombol Atas -->
    <div class="flex flex-col sm:flex-row justify-between items-center bg-white p-4 rounded-2xl shadow-sm border border-gray-100 mb-8 gap-4">
        <h1 class="text-2xl font-extrabold text-gray-900">Katalog Aset PILI</h1>
        <div class="flex gap-3 w-full sm:w-auto">
            <a href="{{ route('peminjaman.riwayat') }}" class="flex-1 sm:flex-none text-center bg-white border border-gray-300 text-gray-700 font-bold py-2.5 px-6 rounded-xl hover:bg-gray-50 transition">
                Riwayat Peminjaman
            </a>
            <a href="{{ route('peminjaman.create') }}" class="flex-1 sm:flex-none text-center bg-pili-primary text-white font-bold py-2.5 px-6 rounded-xl shadow-md hover:bg-pili-dark transition flex justify-center items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                + Pinjam Aset
            </a>
        </div>
    </div>

    <!-- Loop Kategori Barang -->
    <div class="space-y-12">
        @foreach($kategoriBarang as $kategori => $barangs)
            <div>
                <h2 class="text-xl font-extrabold text-pili-dark mb-4 border-b-2 border-pili-light pb-2 uppercase tracking-wide">
                    {{ $kategori }}
                </h2>
                
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
                    @foreach($barangs as $barang)
                        <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-soft hover:shadow-md transition flex flex-col h-full">
                            <!-- Foto Placeholder -->
                            <div class="w-full h-32 bg-gray-100 rounded-xl mb-3 flex items-center justify-center overflow-hidden">
                                @if($barang->foto)
                                    <img src="{{ asset($barang->foto) }}" class="object-cover w-full h-full">
                                @else
                                    <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                @endif
                            </div>
                            
                            <!-- Info Singkat -->
                            <div class="flex-grow">
                                <p class="text-[10px] font-bold text-gray-400 uppercase">{{ $barang->kode_inventaris }}</p>
                                <h3 class="font-bold text-gray-800 text-sm leading-tight mb-2">{{ $barang->nama }}</h3>
                                
                                <div class="flex justify-between items-center mb-3">
                                    <span class="text-xs font-bold {{ $barang->kondisi == 'Baik' ? 'text-green-600' : 'text-red-500' }}">Kondisi: {{ $barang->kondisi }}</span>
                                    <span class="text-xs bg-blue-50 text-blue-700 px-2 py-0.5 rounded font-bold border border-blue-100">Stok: {{ $barang->jumlah_tersedia }}</span>
                                </div>
                            </div>

                            <!-- Tombol Detail Modal -->
                            <button @click="modalData = { nama: '{{ addslashes($barang->nama) }}', kode: '{{ $barang->kode_inventaris }}', deskripsi: '{{ addslashes($barang->deskripsi ?? 'Tidak ada deskripsi rinci.') }}', stok: '{{ $barang->jumlah_tersedia }}', kondisi: '{{ $barang->kondisi }}' }; showModal = true" class="w-full bg-gray-50 hover:bg-gray-100 text-gray-700 border border-gray-200 font-bold py-2 rounded-lg text-xs transition">
                                Detail Barang
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    <!-- Modal Detail Barang -->
    <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showModal" @click="showModal = false" class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-50 backdrop-blur-sm"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div x-show="showModal" class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full p-6">
                <div class="flex justify-between items-center mb-4 border-b pb-3">
                    <h3 class="text-xl leading-6 font-extrabold text-gray-900" x-text="modalData.nama"></h3>
                    <button @click="showModal = false" class="text-gray-400 hover:text-gray-500"><svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                </div>
                <div class="mb-4 space-y-2">
                    <p class="text-sm"><span class="font-bold text-gray-700">Kode Inventaris:</span> <span x-text="modalData.kode" class="bg-gray-100 px-2 py-0.5 rounded text-xs"></span></p>
                    <p class="text-sm"><span class="font-bold text-gray-700">Kondisi:</span> <span x-text="modalData.kondisi" class="text-green-600 font-bold"></span></p>
                    <p class="text-sm"><span class="font-bold text-gray-700">Sisa Stok:</span> <span x-text="modalData.stok" class="font-bold"></span> Unit</p>
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-700 mb-1">Deskripsi Lengkap:</p>
                    <p class="text-sm text-gray-600 bg-gray-50 p-3 rounded-xl border border-gray-100 leading-relaxed" x-text="modalData.deskripsi"></p>
                </div>
                <div class="mt-6 flex justify-end">
                    <a href="{{ route('peminjaman.create') }}" class="bg-pili-primary text-white px-6 py-2 rounded-xl font-bold shadow hover:bg-pili-dark transition">Menuju Form Pinjam</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection