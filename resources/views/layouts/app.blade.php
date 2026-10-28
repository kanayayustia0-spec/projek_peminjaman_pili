<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PILI Green Network - Sistem Peminjaman Aset</title>
    
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS (CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['"Plus Jakarta Sans"', 'sans-serif'] },
                    colors: {
                        pili: { primary: '#1A6BB8', dark: '#145391', light: '#E8F1F8' }
                    },
                    boxShadow: { soft: '0 4px 20px -2px rgba(0, 0, 0, 0.05)' }
                }
            }
        }
    </script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        html, body { overflow-x: hidden; width: 100%; -webkit-tap-highlight-color: transparent; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 antialiased font-sans flex flex-col min-h-screen overflow-x-hidden w-full">

    <!-- Top Navigation Bar -->
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm" x-data="{ profileDropdown: false, mobileMenu: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                
                <!-- Logo & Main Menu -->
                <div class="flex items-center">
                    <a href="{{ Auth::guard('admin')->check() ? route('admin.dashboard') : (Auth::check() ? route('dashboard') : route('login')) }}" class="flex-shrink-0 flex items-center gap-2.5 group">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo PILI Green Network" class="h-9 w-auto object-contain transition-transform group-hover:scale-105">
                        <span class="font-extrabold text-xl text-gray-900 tracking-tight">PILI<span class="text-pili-primary">Aset</span></span>
                    </a>
                    
                    <!-- Desktop Menu -->
                    <div class="hidden sm:ml-10 sm:flex sm:space-x-8">
                        @if(Auth::guard('admin')->check())
                            <!-- Menu Admin -->
                            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('admin.dashboard') ? 'border-pili-primary text-gray-900 font-bold' : 'border-transparent text-gray-500 hover:text-gray-900 hover:border-gray-300 font-medium' }} text-sm transition">Dashboard</a>
                            
                            @if(Auth::guard('admin')->user()->role == 'pengelola')
                                <a href="{{ route('admin.barang.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('admin.barang.*') ? 'border-pili-primary text-gray-900 font-bold' : 'border-transparent text-gray-500 hover:text-gray-900 hover:border-gray-300 font-medium' }} text-sm transition">Register Aset</a>
                                <a href="{{ route('admin.stock-opname.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('admin.stock-opname.*') ? 'border-pili-primary text-gray-900 font-bold' : 'border-transparent text-gray-500 hover:text-gray-900 hover:border-gray-300 font-medium' }} text-sm transition">Stock Opname</a>
                            @endif

                            @if(Auth::guard('admin')->user()->role == 'persetujuan')
                                <a href="{{ route('admin.persetujuan.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('admin.persetujuan.*') ? 'border-pili-primary text-pili-primary font-bold' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-900 font-medium' }} text-sm transition">Antrean Persetujuan</a>
                                <a href="{{ route('admin.register-admin') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('admin.register-admin') ? 'border-pili-primary text-pili-primary font-bold' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-900 font-medium' }} text-sm transition">+ Tambah Admin</a>
                            @endif

                            <a href="{{ route('admin.rekap') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('admin.rekap') ? 'border-pili-primary text-gray-900 font-bold' : 'border-transparent text-gray-500 hover:text-gray-900 hover:border-gray-300 font-medium' }} text-sm transition">Rekap Laporan</a>
                            
                        @elseif(Auth::check())
                            <!-- Menu Peminjam (User Logged In) -->
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('dashboard') ? 'border-pili-primary text-gray-900 font-extrabold' : 'border-transparent text-gray-900 hover:border-gray-300 font-semibold' }} text-sm transition">Beranda & Aset</a>
                            
                            <a href="{{ route('cara-kerja') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('cara-kerja') ? 'border-pili-primary text-gray-900 font-extrabold' : 'border-transparent text-gray-900 hover:border-gray-300 font-semibold' }} text-sm transition">Cara Kerja</a>
                            
                            <a href="{{ route('peminjaman.riwayat') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('peminjaman.riwayat') ? 'border-pili-primary text-gray-900 font-extrabold' : 'border-transparent text-gray-900 hover:border-gray-300 font-semibold' }} text-sm transition">Riwayat Peminjaman</a>
                            
                            <a href="{{ route('peminjaman.create') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-pili-primary hover:border-pili-primary hover:text-pili-dark text-sm font-extrabold transition">+ Pinjam Aset</a>
                        @else
                            <!-- Menu Tamu (Belum Login) -->
                            <a href="{{ route('cara-kerja') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('cara-kerja') ? 'border-pili-primary text-gray-900 font-extrabold' : 'border-transparent text-gray-900 hover:border-gray-300 font-semibold' }} text-sm transition">Cara Kerja</a>
                        @endif
                    </div>
                </div>

                <!-- User Profile (Desktop) -->
                <div class="hidden sm:ml-6 sm:flex sm:items-center">
                    @if(Auth::guard('admin')->check() || Auth::check())
                        <div class="relative">
                            <button @click="profileDropdown = !profileDropdown" @click.away="profileDropdown = false" class="flex items-center gap-2 bg-white border border-gray-200 rounded-full py-1 px-2 hover:bg-gray-50 transition shadow-sm">
                                <img class="h-8 w-8 rounded-full bg-pili-light p-1 object-cover" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::guard('admin')->user()?->username ?? Auth::user()?->name) }}&color=1A6BB8&background=E8F1F8" alt="Avatar">
                                <span class="text-sm font-bold text-gray-700 pr-2">
                                    {{ Auth::guard('admin')->user()?->username ?? Auth::user()?->name }}
                                </span>
                                <svg class="w-4 h-4 text-gray-400 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="profileDropdown" x-transition.opacity.duration.200ms class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg py-1 border border-gray-100 z-50" style="display: none;">
                                <div class="px-4 py-2 border-b border-gray-100">
                                    <p class="text-xs text-gray-500">Login sebagai</p>
                                    <p class="text-sm font-bold truncate">
                                        {{ Auth::guard('admin')->check() ? 'Admin ' . ucfirst(Auth::guard('admin')->user()?->role) : 'Staf / Peminjam' }}
                                    </p>
                                </div>
                                <form action="{{ route('logout') }}" method="POST" class="block w-full">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 hover:text-red-700 font-bold transition">
                                        Keluar (Logout)
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <!-- Tombol Login untuk Tamu -->
                        <a href="{{ route('login') }}" class="bg-pili-primary hover:bg-pili-dark text-white text-sm font-bold py-2 px-5 rounded-full shadow transition">Login Masuk</a>
                    @endif
                </div>

                <!-- Hamburger Button (Mobile) -->
                <div class="-mr-2 flex items-center sm:hidden">
                    <button @click="mobileMenu = !mobileMenu" type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': mobileMenu, 'inline-flex': !mobileMenu }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': !mobileMenu, 'inline-flex': mobileMenu }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

            </div> <!-- Penutup Flex h-16 -->
        </div> <!-- Penutup max-w-7xl -->

        <!-- Mobile Menu Panel -->
        <div x-show="mobileMenu" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="sm:hidden border-t border-gray-200 bg-white shadow-xl" style="display: none;">
            <div class="pt-2 pb-3 space-y-1">
                @if(Auth::guard('admin')->check())
                    <!-- Mobile Menu Admin -->
                    <a href="{{ route('admin.dashboard') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('admin.dashboard') ? 'border-pili-primary bg-pili-light text-pili-primary font-bold' : 'border-transparent text-gray-500 font-medium' }} text-base">Dashboard</a>
                    
                    @if(Auth::guard('admin')->user()->role == 'pengelola')
                        <a href="{{ route('admin.barang.index') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('admin.barang.*') ? 'border-pili-primary bg-pili-light text-pili-primary font-bold' : 'border-transparent text-gray-500 font-medium' }} text-base">Register Aset</a>
                        <a href="{{ route('admin.stock-opname.index') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('admin.stock-opname.*') ? 'border-pili-primary bg-pili-light text-pili-primary font-bold' : 'border-transparent text-gray-500 font-medium' }} text-base">Stock Opname</a>
                    @endif

                    @if(Auth::guard('admin')->user()->role == 'persetujuan')
                        <a href="{{ route('admin.persetujuan.index') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('admin.persetujuan.*') ? 'border-pili-primary bg-pili-light text-pili-primary font-bold' : 'border-transparent text-gray-500 font-medium' }} text-base">Antrean Persetujuan</a>
                        <a href="{{ route('admin.register-admin') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('admin.register-admin') ? 'border-pili-primary bg-pili-light text-pili-primary font-bold' : 'border-transparent text-gray-500 font-medium' }} text-base">+ Tambah Admin</a>
                    @endif
                    
                    <a href="{{ route('admin.rekap') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('admin.rekap') ? 'border-pili-primary bg-pili-light text-pili-primary font-bold' : 'border-transparent text-gray-500 font-medium' }} text-base">Rekap Laporan</a>

                @elseif(Auth::check())
                    <!-- Mobile Menu User -->
                    <a href="{{ route('dashboard') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('dashboard') ? 'border-pili-primary bg-pili-light text-pili-primary font-bold' : 'border-transparent text-gray-600 font-medium' }} text-base">Beranda & Aset</a>
                    <a href="{{ route('cara-kerja') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('cara-kerja') ? 'border-pili-primary bg-pili-light text-pili-primary font-bold' : 'border-transparent text-gray-600 font-medium' }} text-base">Cara Kerja</a>
                    <a href="{{ route('peminjaman.riwayat') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('peminjaman.riwayat') ? 'border-pili-primary bg-pili-light text-pili-primary font-bold' : 'border-transparent text-gray-600 font-medium' }} text-base">Riwayat Peminjaman</a>
                    <a href="{{ route('peminjaman.create') }}" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-pili-primary font-extrabold text-base">+ Pinjam Aset</a>
                @else
                    <!-- Mobile Menu Tamu -->
                    <a href="{{ route('cara-kerja') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('cara-kerja') ? 'border-pili-primary bg-pili-light text-pili-primary font-bold' : 'border-transparent text-gray-600 font-medium' }} text-base">Cara Kerja</a>
                @endif
            </div>

            <!-- Mobile Profile & Logout -->
            <div class="pt-4 pb-3 border-t border-gray-200 bg-gray-50">
                @if(Auth::guard('admin')->check() || Auth::check())
                    <div class="flex items-center px-4 mb-3">
                        <div class="flex-shrink-0">
                            <img class="h-10 w-10 rounded-full bg-pili-light p-1 object-cover" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::guard('admin')->user()?->username ?? Auth::user()?->name) }}&color=1A6BB8&background=E8F1F8" alt="Avatar">
                        </div>
                        <div class="ml-3">
                            <div class="text-base font-bold text-gray-800">{{ Auth::guard('admin')->user()?->username ?? Auth::user()?->name }}</div>
                            <div class="text-sm font-medium text-gray-500">{{ Auth::guard('admin')->check() ? 'Admin ' . ucfirst(Auth::guard('admin')->user()?->role) : 'Staf / Peminjam' }}</div>
                        </div>
                    </div>
                    <div class="mt-3 space-y-1">
                        <form action="{{ route('logout') }}" method="POST" class="block w-full">
                            @csrf
                            <button type="submit" class="block w-full text-left pl-3 pr-4 py-2 border-l-4 border-transparent text-red-600 font-bold hover:bg-red-50 text-base">
                                Keluar (Logout)
                            </button>
                        </form>
                    </div>
                @else
                    <div class="px-4 mt-2">
                        <a href="{{ route('login') }}" class="block text-center bg-pili-primary text-white font-bold py-2 rounded-lg">Login Masuk</a>
                    </div>
                @endif
            </div>
        </div>
    </nav>

    <!-- Main Content Area -->
    <main class="flex-grow w-full">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-auto">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 text-center text-sm text-gray-500 font-medium">
            &copy; {{ date('Y') }} Yayasan PILI Green Network. Sistem Manajemen Aset.
        </div>
    </footer>

</body>
</html>