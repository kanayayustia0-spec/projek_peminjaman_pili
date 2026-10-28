@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-5 sm:py-8 px-3.5 sm:px-6 lg:px-8">
    
    <!-- HEADER -->
    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">Otorisasi & Persetujuan</h1>
            <p class="text-gray-500 text-xs sm:text-sm mt-1">Daftar permohonan peminjaman aset yang membutuhkan review dan otorisasi Anda.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-1.5 bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 text-xs sm:text-sm font-bold px-4 py-2.5 rounded-xl shadow-2xs transition">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                <span>Dashboard Admin</span>
            </a>
        </div>
    </div>

    <!-- FLASH MESSAGES -->
    @if(session('success'))
        <div class="bg-emerald-50 text-emerald-800 p-4 rounded-2xl mb-6 font-bold border border-emerald-200 flex items-center gap-2 shadow-2xs">
            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="bg-rose-50 text-rose-800 p-4 rounded-2xl mb-6 font-bold border border-rose-200 flex items-center gap-2 shadow-2xs">
            <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- FILTER TABS -->
    <div class="flex flex-wrap items-center gap-2 mb-6 border-b border-gray-200 pb-3">
        <a href="{{ route('admin.persetujuan.index', ['tab' => 'pending']) }}" 
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-extrabold transition {{ $tab === 'pending' ? 'bg-pili-primary text-white shadow-md' : 'bg-white text-gray-600 hover:bg-gray-100 border border-gray-200' }}">
            <span>Menunggu Otorisasi</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] {{ $tab === 'pending' ? 'bg-white/20 text-white' : 'bg-amber-100 text-amber-800' }}">
                {{ $counts['pending'] }}
            </span>
        </a>
        <a href="{{ route('admin.persetujuan.index', ['tab' => 'disetujui']) }}" 
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-extrabold transition {{ $tab === 'disetujui' ? 'bg-emerald-600 text-white shadow-md' : 'bg-white text-gray-600 hover:bg-gray-100 border border-gray-200' }}">
            <span>Telah Disetujui</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] {{ $tab === 'disetujui' ? 'bg-white/20 text-white' : 'bg-emerald-100 text-emerald-800' }}">
                {{ $counts['disetujui'] }}
            </span>
        </a>
        <a href="{{ route('admin.persetujuan.index', ['tab' => 'ditolak']) }}" 
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-extrabold transition {{ $tab === 'ditolak' ? 'bg-rose-600 text-white shadow-md' : 'bg-white text-gray-600 hover:bg-gray-100 border border-gray-200' }}">
            <span>Ditolak</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] {{ $tab === 'ditolak' ? 'bg-white/20 text-white' : 'bg-rose-100 text-rose-800' }}">
                {{ $counts['ditolak'] }}
            </span>
        </a>
        <a href="{{ route('admin.persetujuan.index', ['tab' => 'semua']) }}" 
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-extrabold transition {{ $tab === 'semua' ? 'bg-gray-800 text-white shadow-md' : 'bg-white text-gray-600 hover:bg-gray-100 border border-gray-200' }}">
            <span>Semua Riwayat</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] {{ $tab === 'semua' ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-700' }}">
                {{ $counts['semua'] }}
            </span>
        </a>
    </div>

    <!-- TABEL DAFTAR PERSETUJUAN -->
    <div class="bg-white rounded-2xl shadow-soft border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[700px]">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-[11px] uppercase tracking-wider border-b border-gray-200">
                        <th class="p-4 font-bold">Tanggal & Trx</th>
                        <th class="p-4 font-bold">Pemohon & Divisi</th>
                        <th class="p-4 font-bold">Kegiatan & Durasi</th>
                        <th class="p-4 font-bold">Status Otorisasi</th>
                        <th class="p-4 font-bold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-xs sm:text-sm">
                    @forelse($peminjamans as $row)
                        <tr class="hover:bg-gray-50/80 transition">
                            <!-- Tanggal & Trx -->
                            <td class="p-4">
                                <span class="font-mono text-xs font-bold text-pili-primary bg-blue-50 border border-blue-200 px-2 py-0.5 rounded">
                                    #PILI-{{ str_pad($row->id, 5, '0', STR_PAD_LEFT) }}
                                </span>
                                <div class="text-[11px] text-gray-500 mt-1">
                                    {{ $row->created_at->format('d M Y, H:i') }}
                                </div>
                            </td>

                            <!-- Pemohon -->
                            <td class="p-4">
                                <div class="font-extrabold text-gray-900">{{ $row->user->name }}</div>
                                <div class="text-[11px] text-gray-500">{{ $row->user->email }}</div>
                                <span class="inline-block mt-0.5 text-[10px] bg-gray-100 text-gray-600 px-1.5 py-0.5 rounded font-medium">
                                    {{ $row->user->divisi_program ?? 'Staf PILI' }}
                                </span>
                            </td>

                            <!-- Kegiatan & Durasi -->
                            <td class="p-4">
                                <div class="font-bold text-gray-800">{{ $row->nama_kegiatan }}</div>
                                <div class="text-xs text-gray-500 flex items-center gap-1 mt-0.5">
                                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                                    <span>{{ $row->lokasi_penggunaan }}</span>
                                </div>
                                <div class="mt-1 flex items-center gap-2">
                                    <span class="bg-blue-50 text-blue-700 text-[10px] font-bold px-2 py-0.5 rounded-full border border-blue-200">
                                        {{ \Carbon\Carbon::parse($row->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($row->tanggal_selesai)) + 1 }} Hari
                                    </span>
                                    <span class="text-[11px] text-gray-400">
                                        ({{ $row->items->sum('jumlah') }} unit aset)
                                    </span>
                                </div>
                            </td>

                            <!-- Status Otorisasi -->
                            <td class="p-4">
                                <div class="space-y-1 text-xs">
                                    <div class="flex items-center gap-2">
                                        <span class="text-gray-500 text-[11px] w-20">Atasan:</span>
                                        <span class="font-extrabold {{ $row->status_persetujuan == 'Disetujui' ? 'text-emerald-600' : ($row->status_persetujuan == 'Ditolak' ? 'text-rose-600' : 'text-amber-600') }}">
                                            {{ $row->status_persetujuan }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-gray-500 text-[11px] w-20">Pengelola:</span>
                                        <span class="font-bold {{ $row->status_pengelola == 'Disetujui' ? 'text-emerald-600' : ($row->status_pengelola == 'Ditolak' ? 'text-rose-600' : 'text-gray-400') }}">
                                            {{ $row->status_pengelola }}
                                        </span>
                                    </div>
                                    <div class="pt-1">
                                        <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-extrabold border
                                            {{ $row->status_keseluruhan == 'Disetujui' ? 'bg-blue-50 text-blue-700 border-blue-200' : '' }}
                                            {{ $row->status_keseluruhan == 'Aset Diserahkan' ? 'bg-indigo-50 text-indigo-700 border-indigo-200' : '' }}
                                            {{ $row->status_keseluruhan == 'Menunggu Persetujuan' ? 'bg-amber-50 text-amber-700 border-amber-200' : '' }}
                                            {{ $row->status_keseluruhan == 'Selesai' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : '' }}
                                            {{ $row->status_keseluruhan == 'Ditolak' ? 'bg-rose-50 text-rose-700 border-rose-200' : '' }}
                                            {{ $row->status_keseluruhan == 'Perlu Tindak Lanjut' ? 'bg-rose-50 text-rose-700 border-rose-300' : '' }}
                                        ">
                                            Status: {{ $row->status_keseluruhan }}
                                        </span>
                                    </div>
                                    @if($row->status_keseluruhan == 'Ditolak' && $row->alasan_penolakan)
                                        <p class="text-[10px] text-rose-600 font-medium italic mt-1 max-w-xs truncate" title="{{ $row->alasan_penolakan }}">
                                            Alasan: "{{ $row->alasan_penolakan }}"
                                        </p>
                                    @endif
                                </div>
                            </td>

                            <!-- Aksi Review -->
                            <td class="p-4 text-right">
                                <div class="flex flex-col items-end gap-1.5">
                                    <a href="{{ route('admin.persetujuan.show', $row->id) }}" class="inline-flex items-center gap-1.5 bg-pili-primary hover:bg-pili-dark text-white text-xs font-extrabold px-4 py-2 rounded-xl transition shadow-sm">
                                        <span>Review & Detail</span>
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                    </a>

                                    @if(in_array($row->status_persetujuan, ['Disetujui', 'Ditolak']))
                                        <form action="{{ route('admin.approve.reset', $row->id) }}" method="POST" onsubmit="return confirm('Kembalikan status pengajuan ini ke Pending?')">
                                            @csrf
                                            <button type="submit" class="text-[11px] text-gray-500 hover:text-pili-primary font-bold px-2 py-1 rounded hover:bg-gray-100 transition">
                                                Reset Approval
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-12 text-center text-gray-500 font-medium">
                                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3 text-gray-400">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                                @if($tab === 'pending')
                                    Tidak ada antrean peminjaman baru yang butuh persetujuan saat ini.
                                @elseif($tab === 'disetujui')
                                    Belum ada data peminjaman yang telah disetujui.
                                @elseif($tab === 'ditolak')
                                    Belum ada data peminjaman yang ditolak.
                                @else
                                    Belum ada riwayat transaksi peminjaman.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection