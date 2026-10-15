@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-8 px-4">
    <div class="bg-white rounded-2xl shadow-soft p-8">
        <h2 class="text-2xl font-bold text-pili-primary border-b pb-4 mb-6">
            Verifikasi Pengembalian Aset
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
            <!-- Kolom Kiri: Laporan Peminjam -->
            <div class="bg-gray-50 p-6 rounded-xl border">
                <h3 class="font-bold text-gray-800 mb-4 border-b pb-2">Laporan dari Peminjam</h3>
                <p class="text-sm mb-1"><strong>Nama:</strong> {{ $peminjaman->user->name }}</p>
                <p class="text-sm mb-4"><strong>Tanggal Kembali:</strong> {{ $peminjaman->pengembalian->tanggal_pengembalian->format('d M Y, H:i') }}</p>
                
                <div class="mb-4">
                    <strong class="block text-sm mb-2">Foto Kondisi:</strong>
                    <!-- Menampilkan foto yang diupload peminjam -->
                    <a href="{{ asset('storage/' . $peminjaman->pengembalian->foto_kondisi_akhir) }}" target="_blank">
                        <img src="{{ asset('storage/' . $peminjaman->pengembalian->foto_kondisi_akhir) }}" class="w-full h-48 object-cover rounded-lg border shadow-sm hover:opacity-90 transition">
                    </a>
                </div>

                <div>
                    <strong class="block text-sm mb-1">Catatan Peminjam:</strong>
                    <div class="bg-white p-3 rounded border text-sm italic text-gray-700">
                        "{{ $peminjaman->pengembalian->catatan_kondisi_peminjam }}"
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Form Keputusan Admin -->
            <div>
                <h3 class="font-bold text-gray-800 mb-4 border-b pb-2">Hasil Pengecekan Admin Pengelola</h3>
                
                <form action="{{ route('admin.pengembalian.verifikasi', $peminjaman->id) }}" method="POST">
                    @csrf
                    
                    <div class="mb-6 space-y-4">
                        @foreach($peminjaman->items as $item)
                        <div class="border p-3 rounded-lg bg-gray-50">
                            <p class="font-bold text-sm mb-2">{{ $item->barang->nama }} (Qty: {{ $item->jumlah }})</p>
                            <div class="grid grid-cols-2 gap-2">
                                <select name="checklist_kondisi_akhir[{{ $item->id }}]" required class="border rounded p-1 text-sm w-full">
                                    <option value="Baik">Fisik Baik</option>
                                    <option value="Rusak">Fisik Rusak</option>
                                    <option value="Hilang">Hilang</option>
                                </select>
                                <select name="kelengkapan_kembali[{{ $item->id }}]" required class="border rounded p-1 text-sm w-full">
                                    <option value="Lengkap">Kelengkapan Lengkap</option>
                                    <option value="Tidak Lengkap">Tidak Lengkap</option>
                                </select>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-800 mb-2">Keputusan Akhir Pemeriksaan</label>
                        <select name="hasil_pemeriksaan" required class="w-full border-2 border-gray-300 rounded-lg p-3 bg-white">
                            <option value="">-- Pilih Keputusan --</option>
                            <option value="Sesuai">Sesuai (Aman, Selesaikan Transaksi)</option>
                            <option value="Ada Kerusakan">Ada Kerusakan (Lanjut Pelaporan)</option>
                            <option value="Ada Kehilangan">Ada Kehilangan (Lanjut Pelaporan)</option>
                            <option value="Perlu Tindak Lanjut">Perlu Tindak Lanjut Lainnya</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full bg-pili-primary text-white font-bold py-3 rounded-xl hover:bg-pili-dark transition">
                        Simpan Hasil Verifikasi
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection