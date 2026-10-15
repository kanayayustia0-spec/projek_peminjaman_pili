@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Peminjaman PILI</title>
    <!-- Memanggil Tailwind CSS agar tampilan langsung rapi -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen">
    
    <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-md border border-gray-100">
        
        <!-- Bagian Header / Logo -->
        <div class="text-center mb-8">
            <!-- Bisa diganti dengan tag <img> jika punya logo -->
            <div class="inline-flex items-center justify-center w-16 h-16 bg-emerald-100 rounded-full mb-4">
                <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-800">Sistem Peminjaman</h2>
            <p class="text-sm text-gray-500 mt-1">PILI Green Network</p>
        </div>

        <!-- Menampilkan Pesan Error jika login gagal -->
        @if($errors->has('login_id'))
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm" role="alert">
                <p class="font-medium text-sm">{{ $errors->first('login_id') }}</p>
            </div>
        @endif

        <!-- Form Login -->
        <form action="{{ route('login.proses') }}" method="POST">
            @csrf
            
            <div class="mb-5">
                <label for="login_id" class="block text-gray-700 text-sm font-semibold mb-2">Email atau Username</label>
                <input type="text" id="login_id" name="login_id" required 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                    placeholder="Masukkan email / username" value="{{ old('login_id') }}">
            </div>

            <div class="mb-6">
                <label for="password" class="block text-gray-700 text-sm font-semibold mb-2">Password</label>
                <input type="password" id="password" name="password" required 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                    placeholder="••••••••">
            </div>

            <button type="submit" 
                class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 px-4 rounded-lg focus:outline-none focus:ring-4 focus:ring-emerald-300 transition-colors duration-300 shadow-md">
                Masuk ke Sistem
            </button>
        </form>

        <div class="mt-8 text-center">
            <p class="text-xs text-gray-400">&copy; {{ date('Y') }} PILI Green Network. Hak Cipta Dilindungi.</p>
        </div>
        
    </div>

</body>
</html>
@endsection