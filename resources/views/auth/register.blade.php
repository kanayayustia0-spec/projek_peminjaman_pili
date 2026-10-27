@extends('layouts.auth')

@section('content')
<div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl shadow-blue-900/10 border border-white/80 p-8 sm:p-10 space-y-6 animate-fade-in">
    
    <!-- Header -->
    <div class="text-center space-y-2">
        <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white shadow-lg shadow-blue-600/30 mb-2">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
        </div>
        <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-gray-900">Pendaftaran Staf</h1>
        <p class="text-xs sm:text-sm font-medium text-gray-500">Buat akun baru untuk mengakses sistem peminjaman</p>
    </div>

    @if ($errors->any())
        <div class="bg-rose-50 border-l-4 border-rose-500 text-rose-700 p-4 rounded-xl text-xs sm:text-sm font-medium shadow-sm">
            <p class="font-bold mb-1">Periksa kembali data Anda:</p>
            <ul class="list-disc list-inside space-y-0.5">
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
            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Nama Lengkap</label>
            <input name="name" type="text" required value="{{ old('name') }}" 
                   class="w-full px-4 py-3 bg-gray-50/50 rounded-xl border border-gray-200 focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 outline-none transition text-xs sm:text-sm font-medium" 
                   placeholder="Nama Lengkap Anda">
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Username</label>
            <input name="username" type="text" required value="{{ old('username') }}" 
                   class="w-full px-4 py-3 bg-gray-50/50 rounded-xl border border-gray-200 focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 outline-none transition text-xs sm:text-sm font-medium" 
                   placeholder="username_staf">
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Email Aktif</label>
            <input name="email" type="email" required value="{{ old('email') }}" 
                   class="w-full px-4 py-3 bg-gray-50/50 rounded-xl border border-gray-200 focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 outline-none transition text-xs sm:text-sm font-medium" 
                   placeholder="nama@email.com">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Password</label>
                <input name="password" type="password" required 
                       class="w-full px-4 py-3 bg-gray-50/50 rounded-xl border border-gray-200 focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 outline-none transition text-xs sm:text-sm font-medium" 
                       placeholder="Min. 8 karakter">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Konfirmasi</label>
                <input name="password_confirmation" type="password" required 
                       class="w-full px-4 py-3 bg-gray-50/50 rounded-xl border border-gray-200 focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 outline-none transition text-xs sm:text-sm font-medium" 
                       placeholder="Ulangi password">
            </div>
        </div>

        <div class="pt-2">
            <button type="submit" :disabled="loading" 
                    class="w-full py-3.5 px-4 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-extrabold text-xs sm:text-sm shadow-lg shadow-blue-600/30 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                <span x-show="!loading">Daftar Sekarang</span>
                <span x-show="loading" class="flex items-center gap-2" x-cloak>
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    <span>Mendaftarkan...</span>
                </span>
            </button>
        </div>

        <div class="text-center pt-4 border-t border-gray-100">
            <p class="text-xs sm:text-sm text-gray-500">
                Sudah punya akun? 
                <a href="{{ route('login') }}" class="font-bold text-blue-600 hover:text-blue-700 hover:underline">Masuk di sini</a>
            </p>
        </div>
    </form>

</div>
@endsection