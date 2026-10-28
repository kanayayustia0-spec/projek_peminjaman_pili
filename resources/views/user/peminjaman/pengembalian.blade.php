@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    
    <!-- Header Navigasi -->
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <a href="{{ route('peminjaman.riwayat') }}" class="inline-flex items-center gap-2 text-sm font-bold text-gray-500 hover:text-pili-primary transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Riwayat
        </a>
        <span class="text-xs font-mono font-bold bg-gray-100 text-gray-600 px-3 py-1 rounded-full border border-gray-200">
            TRX #PILI-{{ str_pad($peminjaman->id, 5, '0', STR_PAD_LEFT) }}
        </span>
    </div>

    <!-- Judul & Info -->
    <div class="mb-6">
        <h1 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight">Form Pengembalian Aset</h1>
        <p class="text-sm text-gray-500 mt-1">Harap pastikan seluruh barang dikembalikan lengkap, tepat waktu, dan disertai foto bukti kondisi terkini.</p>
    </div>

    <!-- Error Alert jika ada validasi gagal -->
    @if ($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-xl shadow-sm">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-bold text-red-800">Mohon lengkapi data pengembalian:</h3>
                    <ul class="mt-1 list-disc list-inside text-xs text-red-700 space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- Card Info Peminjaman -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200/80 rounded-2xl p-5 mb-8">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
            <div>
                <span class="text-gray-500 block font-medium">Kegiatan / Keperluan:</span>
                <span class="font-bold text-gray-900 text-sm">{{ $peminjaman->nama_kegiatan }}</span>
            </div>
            <div>
                <span class="text-gray-500 block font-medium">Lokasi Penggunaan:</span>
                <span class="font-bold text-gray-900 text-sm">{{ $peminjaman->lokasi_penggunaan }}</span>
            </div>
            <div>
                <span class="text-gray-500 block font-medium">Jadwal Selesai Pinjam:</span>
                <span class="font-bold text-indigo-700 text-sm flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ $peminjaman->tanggal_selesai->format('d M Y, H:i') }} WIB
                </span>
            </div>
        </div>
    </div>

    <!-- Form Pengembalian -->
    <form action="{{ route('peminjaman.pengembalian.proses', $peminjaman->id) }}" 
          method="POST" 
          enctype="multipart/form-data" 
          x-data="{ 
              photoPreview: '',
              catatan: '{{ old('catatan_kondisi_peminjam', '') }}',
              setCatatan(text) {
                  this.catatan = text;
              },
              updatePreview(event) {
                  const file = event.target.files[0];
                  if (file) {
                      const reader = new FileReader();
                      reader.onload = (e) => { this.photoPreview = e.target.result; };
                      reader.readAsDataURL(file);
                  } else {
                      this.photoPreview = '';
                  }
              }
          }" 
          class="space-y-8">
        
        @csrf

        <div class="bg-white rounded-3xl shadow-soft border border-gray-100 p-6 sm:p-8 space-y-8">
            
            <!-- BAGIAN 1: CEKLIS KELENGKAPAN BARANG -->
            <div>
                <div class="flex items-center justify-between mb-3 border-b border-gray-100 pb-3">
                    <div class="flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-blue-100 text-pili-primary font-black text-xs flex items-center justify-center">1</span>
                        <h2 class="text-base sm:text-lg font-extrabold text-gray-900">Kelengkapan Aset yang Dikembalikan</h2>
                    </div>
                    <span class="text-xs bg-emerald-100 text-emerald-800 font-bold px-2.5 py-1 rounded-full">Wajib Lengkap</span>
                </div>
                <p class="text-xs text-gray-500 mb-4">Pastikan unit dan aksesoris (kabel, charger, box, dll) telah diperiksa sebelum diserahkan:</p>

                <div class="space-y-3 bg-gray-50/70 p-4 sm:p-5 rounded-2xl border border-gray-200">
                    @foreach($peminjaman->items as $item)
                        <label class="flex items-center justify-between bg-white p-3.5 rounded-xl border border-gray-200 shadow-sm cursor-pointer hover:border-blue-300 transition">
                            <div class="flex items-center gap-3">
                                <input type="checkbox" name="items_kembali[{{ $item->id }}]" value="Lengkap" checked required
                                       class="w-4 h-4 text-pili-primary rounded border-gray-300 focus:ring-pili-primary">
                                <div>
                                    <span class="font-bold text-gray-800 text-sm block">{{ $item->barang->nama }}</span>
                                    <span class="text-xs text-gray-500 font-mono">{{ $item->barang->kode_inventaris }} &bull; {{ $item->barang->kategori }}</span>
                                </div>
                            </div>
                            <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-lg text-xs font-black">
                                {{ $item->jumlah }} Unit
                            </span>
                        </label>
                    @endforeach
                </div>

                <!-- Checkbox Konfirmasi Lengkap -->
                <div class="mt-4 p-4 bg-emerald-50/70 border border-emerald-200 rounded-2xl">
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" name="konfirmasi_lengkap" value="1" required {{ old('konfirmasi_lengkap') ? 'checked' : '' }}
                               class="mt-1 w-5 h-5 text-emerald-600 rounded border-emerald-300 focus:ring-emerald-500">
                        <div class="text-xs sm:text-sm text-emerald-900">
                            <span class="font-bold block">Konfirmasi Kelengkapan Barang <span class="text-red-500">*</span></span>
                            <span class="text-emerald-700">Saya menyatakan bahwa seluruh barang yang dipinjam beserta seluruh perlengkapan/aksesorisnya telah lengkap dan siap diserahkan ke gudang.</span>
                        </div>
                    </label>
                    @error('konfirmasi_lengkap')
                        <p class="text-xs text-red-600 mt-1 font-bold">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- BAGIAN 2: TANGGAL PENGEMBALIAN SESUAI WAKTU -->
            <div>
                <div class="flex items-center gap-2 mb-3 border-b border-gray-100 pb-3">
                    <span class="w-7 h-7 rounded-full bg-blue-100 text-pili-primary font-black text-xs flex items-center justify-center">2</span>
                    <h2 class="text-base sm:text-lg font-extrabold text-gray-900">Waktu Pengembalian</h2>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                            Tanggal & Jam Pengembalian <span class="text-red-500">*</span>
                        </label>
                        <input type="datetime-local" 
                               name="tanggal_pengembalian" 
                               id="tanggal_pengembalian"
                               required 
                               value="{{ old('tanggal_pengembalian', now()->format('Y-m-d\TH:i')) }}"
                               class="w-full border-gray-300 rounded-xl p-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-pili-primary focus:border-pili-primary font-medium text-sm">
                        <span class="text-[11px] text-gray-500 block mt-1">
                            Isi sesuai waktu fisik barang diserahkan ke bagian gudang logistik.
                        </span>
                        @error('tanggal_pengembalian')
                            <p class="text-xs text-red-600 mt-1 font-bold">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                            Status Kondisi Fisik Umum
                        </label>
                        <select name="kondisi_umum" class="w-full border-gray-300 rounded-xl p-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-pili-primary focus:border-pili-primary font-medium text-sm">
                            <option value="Baik" {{ old('kondisi_umum') == 'Baik' ? 'selected' : '' }}>Sangat Baik & Bersih (Normal)</option>
                            <option value="Baik dengan Catatan" {{ old('kondisi_umum') == 'Baik dengan Catatan' ? 'selected' : '' }}>Baik dengan Catatan Penggunaan Ringan</option>
                            <option value="Perlu Pemeriksaan" {{ old('kondisi_umum') == 'Perlu Pemeriksaan' ? 'selected' : '' }}>Perlu Pemeriksaan Lebih Lanjut oleh Gudang</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- BAGIAN 3: WAJIB FOTO KONDISI BARANG -->
            <div>
                <div class="flex items-center justify-between mb-3 border-b border-gray-100 pb-3">
                    <div class="flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-blue-100 text-pili-primary font-black text-xs flex items-center justify-center">3</span>
                        <h2 class="text-base sm:text-lg font-extrabold text-gray-900">Foto Bukti Kondisi Barang</h2>
                    </div>
                    <span class="text-xs bg-red-100 text-red-700 font-bold px-2.5 py-1 rounded-full">Wajib Diunggah</span>
                </div>
                <p class="text-xs text-gray-500 mb-3">
                    Unggah foto yang memperlihatkan kondisi fisik barang terkini beserta kelengkapannya secara jelas:
                </p>

                <!-- Box Upload Foto dengan Preview -->
                <div class="w-full bg-gray-50/80 rounded-2xl border-2 border-dashed border-gray-300 relative hover:border-pili-primary hover:bg-gray-100/60 transition p-6 text-center">
                    
                    <template x-if="!photoPreview">
                        <div class="flex flex-col items-center justify-center py-6">
                            <div class="w-16 h-16 rounded-full bg-blue-50 text-pili-primary flex items-center justify-center mb-3">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </div>
                            <span class="text-sm font-bold text-gray-800 block mb-1">Ambil Foto atau Pilih Gambar</span>
                            <span class="text-xs text-gray-400">Format: JPG, JPEG, PNG, WEBP (Maksimal 5MB)</span>
                            <span class="mt-3 inline-block bg-white border border-gray-200 text-gray-700 font-bold px-4 py-2 rounded-xl text-xs shadow-sm">
                                Pilih File Foto
                            </span>
                        </div>
                    </template>
                    
                    <template x-if="photoPreview">
                        <div class="relative inline-block w-full max-w-md mx-auto">
                            <img :src="photoPreview" class="w-full h-64 object-cover rounded-xl border border-gray-200 shadow-md">
                            <span class="absolute top-2 right-2 bg-emerald-600 text-white text-[10px] font-bold px-2 py-1 rounded-md shadow">
                                Foto Terpilih
                            </span>
                            <p class="text-xs text-gray-500 mt-2 font-medium">Klik kembali jika ingin mengganti foto</p>
                        </div>
                    </template>

                    <input type="file" 
                           name="foto_kondisi_akhir" 
                           id="foto_kondisi_akhir"
                           accept="image/jpeg,image/png,image/jpg,image/webp" 
                           @change="updatePreview" 
                           required 
                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                </div>
                @error('foto_kondisi_akhir')
                    <p class="text-xs text-red-600 mt-1 font-bold">{{ $message }}</p>
                @enderror
            </div>

            <!-- BAGIAN 4: CATATAN KONDISI BARANG -->
            <div>
                <div class="flex items-center gap-2 mb-3 border-b border-gray-100 pb-3">
                    <span class="w-7 h-7 rounded-full bg-blue-100 text-pili-primary font-black text-xs flex items-center justify-center">4</span>
                    <h2 class="text-base sm:text-lg font-extrabold text-gray-900">Catatan Kondisi Barang</h2>
                </div>
                
                <!-- Quick Preset Buttons -->
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    <span class="text-xs text-gray-400 font-medium">Pilihan Cepat:</span>
                    <button type="button" @click="setCatatan('Semua barang dalam kondisi bersih, lengkap, dan berfungsi dengan baik tanpa cacat.')"
                            class="text-xs bg-gray-100 hover:bg-blue-50 hover:text-pili-primary text-gray-700 font-medium px-2.5 py-1 rounded-lg border border-gray-200 transition">
                        Kondisi Baik & Lengkap
                    </button>
                    <button type="button" @click="setCatatan('Barang telah selesai digunakan dan berfungsi normal. Kelengkapan kabel dan aksesoris tersimpan rapi.')"
                            class="text-xs bg-gray-100 hover:bg-blue-50 hover:text-pili-primary text-gray-700 font-medium px-2.5 py-1 rounded-lg border border-gray-200 transition">
                        Selesai Digunakan Normal
                    </button>
                </div>

                <textarea name="catatan_kondisi_peminjam" 
                          x-model="catatan"
                          rows="3" 
                          required
                          class="w-full border-gray-300 rounded-xl p-3.5 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-pili-primary focus:border-pili-primary text-sm font-medium" 
                          placeholder="Jelaskan kondisi fisik barang saat diserahkan (misal: barang berfungsi baik, tidak ada goresan, kabel lengkap)..."></textarea>
                @error('catatan_kondisi_peminjam')
                    <p class="text-xs text-red-600 mt-1 font-bold">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tombol Aksi Submit -->
            <div class="pt-4 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                <a href="{{ route('peminjaman.riwayat') }}" class="text-sm font-bold text-gray-500 hover:text-gray-800 transition">
                    Batal
                </a>
                <button type="submit" 
                        class="w-full sm:w-auto bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 text-white font-extrabold px-8 py-3.5 rounded-xl shadow-lg hover:shadow-xl transition transform active:scale-95 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>Kirim Pengajuan Pengembalian</span>
                </button>
            </div>

        </div>
    </form>
</div>
@endsection