@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    
    <div class="mb-6 flex justify-between items-center print:hidden">
        <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-pili-primary font-bold">&larr; Kembali ke Riwayat</a>
        <button onclick="window.print()" class="bg-gray-800 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-black shadow flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Cetak / Screenshot Struk
        </button>
    </div>

    <!-- STRUK DIGITAL START -->
    <div class="bg-white p-8 md:p-12 shadow-2xl rounded-sm border-t-8 border-pili-primary relative mx-auto" style="font-family: 'Courier New', Courier, monospace;">
        
        <!-- Header Struk -->
        <div class="text-center mb-8 border-b-2 border-dashed border-gray-300 pb-6">
            <h1 class="text-2xl font-bold text-gray-900 tracking-widest uppercase">YAYASAN PILI</h1>
            <p class="text-sm text-gray-500">Green Network - Sistem Manajemen Aset</p>
            <p class="text-xs text-gray-400 mt-1">====================================</p>
            <h2 class="text-lg font-bold text-gray-800 mt-4 uppercase">Bukti Peminjaman Aset</h2>
            <p class="text-xs text-gray-500">KODE TRX: #PILI-{{ str_pad($peminjaman->id, 5, '0', STR_PAD_LEFT) }}</p>
        </div>

        <!-- Data Peminjam & Kegiatan (5W) -->
        <div class="text-sm text-gray-700 mb-8 space-y-2 border-b-2 border-dashed border-gray-300 pb-6">
            <div class="flex justify-between"><span class="font-bold">PEMINJAM (WHO):</span> <span>{{ $peminjaman->user->name }}</span></div>
            <div class="flex justify-between"><span class="font-bold">KEGIATAN (WHAT):</span> <span class="text-right">{{ $peminjaman->nama_kegiatan }}</span></div>
            <div class="flex justify-between"><span class="font-bold">LOKASI (WHERE):</span> <span class="text-right">{{ $peminjaman->lokasi_penggunaan }}</span></div>
            <div class="flex justify-between"><span class="font-bold">JADWAL (WHEN):</span> <span class="text-right">{{ $peminjaman->tanggal_mulai->format('d/m/Y H:i') }} <br> s/d {{ $peminjaman->tanggal_selesai->format('d/m/Y H:i') }}</span></div>
        </div>

        <!-- Daftar Barang (Keranjang) -->
        <div class="mb-8 border-b-2 border-dashed border-gray-300 pb-6">
            <h3 class="font-bold text-gray-900 mb-3 text-center">RINCIAN ASET DIPINJAM</h3>
            <table class="w-full text-sm text-gray-700">
                <thead>
                    <tr class="border-b border-gray-300">
                        <th class="text-left pb-2">ITEM / KATEGORI</th>
                        <th class="text-right pb-2">QTY</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($peminjaman->items as $item)
                    <tr class="border-b border-gray-100 last:border-0">
                        <td class="py-3">
                            <div class="font-bold text-gray-900 uppercase">{{ $item->barang->nama }}</div>
                            <div class="text-xs text-gray-500">[{!! $item->barang->kode_inventaris !!}] - {{ $item->kategori_saat_pinjam }}</div>
                        </td>
                        <td class="py-3 text-right font-bold text-lg">x{{ $item->jumlah }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Status Approval -->
        <div class="mb-8">
            <h3 class="font-bold text-gray-900 mb-2 text-center">STATUS OTORISASI</h3>
            <div class="flex justify-between items-center text-sm mb-1">
                <span>Admin Pengelola:</span> 
                <span class="font-bold {{ $peminjaman->status_pengelola == 'Disetujui' ? 'text-green-600' : 'text-gray-500' }}">[{{ strtoupper($peminjaman->status_pengelola) }}]</span>
            </div>
            <div class="flex justify-between items-center text-sm">
                <span>Admin Persetujuan:</span> 
                <span class="font-bold {{ $peminjaman->status_persetujuan == 'Disetujui' ? 'text-green-600' : 'text-gray-500' }}">[{{ strtoupper($peminjaman->status_persetujuan) }}]</span>
            </div>
        </div>

        <!-- Barcode Dummy / Tanda Terima -->
        <div class="text-center mt-12 pt-6 border-t-2 border-dashed border-gray-300">
            @if($peminjaman->status_keseluruhan == 'Disetujui' || $peminjaman->status_keseluruhan == 'Aset Diserahkan' || $peminjaman->status_keseluruhan == 'Selesai')
                <div class="inline-block p-4 border-4 border-green-500 text-green-600 font-black text-3xl rotate-[-5deg] opacity-70 mb-6 tracking-widest uppercase">
                    APPROVED
                </div>
            @endif
            <p class="text-xs text-gray-400">Scan barcode saat pengambilan barang di gudang</p>
            <!-- Barcode CSS Aesthetic -->
            <div class="flex justify-center h-12 mt-2 gap-1 opacity-60">
                <div class="w-1 bg-black h-full"></div><div class="w-3 bg-black h-full"></div><div class="w-1 bg-black h-full"></div><div class="w-2 bg-black h-full"></div><div class="w-4 bg-black h-full"></div><div class="w-1 bg-black h-full"></div><div class="w-3 bg-black h-full"></div><div class="w-1 bg-black h-full"></div><div class="w-2 bg-black h-full"></div><div class="w-1 bg-black h-full"></div>
            </div>
            <p class="text-[10px] text-gray-500 mt-4">Simpan struk digital ini sebagai bukti sah peminjaman.</p>
        </div>
        
    </div>
    <!-- STRUK DIGITAL END -->

</div>
@endsection