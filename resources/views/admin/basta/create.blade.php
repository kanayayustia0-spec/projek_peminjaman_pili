<!-- resources/views/admin/basta/create.blade.php -->
@extends('layouts.app')

@section('content')
<!-- Load Library Signature Pad -->
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>

<div class="max-w-4xl mx-auto py-8">
    <div class="bg-white rounded-2xl shadow-soft p-8">
        <h2 class="text-2xl font-bold text-pili-primary border-b pb-4 mb-6">
            Berita Acara Serah Terima (BASTA)
        </h2>

        <div class="bg-blue-50 p-4 rounded-lg mb-6">
            <p><strong>Peminjam:</strong> {{ $peminjaman->user->name }}</p>
            <p><strong>Kegiatan:</strong> {{ $peminjaman->nama_kegiatan }}</p>
        </div>

        <form action="{{ route('admin.basta.store', $peminjaman->id) }}" method="POST" enctype="multipart/form-data" id="bastaForm">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tanggal Serah</label>
                    <input type="date" name="tanggal_serah" value="{{ date('Y-m-d') }}" required class="w-full border rounded-lg p-2 mt-1">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Jam Serah</label>
                    <input type="time" name="jam_serah" value="{{ date('H:i') }}" required class="w-full border rounded-lg p-2 mt-1">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Lokasi Serah</label>
                    <input type="text" name="lokasi_serah" value="Kantor Yayasan PILI" required class="w-full border rounded-lg p-2 mt-1">
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Checklist Kondisi & Kelengkapan Aset</label>
                <div class="bg-gray-50 p-4 rounded-lg border">
                    @foreach($peminjaman->items as $index => $item)
                        <div class="mb-4 pb-4 border-b last:border-0 last:mb-0 last:pb-0">
                            <p class="font-bold text-gray-800">{{ $item->barang->nama }} (Qty: {{ $item->jumlah }})</p>
                            
                            <div class="mt-2 grid grid-cols-2 gap-4">
                                <!-- Kondisi -->
                                <div>
                                    <select name="checklist_kondisi_awal[{{ $item->id }}]" required class="w-full border rounded p-1 text-sm">
                                        <option value="">-- Pilih Kondisi Fisik --</option>
                                        <option value="Baik">Fisik Baik & Berfungsi</option>
                                        <option value="Terdapat Minus">Berfungsi dengan Catatan</option>
                                    </select>
                                </div>
                                <!-- Kelengkapan -->
                                <div>
                                    <input type="text" name="kelengkapan[{{ $item->id }}]" placeholder="Kelengkapan (Tas, Charger, dll)" required class="w-full border rounded p-1 text-sm">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700">Upload Bukti Foto Fisik (Wajib)</label>
                <input type="file" name="foto_kondisi_awal" accept="image/*" required class="w-full border rounded-lg p-2 mt-1">
                <p class="text-xs text-gray-500 mt-1">Foto keseluruhan barang yang diserahkan. Maks 5MB.</p>
            </div>

            <!-- TANDA TANGAN DIGITAL AREA -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-10">
                <!-- TTD Admin -->
                <div>
                    <label class="block font-bold text-gray-800 mb-2">Tanda Tangan Admin Pengelola</label>
                    <canvas id="canvas_pengelola" class="border-2 border-dashed border-gray-400 rounded-lg w-full h-40 bg-gray-50 touch-none"></canvas>
                    <button type="button" onclick="padPengelola.clear()" class="text-xs text-red-500 mt-1">Hapus TTD Admin</button>
                    <input type="hidden" name="ttd_digital_pengelola" id="ttd_pengelola">
                    
                    <label class="flex items-start space-x-2 mt-4">
                        <input type="checkbox" name="konfirmasi_checkbox_pengelola" required class="mt-1">
                        <span class="text-xs text-gray-600">Saya sebagai Admin menyerahkan aset dalam kondisi sesuai deskripsi.</span>
                    </label>
                </div>

                <!-- TTD Peminjam -->
                <div>
                    <label class="block font-bold text-gray-800 mb-2">Tanda Tangan Peminjam</label>
                    <canvas id="canvas_peminjam" class="border-2 border-dashed border-gray-400 rounded-lg w-full h-40 bg-gray-50 touch-none"></canvas>
                    <button type="button" onclick="padPeminjam.clear()" class="text-xs text-red-500 mt-1">Hapus TTD Peminjam</button>
                    <input type="hidden" name="ttd_digital_peminjam" id="ttd_peminjam">
                    
                    <label class="flex items-start space-x-2 mt-4">
                        <input type="checkbox" name="konfirmasi_checkbox_peminjam" required class="mt-1">
                        <span class="text-xs text-gray-600">Saya menerima aset tersebut dan bertanggung jawab penuh.</span>
                    </label>
                </div>
            </div>

            <button type="button" onclick="submitBasta()" class="mt-8 w-full bg-pili-primary text-white font-bold py-3 rounded-xl hover:bg-pili-dark transition">
                Simpan & Selesaikan Serah Terima
            </button>
        </form>
    </div>
</div>

<script>
    // Inisialisasi Canvas Tanda Tangan
    const canvasPengelola = document.getElementById('canvas_pengelola');
    const canvasPeminjam = document.getElementById('canvas_peminjam');

    // Menyesuaikan ukuran resolusi internal canvas agar tidak buram
    function resizeCanvas(canvas) {
        const ratio =  Math.max(window.devicePixelRatio || 1, 1);
        canvas.width = canvas.offsetWidth * ratio;
        canvas.height = canvas.offsetHeight * ratio;
        canvas.getContext("2d").scale(ratio, ratio);
    }

    resizeCanvas(canvasPengelola);
    resizeCanvas(canvasPeminjam);

    const padPengelola = new SignaturePad(canvasPengelola);
    const padPeminjam = new SignaturePad(canvasPeminjam);

    // Fungsi submit form untuk memastikan TTD terekam sebagai base64
    function submitBasta() {
        if (padPengelola.isEmpty() || padPeminjam.isEmpty()) {
            alert('Kedua tanda tangan wajib diisi!');
            return;
        }

        // Ambil data gambar (base64) dari canvas dan masukkan ke input hidden
        document.getElementById('ttd_pengelola').value = padPengelola.toDataURL();
        document.getElementById('ttd_peminjam').value = padPeminjam.toDataURL();

        // Submit form
        document.getElementById('bastaForm').submit();
    }
</script>
@endsection