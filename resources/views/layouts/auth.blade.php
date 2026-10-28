<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Sistem Peminjaman Aset - Yayasan PILI' }}</title>
    
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS with custom configuration -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['"Plus Jakarta Sans"', 'sans-serif'] },
                    colors: {
                        pili: {
                            blue: '#19549f',
                            'blue-dark': '#103d77',
                            'blue-light': '#e9f1fa',
                            green: '#48bb31',
                            'green-dark': '#359325',
                            'green-light': '#edf8ec',
                        }
                    },
                    boxShadow: {
                        'modern': '0 20px 50px -12px rgba(25, 84, 159, 0.15)',
                        'modern-lg': '0 25px 60px -15px rgba(25, 84, 159, 0.22)',
                        'glow-blue': '0 0 40px rgba(25, 84, 159, 0.15)',
                        'glow-green': '0 0 40px rgba(72, 187, 49, 0.15)'
                    }
                }
            }
        }
    </script>
    
    @if(file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        [x-cloak] { display: none !important; }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(18px) scale(0.99);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

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
<body class="min-h-full bg-gradient-to-br from-slate-100 via-sky-50/50 to-emerald-50/40 font-sans text-gray-900 antialiased flex items-center justify-center p-4 sm:p-6 lg:p-8 relative overflow-x-hidden selection:bg-pili-blue selection:text-white">

    <!-- Decorative Gradient Glow Orbs -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none -z-10">
        <div class="absolute -top-32 -left-32 w-96 h-96 bg-pili-blue/15 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-pili-green/15 rounded-full blur-3xl"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-gradient-to-tr from-blue-300/10 via-emerald-200/10 to-transparent rounded-full blur-3xl"></div>
    </div>

    <!-- Main Container -->
    <main class="relative z-10 w-full max-w-md my-auto animate-fade-in-up">
        @yield('content')
    </main>

</body>
</html>