@extends('layouts.auth')

@section('content')
<!-- Floating Card dengan Shadow Berlapis & Sudut Melengkung Modern -->
<div class="bg-white/90 backdrop-blur-2xl rounded-[2.5rem] shadow-[0_20px_50px_rgba(15,23,42,0.08)] border border-white/80 p-8 sm:p-10 space-y-8 transition-all duration-300 hover:shadow-[0_25px_60px_rgba(15,23,42,0.12)] hover:-translate-y-1">
    
    <!-- Header Logo & Title -->
    <div class="text-center space-y-3">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-3xl bg-gradient-to-tr from-blue-600 via-indigo-600 to-blue-700 text-white shadow-lg shadow-blue-600/30 mb-1 transform hover:scale-105 transition-transform duration-300">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
        </div>
        <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-gray-900">Selamat Datang</h1>
        <p class="text-xs sm:text-sm font-medium text-gray-500">Sistem Peminjaman Aset Yayasan PILI</p>
    </div>

    <!-- Notifikasi Error / Success -->
    @if ($errors->any())
        <div class="bg-rose-50 border-l-4 border-rose-500 text-rose-700 p-4 rounded-2xl text-xs sm:text-sm font-medium shadow-sm transition-all">
            <p class="font-bold mb-1">Terjadi Kesalahan:</p>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 p-4 rounded-2xl text-xs sm:text-sm font-medium shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <!-- Form Login dengan Alpine.js -->
    <form class="space-y-6" action="{{ route('login.post') }}" method="POST" x-data="{ role: 'staf', loading: false }" @submit="loading = true">
        @csrf

        <!-- Pilihan Jenis Pengguna (Tabs Halus) -->
        <div class="space-y-2">
            <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider">Pilih Jenis Akun</label>
            <div class="grid grid-cols-2 gap-2 p-1.5 bg-gray-100/80 rounded-2xl border border-gray-200/60 shadow-inner">
                <button type="button" 
                        @click="role = 'staf'" 
                        :class="role === 'staf' ? 'bg-white text-blue-600 shadow-md font-bold scale-[1.02]' : 'text-gray-500 hover:text-gray-900 font-medium'"
                        class="py-3 px-4 rounded-xl text-xs sm:text-sm transition-all duration-300 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    <span>Staf</span>
                </button>
                <button type="button" 
                        @click="role = 'admin'" 
                        :class="role === 'admin' ? 'bg-white text-blue-600 shadow-md font-bold scale-[1.02]' : 'text-gray-500 hover:text-gray-900 font-medium'"
                        class="py-3 px-4 rounded-xl text-xs sm:text-sm transition-all duration-300 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    <span>Admin</span>
                </button>
            </div>
            <!-- Hidden input untuk backend -->
            <input type="hidden" name="login_type" x-model="role">
        </div>

        <!-- Input Fields -->
        <div class="space-y-4">
            <div>
                <label for="email" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Email atau Username</label>
                <div class="relative group">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-blue-600 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                    </span>
                    <input id="email" name="email" type="text" required value="{{ old('email') }}" 
                           class="w-full pl-11 pr-4 py-3.5 bg-gray-50/70 rounded-2xl border border-gray-200/80 shadow-inner focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 outline-none transition-all duration-300 text-xs sm:text-sm font-medium" 
                           placeholder="nama@email.com / username">
                </div>
            </div>

            <div>
                <label for="password" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Password</label>
                <div class="relative group">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-blue-600 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </span>
                    <input id="password" name="password" type="password" required 
                           class="w-full pl-11 pr-4 py-3.5 bg-gray-50/70 rounded-2xl border border-gray-200/80 shadow-inner focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 outline-none transition-all duration-300 text-xs sm:text-sm font-medium" 
                           placeholder="••••••••">
                </div>
            </div>
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between text-xs sm:text-sm">
            <label class="flex items-center cursor-pointer select-none">
                <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded cursor-pointer transition">
                <span class="ml-2 text-gray-600 font-medium">Ingat Saya</span>
            </label>
        </div>

        <!-- Tombol Login -->
        <div>
            <button type="submit" :disabled="loading" 
                    class="w-full py-4 px-4 rounded-2xl bg-gradient-to-r from-blue-600 via-indigo-600 to-blue-700 hover:from-blue-700 hover:to-indigo-800 text-white font-extrabold text-xs sm:text-sm shadow-lg shadow-blue-600/30 hover:shadow-xl hover:shadow-blue-600/40 transition-all duration-300 transform active:scale-[0.98] flex items-center justify-center gap-2">
                <span x-show="!loading">Masuk ke Sistem</span>
                <span x-show="loading" class="flex items-center gap-2" x-cloak>
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    <span>Memproses...</span>
                </span>
            </button>
        </div>

        <!-- Tautan Register Staf -->
        <div class="text-center pt-5 border-t border-gray-100">
            <p class="text-xs sm:text-sm text-gray-500">
                Belum punya akun Staf? 
                <a href="{{ route('register') }}" class="font-bold text-blue-600 hover:text-blue-700 hover:underline transition-colors">Daftar sekarang</a>
            </p>
        </div>
    </form>

</div>
@endsection