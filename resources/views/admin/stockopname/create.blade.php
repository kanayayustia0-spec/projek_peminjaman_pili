@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    
    <div class="mb-6 flex items-center gap-4 border-b border-gray-200 pb-5">
        <a href="{{ route('admin.stock-opname.index') }}" class="text-gray-500 hover:text-pili-primary font-bold">&larr; Kembali</a>
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Form Pelaksanaan Stock Opname</h1>
    </div>

    <form action="{{ route('admin.stock-opname.store') }}" method="POST">
        @csrf

        <!-- INFORMASI PELAKSANAAN -->
        <div class="bg-white rounded-2xl shadow-soft border border-gray-100 p-6 mb-8 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Tanggal Audit</label>
                <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required class="w-full border-gray-300 rounded-lg p-2.5 bg-gray-50 focus:border-pili-primary focus:ring-pili-primary">
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Lokasi Pengecekan</label>
                <input type="text" name="lokasi" required placeholder="Contoh: Gudang Utama PILI" class="w-full border-gray-300 rounded-lg p-2.5 bg-gray-50 focus:border-pili-primary focus:ring-pili-primary">
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Tim Pemeriksa</label>
                <input type="text" name="tim_pemeriksa" required placeholder="Contoh: Budi, Andi & Siti" class="w-full border-gray-300 rounded-lg p-2.5 bg-gray-50 focus:border-pili-primary focus:ring-pili-primary">
            </div>
        </div>

        <!-- TABEL INPUT AUDIT -->
        <div class="bg-white rounded-2xl shadow-soft border border-gray-100 overflow-hidden mb-8">
            <div class="p-4 bg-gray-800 text-white font-bold">Daftar Aset untuk Diaudit</div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wider border-b border-gray-200">
                            <th class="p-4 font-bold">Kode & Nama Aset</th>
                            <th class="p-4 font-bold text-center bg-gray-100">Stok Sistem</th>
                            <th class="p-4 font-bold text-center text-green-700 bg-green-50">Fisik Baik</th>
                            <th class="p-4 font-bold text-center text-orange-700 bg-orange-50">Fisik Rusak</th>
                            <th class="p-4 font-bold text-center text-red-700 bg-red-50">Hilang</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($barangs as $item)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="p-4">
                                    <div class="font-bold text-gray-900">{{ $item->kode_inventaris }}</div>
                                    <div class="text-sm text-gray-500">{{ $item->nama }}</div>
                                    <!-- Input Hidden untuk Data Sistem -->
                                    <input type="hidden" name="audit[{{ $item->id }}][sistem]" value="{{ $item->jumlah_total }}">
                                </td>
                                
                                <td class="p-4 text-center font-black text-xl text-gray-400 bg-gray-50/50">
                                    {{ $item->jumlah_total }}
                                </td>
                                
                                <td class="p-4 bg-green-50/30">
                                    <input type="number" name="audit[{{ $item->id }}][fisik]" value="{{ $item->jumlah_total }}" min="0" required class="w-20 mx-auto block border-gray-300 rounded text-center font-bold text-green-700 focus:ring-green-500 focus:border-green-500">
                                </td>
                                
                                <td class="p-4 bg-orange-50/30">
                                    <input type="number" name="audit[{{ $item->id }}][rusak]" value="0" min="0" required class="w-20 mx-auto block border-gray-300 rounded text-center font-bold text-orange-700 focus:ring-orange-500 focus:border-orange-500">
                                </td>
                                
                                <td class="p-4 bg-red-50/30">
                                    <input type="number" name="audit[{{ $item->id }}][hilang]" value="0" min="0" required class="w-20 mx-auto block border-gray-300 rounded text-center font-bold text-red-700 focus:ring-red-500 focus:border-red-500">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- REKOMENDASI & SUBMIT -->
        <div class="bg-white rounded-2xl shadow-soft border border-gray-100 p-6 mb-8">
            <label class="block text-sm font-bold text-gray-700 mb-2">Rekomendasi Tindak Lanjut (Opsional)</label>
            <textarea name="rekomendasi" rows="3" class="w-full border-gray-300 rounded-lg p-3 bg-gray-50 focus:border-pili-primary focus:ring-pili-primary" placeholder="Tuliskan catatan, kendala, atau rekomendasi perbaikan aset di sini..."></textarea>
        </div>

        <div class="flex justify-end gap-4 mb-10">
            <a href="{{ route('admin.stock-opname.index') }}" class="bg-white border-2 border-gray-200 text-gray-700 font-bold px-8 py-3.5 rounded-xl hover:bg-gray-50 transition">Batal</a>
            <button type="submit" class="bg-pili-primary hover:bg-pili-dark text-white font-extrabold px-8 py-3.5 rounded-xl shadow-lg transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                Simpan Hasil Audit
            </button>
        </div>
    </form>

</div>
@endsection