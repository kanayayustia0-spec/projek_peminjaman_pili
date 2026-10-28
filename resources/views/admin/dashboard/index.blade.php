@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-5 sm:py-8 px-3.5 sm:px-6 lg:px-8">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-3">
        <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">Dashboard Admin <span class="text-pili-primary">({{ ucfirst($admin->role) }})</span></h1>
        <div class="text-xs sm:text-sm bg-blue-50 border border-blue-100 text-blue-800 px-3.5 py-1.5 rounded-xl font-semibold shadow-sm">
            Selamat datang, {{ $admin->username }}
        </div>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 p-4 rounded-xl mb-5 flex items-center gap-3">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span class="text-xs sm:text-sm">{{ session('success') }}</span>
        </div>
    @endif
    @if(session('warning'))
        <div class="bg-orange-50 border border-orange-200 text-orange-700 p-4 rounded-xl mb-5 flex items-center gap-3">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            <span class="text-xs sm:text-sm">{{ session('warning') }}</span>
        </div>
    @endif

    <div class="bg-white shadow-soft rounded-2xl overflow-hidden border border-gray-100">
        <div class="p-4 sm:p-6 border-b border-gray-100 bg-gray-50/50">
            <h2 class="text-base sm:text-lg font-bold text-gray-800">Antrean Transaksi Peminjaman</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[700px]">
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
                                    {{ $row->status_keseluruhan == 'Perlu Tindak Lanjut' ? 'bg-rose-50 text-rose-700 border-rose-300 animate-pulse' : '' }}
                                    {{ $row->status_keseluruhan == 'Selesai' ? 'bg-green-50 text-green-700 border-green-200' : '' }}
                                    {{ $row->status_keseluruhan == 'Menunggu Persetujuan' ? 'bg-yellow-50 text-yellow-700 border-yellow-200' : '' }}
                                    {{ $row->status_keseluruhan == 'Ditolak' ? 'bg-rose-50 text-rose-700 border-rose-200' : '' }}
                                ">
                                    {{ $row->status_keseluruhan }}
                                </span>
                                @if($row->status_keseluruhan == 'Ditolak' && $row->alasan_penolakan)
                                    <p class="text-[10px] text-rose-600 font-medium italic mt-1 max-w-xs truncate" title="{{ $row->alasan_penolakan }}">
                                        Alasan: "{{ $row->alasan_penolakan }}"
                                    </p>
                                @endif
                            </td>

                            <!-- Kolom 5: Aksi -->
                            <td class="p-4 text-right">
                                <div class="flex flex-col items-end gap-1.5">
                                    @if($admin->role == 'pengelola')
                                        <!-- Aksi Khusus Admin Pengelola -->

                                        <!-- 1. Kondisi: Pengelola Belum Memilih (Pending) -->
                                        @if($row->status_keseluruhan == 'Menunggu Persetujuan' && $row->status_pengelola == 'Pending')
                                            <div class="flex items-center gap-1 justify-end">
                                                <form action="{{ route('admin.approve.pengelola', $row->id) }}" method="POST">
                                                    @csrf <input type="hidden" name="status" value="Disetujui">
                                                    <button class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs px-3 py-1.5 rounded-lg font-bold transition shadow-sm">
                                                        Setujui
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.approve.pengelola', $row->id) }}" method="POST" onsubmit="var r = prompt('Masukkan alasan penolakan (opsional):'); if(r === null) return false; this.alasan.value = r;">
                                                    @csrf 
                                                    <input type="hidden" name="status" value="Ditolak">
                                                    <input type="hidden" name="alasan" value="">
                                                    <button class="bg-rose-600 hover:bg-rose-700 text-white text-xs px-3 py-1.5 rounded-lg font-bold transition shadow-sm">
                                                        Tolak
                                                    </button>
                                                </form>
                                            </div>
                                        @endif

                                        <!-- 2. Kondisi (Kasus Kanaya): Pengelola Sudah Setujui, Menunggu Persetujuan Atasan -->
                                        @if($row->status_keseluruhan == 'Menunggu Persetujuan' && $row->status_pengelola == 'Disetujui' && $row->status_persetujuan == 'Pending')
                                            <div class="text-[11px] font-bold text-amber-700 bg-amber-50 border border-amber-200 px-2.5 py-1 rounded-lg flex items-center gap-1.5 shadow-2xs">
                                                <svg class="w-3.5 h-3.5 text-amber-500 animate-spin shrink-0" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                                <span>Menunggu Persetujuan Atasan</span>
                                            </div>
                                            <div class="flex items-center gap-1 justify-end">
                                                <!-- Tombol Setujui Sepenuhnya (Bypass agar langsung lanjut BASTA) -->
                                                <form action="{{ route('admin.approve.langsung', $row->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" title="Setujui sepenuhnya agar langsung lanjut ke BASTA" class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs px-2.5 py-1 rounded-lg font-extrabold transition shadow-sm flex items-center gap-1">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                                        <span>Setujui Sepenuhnya</span>
                                                    </button>
                                                </form>

                                                <!-- Tombol Batalkan / Reset ke Pending -->
                                                <form action="{{ route('admin.approve.reset', $row->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" title="Kembalikan status ke Pending" class="bg-gray-100 hover:bg-gray-200 text-gray-600 text-xs px-2 py-1 rounded-lg font-bold transition">
                                                        Reset
                                                    </button>
                                                </form>
                                            </div>
                                        @endif

                                        <!-- 3. Kondisi: Sudah Disetujui Kedua Pihak -> Siap BASTA -->
                                        @if($row->status_keseluruhan == 'Disetujui')
                                            <a href="{{ route('admin.basta.create', $row->id) }}" class="inline-flex items-center justify-center gap-1 bg-pili-primary hover:bg-pili-dark text-white text-xs px-3.5 py-1.5 rounded-lg shadow-sm font-extrabold transition">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                <span>Proses BASTA</span>
                                            </a>
                                        @endif

                                        <!-- 4. Kondisi: Menunggu Konfirmasi Pengembalian -->
                                        @if($row->status_keseluruhan == 'Menunggu Konfirmasi Pengembalian')
                                            <a href="{{ route('admin.pengembalian.verifikasi.form', $row->id) }}" class="inline-flex items-center justify-center gap-1 bg-amber-500 hover:bg-amber-600 text-white text-xs px-3 py-1.5 rounded-lg shadow-sm font-bold transition">
                                                <span>Cek Pengembalian</span>
                                            </a>
                                        @endif

                                        <!-- 5. Kondisi: Ada Kerusakan / Perlu Tindak Lanjut -->
                                        @if($row->status_keseluruhan == 'Perlu Tindak Lanjut')
                                            <a href="{{ route('admin.laporan.create', $row->id) }}" class="inline-flex items-center justify-center gap-1 bg-rose-600 hover:bg-rose-700 text-white text-xs px-3 py-1.5 rounded-lg shadow-sm font-bold transition">
                                                <span>Lapor Masalah</span>
                                            </a>
                                        @endif

                                        <!-- 6. Kondisi: Sedang Digunakan / Terlambat -->
                                        @if(in_array($row->status_keseluruhan, ['Terlambat', 'Sedang Digunakan']))
                                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $row->user->no_hp) }}?text=Halo%20{{ urlencode($row->user->name) }},%20mengenai%20peminjaman%20aset%20PILI..." target="_blank" class="inline-flex items-center justify-center gap-1 bg-emerald-600 hover:bg-emerald-700 text-white text-xs px-3 py-1.5 rounded-lg shadow-sm font-bold transition">
                                                <span>Chat WA</span>
                                            </a>
                                        @endif
                                    @endif

                                    @if($admin->role == 'persetujuan')
                                        <!-- Aksi Khusus Admin Persetujuan -->
                                        @if($row->status_keseluruhan == 'Menunggu Persetujuan' && $row->status_persetujuan == 'Pending')
                                            <div class="flex items-center gap-1 justify-end">
                                                <a href="{{ route('admin.persetujuan.show', $row->id) }}" class="bg-pili-primary hover:bg-pili-dark text-white text-xs px-2.5 py-1.5 rounded-lg font-bold transition shadow-sm flex items-center gap-1">
                                                    <span>Review</span>
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                                </a>
                                                <form action="{{ route('admin.approve.persetujuan', $row->id) }}" method="POST">
                                                    @csrf <input type="hidden" name="status" value="Disetujui">
                                                    <button class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs px-2.5 py-1.5 rounded-lg font-bold transition shadow-sm">
                                                        Setujui
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.approve.persetujuan', $row->id) }}" method="POST" onsubmit="var r = prompt('Masukkan alasan penolakan (opsional):'); if(r === null) return false; this.alasan.value = r;">
                                                    @csrf 
                                                    <input type="hidden" name="status" value="Ditolak">
                                                    <input type="hidden" name="alasan" value="">
                                                    <button class="bg-rose-600 hover:bg-rose-700 text-white text-xs px-2.5 py-1.5 rounded-lg font-bold transition shadow-sm">
                                                        Tolak
                                                    </button>
                                                </form>
                                            </div>
                                        @elseif($row->status_persetujuan == 'Disetujui' && $row->status_pengelola == 'Pending')
                                            <div class="text-[11px] font-bold text-amber-700 bg-amber-50 border border-amber-200 px-2.5 py-1 rounded-lg flex items-center gap-1.5 shadow-2xs">
                                                <svg class="w-3.5 h-3.5 text-amber-500 animate-spin shrink-0" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                                <span>Menunggu Pengelola Gudang</span>
                                            </div>
                                            <div class="flex items-center gap-1 justify-end">
                                                <!-- Tombol Setujui Sepenuhnya (Bypass) -->
                                                <form action="{{ route('admin.approve.langsung', $row->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" title="Setujui sepenuhnya agar langsung lanjut ke BASTA" class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs px-2.5 py-1 rounded-lg font-extrabold transition shadow-sm flex items-center gap-1">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                                        <span>Setujui Sepenuhnya</span>
                                                    </button>
                                                </form>

                                                <!-- Tombol Reset ke Pending -->
                                                <form action="{{ route('admin.approve.reset', $row->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" title="Kembalikan status ke Pending" class="bg-gray-100 hover:bg-gray-200 text-gray-600 text-xs px-2 py-1 rounded-lg font-bold transition">
                                                        Reset
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    @endif

                                    <!-- Tombol Reset jika Ditolak (Bisa diakses kedua role) -->
                                    @if($row->status_keseluruhan == 'Ditolak')
                                        <form action="{{ route('admin.approve.reset', $row->id) }}" method="POST" onsubmit="return confirm('Buka kembali pengajuan ini dan reset status ke Pending?')">
                                            @csrf
                                            <button type="submit" class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs px-2.5 py-1 rounded-lg font-bold transition flex items-center gap-1">
                                                <svg class="w-3 h-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                                <span>Reset Pengajuan</span>
                                            </button>
                                        </form>
                                    @endif

                                    <!-- Tombol Struk & Detail Tersedia untuk Semua Baris -->
                                    <div class="flex items-center gap-1 pt-0.5 justify-end">
                                        <a href="{{ route('peminjaman.struk', $row->id) }}" target="_blank" class="inline-flex items-center gap-1 text-[11px] font-bold text-pili-primary hover:text-pili-dark bg-blue-50/70 hover:bg-blue-100/70 border border-blue-200/60 px-2.5 py-1 rounded-lg transition">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                            <span>Lihat Struk</span>
                                        </a>
                                    </div>
                                </div>
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