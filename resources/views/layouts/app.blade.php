<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PILI Green Network- Sistem Peminjaman Aset</title>
    
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
</head>
<body class="bg-gray-50 text-gray-800 antialiased font-sans flex flex-col min-h-screen">

    <!-- Top Navigation Bar -->
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm" x-data="{ profileDropdown: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                
                <!-- Logo & Main Menu -->
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center gap-2">
                        <div class="w-8 h-8 bg-pili-primary rounded-lg flex items-center justify-center text-white font-bold text-xl">P</div>
                        <span class="font-extrabold text-xl text-gray-900 tracking-tight">PILI<span class="text-pili-primary">Aset</span></span>
                    </div>
                    
                    <!-- Desktop Menu -->
                    <div class="hidden sm:ml-10 sm:flex sm:space-x-8">
                     @if(Auth::guard('admin')->check())
                            <!-- Menu Admin -->
                            <a href="{{ route('admin.dashboard') }}" class="text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('admin.dashboard') ? 'border-pili-primary' : 'border-transparent' }} text-sm font-semibold">Dashboard</a>
                            
                            @if(Auth::guard('admin')->user()->role == 'pengelola')
                                <a href="{{ route('admin.barang.index') }}" class="text-gray-500 hover:text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-gray-300 text-sm font-medium transition">Register Aset</a>
                                <a href="{{ route('admin.stock-opname.index') }}" class="text-gray-500 hover:text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-gray-300 text-sm font-medium transition">Stock Opname</a>
                            @endif

                            <!-- TAMBAHAN MENU ADMIN PERSETUJUAN -->
                            @if(Auth::guard('admin')->user()->role == 'persetujuan')
                                <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('admin.persetujuan.*') ? 'border-pili-primary text-pili-primary font-bold' : 'border-transparent hover:border-gray-300 font-medium' }} text-sm transition">Antrean Persetujuan</a>
                            @endif

                            <a href="{{ route('admin.rekap') }}" class="text-gray-500 hover:text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-gray-300 text-sm font-medium transition">Rekap Laporan</a>
                            
                           @else
                            <!-- Menu Peminjam -->
                            <a href="{{ route('dashboard') }}" class="text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('dashboard') ? 'border-pili-primary font-extrabold' : 'border-transparent hover:border-gray-300 font-semibold' }} text-sm transition">Beranda & Aset</a>
                            
                            <a href="{{ route('cara-kerja') }}" class="text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('cara-kerja') ? 'border-pili-primary font-extrabold' : 'border-transparent hover:border-gray-300 font-semibold' }} text-sm transition">Cara Kerja</a>
                            
                            <a href="{{ route('peminjaman.riwayat') }}" class="text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('peminjaman.riwayat') ? 'border-pili-primary font-extrabold' : 'border-transparent hover:border-gray-300 font-semibold' }} text-sm transition">Riwayat Peminjaman</a>
                            
                            <a href="{{ route('peminjaman.create') }}" class="text-pili-primary inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-pili-primary text-sm font-extrabold transition">+ Pinjam Aset</a>
                        @endif
                    
                    </div>
                </div>

                <!-- User Profile & Logout -->
                <div class="hidden sm:ml-6 sm:flex sm:items-center">
                    <div class="relative">
                        <button @click="profileDropdown = !profileDropdown" @click.away="profileDropdown = false" class="flex items-center gap-2 bg-white border border-gray-200 rounded-full py-1 px-2 hover:bg-gray-50 transition shadow-sm">
                            <img class="h-8 w-8 rounded-full bg-pili-light p-1 object-cover" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::guard('admin')->user()?->username ?? Auth::user()?->name ?? 'Guest') }}&color=1A6BB8&background=E8F1F8" alt="Avatar">
                            <span class="text-sm font-bold text-gray-700 pr-2">
                                {{ Auth::guard('admin')->user()?->username ?? Auth::user()?->name ?? 'Guest' }}
                            </span>
                            <svg class="w-4 h-4 text-gray-400 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="profileDropdown" x-transition.opacity.duration.200ms class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg py-1 border border-gray-100 z-50" style="display: none;">
                            <div class="px-4 py-2 border-b border-gray-100">
                                <p class="text-xs text-gray-500">Login sebagai</p>
                                <p class="text-sm font-bold truncate">
                                    {{ Auth::guard('admin')->check() ? 'Admin ' . ucfirst(Auth::guard('admin')->user()?->role) : 'Peminjam' }}
                                </p>
                            </div>
                            <form action="{{ route('logout') }}" method="POST" class="block w-full">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 hover:text-red-700 font-semibold transition">
                                    Keluar (Logout)
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div> <!-- Penutup Flex h-16 -->
        </div> <!-- Penutup max-w-7xl -->
    </nav>

    <!-- Main Content Area -->
    <main class="flex-grow w-full">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-auto">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 text-center text-sm text-gray-500">
            &copy; {{ date('Y') }}PILI Green Network. Sistem Peminjaman Aset v1.0.
        </div>
    </footer>

</body>
</html>