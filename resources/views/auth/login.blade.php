<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Peminjaman Yayasan PILI</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['"Plus Jakarta Sans"', 'sans-serif'] },
                    colors: {
                        pili: { primary: '#1A6BB8', dark: '#145391' }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8 font-sans">

    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <!-- Logo -->
        <div class="flex justify-center mb-4">
            <div class="w-12 h-12 bg-pili-primary rounded-xl flex items-center justify-center text-white font-bold text-3xl shadow-lg">P</div>
        </div>
        <h2 class="text-center text-3xl font-extrabold text-gray-900 tracking-tight">
            Sistem Aset PILI
        </h2>
        <p class="mt-2 text-center text-sm text-gray-600">
            Silakan login untuk mengakses portal peminjaman
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow sm:rounded-2xl sm:px-10 border border-gray-100">
            
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 text-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" class="space-y-6">
                @csrf
                
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Masuk Sebagai</label>
                    <select name="login_sebagai" class="w-full border-gray-300 rounded-lg p-3 bg-gray-50 focus:ring-pili-primary focus:border-pili-primary border" required>
                        <option value="peminjam">Peminjam (Staf / Relawan)</option>
                        <option value="admin">Admin (Pengelola / Persetujuan)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Email / Username</label>
                    <input type="text" name="identifier" required placeholder="Masukkan email atau username" class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:border-pili-primary focus:ring-1 focus:ring-pili-primary transition">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Password</label>
                    <input type="password" name="password" required placeholder="••••••••" class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:border-pili-primary focus:ring-1 focus:ring-pili-primary transition">
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-pili-primary hover:bg-pili-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pili-primary transition">
                        Masuk ke Sistem
                    </button>
                </div>
            </form>
            
        </div>
    </div>

</body>
</html>