@extends('layouts.auth')

@section('content')
<!-- Modern Auth Card Container -->
<div class="relative bg-white/95 backdrop-blur-2xl rounded-3xl sm:rounded-[2.5rem] shadow-modern-lg border border-slate-100/80 overflow-hidden transition-all duration-300">
    
    <!-- Top Accent Bar (Simple Blue) -->
    <div class="h-1.5 w-full bg-blue-600"></div>

    <div class="p-6 sm:p-10 space-y-6 sm:space-y-8">
        <!-- Header: Logo & Branding -->
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
                    Selamat Datang
                </h1>
                <p class="text-xs sm:text-sm font-medium text-slate-600">
                    di Sistem Peminjaman Barang PILI Green Network
                </p>
            </div>
        </div>

        <!-- Error & Status Alerts -->
        @if (isset($errors) && $errors->any())
            <div class="bg-rose-50/90 border border-rose-200/80 text-rose-800 p-4 rounded-2xl text-xs sm:text-sm font-medium shadow-sm transition-all animate-shake">
                <div class="flex items-center gap-2 font-bold mb-1">
                    <svg class="w-4 h-4 text-rose-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <span>Terjadi Kesalahan</span>
                </div>
                <ul class="list-disc list-inside space-y-0.5 text-xs sm:text-sm text-rose-700">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="bg-emerald-50/90 border border-emerald-200/80 text-emerald-800 p-4 rounded-2xl text-xs sm:text-sm font-medium shadow-sm flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Form Login dengan Alpine.js -->
        <form class="space-y-5 sm:space-y-6" 
              action="{{ route('login.post') }}" 
              method="POST" 
              x-data="{ role: 'staf', showPassword: false, loading: false }" 
              @submit="loading = true">
            @csrf

            <!-- Selector Jenis Akun (Modern Segmented Pills) -->
            <div>
                <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2 text-center">
                    Masuk Sebagai
                </label>
                <div class="grid grid-cols-2 gap-2 p-1.5 bg-slate-100/80 rounded-2xl border border-slate-200/70">
                    <button type="button" 
                            @click="role = 'staf'" 
                            :class="role === 'staf' 
                                ? 'bg-white text-pili-blue shadow-sm font-bold border border-slate-200/60' 
                                : 'text-slate-500 hover:text-slate-800 font-semibold'"
                            class="py-2.5 px-4 rounded-xl text-xs sm:text-sm transition-all duration-200 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span>Staf</span>
                    </button>
                    <button type="button" 
                            @click="role = 'admin'" 
                            :class="role === 'admin' 
                                ? 'bg-white text-pili-blue shadow-sm font-bold border border-slate-200/60' 
                                : 'text-slate-500 hover:text-slate-800 font-semibold'"
                            class="py-2.5 px-4 rounded-xl text-xs sm:text-sm transition-all duration-200 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        <span>Admin</span>
                    </button>
                </div>
                <input type="hidden" name="login_type" x-model="role">
            </div>

            <!-- Input Fields -->
            <div class="space-y-4">
                <!-- Username / Email -->
                <div>
                    <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Email atau Username
                    </label>
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-pili-blue transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                            </svg>
                        </span>
                        <input id="email" 
                               name="email" 
                               type="text" 
                               required 
                               value="{{ old('email') }}" 
                               class="w-full pl-11 pr-4 py-3 sm:py-3.5 bg-slate-50/70 hover:bg-slate-50 rounded-2xl border border-slate-200 focus:bg-white focus:border-pili-blue focus:ring-4 focus:ring-pili-blue/15 outline-none transition-all duration-200 text-xs sm:text-sm font-medium text-slate-800 placeholder-slate-400" 
                               placeholder="nama@email.com / username">
                    </div>
                </div>

                <!-- Password with Show/Hide Toggle -->
                <div>
                    <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Password
                    </label>
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-pili-blue transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </span>
                        <input id="password" 
                               name="password" 
                               :type="showPassword ? 'text' : 'password'" 
                               required 
                               class="w-full pl-11 pr-11 py-3 sm:py-3.5 bg-slate-50/70 hover:bg-slate-50 rounded-2xl border border-slate-200 focus:bg-white focus:border-pili-blue focus:ring-4 focus:ring-pili-blue/15 outline-none transition-all duration-200 text-xs sm:text-sm font-medium text-slate-800 placeholder-slate-400" 
                               placeholder="••••••••">
                        <button type="button" 
                                @click="showPassword = !showPassword" 
                                class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-700 focus:outline-none transition-colors">
                            <svg x-show="!showPassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg x-show="showPassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-cloak>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between text-xs sm:text-sm pt-0.5">
                <label class="flex items-center cursor-pointer select-none">
                    <input id="remember" 
                           name="remember" 
                           type="checkbox" 
                           class="h-4 w-4 text-pili-blue focus:ring-pili-blue border-slate-300 rounded cursor-pointer transition">
                    <span class="ml-2.5 text-slate-600 font-medium">Ingat Saya</span>
                </label>
            </div>

            <!-- Tombol Login (Warna Biru Netral & Simpel) -->
            <div>
                <button type="submit" 
                        :disabled="loading" 
                        class="w-full py-3.5 px-4 rounded-xl bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-bold text-sm shadow-sm hover:shadow transition-colors duration-200 flex items-center justify-center gap-2">
                    <span x-show="!loading" class="flex items-center gap-2">
                        <span>Masuk ke Akun</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </span>
                    <span x-show="loading" class="flex items-center gap-2" x-cloak>
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>Memproses...</span>
                    </span>
                </button>
            </div>

            <!-- Tautan Register Staf -->
            <div class="text-center pt-4 sm:pt-5 border-t border-slate-100">
                <p class="text-xs sm:text-sm text-slate-500">
                    Belum punya akun Staf? 
                    <a href="{{ route('register') }}" class="font-bold text-pili-blue hover:text-pili-blue-dark hover:underline transition-colors">
                        Daftar sekarang
                    </a>
                </p>
            </div>
        </form>
    </div>
</div>
@endsection