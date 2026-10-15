
@extends('layouts.app')

@section('content')<div class="max-w-7xl mx-auto py-8 px-4">
    <h1 class="text-3xl font-bold text-pili-primary mb-6">Rekapitulasi Transaksi Peminjaman</h1>

    <!-- Form Filter -->
    <form action="{{ route('admin.rekap') }}" method="GET" class="bg-white p-4 rounded-xl shadow-soft mb-6 flex gap-4 items-end">
        <div>
            <label class="block text-xs font-bold text-gray-700">Dari Tanggal</label>
            <input type="date" name="start_date" value="{{ request('start_date') }}" class="border rounded p-2 text-sm">
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-700">Sampai Tanggal</label>
            <input type="date" name="end_date" value="{{ request('end_date') }}" class="border rounded p-2 text-sm">
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-700">Status</label>
            <select name="status" class="border rounded p-2 text-sm">
                <option value="">Semua Status</option>
                <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                <option value="Terlambat" {{ request('status') == 'Terlambat' ? 'selected' : '' }}>Terlambat</option>
                <option value="Perlu Tindak Lanjut" {{ request('status') == 'Perlu Tindak Lanjut' ? 'selected' : '' }}>Bermasalah</option>
            </select>
        </div>
        <button type="submit" class="bg-pili-dark text-white px-4 py-2 rounded text-sm font-bold">Filter</button>
        <a href="{{ route('admin.rekap') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded text-sm font-bold">Reset</a>
    </form>

    <!-- Tabel Rekap -->
    <div class="bg-white rounded-xl shadow-soft overflow-x-auto">
        <table class="w-full text-left text-sm border-collapse">
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