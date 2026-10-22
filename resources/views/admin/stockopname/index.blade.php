@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    
    <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-end gap-4 border-b border-gray-200 pb-5">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Riwayat Stock Opname</h1>
            <p class="text-gray-500 text-sm mt-1">Daftar rekapan hasil audit fisik gudang Yayasan PILI.</p>
        </div>
        
        <a href="{{ route('admin.stock-opname.create') }}" class="bg-pili-primary hover:bg-pili-dark text-white font-extrabold py-3 px-6 rounded-xl shadow-md transition flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            + Lakukan Audit Baru
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 text-green-700 p-4 rounded-xl mb-6 font-bold border border-green-200">{{ session('success') }}</div>
    @endif

    <div class="bg-white rounded-2xl shadow-soft border border-gray-100 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider border-b border-gray-200">
                    <th class="p-4 font-bold">Tanggal & Tim</th>
                    <th class="p-4 font-bold text-center">Terdaftar (Sistem)</th>
                    <th class="p-4 font-bold text-center text-green-600">Ditemukan (Fisik)</th>
                    <th class="p-4 font-bold text-center text-red-500">Rusak/Hilang</th>
                    <th class="p-4 font-bold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($opnames as $row)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="p-4">
                            <div class="font-bold text-gray-900">{{ \Carbon\Carbon::parse($row->tanggal)->format('d M Y') }}</div>
                            <div class="text-xs text-gray-500">Tim: {{ $row->tim_pemeriksa }} | Lokasi: {{ $row->lokasi }}</div>
                        </td>
                        <td class="p-4 text-center font-bold text-gray-600">{{ $row->jumlah_terdaftar }} Item</td>
                        <td class="p-4 text-center font-black text-green-600">{{ $row->jumlah_ditemukan }} Item</td>
                        <td class="p-4 text-center">
                            <span class="text-xs font-bold text-orange-600 bg-orange-50 px-2 py-1 rounded">Rusak: {{ $row->jumlah_rusak }}</span>
                            <span class="text-xs font-bold text-red-600 bg-red-50 px-2 py-1 rounded ml-1">Hilang: {{ $row->jumlah_hilang }}</span>
                        </td>
                        <td class="p-4 text-right">
                            <button class="text-blue-600 hover:underline text-sm font-bold">Lihat Rincian</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="p-12 text-center text-gray-500 font-medium">Belum ada data stock opname yang tercatat.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection