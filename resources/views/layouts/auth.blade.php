<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Sistem Peminjaman Aset Yayasan PILI' }}</title>
    
    <!-- Tailwind CSS & Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        [x-cloak] { display: none !important; }

        /* Animasi Masuk: Fade-in & Slide-up */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        /* Dukungan Prefers-Reduced-Motion untuk Aksesibilitas */
        @media (prefers-reduced-motion: reduce) {
            .animate-fade-in-up, 
            .transition-all,
            .transform {
                animation: none !important;
                transition: none !important;
                transform: none !important;
            }
        }
    </style>
</head>
<body class="h-full bg-gradient-to-br from-slate-100 via-blue-50/50 to-indigo-100/60 font-sans text-gray-900 antialiased flex items-center justify-center p-4 sm:p-6 lg:p-8 overflow-y-auto selection:bg-blue-600 selection:text-white">

    <!-- Aksen Cahaya / Dekorasi Latar Belakang yang Sangat Halus -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-gradient-to-tr from-blue-400/10 to-indigo-400/10 rounded-full blur-3xl"></div>
    </div>

    <!-- Main Container: Centered Floating Card Wrapper -->
    <main class="relative z-10 w-full max-w-md my-auto animate-fade-in-up">
        @yield('content')
    </main>

</body>
</html>