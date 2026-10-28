
@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-5 sm:py-8 px-3.5 sm:px-6 lg:px-8">
    <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight mb-5">Rekapitulasi Transaksi Peminjaman</h1>

    <!-- Form Filter (Responsif HP) -->
    <form action="{{ route('admin.rekap') }}" method="GET" class="bg-white p-4 sm:p-5 rounded-2xl shadow-soft mb-6 flex flex-col sm:flex-row flex-wrap gap-3 sm:gap-4 sm:items-end border border-gray-100">
        <div class="w-full sm:w-auto">
            <label class="block text-xs font-bold text-gray-700 mb-1">Dari Tanggal</label>
            <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full border border-gray-300 rounded-xl p-2 text-xs sm:text-sm">
        </div>
        <div class="w-full sm:w-auto">
            <label class="block text-xs font-bold text-gray-700 mb-1">Sampai Tanggal</label>
            <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full border border-gray-300 rounded-xl p-2 text-xs sm:text-sm">
        </div>
        <div class="w-full sm:w-auto">
            <label class="block text-xs font-bold text-gray-700 mb-1">Status</label>
            <select name="status" class="w-full border border-gray-300 rounded-xl p-2 text-xs sm:text-sm">
                <option value="">Semua Status</option>
                <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                <option value="Terlambat" {{ request('status') == 'Terlambat' ? 'selected' : '' }}>Terlambat</option>
                <option value="Perlu Tindak Lanjut" {{ request('status') == 'Perlu Tindak Lanjut' ? 'selected' : '' }}>Bermasalah</option>
            </select>
        </div>
        <div class="flex items-center gap-2 pt-1 sm:pt-0">
            <button type="submit" class="bg-pili-primary hover:bg-pili-dark text-white px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition">Filter</button>
            <a href="{{ route('admin.rekap') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition">Reset</a>
        </div>
        <div class="w-full sm:w-auto sm:ml-auto pt-2 sm:pt-0">
            <a href="{{ route('admin.rekap.export', request()->query()) }}" class="w-full sm:w-auto bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-xl text-xs sm:text-sm font-bold flex items-center justify-center gap-1.5 transition shadow-sm text-center">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span>Export ke Excel</span>
            </a>
        </div>
    </form>

    <!-- Tabel Rekap (Scrollable) -->
    <div class="bg-white rounded-2xl shadow-soft overflow-x-auto border border-gray-100">
        <table class="w-full text-left text-xs sm:text-sm border-collapse min-w-[650px]">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="p-3">Tgl Pengajuan</th>
                    <th class="p-3">Peminjam</th>
                    <th class="p-3">Kegiatan</th>
                    <th class="p-3">Status Akhir</th>
                    <th class="p-3">BASTA / Pengembalian</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($peminjamans as $row)
                <tr class="hover:bg-gray-50">
                    <td class="p-3">{{ $row->created_at->format('d/m/Y') }}</td>
                    <td class="p-3 font-bold">{{ $row->user->name }}</td>
                    <td class="p-3">{{ $row->nama_kegiatan }}</td>
                    <td class="p-3">
                        <span class="px-2 py-1 rounded bg-gray-100 font-bold border">{{ $row->status_keseluruhan }}</span>
                    </td>
                    <td class="p-3 text-xs">
                        Serah: {{ $row->basta ? $row->basta->tanggal_serah : '-' }} <br>
                        Kembali: {{ $row->pengembalian ? $row->pengembalian->tanggal_pengembalian->format('d/m/Y') : '-' }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection