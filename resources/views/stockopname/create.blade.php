@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="flex items-center mb-6 space-x-4">
        <a href="{{ route('admin.stock-opname.index') }}" class="text-gray-500 hover:text-pili-primary font-bold">&larr; Kembali</a>
        <h1 class="text-2xl font-extrabold text-gray-900">Form Pengecekan Stock Opname</h1>
    </div>

    <form action="{{ route('admin.stock-opname.store') }}" method="POST" class="bg-white shadow-soft rounded-2xl p-6 md:p-8 border border-gray-100">
        @csrf

        <!-- Informasi Audit -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 bg-gray-50 p-5 rounded-xl border border-gray-200">
            <div>
                <label class="block text-sm font-bold text-gray-700">Tanggal Audit</label>
                <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required class="w-full border-gray-300 rounded-lg p-2.5 mt-1 focus:ring-pili-primary focus:border-pili-primary shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700">Lokasi Pengecekan</label>
                <input type="text" name="lokasi" value="Gudang Utama PILI" required class="w-full border-gray-300 rounded-lg p-2.5 mt-1 focus:ring-pili-primary focus:border-pili-primary shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700">Tim Pemeriksa</label>
                <input type="text" name="tim_pemeriksa" required placeholder="Nama-nama tim..." class="w-full border-gray-300 rounded-lg p-2.5 mt-1 focus:ring-pili-primary focus:border-pili-primary shadow-sm">
            </div>
        </div>

        <h3 class="font-bold text-lg mb-4 text-gray-800 border-b pb-2">Detail Pengecekan Fisik Aset</h3>
        
        <!-- Tabel Input Fisik -->
        <div class="overflow-x-auto mb-8 border border-gray-200 rounded-xl">
            <table class="w-full text-left text-sm border-collapse">
                <thead>
                    <tr class="bg-gray-100 text-gray-600 border-b border-gray-200">
                        <th class="p-3 font-bold uppercase tracking-wider text-xs">Kode & Nama</th>
                        <th class="p-3 font-bold uppercase tracking-wider text-xs text-center bg-blue-100/50">Stok Sistem</th>
                        <th class="p-3 font-bold uppercase tracking-wider text-xs text-center bg-green-100/50 text-green-800">Fisik Baik</th>
                        <th class="p-3 font-bold uppercase tracking-wider text-xs text-center bg-yellow-100/50 text-yellow-800">Fisik Rusak</th>
                        <th class="p-3 font-bold uppercase tracking-wider text-xs text-center bg-red-100/50 text-red-800">Hilang</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($barangs as $barang)
                    <tr class="hover:bg-gray-50/80 transition">
                        <td class="p-3">
                            <div class="font-bold text-gray-800">{{ $barang->kode_inventaris }}</div>
                            <div class="text-xs text-gray-500">{{ $barang->nama }}</div>
                        </td>
                        
                        <!-- Data Stok Sistem (Readonly/Hidden untuk disubmit) -->
                        <td class="p-3 text-center bg-blue-50/30">
                            <span class="font-bold text-lg text-blue-700">{{ $barang->jumlah_tersedia }}</span>
                            <input type="hidden" name="audit[{{ $barang->id }}][sistem]" value="{{ $barang->jumlah_tersedia }}">
                        </td>
                        
                        <!-- Input Pengecekan Fisik -->
                        <td class="p-3 bg-green-50/30">
                            <input type="number" name="audit[{{ $barang->id }}][fisik]" min="0" required class="w-full border-gray-300 rounded p-2 text-center focus:ring-green-500 focus:border-green-500 shadow-sm font-bold text-green-700">
                        </td>
                        <td class="p-3 bg-yellow-50/30">
                            <input type="number" name="audit[{{ $barang->id }}][rusak]" min="0" value="0" required class="w-full border-gray-300 rounded p-2 text-center focus:ring-yellow-500 focus:border-yellow-500 shadow-sm font-bold text-yellow-700">
                        </td>
                        <td class="p-3 bg-red-50/30">
                            <input type="number" name="audit[{{ $barang->id }}][hilang]" min="0" value="0" required class="w-full border-gray-300 rounded p-2 text-center focus:ring-red-500 focus:border-red-500 shadow-sm font-bold text-red-700">
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mb-8">
            <label class="block text-sm font-bold text-gray-700 mb-2">Rekomendasi / Catatan Audit</label>
            <textarea name="rekomendasi" rows="3" class="w-full border-gray-300 rounded-lg p-3 focus:ring-pili-primary focus:border-pili-primary shadow-sm" placeholder="Tuliskan temuan atau catatan evaluasi untuk stock opname kali ini..."></textarea>
        </div>

        <div class="flex justify-end pt-4 border-t border-gray-100">
            <button type="submit" class="bg-pili-primary text-white font-extrabold py-3 px-8 rounded-xl hover:bg-pili-dark transition shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                Simpan Hasil Audit
            </button>
        </div>
    </form>
</div>
@endsection