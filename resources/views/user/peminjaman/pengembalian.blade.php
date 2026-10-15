<!-- resources/views/user/peminjaman/pengembalian.blade.php -->
@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-8 px-4">
    <div class="bg-white rounded-2xl shadow-soft p-8">
        <h2 class="text-2xl font-bold text-pili-primary border-b pb-4 mb-6">
            Form Pengembalian Aset
        </h2>

        <div class="bg-orange-50 text-orange-800 p-4 rounded-lg mb-6 text-sm">
            <strong>Perhatian:</strong> Sesuai SOP Yayasan PILI, Anda <b>diwajibkan</b> untuk melampirkan foto kondisi fisik terbaru dari seluruh aset yang dipinjam beserta penjelasan/catatannya.
        </div>

        <form action="{{ route('peminjaman.pengembalian.proses', $peminjaman->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="mb-6">
                <h3 class="font-bold text-gray-800 mb-2">Aset yang harus dikembalikan:</h3>
                <ul class="list-disc list-inside bg-gray-50 p-4 rounded-lg border">
                    @foreach($peminjaman->items as $item)
                        <li class="text-sm">{{ $item->barang->nama }} (Qty: {{ $item->jumlah }})</li>
                    @endforeach
                </ul>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700">Upload Foto Kondisi Terakhir (Wajib)</label>
                <input type="file" name="foto_kondisi_akhir" accept="image/*" required class="w-full border rounded-lg p-2 mt-1">
                <p class="text-xs text-gray-500 mt-1">Foto keseluruhan barang. Maksimal 5MB.</p>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700">Catatan & Penjelasan Kondisi (Wajib)</label>
                <textarea name="catatan_kondisi_peminjam" rows="4" required class="w-full border rounded-lg p-2 mt-1" placeholder="Misal: 'Barang aman dan lengkap beserta charger. Namun ada sedikit goresan di body tas...'"></textarea>
                <p class="text-xs text-gray-500 mt-1">Tuliskan kondisi dengan jujur sebagai dasar pertanggungjawaban Anda.</p>
            </div>

            <button type="submit" class="w-full bg-pili-primary text-white font-bold py-3 rounded-xl hover:bg-pili-dark transition">
                Kirim Laporan Pengembalian
            </button>
        </form>
    </div>
</div>
@endsection