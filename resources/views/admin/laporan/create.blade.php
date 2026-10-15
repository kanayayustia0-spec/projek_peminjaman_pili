@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4">
    <div class="flex items-center mb-6 space-x-4">
        <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-pili-primary font-bold">&larr; Kembali ke Dashboard</a>
        <h1 class="text-2xl font-bold text-pili-primary">Buat Laporan Kerusakan / Kehilangan</h1>
    </div>

    <div class="bg-red-50 text-red-800 p-4 rounded-lg mb-6 text-sm border border-red-200">
        <strong>Peminjam:</strong> {{ $peminjaman->user->name }} | <strong>Kegiatan:</strong> {{ $peminjaman->nama_kegiatan }}<br>
        Form ini akan mengubah status barang di inventaris menjadi "Rusak" atau "Hilang" dan menunda penyelesaian transaksi peminjaman ini sampai ada keputusan dari Admin Persetujuan.
    </div>

    <form action="{{ route('admin.laporan.store', $peminjaman->id) }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-soft rounded-2xl p-6">
        @csrf

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700">Pilih Aset yang Bermasalah</label>
            <select name="barang_id" required class="w-full border border-gray-300 rounded-lg p-2 mt-1">
                <option value="">-- Pilih Aset --</option>
                @foreach($peminjaman->items as $item)
                    <option value="{{ $item->barang_id }}">
                        {{ $item->barang->kode_inventaris }} - {{ $item->barang->nama }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700">Tanggal Kejadian (Perkiraan)</label>
                <input type="date" name="tanggal_kejadian" required class="w-full border rounded-lg p-2 mt-1">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Lokasi Kejadian</label>
                <input type="text" name="lokasi" required class="w-full border rounded-lg p-2 mt-1" placeholder="Misal: Hutan Lindung Desa X / Basecamp">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Jenis Kejadian</label>
                <select name="jenis_kejadian" required class="w-full border rounded-lg p-2 mt-1">
                    <option value="Kerusakan">Kerusakan</option>
                    <option value="Kehilangan">Kehilangan</option>
                    <option value="Force Majeure">Force Majeure (Bencana Alam, dsb)</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Dugaan Penyebab</label>
                <select name="penyebab" required class="w-full border rounded-lg p-2 mt-1">
                    <option value="Belum Diketahui">Belum Diketahui</option>
                    <option value="Pemakaian Normal">Pemakaian Normal (Usia Barang)</option>
                    <option value="Kelalaian">Kelalaian Peminjam</option>
                    <option value="Pihak Ketiga">Dicuri / Pihak Ketiga</option>
                    <option value="Force Majeure">Force Majeure</option>
                </select>
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700">Kronologi Singkat</label>
            <textarea name="kronologi" rows="4" required class="w-full border rounded-lg p-2 mt-1" placeholder="Ceritakan bagaimana barang tersebut bisa rusak/hilang menurut pengakuan peminjam atau hasil temuan..."></textarea>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700">Upload Bukti Pendukung (Bisa lebih dari 1 file)</label>
            <!-- perhatikan attribute 'multiple' dan kurung siku '[]' pada name -->
            <input type="file" name="bukti_pendukung[]" multiple accept="image/*" class="w-full border rounded-lg p-2 mt-1 bg-gray-50">
            <p class="text-xs text-gray-500 mt-1">Upload foto barang yang rusak, atau surat keterangan kehilangan dari kepolisian (jika ada).</p>
        </div>

        <div class="flex justify-end mt-8">
            <button type="submit" class="bg-red-600 text-white font-bold py-3 px-6 rounded-xl hover:bg-red-700 transition shadow">
                Kirim Laporan untuk Diputuskan
            </button>
        </div>
    </form>
</div>
@endsection