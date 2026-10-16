@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    
    <div class="mb-6 flex justify-between items-center print:hidden">
        <a href="{{ route('peminjaman.riwayat') }}" class="text-gray-500 hover:text-pili-primary font-bold">&larr; Kembali ke Riwayat</a>
        <button onclick="window.print()" class="bg-gray-800 text-white px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-black shadow-md flex items-center gap-2 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Cetak Struk / PDF
        </button>
    </div>

    <!-- STRUK DIGITAL -->
    <div class="bg-white p-8 md:p-12 shadow-2xl rounded-sm border-t-8 {{ $peminjaman->status_keseluruhan == 'Ditolak' ? 'border-red-500' : 'border-pili-primary' }} relative mx-auto" style="font-family: 'Courier New', Courier, monospace;">
        
        <!-- Header Struk -->
        <div class="text-center mb-8 border-b-2 border-dashed border-gray-300 pb-6">
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-widest uppercase">YAYASAN PILI</h1>
            <p class="text-sm text-gray-500">Green Network - Sistem Manajemen Aset</p>
            <p class="text-xs text-gray-400 mt-2">====================================</p>
            <h2 class="text-xl font-bold text-gray-800 mt-4 uppercase">Detail Transaksi Aset</h2>
            <p class="text-sm font-bold text-gray-500 mt-1">KODE TRX: #PILI-{{ str_pad($peminjaman->id, 5, '0', STR_PAD_LEFT) }}</p>
        </div>

        <!-- Jika Ditolak, Tampilkan Kotak Merah -->
        @if($peminjaman->status_keseluruhan == 'Ditolak')
            <div class="border-2 border-red-500 p-4 mb-8 text-center rotate-[-2deg]">
                <h3 class="font-black text-red-600 text-xl tracking-widest uppercase mb-1">PENGAJUAN DITOLAK</h3>
                <p class="text-sm text-red-700 font-bold">
                    Alasan: "{{ $peminjaman->alasan_penolakan ?? 'Barang tidak tersedia atau tidak disetujui oleh atasan.' }}"
                </p>
            </div>
        @endif

        <!-- Data Peminjam & Kegiatan (5W) -->
        <div class="text-sm text-gray-700 mb-8 space-y-3 border-b-2 border-dashed border-gray-300 pb-6">
            <div class="flex justify-between"><span class="font-bold">PEMINJAM (WHO):</span> <span class="font-bold">{{ $peminjaman->user->name }}</span></div>
            <div class="flex justify-between"><span class="font-bold">KEGIATAN (WHAT):</span> <span class="text-right uppercase">{{ $peminjaman->nama_kegiatan }}</span></div>
            <div class="flex justify-between"><span class="font-bold">LOKASI (WHERE):</span> <span class="text-right">{{ $peminjaman->lokasi_penggunaan }}</span></div>
            <div class="flex justify-between"><span class="font-bold">JADWAL (WHEN):</span> <span class="text-right font-bold">{{ $peminjaman->tanggal_mulai->format('d/m/Y H:i') }} <br> s/d {{ $peminjaman->tanggal_selesai->format('d/m/Y H:i') }}</span></div>
        </div>

        <!-- Daftar Barang (Keranjang) -->
        <div class="mb-8 border-b-2 border-dashed border-gray-300 pb-6">
            <h3 class="font-bold text-gray-900 mb-4 text-center border-b border-gray-200 pb-2 inline-block mx-auto w-full">RINCIAN ASET DIMINTA</h3>
            <table class="w-full text-sm text-gray-700">
                <thead>
                    <tr class="border-b border-gray-300 text-gray-500">
                        <th class="text-left pb-2">ITEM / KATEGORI</th>
                        <th class="text-right pb-2">QTY</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($peminjaman->items as $item)
                    <tr class="border-b border-gray-100 last:border-0">
                        <td class="py-3">
                            <div class="font-bold text-gray-900 uppercase">{{ $item->barang->nama }}</div>
                            <div class="text-xs text-gray-500">[{!! $item->barang->kode_inventaris !!}] - {{ $item->kategori_saat_pinjam ?? $item->barang->kategori }}</div>
                        </td>
                        <td class="py-3 text-right font-black text-lg">x{{ $item->jumlah }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Status Approval & Pengembalian -->
        <div class="mb-8 border-b-2 border-dashed border-gray-300 pb-6">
            <h3 class="font-bold text-gray-900 mb-3 text-center">STATUS OTORISASI & LOGISTIK</h3>
            <div class="flex justify-between items-center text-sm mb-1.5">
                <span>Otorisasi Atasan:</span> 
                <span class="font-bold {{ $peminjaman->status_persetujuan == 'Disetujui' ? 'text-green-600' : ($peminjaman->status_persetujuan == 'Ditolak' ? 'text-red-600' : 'text-yellow-600') }}">[{{ strtoupper($peminjaman->status_persetujuan) }}]</span>
            </div>
            <div class="flex justify-between items-center text-sm mb-1.5">
                <span>Logistik Gudang:</span> 
                <span class="font-bold {{ $peminjaman->status_pengelola == 'Disetujui' ? 'text-green-600' : ($peminjaman->status_pengelola == 'Ditolak' ? 'text-red-600' : 'text-gray-500') }}">[{{ strtoupper($peminjaman->status_pengelola) }}]</span>
            </div>
            <div class="flex justify-between items-center text-sm">
                <span>Status Barang Saat Ini:</span> 
                <span class="font-black {{ $peminjaman->status_keseluruhan == 'Selesai' ? 'text-green-600' : 'text-blue-600' }}">[{{ strtoupper($peminjaman->status_keseluruhan) }}]</span>
            </div>
        </div>

        <!-- Cap Digital (Watermark) -->
        <div class="text-center mt-12 pt-6">
            @if(in_array($peminjaman->status_keseluruhan, ['Disetujui', 'Aset Diserahkan', 'Sedang Digunakan', 'Menunggu Pengecekan Gudang']))
                <div class="inline-block p-4 border-4 border-green-500 text-green-600 font-black text-3xl rotate-[-5deg] opacity-60 mb-6 tracking-widest uppercase">
                    APPROVED
                </div>
            @elseif($peminjaman->status_keseluruhan == 'Selesai')
                <div class="inline-block p-4 border-4 border-blue-500 text-blue-600 font-black text-3xl rotate-[-5deg] opacity-60 mb-6 tracking-widest uppercase">
                    SELESAI / DIKEMBALIKAN
                </div>
            @endif
            
            <p class="text-xs text-gray-400">Scan barcode saat serah terima barang dengan Admin</p>
            <div class="flex justify-center h-12 mt-2 gap-1 opacity-50">
                <div class="w-1 bg-black h-full"></div><div class="w-3 bg-black h-full"></div><div class="w-1 bg-black h-full"></div><div class="w-2 bg-black h-full"></div><div class="w-4 bg-black h-full"></div><div class="w-1 bg-black h-full"></div><div class="w-3 bg-black h-full"></div><div class="w-1 bg-black h-full"></div><div class="w-2 bg-black h-full"></div><div class="w-1 bg-black h-full"></div>
            </div>
            <p class="text-[10px] text-gray-500 mt-4">Simpan struk digital ini sebagai bukti sah peminjaman di Yayasan PILI.</p>
        </div>
        
    </div>
</div>
@endsection