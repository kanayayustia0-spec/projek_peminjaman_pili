@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-5 sm:py-8 px-3.5 sm:px-6 lg:px-8" x-data="{ 
    searchKeyword: '',
    selectedCategory: 'ALL',
    cart: {}, // { id: { id, nama, kode, kategori, max, qty } }
    tanggalMulai: '{{ date('Y-m-d\TH:i') }}',
    tanggalSelesai: '{{ date('Y-m-d\TH:i', strtotime('+1 day')) }}',
    durationError: '',
    durationText: '',

    tambah(id, nama, kode, kategori, max) { 
        if(max <= 0) return;
        if(!this.cart[id]) {
            this.cart[id] = { id: id, nama: nama, kode: kode, kategori: kategori, max: max, qty: 1 };
        } else if(this.cart[id].qty < max) {
            this.cart[id].qty++;
        }
    },

    kurang(id) { 
        if(this.cart[id]) {
            this.cart[id].qty--;
            if(this.cart[id].qty <= 0) {
                delete this.cart[id];
            }
        }
    },

    hapus(id) {
        if(this.cart[id]) delete this.cart[id];
    },

    get totalItems() {
        return Object.values(this.cart).reduce((sum, item) => sum + item.qty, 0);
    },

    get cartList() {
        return Object.values(this.cart);
    },

    validateDuration() {
        if (!this.tanggalMulai || !this.tanggalSelesai) {
            this.durationText = '';
            this.durationError = '';
            return;
        }

        const start = new Date(this.tanggalMulai);
        const end = new Date(this.tanggalSelesai);
        const diffMs = end - start;
        const diffHours = diffMs / (1000 * 60 * 60);

        if (diffHours <= 0) {
            this.durationError = 'Tanggal selesai harus lebih besar dari tanggal mulai.';
            this.durationText = '';
        } else if (diffHours > 50) { // toleransi margin
            const days = (diffHours / 24).toFixed(1);
            this.durationError = `Durasi peminjaman (${days} hari / ${Math.round(diffHours)} jam) melebihi batas maksimal 2 hari (48 jam)!`;
            this.durationText = '';
        } else {
            this.durationError = '';
            const days = Math.floor(diffHours / 24);
            const remainingHours = Math.round(diffHours % 24);
            this.durationText = `Durasi: ${days > 0 ? days + ' hari ' : ''}${remainingHours} jam (Maks. 2 Hari)`;
        }
    },

    init() {
        this.validateDuration();
        this.$watch('tanggalMulai', () => this.validateDuration());
        this.$watch('tanggalSelesai', () => this.validateDuration());
    }
}">
    
    <!-- HEADER (RESPONSIF MOBILE) -->
    <div class="mb-5 sm:mb-8 flex flex-col md:flex-row md:items-center justify-between gap-3 sm:gap-4">
        <div>
            <div class="flex items-center gap-1.5 text-[11px] font-bold text-pili-primary uppercase tracking-wider mb-1">
                <a href="{{ route('dashboard') }}" class="hover:underline flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    <span>Beranda</span>
                </a>
                <span>/</span>
                <span class="text-gray-400">Formulir Peminjaman</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight">Form Peminjaman Aset PILI</h1>
            <p class="text-gray-500 text-xs sm:text-sm mt-0.5">
                Pilih barang dari kategori A s/d F, atur jadwal (maks. 2 hari), dan lengkapi data kegiatan.
            </p>
        </div>

        <div class="flex items-center">
            <span class="w-full sm:w-auto bg-blue-50 border border-blue-200 text-pili-primary text-xs font-bold px-3.5 py-2 rounded-xl shadow-sm flex items-center justify-center gap-1.5">
                <svg class="w-4 h-4 text-pili-primary shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>Durasi Maksimal: <strong>2 Hari (48 Jam)</strong></span>
            </span>
        </div>
    </div>

    <!-- NOTIFIKASI ERROR -->
    @if(session('error'))
        <div class="bg-rose-50 border border-rose-200 text-rose-800 p-4 rounded-2xl mb-5 flex items-start gap-3 shadow-sm">
            <svg class="w-5 h-5 text-rose-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            <div class="text-xs sm:text-sm font-semibold leading-relaxed">{{ session('error') }}</div>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-rose-50 border border-rose-200 text-rose-800 p-4 rounded-2xl mb-5 shadow-sm">
            <p class="font-bold text-xs sm:text-sm mb-1">Terdapat kesalahan pada input form:</p>
            <ul class="list-disc list-inside text-xs space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('peminjaman.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 sm:gap-8">
            
            <!-- KOLOM KIRI: KERANJANG & SEARCH ASET (5 COLS DI DESKTOP / 1 KOLOM DI HP) -->
            <div class="lg:col-span-5 space-y-4">
                
                <div class="bg-white rounded-2xl sm:rounded-3xl shadow-soft p-4 sm:p-6 border border-gray-100 flex flex-col h-[460px] sm:h-[540px] lg:h-[680px]">
                    
                    <!-- Heading Keranjang -->
                    <div class="border-b border-gray-100 pb-3 mb-3 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-xl bg-pili-light text-pili-primary flex items-center justify-center font-bold">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            </div>
                            <div>
                                <h3 class="font-black text-gray-900 text-sm sm:text-base leading-tight">Pilih Barang ke Keranjang</h3>
                                <p class="text-[10px] sm:text-[11px] text-gray-400">Kategori A s/d F</p>
                            </div>
                        </div>

                        <!-- Badge Total Terpilih -->
                        <span class="text-[11px] sm:text-xs font-black px-2.5 py-1 bg-pili-primary text-white rounded-full shadow-sm" x-text="totalItems + ' Unit'"></span>
                    </div>

                    <!-- SEARCH BAR LIVE DI BAGIAN KERANJANG -->
                    <div class="space-y-2 mb-3">
                        <div class="relative">
                            <input type="text" x-model="searchKeyword" placeholder="Cari nama barang atau kode..." class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-9 pr-7 py-2 text-xs text-gray-900 placeholder-gray-400 focus:bg-white focus:ring-2 focus:ring-pili-primary outline-none transition">
                            <svg class="w-4 h-4 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            <button type="button" x-show="searchKeyword" @click="searchKeyword = ''" class="absolute right-2.5 top-2 text-gray-400 hover:text-gray-600 text-sm font-bold">&times;</button>
                        </div>

                        <!-- FILTER KATEGORI PILLS (SCROLL DI HP) -->
                        <div class="flex items-center gap-1.5 overflow-x-auto pb-1 scrollbar-none text-[10px] sm:text-[11px] -mx-1 px-1">
                            <button type="button" @click="selectedCategory = 'ALL'" :class="selectedCategory === 'ALL' ? 'bg-gray-900 text-white font-bold' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'" class="px-2.5 py-1 rounded-lg transition whitespace-nowrap shrink-0">
                                Semua
                            </button>
                            @foreach(\App\Models\Barang::KATEGORI_LIST as $kode => $label)
                                <button type="button" @click="selectedCategory = '{{ $kode }}'" :class="selectedCategory === '{{ $kode }}' ? 'bg-pili-primary text-white font-bold' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'" class="px-2.5 py-1 rounded-lg transition whitespace-nowrap shrink-0">
                                    Kat {{ $kode }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- DAFTAR ASET (TOUCH-FRIENDLY & SCROLLABLE) -->
                    <div class="flex-1 overflow-y-auto space-y-2 pr-1 divide-y divide-gray-50">
                        @foreach($barangs as $b)
                            @php
                                $isHabis = ($b->jumlah_tersedia <= 0 || $b->status !== 'Tersedia');
                                $katKode = $b->kategori_kode;
                            @endphp
                            <div x-show="(selectedCategory === 'ALL' || selectedCategory === '{{ $katKode }}') && ('{{ strtolower(addslashes($b->nama . ' ' . $b->kode_inventaris . ' ' . $b->kategori_label)) }}'.includes(searchKeyword.toLowerCase().trim()))" 
                                 class="pt-2 flex items-center justify-between gap-2.5 p-2.5 sm:p-3 rounded-2xl border border-gray-100 {{ $isHabis ? 'bg-gray-50/60 opacity-60' : 'bg-gray-50 hover:bg-white hover:border-blue-200' }} transition">
                                
                                <div class="w-7/12 sm:w-3/5">
                                    <div class="flex items-center gap-1.5 mb-0.5">
                                        <span class="text-[9px] font-extrabold px-1.5 py-0.5 rounded bg-blue-100 text-pili-primary uppercase shrink-0">Kat {{ $katKode }}</span>
                                        <span class="text-[10px] font-mono text-gray-400 font-bold truncate">{{ $b->kode_inventaris }}</span>
                                    </div>
                                    <h4 class="text-xs font-bold text-gray-900 leading-snug line-clamp-1">{{ $b->nama }}</h4>
                                    
                                    @if($isHabis)
                                        <span class="text-[10px] font-extrabold text-rose-600">Stok Habis</span>
                                    @else
                                        <span class="text-[10px] text-gray-500">Tersedia: <strong class="text-emerald-700 font-bold">{{ $b->jumlah_tersedia }}</strong></span>
                                    @endif
                                </div>

                                <!-- CONTROLLER QUANTITY KERANJANG (TOMBOL LEBIH BESAR UNTUK JARI HP) -->
                                <div class="w-5/12 sm:w-2/5 flex justify-end">
                                    @if($isHabis)
                                        <span class="text-[10px] bg-rose-100 text-rose-700 font-bold px-2 py-1 rounded-lg">
                                            Habis
                                        </span>
                                    @else
                                        <div class="flex items-center gap-1 bg-white border border-gray-200 rounded-xl p-1 shadow-sm">
                                            <button type="button" @click="kurang({{ $b->id }})" class="w-7 h-7 sm:w-7 sm:h-7 flex items-center justify-center bg-gray-100 active:bg-gray-300 text-gray-700 rounded-lg font-bold text-sm transition">
                                                -
                                            </button>
                                            <span class="w-5 text-center text-xs font-black text-gray-900" x-text="cart[{{ $b->id }}]?.qty || 0"></span>
                                            <button type="button" @click="tambah({{ $b->id }}, '{{ addslashes($b->nama) }}', '{{ $b->kode_inventaris }}', '{{ $b->kategori_kode }}', {{ $b->jumlah_tersedia }})" class="w-7 h-7 sm:w-7 sm:h-7 flex items-center justify-center bg-pili-primary active:bg-pili-dark text-white rounded-lg font-bold text-sm transition">
                                                +
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- RINGKASAN ITEM TERPILIH -->
                    <div class="border-t border-gray-100 pt-2.5 mt-2">
                        <div class="text-[11px] font-bold text-gray-500 mb-1 flex justify-between">
                            <span>Item Terpilih:</span>
                            <span class="text-pili-primary font-black" x-text="cartList.length + ' Macam Barang'"></span>
                        </div>
                        <div class="max-h-20 overflow-y-auto space-y-1">
                            <template x-for="item in cartList" :key="item.id">
                                <div class="flex items-center justify-between text-[11px] bg-blue-50/70 px-2 py-1 rounded-lg">
                                    <span class="font-medium text-gray-800 truncate w-4/5" x-text="item.nama + ' (x' + item.qty + ')'"></span>
                                    <button type="button" @click="hapus(item.id)" class="text-rose-500 hover:text-rose-700 font-bold text-xs p-0.5">&times;</button>
                                </div>
                            </template>
                            <template x-if="cartList.length === 0">
                                <p class="text-[10px] text-gray-400 italic">Belum ada barang di keranjang. Tekan (+) di daftar atas.</p>
                            </template>
                        </div>
                    </div>

                </div>
            </div>

            <!-- KOLOM KANAN: FORM 5W (WHO, WHAT, WHY, WHERE, WHEN) -->
            <div class="lg:col-span-7 bg-white rounded-2xl sm:rounded-3xl shadow-soft p-5 sm:p-8 border border-gray-100 space-y-4 sm:space-y-6">
                
                <div class="border-b border-gray-100 pb-3 sm:pb-4">
                    <h3 class="font-black text-lg sm:text-xl text-gray-900 tracking-tight">Detail Kegiatan & Peminjam</h3>
                    <p class="text-[11px] sm:text-xs text-gray-500 mt-0.5">Informasi untuk peninjauan Admin Pengelola & Admin Persetujuan.</p>
                </div>

                <!-- INFO PEMINJAM (WHO) -->
                <div class="bg-gray-50 rounded-2xl p-3.5 sm:p-4 border border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between text-xs gap-2">
                    <div>
                        <span class="text-gray-400 font-medium text-[10px] sm:text-xs">Pemohon (Who):</span>
                        <p class="font-bold text-gray-900 text-xs sm:text-sm">{{ Auth::user()->name }}</p>
                    </div>
                    <div>
                        <span class="text-gray-400 font-medium text-[10px] sm:text-xs">Divisi / Program:</span>
                        <p class="font-bold text-gray-800 text-xs sm:text-sm">{{ Auth::user()->divisi_program ?? 'Umum & Lapangan' }}</p>
                    </div>
                </div>

                <!-- WHAT: NAMA KEGIATAN -->
                <div>
                    <label class="block text-[11px] sm:text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">
                        Nama Kegiatan (What) <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="nama_kegiatan" value="{{ old('nama_kegiatan') }}" required placeholder="Contoh: Survei Lapangan & Dokumentasi Konservasi" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-2.5 sm:p-3 text-xs sm:text-sm font-medium focus:bg-white focus:ring-2 focus:ring-pili-primary outline-none transition">
                </div>

                <!-- WHERE: LOKASI PENGGUNAAN -->
                <div>
                    <label class="block text-[11px] sm:text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">
                        Lokasi Penggunaan (Where) <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="lokasi_penggunaan" value="{{ old('lokasi_penggunaan') }}" required placeholder="Contoh: Kawasan Konservasi Teluk Semangka / Kantor PILI" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-2.5 sm:p-3 text-xs sm:text-sm font-medium focus:bg-white focus:ring-2 focus:ring-pili-primary outline-none transition">
                </div>

                <!-- WHY: TUJUAN & URGENSI -->
                <div>
                    <label class="block text-[11px] sm:text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">
                        Tujuan / Urgensi Peminjaman (Why) <span class="text-rose-500">*</span>
                    </label>
                    <textarea name="tujuan_peminjaman" required rows="2" placeholder="Jelaskan tujuan pemanfaatan aset dan urgensinya..." class="w-full bg-gray-50 border border-gray-200 rounded-xl p-2.5 sm:p-3 text-xs sm:text-sm font-medium focus:bg-white focus:ring-2 focus:ring-pili-primary outline-none transition">{{ old('tujuan_peminjaman') }}</textarea>
                </div>

                <!-- WHEN: JADWAL & MAKSIMAL 2 HARI -->
                <div class="space-y-2">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1">
                        <label class="block text-[11px] sm:text-xs font-bold text-gray-700 uppercase tracking-wider">
                            Jadwal Peminjaman (Maks. 2 Hari) <span class="text-rose-500">*</span>
                        </label>
                        <span class="text-[10px] sm:text-[11px] font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-md inline-block self-start sm:self-auto" x-text="durationText"></span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                        <div>
                            <label class="block text-[10px] sm:text-[11px] font-medium text-gray-500 mb-1">Tanggal & Jam Mulai</label>
                            <input type="datetime-local" name="tanggal_mulai" x-model="tanggalMulai" required class="w-full bg-gray-50 border border-gray-200 rounded-xl p-2.5 sm:p-3 text-xs sm:text-sm font-medium focus:bg-white focus:ring-2 focus:ring-pili-primary outline-none transition">
                        </div>
                        <div>
                            <label class="block text-[10px] sm:text-[11px] font-medium text-gray-500 mb-1">Tanggal & Jam Selesai</label>
                            <input type="datetime-local" name="tanggal_selesai" x-model="tanggalSelesai" required class="w-full bg-gray-50 border border-gray-200 rounded-xl p-2.5 sm:p-3 text-xs sm:text-sm font-medium focus:bg-white focus:ring-2 focus:ring-pili-primary outline-none transition">
                        </div>
                    </div>

                    <!-- Alert Validasi Durasi Real-Time -->
                    <div x-show="durationError" class="p-3 bg-rose-50 border border-rose-200 rounded-xl text-rose-700 text-xs font-bold flex items-center gap-2">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span x-text="durationError"></span>
                    </div>
                </div>

                <!-- INPUT TERSEMBUNYI UNTUK DATA KERANJANG -->
                <template x-for="(item, id) in cart" :key="id">
                    <div>
                        <input type="hidden" name="barang_id[]" :value="item.id">
                        <input type="hidden" name="jumlah[]" :value="item.qty">
                    </div>
                </template>

                <!-- PERNYATAAN TANGGUNG JAWAB -->
                <div class="bg-blue-50 border border-blue-200/80 rounded-2xl p-3.5 sm:p-4">
                    <label class="flex items-start gap-2.5 cursor-pointer">
                        <input type="checkbox" required class="mt-1 w-4 h-4 text-pili-primary rounded border-gray-300 focus:ring-pili-primary shrink-0">
                        <span class="text-[11px] sm:text-xs text-blue-900 font-medium leading-relaxed">
                            Saya bertanggung jawab penuh atas keutuhan dan keamanan aset yang dipinjam serta bersedia mematuhi SOP BASTA Yayasan PILI.
                        </span>
                    </label>
                </div>

                <!-- TOMBOL SUBMIT (RESPONSIF MOBILE) -->
                <div>
                    <button type="submit" 
                            :disabled="cartList.length === 0 || durationError !== ''" 
                            :class="(cartList.length === 0 || durationError !== '') ? 'bg-gray-300 cursor-not-allowed text-gray-500' : 'bg-pili-primary hover:bg-pili-dark text-white shadow-lg transform active:scale-95'"
                            class="w-full font-black py-3.5 sm:py-4 px-6 rounded-2xl transition duration-150 text-xs sm:text-sm tracking-wide flex items-center justify-center gap-2">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>KIRIM PENGAJUAN SEKARANG</span>
                    </button>
                    <p class="text-[10px] sm:text-[11px] text-gray-400 text-center mt-2" x-show="cartList.length === 0">
                        * Pilih minimal 1 barang di keranjang sebelah kiri untuk mengaktifkan tombol.
                    </p>
                </div>

            </div>

        </div>
    </form>

</div>
@endsection