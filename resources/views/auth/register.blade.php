@extends('layouts.auth')

@section('content')
<div class="relative bg-white/95 backdrop-blur-2xl rounded-3xl sm:rounded-[2.5rem] shadow-modern-lg border border-slate-100/80 overflow-hidden transition-all duration-300">
    
    <!-- Top Brand Accent Bar (PILI Blue to Green Gradient) -->
    <div class="h-2 w-full bg-gradient-to-r from-pili-blue via-sky-500 to-pili-green"></div>

    <div class="p-6 sm:p-10 space-y-6">
        <!-- Header -->
        <div class="text-center space-y-3">
            <div class="flex justify-center">
                <div class="p-2.5 bg-slate-50/80 rounded-2xl border border-slate-100 shadow-sm inline-flex items-center justify-center transform hover:scale-105 transition-transform duration-300">
                    <img src="{{ asset('images/logo.png') }}" 
                         alt="Logo PILI Green Network" 
                         class="h-12 sm:h-14 w-auto object-contain drop-shadow-sm">
                </div>
            </div>
            
            <div class="space-y-1">
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900">
                    Pendaftaran Staf
                </h1>
                <p class="text-xs sm:text-sm font-medium text-slate-500">
                    Buat akun baru untuk mengajukan peminjaman aset
                </p>
            </div>
        </div>

        @if (isset($errors) && $errors->any())
            <div class="bg-rose-50/90 border border-rose-200/80 text-rose-800 p-4 rounded-2xl text-xs sm:text-sm font-medium shadow-sm transition-all">
                <div class="flex items-center gap-2 font-bold mb-1">
                    <svg class="w-4 h-4 text-rose-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <span>Periksa kembali data Anda:</span>
                </div>
                <ul class="list-disc list-inside space-y-0.5 text-xs sm:text-sm text-rose-700">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form Register -->
        <form class="space-y-4" action="{{ route('register.post') }}" method="POST" x-data="{ loading: false }" @submit="loading = true">
            @csrf

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nama Lengkap</label>
                <input name="name" type="text" required value="{{ old('name') }}" 
                       class="w-full px-4 py-3 bg-slate-50/70 hover:bg-slate-50 rounded-2xl border border-slate-200 focus:bg-white focus:border-pili-blue focus:ring-4 focus:ring-pili-blue/15 outline-none transition text-xs sm:text-sm font-medium text-slate-800 placeholder-slate-400" 
                       placeholder="Nama Lengkap Anda">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Username</label>
                <input name="username" type="text" required value="{{ old('username') }}" 
                       class="w-full px-4 py-3 bg-slate-50/70 hover:bg-slate-50 rounded-2xl border border-slate-200 focus:bg-white focus:border-pili-blue focus:ring-4 focus:ring-pili-blue/15 outline-none transition text-xs sm:text-sm font-medium text-slate-800 placeholder-slate-400" 
                       placeholder="username_staf">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Email Aktif</label>
                <input name="email" type="email" required value="{{ old('email') }}" 
                       class="w-full px-4 py-3 bg-slate-50/70 hover:bg-slate-50 rounded-2xl border border-slate-200 focus:bg-white focus:border-pili-blue focus:ring-4 focus:ring-pili-blue/15 outline-none transition text-xs sm:text-sm font-medium text-slate-800 placeholder-slate-400" 
                       placeholder="nama@email.com">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Password</label>
                    <input name="password" type="password" required 
                           class="w-full px-4 py-3 bg-slate-50/70 hover:bg-slate-50 rounded-2xl border border-slate-200 focus:bg-white focus:border-pili-blue focus:ring-4 focus:ring-pili-blue/15 outline-none transition text-xs sm:text-sm font-medium text-slate-800 placeholder-slate-400" 
                           placeholder="Min. 8 karakter">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Konfirmasi</label>
                    <input name="password_confirmation" type="password" required 
                           class="w-full px-4 py-3 bg-slate-50/70 hover:bg-slate-50 rounded-2xl border border-slate-200 focus:bg-white focus:border-pili-blue focus:ring-4 focus:ring-pili-blue/15 outline-none transition text-xs sm:text-sm font-medium text-slate-800 placeholder-slate-400" 
                           placeholder="Ulangi password">
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" :disabled="loading" 
                        class="w-full py-3.5 px-6 rounded-2xl bg-gradient-to-r from-pili-blue via-[#1f66be] to-pili-green hover:opacity-95 text-white font-extrabold text-xs sm:text-sm tracking-wide shadow-lg shadow-pili-blue/25 hover:shadow-xl hover:shadow-pili-blue/35 transition-all duration-300 transform active:scale-[0.98] flex items-center justify-center gap-2">
                    <span x-show="!loading">Daftar Sekarang</span>
                    <span x-show="loading" class="flex items-center gap-2" x-cloak>
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>Mendaftarkan...</span>
                    </span>
                </button>
            </div>

            <div class="text-center pt-4 border-t border-slate-100">
                <p class="text-xs sm:text-sm text-slate-500">
                    Sudah punya akun? 
                    <a href="{{ route('login') }}" class="font-bold text-pili-blue hover:text-pili-blue-dark hover:underline transition-colors">
                        Masuk di sini
                    </a>
                </p>
            </div>
        </form>

    </div>
</div>
@endsection