<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Menampilkan halaman login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Memproses form login
    public function login(Request $request)
    {
        $request->validate([
            'identifier' => 'required|string', // Bisa Email (User) atau Username (Admin)
            'password' => 'required|string',
            'login_sebagai' => 'required|in:peminjam,admin'
        ]);

        // Jika login sebagai Admin
        if ($request->login_sebagai === 'admin') {
            if (Auth::guard('admin')->attempt(['username' => $request->identifier, 'password' => $request->password])) {
                $request->session()->regenerate();
                return redirect()->route('admin.dashboard');
            }
        } 
        // Jika login sebagai Peminjam (User)
        else {
            if (Auth::guard('web')->attempt(['email' => $request->identifier, 'password' => $request->password])) {
                $request->session()->regenerate();
                return redirect()->route('dashboard'); // Arahkan ke dashboard user
            }
        }

        return back()->withErrors([
            'identifier' => 'Kombinasi akun, password, atau pilihan peran salah.',
        ]);
    }

    // Memproses Logout
    public function logout(Request $request)
    {
        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
        } else {
            Auth::guard('web')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}