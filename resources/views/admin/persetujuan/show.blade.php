@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-8 px-4 sm:px-6 lg:px-8" x-data="{ showRejectModal: false }">
    
    <div class="mb-6 flex items-center gap-4">
        <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-pili-primary font-bold">&larr; Kembali ke Antrean</a>
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Review Pengajuan</h1>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        
        <!-- KOLOM KIRI: Data 5W -->
        <div class="md:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl shadow-soft p-6 md:p-8 border border-gray-100">
                <h2 class="text-xl font-extrabold text-gray-900 mb-6 border-b pb-3">Informasi Kegiatan (5W)</h2>
                
                <div class="space-y-5">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Who (Pemohon)</p>
                        <p class="text-gray-900 font-bold text-lg">{{ $peminjaman->user->name }}</p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">What (Nama Kegiatan)</p>
                            <p class="text-gray-900 font-bold">{{ $peminjaman->nama_kegiatan }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Where (Lokasi)</p>
                            <p class="text-gray-900 font-bold">{{ $peminjaman->lokasi_penggunaan }}</p>
                        </div>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">When (Waktu Pelaksanaan)</p>
                        <p class="text-gray-900 font-bold">{{ $peminjaman->tanggal_mulai->format('d M Y, H:i') }} <span class="text-gray-400 mx-2">s/d</span> {{ $peminjaman->tanggal_selesai->format('d M Y, H:i') }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Why (Urgensi / Tujuan)</p>
                        <p class="text-gray-800 leading-relaxed">{{ $peminjaman->tujuan_peminjaman }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- KOLOM KANAN: Daftar Keranjang & Keputusan -->
        <div class="md:col-span-1 space-y-6">
            
            <!-- Keranjang Aset -->
            <div class="bg-white rounded-2xl shadow-soft p-6 border border-gray-100">
                <h3 class="text-lg font-extrabold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-pili-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    Aset yang Diminta
                </h3>
                <ul class="space-y-3">
                    @foreach($peminjaman->items as $item)
                        <li class="flex justify-between items-center bg-gray-50 p-3 rounded-xl border border-gray-100">
                            <div>
                                <p class="text-sm font-bold text-gray-900">{{ $item->barang->nama }}</p>
                                <p class="text-[10px] text-gray-500 uppercase">{{ $item->barang->kode_inventaris }}</p>
                            </div>
                            <span class="font-black text-pili-primary bg-pili-light px-2 py-1 rounded-lg">x{{ $item->jumlah }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Panel Keputusan -->
            <div class="bg-white rounded-2xl shadow-soft p-6 border-t-4 border-pili-primary">
                <h3 class="text-lg font-extrabold text-gray-900 mb-4 text-center">Otorisasi Akhir</h3>
                
                <form action="{{ route('admin.persetujuan.approve', $peminjaman->id) }}" method="POST" onsubmit="return confirm('Anda yakin menyetujui pengajuan ini?');">
                    @csrf
                    <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white font-extrabold py-3.5 rounded-xl shadow-lg transition mb-3 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        SETUJUI PEMINJAMAN
                    </button>
                </form>

                <button @click="showRejectModal = true" class="w-full bg-white border-2 border-red-100 hover:bg-red-50 text-red-600 font-bold py-3 rounded-xl transition flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    TOLAK PENGAJUAN
                </button>
            </div>
        </div>
    </div>

    <!-- MODAL PENOLAKAN -->
    <div x-show="showRejectModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-gray-900 bg-opacity-50 backdrop-blur-sm" @click="showRejectModal = false"></div>
            
            <div class="relative bg-white rounded-2xl w-full max-w-md p-6 shadow-xl">
                <div class="text-center mb-6">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                        <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <h3 class="text-lg font-black text-gray-900">Tolak Peminjaman Ini?</h3>
                    <p class="text-sm text-gray-500 mt-2">Peminjaman yang ditolak tidak dapat dibatalkan. Peminjam harus membuat pengajuan ulang.</p>
                </div>
                
                <form action="{{ route('admin.persetujuan.reject', $peminjaman->id) }}" method="POST">
                    @csrf
                    <!-- Jika ingin menyertakan alasan penolakan -->
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Alasan Penolakan (Opsional)</label>
                        <textarea name="alasan" rows="3" class="w-full border-gray-300 rounded-lg p-3 bg-gray-50 focus:border-red-500 focus:ring-red-500" placeholder="Misal: Barang sedang digunakan untuk event lain..."></textarea>
                    </div>
                    
                    <div class="flex gap-3">
                        <button type="button" @click="showRejectModal = false" class="flex-1 bg-white border border-gray-300 text-gray-700 font-bold py-2.5 rounded-xl hover:bg-gray-50">Batal</button>
                        <button type="submit" class="flex-1 bg-red-600 text-white font-bold py-2.5 rounded-xl shadow-md hover:bg-red-700">Ya, Tolak!</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection