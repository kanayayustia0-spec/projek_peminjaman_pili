@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-5 sm:py-8 px-3.5 sm:px-6">
    
    <!-- NOTIFIKASI BERHASIL SUBMIT (RESPONSIF MOBILE) -->
    @if(session('success'))
        <div class="no-print bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 sm:p-5 rounded-2xl mb-5 flex flex-col sm:flex-row sm:items-center justify-between gap-3 shadow-sm">
            <div class="flex items-start sm:items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-emerald-100 flex items-center justify-center text-emerald-600 font-bold shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <div>
                    <p class="font-bold text-sm">Permohonan Berhasil Dikirim!</p>
                    <p class="text-xs text-emerald-700 leading-relaxed">{{ session('success') }}</p>
                </div>
            </div>
            <button onclick="window.print()" class="w-full sm:w-auto bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-4 py-2.5 rounded-xl text-xs flex items-center justify-center gap-1.5 shadow-sm transition shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                <span>Cetak Struk Sekarang</span>
            </button>
        </div>
    @endif

    <!-- TOMBOL NAVIGASI ATAS (NO PRINT) -->
    <div class="no-print flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
        <a href="{{ route('peminjaman.riwayat') }}" class="text-xs font-bold text-gray-500 hover:text-pili-primary flex items-center gap-1.5 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            <span>Riwayat Peminjaman</span>
        </a>

        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="flex-1 sm:flex-none bg-pili-primary hover:bg-pili-dark text-white font-bold px-4 py-2.5 rounded-xl text-xs flex items-center justify-center gap-2 shadow-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                <span>Cetak / PDF</span>
            </button>
            <a href="{{ route('dashboard') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold px-3.5 py-2.5 rounded-xl text-xs transition text-center">
                Beranda
            </a>
        </div>
    </div>

    <!-- LEMBAR STRUK PEMINJAMAN RESMI (RESPONSIF MOBILE & RAPI KETIKA DICETAK) -->
    <div class="bg-white rounded-2xl sm:rounded-3xl border border-gray-200 shadow-xl p-5 sm:p-10 lg:p-12 print:p-0 print:border-none print:shadow-none space-y-6 sm:space-y-8 text-gray-800" id="printArea">
        
        <!-- HEADER KOP STRUK -->
        <div class="border-b-2 border-gray-900 pb-5">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo PILI" class="h-12 sm:h-14 w-auto object-contain shrink-0">
                    <div>
                        <h2 class="text-base sm:text-xl font-black text-gray-900 tracking-tight leading-tight">YAYASAN PILI GREEN NETWORK</h2>
                        <p class="text-[11px] sm:text-xs font-semibold text-gray-500">Pusat Informasi Lingkungan Indonesia • Manajemen Aset</p>
                        <p class="text-[10px] text-gray-500">Jl. Tumenggung Wiradireja No.216, RT.06/RW.09, Cimahpar, Kec. Bogor Utara, Kota Bogor, Jawa Barat 16155 | Email: piligreennetwork@gmail.com</p>
                    </div>
                </div>

                <div class="sm:text-right pt-2 sm:pt-0 border-t sm:border-t-0 border-gray-100">
                    <span class="inline-block px-3 py-1 bg-blue-50 border border-blue-200 rounded-lg text-xs font-mono font-bold text-pili-primary">
                        {{ $peminjaman->nomor_transaksi }}
                    </span>
                    <p class="text-[10px] sm:text-[11px] text-gray-400 mt-0.5">Dicetak: {{ date('d M Y, H:i') }} WIB</p>
                </div>
            </div>

            <div class="mt-4 pt-3 border-t border-dashed border-gray-200 text-center">
                <h3 class="text-sm sm:text-base font-extrabold uppercase tracking-wider text-gray-900">
                    BUKTI PERMOHONAN / STRUK PEMINJAMAN ASET
                </h3>
            </div>
        </div>

        <!-- INFO PEMINJAM & KEGIATAN -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 bg-gray-50/80 p-4 sm:p-5 rounded-2xl border border-gray-100 text-xs">
            <div class="space-y-1.5 sm:space-y-2">
                <h4 class="font-bold text-gray-400 uppercase tracking-wider text-[10px]">Identitas Peminjam</h4>
                <div class="grid grid-cols-3 gap-1">
                    <span class="text-gray-500">Peminjam</span>
                    <span class="col-span-2 font-bold text-gray-900">: {{ $peminjaman->user->name }}</span>
                </div>
                <div class="grid grid-cols-3 gap-1">
                    <span class="text-gray-500">Email</span>
                    <span class="col-span-2 font-medium text-gray-800 truncate">: {{ $peminjaman->user->email }}</span>
                </div>
                <div class="grid grid-cols-3 gap-1">
                    <span class="text-gray-500">Divisi</span>
                    <span class="col-span-2 font-medium text-gray-800">: {{ $peminjaman->user->divisi_program ?? 'Umum' }}</span>
                </div>
                <div class="grid grid-cols-3 gap-1">
                    <span class="text-gray-500">No. WhatsApp</span>
                    <span class="col-span-2 font-medium text-gray-800">: {{ $peminjaman->user->no_hp ?? '-' }}</span>
                </div>
            </div>

            <div class="space-y-1.5 sm:space-y-2 border-t md:border-t-0 pt-3 md:pt-0 border-gray-200">
                <h4 class="font-bold text-gray-400 uppercase tracking-wider text-[10px]">Informasi Kegiatan & Waktu</h4>
                <div class="grid grid-cols-3 gap-1">
                    <span class="text-gray-500">Kegiatan</span>
                    <span class="col-span-2 font-bold text-gray-900">: {{ $peminjaman->nama_kegiatan }}</span>
                </div>
                <div class="grid grid-cols-3 gap-1">
                    <span class="text-gray-500">Lokasi</span>
                    <span class="col-span-2 font-medium text-gray-800">: {{ $peminjaman->lokasi_penggunaan }}</span>
                </div>
                <div class="grid grid-cols-3 gap-1">
                    <span class="text-gray-500">Urgensi</span>
                    <span class="col-span-2 font-medium text-gray-800">: {{ $peminjaman->tujuan_peminjaman }}</span>
                </div>
                <div class="grid grid-cols-3 gap-1">
                    <span class="text-gray-500">Jadwal</span>
                    <span class="col-span-2 font-bold text-pili-primary">: {{ $peminjaman->tanggal_mulai->format('d/m/Y H:i') }} s/d {{ $peminjaman->tanggal_selesai->format('d/m/Y H:i') }} ({{ $peminjaman->durasi_hari }} Hari)</span>
                </div>
            </div>
        </div>

        <!-- TABEL RINCIAN ASET YANG DIPINJAM (SCROLLABLE ON SMALL HP) -->
        <div>
            <div class="flex items-center justify-between mb-2">
                <h4 class="text-xs font-extrabold uppercase tracking-wider text-gray-700">Daftar Aset yang Diajukan</h4>
                <span class="text-[10px] sm:text-[11px] font-bold text-gray-500">{{ $peminjaman->items->count() }} Jenis (Total {{ $peminjaman->items->sum('jumlah') }} Unit)</span>
            </div>

            <div class="overflow-x-auto rounded-xl border border-gray-200">
                <table class="w-full text-left text-xs border-collapse min-w-[460px]">
                    <thead class="bg-gray-100 text-gray-600 font-extrabold uppercase text-[10px] tracking-wider border-b border-gray-200">
                        <tr>
                            <th class="p-2.5 sm:p-3 w-10 text-center">No</th>
                            <th class="p-2.5 sm:p-3">Kode Aset</th>
                            <th class="p-2.5 sm:p-3">Nama Aset / Inventaris</th>
                            <th class="p-2.5 sm:p-3">Kategori</th>
                            <th class="p-2.5 sm:p-3">Kondisi</th>
                            <th class="p-2.5 sm:p-3 text-center">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($peminjaman->items as $index => $item)
                            <tr class="hover:bg-gray-50/50">
                                <td class="p-2.5 sm:p-3 text-center font-bold text-gray-400">{{ $index + 1 }}</td>
                                <td class="p-2.5 sm:p-3 font-mono font-bold text-pili-primary whitespace-nowrap">{{ $item->barang->kode_inventaris }}</td>
                                <td class="p-2.5 sm:p-3 font-bold text-gray-900">{{ $item->barang->nama }}</td>
                                <td class="p-2.5 sm:p-3 whitespace-nowrap">
                                    <span class="bg-blue-50 text-blue-700 px-2 py-0.5 rounded text-[10px] font-bold border border-blue-100">
                                        Kat {{ $item->kategori_saat_pinjam }}
                                    </span>
                                </td>
                                <td class="p-2.5 sm:p-3 text-gray-600">{{ $item->barang->kondisi }}</td>
                                <td class="p-2.5 sm:p-3 text-center font-black text-gray-900 whitespace-nowrap">{{ $item->jumlah }} Unit</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- STATUS PERSETUJUAN & ALUR TRANSAKSI -->
        <div class="bg-gray-50 p-3 sm:p-4 rounded-2xl border border-gray-100 grid grid-cols-3 gap-2 sm:gap-3 text-center text-xs">
            <div class="bg-white p-2.5 sm:p-3 rounded-xl border border-gray-200 shadow-2xs">
                <p class="text-[9px] sm:text-[10px] font-bold text-gray-400 uppercase">Pengelola</p>
                <p class="font-extrabold text-[11px] sm:text-xs mt-0.5 {{ $peminjaman->status_pengelola == 'Disetujui' ? 'text-emerald-600' : ($peminjaman->status_pengelola == 'Ditolak' ? 'text-rose-600' : 'text-amber-600') }}">
                    {{ $peminjaman->status_pengelola }}
                </p>
            </div>
            <div class="bg-white p-2.5 sm:p-3 rounded-xl border border-gray-200 shadow-2xs">
                <p class="text-[9px] sm:text-[10px] font-bold text-gray-400 uppercase">Persetujuan</p>
                <p class="font-extrabold text-[11px] sm:text-xs mt-0.5 {{ $peminjaman->status_persetujuan == 'Disetujui' ? 'text-emerald-600' : ($peminjaman->status_persetujuan == 'Ditolak' ? 'text-rose-600' : 'text-amber-600') }}">
                    {{ $peminjaman->status_persetujuan }}
                </p>
            </div>
            <div class="bg-white p-2.5 sm:p-3 rounded-xl border border-gray-200 shadow-2xs">
                <p class="text-[9px] sm:text-[10px] font-bold text-gray-400 uppercase">Transaksi</p>
                <p class="font-black text-[11px] sm:text-xs mt-0.5 text-pili-primary truncate">
                    {{ $peminjaman->status_keseluruhan }}
                </p>
            </div>
        </div>

        <!-- KETENTUAN DAN CATATAN -->
        <div class="text-[10px] text-gray-500 space-y-1 border-t border-gray-200 pt-3">
            <p class="font-bold text-gray-700">Ketentuan Peminjam:</p>
            <ol class="list-decimal list-inside space-y-0.5 leading-relaxed">
                <li>Struk ini sah sebagai bukti permohonan peminjaman aset inventaris Yayasan PILI.</li>
                <li>Maksimal waktu pinjam adalah 2 hari (48 jam) terhitung sejak serah terima fisik (BASTA).</li>
                <li>Barang wajib diperiksa kelengkapannya dan dikembalikan dalam kondisi bersih serta berfungsi baik.</li>
            </ol>
        </div>

        <!-- KOLOM TANDA TANGAN RESMI (RESPONSIF HP: GRID SESUAI PRINT & SCREEN) -->
        <div class="grid grid-cols-1 sm:grid-cols-3 print:grid-cols-3 gap-6 sm:gap-4 pt-4 sm:pt-6 text-center text-xs">
            <div class="space-y-12 sm:space-y-16">
                <p class="font-bold text-gray-600">Pemohon / Peminjam,</p>
                <div>
                    <p class="font-extrabold text-gray-900 underline">{{ $peminjaman->user->name }}</p>
                    <p class="text-[10px] text-gray-400">{{ $peminjaman->user->jabatan ?? 'Staf PILI' }}</p>
                </div>
            </div>

            <div class="space-y-12 sm:space-y-16">
                <p class="font-bold text-gray-600">Admin Pengelola Aset,</p>
                <div>
                    <p class="font-extrabold text-gray-900 underline">( ........................................ )</p>
                    <p class="text-[10px] text-gray-400">Tim Logistik & Inventaris</p>
                </div>
            </div>

            <div class="space-y-12 sm:space-y-16">
                <p class="font-bold text-gray-600">Admin Persetujuan,</p>
                <div>
                    <p class="font-extrabold text-gray-900 underline">( ........................................ )</p>
                    <p class="text-[10px] text-gray-400">Pimpinan / Koordinator Program</p>
                </div>
            </div>
        </div>

    </div>

</div>

<!-- CSS KHUSUS CETAK PRINTER -->
<style>
@media print {
    body {
        background: white !important;
        color: black !important;
    }
    nav, footer, .no-print {
        display: none !important;
    }
    #printArea {
        border: none !important;
        box-shadow: none !important;
        padding: 0 !important;
        margin: 0 !important;
        width: 100% !important;
    }
}
</style>
@endsection
