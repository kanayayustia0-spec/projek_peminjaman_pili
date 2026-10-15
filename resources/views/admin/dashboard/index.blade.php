@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Dashboard Admin <span class="text-pili-primary">({{ ucfirst($admin->role) }})</span></h1>
        <div class="text-sm bg-blue-50 border border-blue-100 text-blue-800 px-4 py-2 rounded-xl font-semibold shadow-sm">
            Selamat datang, {{ $admin->username }}
        </div>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('success') }}
        </div>
    @endif
    @if(session('warning'))
        <div class="bg-orange-50 border border-orange-200 text-orange-700 px-4 py-3 rounded-xl mb-6 flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            {{ session('warning') }}
        </div>
    @endif

    <div class="bg-white shadow-soft rounded-2xl overflow-hidden border border-gray-100">
        <div class="p-6 border-b border-gray-100 bg-gray-50/50">
            <h2 class="text-lg font-bold text-gray-800">Antrean Transaksi Peminjaman</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider border-b border-gray-200">
                        <th class="p-4 font-bold">Peminjam & Kegiatan</th>
                        <th class="p-4 font-bold">Jadwal</th>
                        <th class="p-4 font-bold">Status Approval</th>
                        <th class="p-4 font-bold">Status Transaksi</th>
                        <th class="p-4 font-bold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($peminjamans as $row)
                        <tr x-data="{ expanded: false }" class="hover:bg-gray-50/80 transition duration-150">
                            
                            <!-- Kolom 1 -->
                            <td class="p-4">
                                <div class="font-bold text-gray-900">{{ $row->user->name }}</div>
                                <div class="text-sm text-gray-500">{{ $row->nama_kegiatan }}</div>
                                <button @click="expanded = !expanded" class="text-xs text-pili-primary mt-2 font-bold flex items-center hover:underline">
                                    <span x-text="expanded ? '- Tutup Detail Barang' : '+ Lihat Detail Barang'"></span>
                                </button>
                            </td>

                            <!-- Kolom 2 -->
                            <td class="p-4 text-sm text-gray-600">
                                <div><span class="font-bold text-gray-700">Mulai:</span> <br>{{ $row->tanggal_mulai->format('d M Y, H:i') }}</div>
                                <div class="mt-1"><span class="font-bold text-gray-700">Selesai:</span> <br>{{ $row->tanggal_selesai->format('d M Y, H:i') }}</div>
                            </td>

                            <!-- Kolom 3 -->
                            <td class="p-4">
                                <div class="text-xs space-y-1.5 w-40">
                                    <div class="flex items-center justify-between bg-gray-50 border border-gray-100 px-2 py-1.5 rounded-lg shadow-sm">
                                        <span class="text-gray-500 font-medium">Pengelola</span>
                                        <span class="font-bold {{ $row->status_pengelola == 'Disetujui' ? 'text-green-600' : ($row->status_pengelola == 'Ditolak' ? 'text-red-600' : 'text-yellow-600') }}">{{ $row->status_pengelola }}</span>
                                    </div>
                                    <div class="flex items-center justify-between bg-gray-50 border border-gray-100 px-2 py-1.5 rounded-lg shadow-sm">
                                        <span class="text-gray-500 font-medium">Persetujuan</span>
                                        <span class="font-bold {{ $row->status_persetujuan == 'Disetujui' ? 'text-green-600' : ($row->status_persetujuan == 'Ditolak' ? 'text-red-600' : 'text-yellow-600') }}">{{ $row->status_persetujuan }}</span>
                                    </div>
                                </div>
                            </td>

                            <!-- Kolom 4 -->
                            <td class="p-4">
                                <span class="px-3 py-1.5 rounded-full text-xs font-bold border
                                    {{ $row->status_keseluruhan == 'Disetujui' ? 'bg-blue-50 text-blue-700 border-blue-200' : '' }}
                                    {{ $row->status_keseluruhan == 'Aset Diserahkan' ? 'bg-indigo-50 text-indigo-700 border-indigo-200' : '' }}
                                    {{ $row->status_keseluruhan == 'Menunggu Konfirmasi Pengembalian' ? 'bg-orange-50 text-orange-700 border-orange-200' : '' }}
                                    {{ $row->status_keseluruhan == 'Terlambat' ? 'bg-red-50 text-red-700 border-red-200 animate-pulse' : '' }}
                                    {{ $row->status_keseluruhan == 'Selesai' ? 'bg-green-50 text-green-700 border-green-200' : '' }}
                                    {{ $row->status_keseluruhan == 'Menunggu Persetujuan' ? 'bg-yellow-50 text-yellow-700 border-yellow-200' : 'bg-gray-50 text-gray-700 border-gray-200' }}
                                ">
                                    {{ $row->status_keseluruhan }}
                                </span>
                            </td>

                            <!-- Kolom 5 -->
                            <td class="p-4 text-right space-y-2">
                                @if($admin->role == 'pengelola')
                                    <!-- Aksi Pengelola -->
                                    @if($row->status_keseluruhan == 'Menunggu Persetujuan' && $row->status_pengelola == 'Pending')
                                        <div class="flex justify-end gap-1">
                                            <form action="{{ route('admin.approve.pengelola', $row->id) }}" method="POST">
                                                @csrf <input type="hidden" name="status" value="Disetujui">
                                                <button class="bg-green-500 text-white text-xs px-3 py-1.5 rounded-lg hover:bg-green-600 font-bold transition">Setujui</button>
                                            </form>
                                            <form action="{{ route('admin.approve.pengelola', $row->id) }}" method="POST">
                                                @csrf <input type="hidden" name="status" value="Ditolak">
                                                <button class="bg-red-500 text-white text-xs px-3 py-1.5 rounded-lg hover:bg-red-600 font-bold transition">Tolak</button>
                                            </form>
                                        </div>
                                    @endif
                                    @if($row->status_keseluruhan == 'Disetujui')
                                        <a href="{{ route('admin.basta.create', $row->id) }}" class="block w-full text-center bg-pili-primary text-white text-xs px-3 py-2 rounded-lg shadow-sm hover:bg-pili-dark font-bold transition">Proses BASTA</a>
                                    @endif
                                    @if($row->status_keseluruhan == 'Menunggu Konfirmasi Pengembalian')
                                        <a href="{{ route('admin.pengembalian.verifikasi.form', $row->id) }}" class="block w-full text-center bg-orange-500 text-white text-xs px-3 py-2 rounded-lg shadow-sm hover:bg-orange-600 font-bold transition">Cek Pengembalian</a>
                                    @endif
                                    @if($row->status_keseluruhan == 'Perlu Tindak Lanjut')
                                        <a href="{{ route('admin.laporan.create', $row->id) }}" class="block w-full text-center bg-red-500 text-white text-xs px-3 py-2 rounded-lg shadow-sm hover:bg-red-600 font-bold transition mt-1">Lapor Masalah</a>
                                    @endif
                                    @if(in_array($row->status_keseluruhan, ['Terlambat', 'Sedang Digunakan']))
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $row->user->no_hp) }}?text=Halo..." target="_blank" class="block w-full text-center bg-green-500 text-white text-xs px-3 py-2 rounded-lg shadow-sm hover:bg-green-600 font-bold transition">Chat WA</a>
                                    @endif
                                @endif

                                @if($admin->role == 'persetujuan')
                                    <!-- Aksi Persetujuan -->
                                    @if($row->status_keseluruhan == 'Menunggu Persetujuan' && $row->status_persetujuan == 'Pending')
                                        <div class="flex justify-end gap-1">
                                            <form action="{{ route('admin.approve.persetujuan', $row->id) }}" method="POST">
                                                @csrf <input type="hidden" name="status" value="Disetujui">
                                                <button class="bg-green-500 text-white text-xs px-3 py-1.5 rounded-lg hover:bg-green-600 font-bold transition">Setujui</button>
                                            </form>
                                            <form action="{{ route('admin.approve.persetujuan', $row->id) }}" method="POST">
                                                @csrf <input type="hidden" name="status" value="Ditolak">
                                                <button class="bg-red-500 text-white text-xs px-3 py-1.5 rounded-lg hover:bg-red-600 font-bold transition">Tolak</button>
                                            </form>
                                        </div>
                                    @endif
                                @endif
                            </td>
                        </tr>

                        <!-- Sub-Tabel Barang (AlpineJS Dropdown) -->
                        <tr x-show="expanded" x-transition class="bg-blue-50/50 border-b border-gray-100">
                            <td colspan="5" class="p-6">
                                <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
                                    <div class="text-sm text-gray-500 mb-3 font-semibold uppercase tracking-wider">Daftar Aset Dipinjam</div>
                                    <ul class="space-y-2">
                                        @foreach($row->items as $item)
                                            <li class="flex items-center text-sm font-medium text-gray-700">
                                                <span class="w-2 h-2 bg-pili-primary rounded-full mr-3"></span>
                                                <span class="font-bold mr-2">{{ $item->barang->kode_inventaris }}</span> 
                                                {{ $item->barang->nama }} (Kategori {{ $item->kategori_saat_pinjam }}) 
                                                <span class="bg-gray-100 text-gray-600 px-2 py-0.5 rounded text-xs ml-auto font-bold border">Qty: {{ $item->jumlah }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="p-12 text-center text-gray-500 font-medium">Belum ada antrean transaksi peminjaman.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection