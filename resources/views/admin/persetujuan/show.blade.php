@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-5 sm:py-8 px-3.5 sm:px-6 lg:px-8" x-data="{ showRejectModal: false }">
    
    <!-- HEADER & NAVIGASI -->
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.persetujuan.index') }}" class="inline-flex items-center gap-1.5 text-xs sm:text-sm font-bold text-gray-500 hover:text-pili-primary transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span>Kembali ke Daftar Otorisasi</span>
            </a>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('peminjaman.struk', $peminjaman->id) }}" target="_blank" class="inline-flex items-center gap-1.5 bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 text-xs sm:text-sm font-bold px-3 py-2 rounded-xl transition shadow-2xs">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                <span>Lihat Struk</span>
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

    <!-- STATUS BANNER SAAT INI -->
    <div class="bg-white rounded-2xl p-5 mb-6 border border-gray-200 shadow-soft flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Status Permohonan Saat Ini</span>
            <div class="flex items-center gap-3 mt-1">
                <span class="text-lg font-black text-gray-900">
                    #PILI-{{ str_pad($peminjaman->id, 5, '0', STR_PAD_LEFT) }}
                </span>
                <span class="px-3 py-1 rounded-full text-xs font-black border
                    {{ $peminjaman->status_keseluruhan == 'Disetujui' ? 'bg-blue-50 text-blue-700 border-blue-200' : '' }}
                    {{ $peminjaman->status_keseluruhan == 'Aset Diserahkan' ? 'bg-indigo-50 text-indigo-700 border-indigo-200' : '' }}
                    {{ $peminjaman->status_keseluruhan == 'Menunggu Persetujuan' ? 'bg-amber-50 text-amber-700 border-amber-200' : '' }}
                    {{ $peminjaman->status_keseluruhan == 'Selesai' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : '' }}
                    {{ $peminjaman->status_keseluruhan == 'Ditolak' ? 'bg-rose-50 text-rose-700 border-rose-200' : '' }}
                    {{ $peminjaman->status_keseluruhan == 'Perlu Tindak Lanjut' ? 'bg-rose-50 text-rose-700 border-rose-300' : '' }}
                ">
                    {{ $peminjaman->status_keseluruhan }}
                </span>
            </div>
            @if($peminjaman->status_keseluruhan == 'Ditolak' && $peminjaman->alasan_penolakan)
                <p class="text-xs text-rose-600 font-semibold mt-1">
                    Alasan Penolakan: "{{ $peminjaman->alasan_penolakan }}"
                </p>
            @endif
        </div>

        <!-- Dua Kolom Status Approval -->
        <div class="grid grid-cols-2 gap-3 text-xs">
            <div class="bg-gray-50 border border-gray-200 px-3 py-2 rounded-xl text-center">
                <span class="text-[10px] text-gray-500 uppercase font-bold block">Status Atasan</span>
                <span class="font-extrabold {{ $peminjaman->status_persetujuan == 'Disetujui' ? 'text-emerald-600' : ($peminjaman->status_persetujuan == 'Ditolak' ? 'text-rose-600' : 'text-amber-600') }}">
                    {{ $peminjaman->status_persetujuan }}
                </span>
            </div>
            <div class="bg-gray-50 border border-gray-200 px-3 py-2 rounded-xl text-center">
                <span class="text-[10px] text-gray-500 uppercase font-bold block">Status Pengelola</span>
                <span class="font-extrabold {{ $peminjaman->status_pengelola == 'Disetujui' ? 'text-emerald-600' : ($peminjaman->status_pengelola == 'Ditolak' ? 'text-rose-600' : 'text-gray-400') }}">
                    {{ $peminjaman->status_pengelola }}
                </span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8">
        
        <!-- KOLOM KIRI: Data 5W & Pemohon -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-3xl shadow-soft p-6 sm:p-8 border border-gray-100">
                <h2 class="text-lg font-black text-gray-900 mb-5 border-b border-gray-100 pb-3 flex items-center justify-between">
                    <span>Informasi Peminjaman (5W)</span>
                    <span class="text-xs font-normal text-gray-400">Diajukan: {{ $peminjaman->created_at->format('d M Y, H:i') }}</span>
                </h2>
                
                <div class="space-y-5 text-sm">
                    <!-- WHO -->
                    <div class="flex items-start gap-3 bg-gray-50/70 p-4 rounded-2xl border border-gray-100">
                        <div class="w-10 h-10 rounded-xl bg-pili-light text-pili-primary flex items-center justify-center font-black text-base shrink-0">
                            {{ strtoupper(substr($peminjaman->user->name, 0, 1)) }}
                        </div>
                        <div class="flex-grow">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Who (Pemohon & Penanggung Jawab)</p>
                            <p class="text-gray-900 font-extrabold text-base">{{ $peminjaman->user->name }}</p>
                            <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-gray-500 mt-1">
                                <span>Email: {{ $peminjaman->user->email }}</span>
                                <span>No. WA: {{ $peminjaman->user->no_hp ?? '-' }}</span>
                                <span>Divisi: {{ $peminjaman->user->divisi_program ?? 'Staf PILI' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- WHAT & WHERE -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="bg-gray-50/70 p-4 rounded-2xl border border-gray-100">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">What (Nama Kegiatan)</p>
                            <p class="text-gray-900 font-bold">{{ $peminjaman->nama_kegiatan }}</p>
                        </div>
                        <div class="bg-gray-50/70 p-4 rounded-2xl border border-gray-100">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Where (Lokasi Penggunaan)</p>
                            <p class="text-gray-900 font-bold">{{ $peminjaman->lokasi_penggunaan }}</p>
                        </div>
                    </div>

                    <!-- WHEN -->
                    <div class="bg-gray-50/70 p-4 rounded-2xl border border-gray-100">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">When (Jadwal Penggunaan)</p>
                        <div class="flex flex-wrap items-center gap-2 text-gray-900 font-bold">
                            <span>{{ $peminjaman->tanggal_mulai->format('d M Y, H:i') }}</span>
                            <span class="text-gray-400">s/d</span>
                            <span>{{ $peminjaman->tanggal_selesai->format('d M Y, H:i') }}</span>
                            <span class="bg-blue-50 text-blue-700 text-xs font-bold px-2.5 py-0.5 rounded-full border border-blue-200 ml-auto">
                                {{ \Carbon\Carbon::parse($peminjaman->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($peminjaman->tanggal_selesai)) + 1 }} Hari
                            </span>
                        </div>
                    </div>

                    <!-- WHY -->
                    <div class="bg-gray-50/70 p-4 rounded-2xl border border-gray-100">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Why (Urgensi / Tujuan Kegiatan)</p>
                        <p class="text-gray-800 leading-relaxed">{{ $peminjaman->tujuan_peminjaman }}</p>
                    </div>

                    <!-- Dokumen Pendukung jika ada -->
                    @if($peminjaman->dokumen_pendukung)
                        <div class="bg-blue-50/50 p-4 rounded-2xl border border-blue-100 flex items-center justify-between">
                            <div class="flex items-center gap-2 text-xs font-bold text-pili-primary">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                <span>Dokumen Pendukung Terlampir</span>
                            </div>
                            <a href="{{ asset('storage/' . $peminjaman->dokumen_pendukung) }}" target="_blank" class="text-xs bg-white text-pili-primary font-bold px-3 py-1.5 rounded-lg border border-blue-200 hover:bg-blue-50 transition">
                                Unduh / Buka
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- KOLOM KANAN: Keranjang Aset & Panel Keputusan -->
        <div class="lg:col-span-1 space-y-6">
            
            <!-- Keranjang Aset yang Diminta -->
            <div class="bg-white rounded-3xl shadow-soft p-6 border border-gray-100">
                <h3 class="text-sm font-black text-gray-900 mb-4 flex items-center justify-between">
                    <span class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-pili-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        Aset yang Diminta
                    </span>
                    <span class="text-xs text-gray-500 font-bold">{{ $peminjaman->items->sum('jumlah') }} Unit</span>
                </h3>

                <ul class="space-y-2.5">
                    @foreach($peminjaman->items as $item)
                        <li class="p-3 bg-gray-50/80 rounded-2xl border border-gray-100 flex items-center justify-between text-xs">
                            <div class="pr-2">
                                <p class="font-bold text-gray-900 leading-tight">{{ $item->barang->nama }}</p>
                                <p class="text-[10px] text-gray-500 font-mono mt-0.5">{{ $item->barang->kode_inventaris }}</p>
                                <span class="inline-block mt-1 bg-blue-50 text-blue-700 text-[9px] font-bold px-1.5 py-0.5 rounded border border-blue-100">
                                    Kat {{ $item->kategori_saat_pinjam }}
                                </span>
                            </div>
                            <span class="font-black text-pili-primary bg-pili-light px-2.5 py-1 rounded-xl shrink-0">
                                x{{ $item->jumlah }}
                            </span>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Panel Keputusan Otorisasi -->
            <div class="bg-white rounded-3xl shadow-soft p-6 border-t-4 border-pili-primary space-y-3">
                <h3 class="text-sm font-black text-gray-900 text-center mb-2">Panel Keputusan Otorisasi</h3>

                @if($peminjaman->status_persetujuan == 'Pending')
                    <!-- 1. Tombol Setujui Normal -->
                    <form action="{{ route('admin.persetujuan.approve', $peminjaman->id) }}" method="POST" onsubmit="return confirm('Setujui pengajuan peminjaman ini?')">
                        @csrf
                        <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white font-extrabold py-3 px-4 rounded-xl shadow-md transition flex items-center justify-center gap-2 text-xs sm:text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            <span>SETUJUI PEMINJAMAN</span>
                        </button>
                    </form>

                    <!-- 2. Tombol Setujui Sepenuhnya (Bypass Pengelola) -->
                    <form action="{{ route('admin.approve.langsung', $peminjaman->id) }}" method="POST" onsubmit="return confirm('Setujui sepenuhnya (bypass) sehingga status langsung disetujui tanpa menunggu pengelola?')">
                        @csrf
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 active:scale-95 text-white font-bold py-2.5 px-4 rounded-xl shadow-sm transition flex items-center justify-center gap-1.5 text-xs">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            <span>Setujui Sepenuhnya (Bypass)</span>
                        </button>
                    </form>

                    <!-- 3. Tombol Tolak -->
                    <button @click="showRejectModal = true" class="w-full bg-white border-2 border-rose-200 hover:bg-rose-50 text-rose-600 font-extrabold py-2.5 px-4 rounded-xl transition flex items-center justify-center gap-1.5 text-xs">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        <span>Tolak Pengajuan</span>
                    </button>

                @elseif($peminjaman->status_persetujuan == 'Disetujui')
                    <div class="bg-emerald-50 text-emerald-800 p-3.5 rounded-2xl border border-emerald-200 text-xs font-bold text-center">
                        <div class="flex items-center justify-center gap-1.5 mb-1 text-emerald-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            <span>Telah Anda Setujui</span>
                        </div>
                        @if($peminjaman->status_pengelola == 'Pending')
                            <p class="text-[11px] font-normal text-emerald-700 mt-1">
                                Menunggu verifikasi dari Admin Pengelola Gudang.
                            </p>
                        @endif
                    </div>

                    <!-- Reset Approval jika ingin revisi -->
                    <form action="{{ route('admin.approve.reset', $peminjaman->id) }}" method="POST" onsubmit="return confirm('Kembalikan status pengajuan ini ke Pending?')">
                        @csrf
                        <button type="submit" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2 px-3 rounded-xl transition text-xs flex items-center justify-center gap-1">
                            <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            <span>Reset Keputusan (Ubah)</span>
                        </button>
                    </form>

                @elseif($peminjaman->status_persetujuan == 'Ditolak')
                    <div class="bg-rose-50 text-rose-800 p-3.5 rounded-2xl border border-rose-200 text-xs font-bold text-center">
                        <div class="flex items-center justify-center gap-1.5 mb-1 text-rose-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                            <span>Telah Anda Tolak</span>
                        </div>
                        <p class="text-[11px] font-normal text-rose-700 mt-1">
                            Alasan: "{{ $peminjaman->alasan_penolakan ?? '-' }}"
                        </p>
                    </div>

                    <!-- Reset Approval jika ingin beri kesempatan lagi -->
                    <form action="{{ route('admin.approve.reset', $peminjaman->id) }}" method="POST" onsubmit="return confirm('Reset status kembali ke Pending?')">
                        @csrf
                        <button type="submit" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2 px-3 rounded-xl transition text-xs flex items-center justify-center gap-1">
                            <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            <span>Reset / Buka Kembali Pengajuan</span>
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <!-- MODAL PENOLAKAN DENGAN ALASAN -->
    <div x-show="showRejectModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-gray-900 bg-opacity-50 backdrop-blur-sm" @click="showRejectModal = false"></div>
            
            <div class="relative bg-white rounded-3xl w-full max-w-md p-6 sm:p-7 shadow-2xl border border-gray-100">
                <div class="text-center mb-5">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-2xl bg-rose-100 text-rose-600 mb-3">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <h3 class="text-base sm:text-lg font-black text-gray-900">Tolak Permohonan Peminjaman?</h3>
                    <p class="text-xs text-gray-500 mt-1">Sertakan alasan penolakan agar pemohon memahami kendala atau alasan kenapa pengajuan tidak dapat disetujui.</p>
                </div>
                
                <form action="{{ route('admin.persetujuan.reject', $peminjaman->id) }}" method="POST">
                    @csrf
                    <div class="mb-5 text-left">
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Alasan Penolakan</label>
                        <textarea name="alasan" rows="3" required class="w-full border-gray-300 rounded-xl p-3 text-xs bg-gray-50 focus:border-rose-500 focus:ring-rose-500 placeholder-gray-400" placeholder="Contoh: Aset kamera sedang diprioritaskan untuk tim lapangan lain pada tanggal tersebut..."></textarea>
                    </div>
                    
                    <div class="flex gap-2.5">
                        <button type="button" @click="showRejectModal = false" class="flex-1 bg-white border border-gray-300 text-gray-700 font-bold py-2.5 rounded-xl hover:bg-gray-50 text-xs">Batal</button>
                        <button type="submit" class="flex-1 bg-rose-600 text-white font-extrabold py-2.5 rounded-xl shadow-md hover:bg-rose-700 text-xs">Ya, Tolak Pengajuan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection