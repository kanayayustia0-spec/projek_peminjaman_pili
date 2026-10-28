@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    
    <!-- Header Navigasi -->
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('admin.dashboard') }}" class="text-xs sm:text-sm font-bold text-gray-500 hover:text-pili-primary flex items-center gap-1.5 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Dashboard
        </a>
        <span class="text-xs font-bold bg-amber-50 text-amber-700 px-3 py-1 rounded-full border border-amber-200">
            Akses Khusus Admin Persetujuan
        </span>
    </div>

    <!-- Alert Error Validasi -->
    @if ($errors->any())
        <div class="bg-rose-50 border-l-4 border-rose-500 text-rose-700 p-4 rounded-xl text-xs sm:text-sm font-medium shadow-sm mb-6">
            <p class="font-bold mb-1">Periksa kembali data pendaftaran admin:</p>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-3xl shadow-soft border border-gray-100 p-6 sm:p-10">
        
        <div class="text-center mb-8">
            <div class="w-14 h-14 bg-blue-50 text-pili-primary rounded-2xl flex items-center justify-center mx-auto mb-3 shadow-inner">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
            </div>
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">Pendaftaran Akun Admin Baru</h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-1">Tambahkan akun pengelola logistik gudang atau staf otorisasi persetujuan baru.</p>
        </div>

        <form action="{{ route('admin.register-admin.post') }}" method="POST" class="space-y-5">
            @csrf

            <!-- Username -->
            <div>
                <label for="username" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                    Username Admin <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input type="text" name="username" id="username" required value="{{ old('username') }}"
                           class="w-full px-4 py-3 bg-gray-50/70 rounded-xl border border-gray-200 focus:bg-white focus:border-pili-primary focus:ring-4 focus:ring-pili-primary/10 outline-none transition text-sm font-medium"
                           placeholder="misal: adminpengelola3 atau adminpersetujuan2">
                </div>
                <p class="text-[11px] text-gray-400 mt-1">Gunakan huruf kecil, angka, atau garis bawah tanpa spasi.</p>
            </div>

            <!-- Role Admin -->
            <div>
                <label for="role" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                    Peran / Role Admin <span class="text-red-500">*</span>
                </label>
                <select name="role" id="role" required 
                        class="w-full px-4 py-3 bg-gray-50/70 rounded-xl border border-gray-200 focus:bg-white focus:border-pili-primary focus:ring-4 focus:ring-pili-primary/10 outline-none transition text-sm font-medium">
                    <option value="">-- Pilih Role Admin --</option>
                    <option value="pengelola" {{ old('role') == 'pengelola' ? 'selected' : '' }}>
                        Admin Pengelola (Kelola Inventaris & Serah Terima Gudang BASTA)
                    </option>
                    <option value="persetujuan" {{ old('role') == 'persetujuan' ? 'selected' : '' }}>
                        Admin Persetujuan (Otorisasi Pengajuan & Kebijakan)
                    </option>
                </select>
            </div>

            <!-- Password & Konfirmasi -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="password" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                        Password <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="password" id="password" required minlength="6"
                           class="w-full px-4 py-3 bg-gray-50/70 rounded-xl border border-gray-200 focus:bg-white focus:border-pili-primary focus:ring-4 focus:ring-pili-primary/10 outline-none transition text-sm font-medium"
                           placeholder="Min. 6 karakter">
                </div>
                <div>
                    <label for="password_confirmation" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                        Konfirmasi Password <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required minlength="6"
                           class="w-full px-4 py-3 bg-gray-50/70 rounded-xl border border-gray-200 focus:bg-white focus:border-pili-primary focus:ring-4 focus:ring-pili-primary/10 outline-none transition text-sm font-medium"
                           placeholder="Ulangi password">
                </div>
            </div>

            <!-- Tombol Submit -->
            <div class="pt-4 border-t border-gray-100 flex items-center justify-between gap-3">
                <a href="{{ route('admin.dashboard') }}" class="text-sm font-bold text-gray-500 hover:text-gray-800 transition">
                    Batal
                </a>
                <button type="submit" class="bg-pili-primary hover:bg-pili-dark text-white font-extrabold px-6 py-3.5 rounded-xl shadow-lg transition transform active:scale-95 flex items-center gap-2 text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span>Simpan Akun Admin</span>
                </button>
            </div>

        </form>

    </div>

</div>
@endsection
