@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-8">

    <div class="mb-2">
        <h1 class="text-3xl font-extrabold text-gray-900">Dashboard {{ ucfirst(Auth::guard('admin')->user()->role) }}</h1>
        <p class="text-gray-500 text-sm mt-1">Selamat datang kembali, {{ Auth::guard('admin')->user()->username }}!</p>
    </div>

    <!-- TAMPILAN KHUSUS ADMIN PERSETUJUAN -->
    @if(Auth::guard('admin')->user()->role == 'persetujuan')
        
        <!-- 3 Kotak Statistik Utama -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Card 1: Perlu Persetujuan (Warna Peringatan) -->
            <div class="bg-white rounded-2xl p-6 border-l-8 border-yellow-400 shadow-soft flex items-center justify-between">
                <div>
                    <p class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-1">Perlu Disetujui</p>
                    <h2 class="text-4xl font-black text-gray-900">{{ $menunggu }}</h2>
                </div>
                <div class="w-14 h-14 bg-yellow-50 text-yellow-500 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>

            <!-- Card 2: Disetujui Bulan Ini (Warna Sukses) -->
            <div class="bg-white rounded-2xl p-6 border-l-8 border-green-500 shadow-soft flex items-center justify-between">
                <div>
                    <p class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-1">Disetujui Bulan Ini</p>
                    <h2 class="text-4xl font-black text-gray-900">{{ $disetujuiBulanIni }}</h2>
                </div>
                <div class="w-14 h-14 bg-green-50 text-green-500 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>

            <!-- Card 3: Ditolak (Warna Bahaya) -->
            <div class="bg-white rounded-2xl p-6 border-l-8 border-red-500 shadow-soft flex items-center justify-between">
                <div>
                    <p class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-1">Total Ditolak</p>
                    <h2 class="text-4xl font-black text-gray-900">{{ $ditolak }}</h2>
                </div>
                <div class="w-14 h-14 bg-red-50 text-red-500 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
        </div>

        <!-- Tabel Antrean Singkat (Quick View) -->
        <div class="bg-white rounded-2xl shadow-soft border border-gray-100 mt-8 overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <h3 class="font-extrabold text-gray-900 text-lg">Antrean Persetujuan Terbaru</h3>
                <a href="#" class="text-sm font-bold text-pili-primary hover:underline">Lihat Semua Antrean &rarr;</a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-white text-gray-500 text-xs uppercase tracking-wider border-b border-gray-200">
                            <th class="p-4 font-bold">Peminjam</th>
                            <th class="p-4 font-bold">Kegiatan</th>
                            <th class="p-4 font-bold">Tgl Mulai</th>
                            <th class="p-4 font-bold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($antreanTerbaru as $trx)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="p-4 font-bold text-gray-900">{{ $trx->user->name }}</td>
                                <td class="p-4 text-sm text-gray-600">{{ $trx->nama_kegiatan }}</td>
                                <td class="p-4 text-sm font-medium text-gray-800">{{ $trx->tanggal_mulai->format('d M Y') }}</td>
                                <td class="p-4 text-right">
                                    <a href="#" class="bg-pili-primary text-white text-xs font-bold px-4 py-2 rounded-lg hover:bg-pili-dark transition">Review</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-8 text-center text-gray-500 font-medium border-b border-gray-100">
                                    Hore! Tidak ada pengajuan yang perlu disetujui saat ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    <!-- TAMPILAN KHUSUS ADMIN PENGELOLA -->
    @else
        <div class="bg-white rounded-2xl p-8 border border-gray-100 text-center shadow-sm">
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Area Admin Pengelola Gudang</h2>
            <p class="text-gray-500">Gunakan menu di atas untuk mengatur Aset dan Stock Opname.</p>
        </div>
    @endif

</div>
@endsection