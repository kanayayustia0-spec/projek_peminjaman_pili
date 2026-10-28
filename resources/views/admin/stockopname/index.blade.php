@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8" 
     x-data="{
        showModal: false,
        selectedOpname: null,
        barangMap: {{ $barangs->toJson() }},
        formatDate(dateStr) {
            if(!dateStr) return '-';
            const d = new Date(dateStr);
            return d.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
        },
        openDetail(opname) {
            this.selectedOpname = opname;
            this.showModal = true;
        },
        printDetail() {
            window.print();
        }
     }">
    
    <!-- HEADER -->
    <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-end gap-4 border-b border-gray-200 pb-5">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Riwayat Stock Opname</h1>
            <p class="text-gray-500 text-sm mt-1">Daftar rekapan hasil audit fisik gudang Yayasan PILI Green Network.</p>
        </div>
        
        <a href="{{ route('admin.stock-opname.create') }}" class="bg-pili-primary hover:bg-pili-dark text-white font-extrabold py-3 px-6 rounded-xl shadow-md transition flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            <span>+ Lakukan Audit Baru</span>
        </a>
    </div>

    <!-- FLASH MESSAGES -->
    @if(session('success'))
        <div class="bg-emerald-50 text-emerald-800 p-4 rounded-xl mb-6 font-bold border border-emerald-200 flex items-center gap-2 shadow-2xs">
            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- TABEL DAFTAR OPNAME -->
    <div class="bg-white rounded-2xl shadow-soft border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[700px]">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider border-b border-gray-200">
                        <th class="p-4 font-bold">Tanggal & Tim</th>
                        <th class="p-4 font-bold text-center">Terdaftar (Sistem)</th>
                        <th class="p-4 font-bold text-center text-emerald-700">Ditemukan (Fisik)</th>
                        <th class="p-4 font-bold text-center text-rose-600">Rusak / Hilang</th>
                        <th class="p-4 font-bold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($opnames as $row)
                        <tr class="hover:bg-gray-50/80 transition">
                            <td class="p-4">
                                <div class="font-extrabold text-gray-900 text-sm">
                                    {{ \Carbon\Carbon::parse($row->tanggal)->translatedFormat('d F Y') }}
                                </div>
                                <div class="text-xs text-gray-500 mt-0.5 flex items-center gap-2">
                                    <span>Tim: <strong class="text-gray-700">{{ $row->tim_pemeriksa }}</strong></span>
                                    <span>&bull;</span>
                                    <span>Lokasi: <strong class="text-gray-700">{{ $row->lokasi }}</strong></span>
                                </div>
                            </td>
                            <td class="p-4 text-center font-bold text-gray-700">
                                <span class="bg-gray-100 px-2.5 py-1 rounded-lg text-xs">{{ $row->jumlah_terdaftar }} Unit</span>
                            </td>
                            <td class="p-4 text-center font-black text-emerald-700">
                                <span class="bg-emerald-50 border border-emerald-200 px-2.5 py-1 rounded-lg text-xs">{{ $row->jumlah_ditemukan }} Unit</span>
                            </td>
                            <td class="p-4 text-center">
                                <span class="text-xs font-bold text-amber-700 bg-amber-50 border border-amber-200 px-2 py-0.5 rounded-lg">
                                    Rusak: {{ $row->jumlah_rusak }}
                                </span>
                                <span class="text-xs font-bold text-rose-700 bg-rose-50 border border-rose-200 px-2 py-0.5 rounded-lg ml-1">
                                    Hilang: {{ $row->jumlah_hilang }}
                                </span>
                            </td>
                            <td class="p-4 text-right">
                                <button type="button" 
                                        @click="openDetail({{ json_encode($row) }})" 
                                        class="inline-flex items-center gap-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 font-bold px-3 py-1.5 rounded-xl text-xs transition border border-blue-200 shadow-2xs hover:shadow-xs">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <span>Lihat Rincian</span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-12 text-center text-gray-500 font-medium">
                                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3 text-gray-400">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                </div>
                                Belum ada data stock opname yang tercatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL RINCIAN STOCK OPNAME -->
    <div x-show="showModal" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 flex items-center justify-center p-3 sm:p-5" 
         x-cloak>
        
        <div @click.away="showModal = false" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="bg-white rounded-3xl shadow-2xl max-w-4xl w-full max-h-[92vh] flex flex-col overflow-hidden border border-slate-100 print:shadow-none print:max-h-none print:w-full print:border-none">
            
            <!-- Modal Header -->
            <div class="p-5 sm:p-6 bg-slate-50 border-b border-slate-200 flex items-start justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-blue-100 text-blue-700 flex items-center justify-center font-bold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div>
                        <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900">Rincian Hasil Stock Opname</h2>
                        <p class="text-xs sm:text-sm text-slate-500">
                            Tanggal: <strong class="text-slate-700" x-text="formatDate(selectedOpname?.tanggal)"></strong> &bull; 
                            Lokasi: <strong class="text-slate-700" x-text="selectedOpname?.lokasi"></strong> &bull; 
                            Tim: <strong class="text-slate-700" x-text="selectedOpname?.tim_pemeriksa"></strong>
                        </p>
                    </div>
                </div>
                <button type="button" @click="showModal = false" class="text-slate-400 hover:text-slate-700 p-2 rounded-xl hover:bg-slate-200/60 transition print:hidden">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Modal Body (Scrollable) -->
            <div class="p-5 sm:p-6 overflow-y-auto space-y-6 flex-1 print:overflow-visible">
                
                <!-- 4 Summary Cards -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4">
                    <div class="p-3.5 bg-blue-50/70 border border-blue-200/70 rounded-2xl text-center">
                        <span class="block text-[11px] font-bold text-blue-600 uppercase">Sistem</span>
                        <span class="text-xl sm:text-2xl font-black text-blue-800" x-text="selectedOpname?.jumlah_terdaftar + ' Unit'"></span>
                    </div>
                    <div class="p-3.5 bg-emerald-50/70 border border-emerald-200/70 rounded-2xl text-center">
                        <span class="block text-[11px] font-bold text-emerald-600 uppercase">Fisik Baik</span>
                        <span class="text-xl sm:text-2xl font-black text-emerald-800" x-text="selectedOpname?.jumlah_ditemukan + ' Unit'"></span>
                    </div>
                    <div class="p-3.5 bg-amber-50/70 border border-amber-200/70 rounded-2xl text-center">
                        <span class="block text-[11px] font-bold text-amber-600 uppercase">Fisik Rusak</span>
                        <span class="text-xl sm:text-2xl font-black text-amber-800" x-text="selectedOpname?.jumlah_rusak + ' Unit'"></span>
                    </div>
                    <div class="p-3.5 bg-rose-50/70 border border-rose-200/70 rounded-2xl text-center">
                        <span class="block text-[11px] font-bold text-rose-600 uppercase">Hilang</span>
                        <span class="text-xl sm:text-2xl font-black text-rose-800" x-text="selectedOpname?.jumlah_hilang + ' Unit'"></span>
                    </div>
                </div>

                <!-- Rekomendasi Box -->
                <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl">
                    <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-pili-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                        <span>Catatan & Rekomendasi Tim Pemeriksa:</span>
                    </h3>
                    <p class="text-sm font-semibold text-slate-800 italic" x-text="selectedOpname?.rekomendasi || 'Tidak ada rekomendasi / catatan khusus.'"></p>
                </div>

                <!-- Tabel Item Audit -->
                <div class="border border-slate-200 rounded-2xl overflow-hidden shadow-2xs">
                    <div class="p-3 bg-slate-100 border-b border-slate-200 font-extrabold text-xs text-slate-700 uppercase tracking-wider">
                        Rincian Audit per Aset
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-xs sm:text-sm">
                            <thead>
                                <tr class="bg-slate-50 text-slate-500 text-[11px] uppercase tracking-wider border-b border-slate-200">
                                    <th class="p-3 font-bold">Kode & Nama Aset</th>
                                    <th class="p-3 font-bold text-center">Sistem</th>
                                    <th class="p-3 font-bold text-center text-emerald-700">Fisik Baik</th>
                                    <th class="p-3 font-bold text-center text-amber-700">Rusak</th>
                                    <th class="p-3 font-bold text-center text-rose-700">Hilang</th>
                                    <th class="p-3 font-bold text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <template x-for="(item, idx) in (selectedOpname?.detail_hasil || [])" :key="idx">
                                    <tr class="hover:bg-slate-50/60 transition">
                                        <td class="p-3">
                                            <div class="font-extrabold text-slate-900" x-text="barangMap[item.barang_id]?.nama || ('Aset ID #' + item.barang_id)"></div>
                                            <div class="text-[11px] text-slate-400 font-mono" x-text="barangMap[item.barang_id]?.kode_inventaris || '-'"></div>
                                        </td>
                                        <td class="p-3 text-center font-bold text-slate-600" x-text="item.sistem"></td>
                                        <td class="p-3 text-center font-bold text-emerald-700" x-text="item.fisik"></td>
                                        <td class="p-3 text-center font-bold text-amber-700" x-text="item.rusak"></td>
                                        <td class="p-3 text-center font-bold text-rose-700" x-text="item.hilang"></td>
                                        <td class="p-3 text-center">
                                            <span x-show="item.rusak == 0 && item.hilang == 0 && item.fisik == item.sistem" 
                                                  class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                Sesuai
                                            </span>
                                            <span x-show="item.rusak > 0 || item.hilang > 0 || item.fisik != item.sistem" 
                                                  class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                                Ada Selisih
                                            </span>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <!-- Modal Footer -->
            <div class="p-4 sm:p-5 bg-slate-50 border-t border-slate-200 flex items-center justify-between gap-3 print:hidden">
                <button type="button" 
                        @click="printDetail()" 
                        class="inline-flex items-center gap-2 bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs sm:text-sm px-4 py-2.5 rounded-xl shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    <span>Cetak Rincian</span>
                </button>
                <button type="button" 
                        @click="showModal = false" 
                        class="bg-white border border-slate-300 text-slate-700 hover:bg-slate-100 font-bold text-xs sm:text-sm px-5 py-2.5 rounded-xl transition">
                    Tutup
                </button>
            </div>

        </div>
    </div>

</div>
@endsection