<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // Menampilkan halaman login
    public function showLoginForm()
    {
        // Jika sudah login sebagai admin, arahkan ke dashboard admin
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }
        
        // Jika sudah login sebagai staf/user, arahkan ke dashboard user
        if (Auth::guard('web')->check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    // Memproses form login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string', // Menampung input dari form (bisa email atau username)
            'password' => 'required|string',
            'login_type' => 'required|in:staf,admin'
        ]);

        $credentials = ['password' => $request->password];

        // 1. Jika login sebagai Admin: Tabel admins HANYA punya kolom username
        if ($request->login_type === 'admin') {
            $credentials['username'] = $request->email; // Input form dicocokkan ke kolom 'username' tabel admins

            if (Auth::guard('admin')->attempt($credentials, $request->filled('remember'))) {
                $request->session()->regenerate();
                return redirect()->intended(route('admin.dashboard'));
            }
        } 
        // 2. Jika login sebagai Staf (User): Tabel users bisa pakai Email atau Username
        else {
            $field = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
            $credentials[$field] = $request->email;

            if (Auth::guard('web')->attempt($credentials, $request->filled('remember'))) {
                $request->session()->regenerate();
                return redirect()->intended(route('dashboard'));
            }
        }

        return back()->withErrors([
            'email' => 'Kombinasi akun, password, atau pilihan jenis akun salah.',
        ])->withInput($request->except('password'));
    }
    // Menampilkan halaman pendaftaran (Register) Staf
    public function showRegisterForm()
    {
        if (Auth::guard('web')->check() || Auth::guard('admin')->check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.register');
    }

    // Memproses pendaftaran Staf baru
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Simpan data ke database dengan password ter-hash aman
        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'jabatan' => $request->jabatan ?? 'Staff',
            'divisi_program' => $request->divisi_program ?? 'Umum',
            'no_hp' => $request->no_hp ?? '-',
        ]);

        // Otomatis login setelah registrasi berhasil
        Auth::guard('web')->login($user);

        return redirect()->route('dashboard')->with('success', 'Akun berhasil didaftarkan dan Anda telah masuk.');
    }

    // Memproses Logout
    public function logout(Request $request)
    {
        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
        } elseif (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }


    // Menampilkan form register khusus admin
    // Menampilkan form register khusus admin
    public function showAdminRegisterForm()
    {
        return view('auth.register-admin');
    }

    // Memproses pendaftaran admin baru secara nyata
    public function registerAdmin(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:admins,username',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|string|in:pengelola,persetujuan',
        ]);

        \App\Models\Admin::create([
            'username' => $request->username,
            'password' => Hash::make($request->password), // Password di-hash aman agar langsung bisa login
            'role' => $request->role,
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Akun admin baru berhasil ditambahkan!');
    }
    
}