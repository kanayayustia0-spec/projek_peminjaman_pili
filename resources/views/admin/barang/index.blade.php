@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Register Inventaris</h1>
            <p class="text-gray-500 text-sm mt-1">Kelola data aset utama Yayasan PILI.</p>
        </div>
        <a href="{{ route('admin.barang.create') }}" class="bg-pili-primary text-white px-5 py-2.5 rounded-xl font-bold hover:bg-pili-dark shadow-md transition flex items-center gap-2">
            <span>+</span> Tambah Aset
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 font-medium">{{ session('success') }}</div>
    @endif

    <div class="bg-white shadow-soft rounded-2xl overflow-hidden border border-gray-100">
       <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider border-b border-gray-200">
                    <th class="p-4 font-bold text-center w-24">Foto</th>
                    <th class="p-4 font-bold">Kode Inventaris</th>
                    <th class="p-4 font-bold">Nama Aset</th>
                    <th class="p-4 font-bold">Kategori</th>
                    <th class="p-4 font-bold text-center">Stok Tersedia</th>
                    <th class="p-4 font-bold text-center">Kondisi</th>
                    <th class="p-4 font-bold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($barangs as $barang)
                    <tr class="hover:bg-gray-50/80 transition">
                        
                        <!-- 1. Kolom Khusus FOTO -->
                        <td class="p-4 align-middle text-center">
                            <div class="w-16 h-16 mx-auto rounded-xl bg-white border border-gray-200 flex-shrink-0 overflow-hidden flex items-center justify-center shadow-sm">
                                @if($barang->foto)
                                    <img src="{{ asset($barang->foto) }}" alt="{{ $barang->nama }}" class="w-full h-full object-cover">
                                @else
                                    <!-- Ikon default jika foto kosong -->
                                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                @endif
                            </div>
                        </td>

                        <!-- 2. Kolom Khusus KODE INVENTARIS -->
                        <td class="p-4 align-middle whitespace-nowrap">
                            <span class="font-extrabold text-gray-900 bg-gray-100 px-2 py-1 rounded-md text-sm border border-gray-200">
                                {{ $barang->kode_inventaris }}
                            </span>
                        </td>

                        <!-- 3. Kolom Khusus NAMA ASET -->
                        <td class="p-4 align-middle">
                            <div class="font-bold text-gray-800 text-sm">{{ $barang->nama }}</div>
                        </td>
                        
                        <!-- 4. Kolom Kategori -->
                        <td class="p-4 text-sm font-bold text-gray-600 align-middle">
                            {{ $barang->kategori }}
                        </td>
                        
                        <!-- 5. Kolom Stok -->
                        <td class="p-4 text-center align-middle">
                            <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $barang->jumlah_tersedia > 0 ? 'bg-green-50 text-green-700 border-green-200' : 'bg-red-50 text-red-700 border-red-200' }}">
                                {{ $barang->jumlah_tersedia }} / {{ $barang->jumlah_total }}
                            </span>
                        </td>
                        
                        <!-- 6. Kolom Kondisi -->
                        <td class="p-4 align-middle text-center">
                            <span class="text-xs font-bold px-3 py-1.5 rounded-lg {{ $barang->kondisi == 'Baik' ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-600' }}">
                                {{ $barang->kondisi }}
                            </span>
                        </td>
                        
                        <!-- 7. Kolom Aksi -->
                        <td class="p-4 align-middle text-right">
                            <div class="flex justify-end items-center gap-2">
                                <a href="{{ route('admin.barang.edit', $barang->id) }}" class="text-blue-600 hover:text-blue-800 text-sm font-bold bg-blue-50 hover:bg-blue-100 px-3 py-2 rounded-lg transition shadow-sm border border-blue-100">Edit</a>
                                
                                <form action="{{ route('admin.barang.destroy', $barang->id) }}" method="POST" onsubmit="return confirm('Yakin menghapus aset ini secara permanen?');" class="m-0 p-0">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-bold bg-red-50 hover:bg-red-100 px-3 py-2 rounded-lg transition shadow-sm border border-red-100">Hapus</button>
                                </form>
                            </div>
                        </td>
                        
                    </tr>
                @empty
                    <!-- Colspan diubah menjadi 7 karena sekarang ada 7 kolom -->
                    <tr><td colspan="7" class="p-12 text-center text-gray-500 font-medium border-b border-gray-100">Belum ada aset terdaftar.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection