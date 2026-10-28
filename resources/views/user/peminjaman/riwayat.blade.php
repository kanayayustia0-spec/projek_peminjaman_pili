@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-5 sm:py-8 px-3.5 sm:px-6 lg:px-8">
    
    <div class="mb-6 sm:mb-8 flex flex-col md:flex-row justify-between items-start md:items-end gap-3 sm:gap-4 border-b border-gray-200 pb-4 sm:pb-5">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">Riwayat Peminjaman Saya</h1>
            <p class="text-gray-500 text-xs sm:text-sm mt-0.5 sm:mt-1">Pantau status pengajuan, detail transaksi, cetak bukti struk, dan pengembalian aset Anda.</p>
        </div>
        <div class="grid grid-cols-2 sm:flex sm:items-center gap-2 sm:gap-3 w-full sm:w-auto">
            <a href="{{ route('peminjaman.export') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 sm:py-3 px-3 sm:px-5 rounded-xl shadow-sm transition flex items-center justify-center gap-1.5 text-xs sm:text-sm text-center">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span>Export Excel</span>
            </a>
            <a href="{{ route('peminjaman.create') }}" class="bg-pili-primary hover:bg-pili-dark text-white font-extrabold py-2.5 sm:py-3 px-3 sm:px-6 rounded-xl shadow-md transition flex items-center justify-center gap-1.5 text-xs sm:text-sm text-center">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span>+ Pinjam Aset</span>
            </a>
        </div>
    </div>

    <!-- Alert Notifikasi -->
    @if(session('success'))
        <div class="bg-green-50 text-green-700 p-4 rounded-xl mb-6 font-bold border border-green-200 shadow-sm flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 text-red-700 p-4 rounded-xl mb-6 font-bold border border-red-200 shadow-sm">
            {{ session('error') }}
        </div>
    @endif

    <!-- Daftar Card Riwayat -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($peminjamans as $row)
            <div class="bg-white rounded-3xl shadow-soft border border-gray-100 overflow-hidden hover:shadow-md transition flex flex-col relative">
                
                <!-- Pita Merah jika Ditolak -->
                @if($row->status_keseluruhan == 'Ditolak')
                    <div class="absolute top-0 right-0 w-16 h-16 overflow-hidden">
                        <div class="bg-red-500 text-white text-[10px] font-black uppercase text-center py-1 absolute top-4 -right-6 w-24 rotate-45 shadow-sm">Ditolak</div>
                    </div>
                @endif

                <!-- Card Header -->
                <div class="p-6 border-b border-gray-50">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="font-extrabold text-lg text-gray-900 leading-tight pr-6">{{ $row->nama_kegiatan }}</h3>
                    </div>
                    <p class="text-xs text-gray-500 flex items-center gap-1.5 mb-3 font-medium">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                        {{ $row->lokasi_penggunaan }}
                    </p>
                    
                    <!-- Status Badge -->
                    <span class="inline-block px-3 py-1.5 text-[11px] uppercase font-bold tracking-wider rounded-lg border
                        {{ $row->status_keseluruhan == 'Disetujui' ? 'bg-blue-50 text-blue-700 border-blue-200' : '' }}
                        {{ in_array($row->status_keseluruhan, ['Aset Diserahkan', 'Sedang Digunakan']) ? 'bg-indigo-50 text-indigo-700 border-indigo-200' : '' }}
                        {{ in_array($row->status_keseluruhan, ['Menunggu Konfirmasi Pengembalian', 'Menunggu Pengecekan Gudang']) ? 'bg-amber-50 text-amber-800 border-amber-300 animate-pulse' : '' }}
                        {{ $row->status_keseluruhan == 'Perlu Tindak Lanjut' ? 'bg-rose-50 text-rose-700 border-rose-300 font-bold' : '' }}
                        {{ $row->status_keseluruhan == 'Terlambat' ? 'bg-red-50 text-red-700 border-red-200 animate-pulse' : '' }}
                        {{ $row->status_keseluruhan == 'Selesai' ? 'bg-green-50 text-green-700 border-green-200' : '' }}
                        {{ $row->status_keseluruhan == 'Menunggu Persetujuan' ? 'bg-yellow-50 text-yellow-700 border-yellow-200' : '' }}
                        {{ $row->status_keseluruhan == 'Ditolak' ? 'bg-red-50 text-red-700 border-red-200' : '' }}
                    ">
                        Status: {{ $row->status_keseluruhan }}
                    </span>

                    @if($row->status_keseluruhan == 'Ditolak')
                        <div class="mt-2.5 bg-rose-50 border border-rose-200 p-2.5 rounded-xl text-xs text-rose-700">
                            <span class="font-bold block text-[11px]">Alasan Penolakan:</span>
                            <span class="italic text-[11px]">{{ $row->alasan_penolakan ?? 'Pengajuan belum memenuhi syarat peminjaman.' }}</span>
                        </div>
                    @endif
                </div>

                <!-- Card Body -->
                <div class="p-6 flex-grow bg-gray-50/50">
                    <div class="text-sm text-gray-600 mb-5 flex items-center gap-2 font-medium">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <span>{{ $row->tanggal_mulai->format('d M') }} - {{ $row->tanggal_selesai->format('d M Y') }}</span>
                    </div>

                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Aset Dipinjam:</p>
                    <ul class="space-y-2">
                        @foreach($row->items->take(3) as $item)
                            <li class="text-sm font-medium text-gray-700 flex justify-between items-center bg-white border border-gray-100 px-3 py-2 rounded-lg shadow-sm">
                                <span class="truncate pr-2">{{ $item->barang->nama }}</span>
                                <span class="text-xs bg-gray-100 text-gray-600 px-1.5 py-0.5 rounded font-bold">x{{ $item->jumlah }}</span>
                            </li>
                        @endforeach
                        @if($row->items->count() > 3)
                            <li class="text-xs text-center text-gray-500 font-bold mt-2 italic">+ {{ $row->items->count() - 3 }} aset lainnya</li>
                        @endif
                    </ul>
                </div>

                <!-- Card Footer (Actions) -->
                <div class="p-5 bg-white border-t border-gray-50 flex flex-col gap-3">
                    
                    @if($row->status_keseluruhan == 'Disetujui')
                        <div class="bg-blue-50/80 p-3.5 rounded-2xl border border-blue-200 flex flex-col gap-2.5">
                            <div class="flex items-center gap-2 text-xs font-bold text-blue-800">
                                <span class="relative flex h-2.5 w-2.5">
                                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                                  <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-blue-600"></span>
                                </span>
                                <span>Menunggu Pengambilan di Gudang</span>
                            </div>
                            <form action="{{ route('peminjaman.ambil', $row->id) }}" method="POST" onsubmit="return confirm('Konfirmasi bahwa barang fisik telah Anda ambil di gudang?')">
                                @csrf
                                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white font-extrabold py-2.5 px-4 rounded-xl text-xs sm:text-sm transition text-center shadow-md flex items-center justify-center gap-2 group">
                                    <svg class="w-4 h-4 text-emerald-100 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                    <span>Barang Sudah Diambil</span>
                                </button>
                            </form>
                        </div>
                    @endif

                    @if(in_array($row->status_keseluruhan, ['Aset Diserahkan', 'Sedang Digunakan', 'Terlambat']))
                        <a href="{{ route('peminjaman.pengembalian.form', $row->id) }}" class="w-full bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 text-white font-extrabold py-3 px-4 rounded-xl text-xs sm:text-sm transition text-center shadow-md hover:shadow-lg flex justify-center items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path></svg>
                            <span>Ajukan Pengembalian Aset</span>
                        </a>
                    @endif

                    @if(in_array($row->status_keseluruhan, ['Menunggu Konfirmasi Pengembalian', 'Menunggu Pengecekan Gudang']))
                        <div class="w-full bg-amber-50 text-amber-800 font-bold py-2.5 px-3 rounded-xl text-xs border border-amber-200 flex items-center justify-center gap-2">
                            <svg class="w-4 h-4 animate-spin text-amber-600 flex-shrink-0" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            <span class="text-center">Menunggu Verifikasi Gudang...</span>
                        </div>
                    @endif

                    @if($row->status_keseluruhan == 'Perlu Tindak Lanjut')
                        <div class="w-full bg-rose-50 text-rose-800 font-bold py-2.5 px-3 rounded-xl text-xs border border-rose-200 flex items-center justify-center gap-2 text-center">
                            <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            <span>Pemeriksaan Menemukan Kendala</span>
                        </div>
                    @endif

                    <div class="grid grid-cols-2 gap-2">
                        <a href="{{ route('peminjaman.struk', $row->id) }}" class="bg-blue-50 border border-blue-200 hover:bg-blue-100 text-pili-primary font-bold py-2.5 px-3 rounded-xl text-xs transition text-center flex items-center justify-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                            <span>Struk</span>
                        </a>
                        <a href="{{ route('peminjaman.show', $row->id) }}" class="bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 font-bold py-2.5 px-3 rounded-xl text-xs transition text-center">
                            Detail
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-3xl p-12 text-center border-2 border-gray-200 border-dashed">
                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <h3 class="text-xl font-extrabold text-gray-900 mb-2">Belum Ada Riwayat</h3>
                <p class="text-gray-500 mb-6">Anda belum pernah mengajukan peminjaman aset sebelumnya.</p>
                <a href="{{ route('peminjaman.create') }}" class="inline-block bg-pili-primary hover:bg-pili-dark text-white font-bold py-2.5 px-6 rounded-xl transition">Mulai Pinjam Aset Pertama</a>
            </div>
        @endforelse
    </div>
</div>
@endsection