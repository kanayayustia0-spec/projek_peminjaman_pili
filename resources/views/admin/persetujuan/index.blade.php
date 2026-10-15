@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Antrean Persetujuan</h1>
            <p class="text-gray-500 text-sm mt-1">Daftar pengajuan peminjaman aset yang membutuhkan otorisasi Anda.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 text-green-700 p-4 rounded-xl mb-6 font-bold border border-green-200">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 text-red-700 p-4 rounded-xl mb-6 font-bold border border-red-200">{{ session('error') }}</div>
    @endif

    <div class="bg-white rounded-2xl shadow-soft border border-gray-100 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider border-b border-gray-200">
                    <th class="p-4 font-bold">Tanggal Pengajuan</th>
                    <th class="p-4 font-bold">Pemohon</th>
                    <th class="p-4 font-bold">Kegiatan</th>
                    <th class="p-4 font-bold text-center">Durasi</th>
                    <th class="p-4 font-bold text-right">Aksi Review</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($peminjamans as $row)
                    <tr class="hover:bg-gray-50/80 transition">
                        <td class="p-4 text-sm font-bold text-gray-700">{{ $row->created_at->format('d M Y - H:i') }}</td>
                        <td class="p-4">
                            <div class="font-extrabold text-gray-900">{{ $row->user->name }}</div>
                            <div class="text-xs text-gray-500">{{ $row->user->email }}</div>
                        </td>
                        <td class="p-4 text-sm text-gray-700 font-medium">{{ $row->nama_kegiatan }}</td>
                        <td class="p-4 text-center">
                            <span class="bg-blue-50 text-blue-700 text-xs font-bold px-2.5 py-1 rounded-full border border-blue-200">
                                {{ \Carbon\Carbon::parse($row->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($row->tanggal_selesai)) + 1 }} Hari
                            </span>
                        </td>
                        <td class="p-4 text-right">
                            <a href="{{ route('admin.persetujuan.show', $row->id) }}" class="inline-flex items-center gap-1 bg-pili-primary hover:bg-pili-dark text-white text-sm font-bold px-5 py-2.5 rounded-xl transition shadow-md">
                                Buka Detail &rarr;
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="p-12 text-center text-gray-500 font-medium">Saat ini tidak ada antrean pengajuan baru.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection