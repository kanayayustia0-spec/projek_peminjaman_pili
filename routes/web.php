<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ApprovalController;
use App\Http\Controllers\Admin\BarangController;
use App\Http\Controllers\Admin\BastaController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\StockOpnameController;
use App\Http\Controllers\Admin\PersetujuanController;

// ==========================================
// ROUTE AUTENTIKASI (LOGIN & LOGOUT)
// ==========================================
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ==========================================
// HALAMAN INFORMASI (Cara Kerja)
// ==========================================
Route::get('/cara-kerja', [PeminjamanController::class, 'caraKerja'])->name('cara-kerja');

// ==========================================
// ROUTES UNTUK PEMINJAM (USER)
// ==========================================
Route::middleware(['auth'])->group(function () {
    
    // Dashboard User / Beranda
    Route::get('/dashboard', [PeminjamanController::class, 'index'])->name('dashboard');

    // Katalog Barang 
    Route::get('/katalog-barang', [PeminjamanController::class, 'katalog'])->name('peminjaman.katalog');

    // Riwayat Peminjaman
    Route::get('/riwayat-peminjaman', [PeminjamanController::class, 'riwayat'])->name('peminjaman.riwayat');

    // Alur Peminjaman
    Route::get('/peminjaman/buat', [PeminjamanController::class, 'create'])->name('peminjaman.create');
    Route::post('/peminjaman', [PeminjamanController::class, 'store'])->name('peminjaman.store');
    Route::get('/peminjaman/{peminjaman}', [PeminjamanController::class, 'show'])->name('peminjaman.show');

    // Terima Aset (BASTA)
    Route::post('/peminjaman/{peminjaman}/terima', [PeminjamanController::class, 'terimaAset'])->name('peminjaman.terima');

    // Pengembalian Aset
    Route::get('/peminjaman/{peminjaman}/pengembalian', [PeminjamanController::class, 'formPengembalian'])->name('peminjaman.pengembalian.form');
    Route::post('/peminjaman/{peminjaman}/pengembalian', [PeminjamanController::class, 'prosesPengembalian'])->name('peminjaman.pengembalian.proses');

});

// ==========================================
// ROUTES UNTUK ADMIN (PENGELOLA & PERSETUJUAN)
// ==========================================
Route::prefix('admin')->name('admin.')->middleware(['auth:admin'])->group(function () {

    // Dashboard & Rekap (Bisa diakses kedua role admin)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/laporan-peminjaman', [DashboardController::class, 'rekapPeminjaman'])->name('rekap');

    // ----------------------------------------------------
    // KHUSUS ADMIN PENGELOLA
    // ----------------------------------------------------
    Route::middleware('can:is-pengelola')->group(function () {
        
        // Kelola Barang
        Route::resource('barang', BarangController::class);

        // Approval Tahap 1
        Route::post('/peminjaman/{peminjaman}/approve-pengelola', [ApprovalController::class, 'approvePengelola'])->name('approve.pengelola');

        // BASTA (Serah Terima)
        Route::get('/peminjaman/{peminjaman}/basta', [BastaController::class, 'create'])->name('basta.create');
        Route::post('/peminjaman/{peminjaman}/basta', [BastaController::class, 'store'])->name('basta.store');

        // Verifikasi Pengembalian
        Route::get('/peminjaman/{peminjaman}/verifikasi-kembali', [ApprovalController::class, 'formVerifikasiPengembalian'])->name('pengembalian.verifikasi.form');
        Route::post('/peminjaman/{peminjaman}/verifikasi-kembali', [ApprovalController::class, 'verifikasiPengembalian'])->name('pengembalian.verifikasi');

        // Laporan Kerusakan
        Route::get('/peminjaman/{peminjaman}/laporan-masalah', [LaporanController::class, 'create'])->name('laporan.create');
        Route::post('/peminjaman/{peminjaman}/laporan-masalah', [LaporanController::class, 'store'])->name('laporan.store');

        // Menu Stock Opname
        Route::get('/stock-opname', [StockOpnameController::class, 'index'])->name('stock-opname.index');
        Route::get('/stock-opname/create', [StockOpnameController::class, 'create'])->name('stock-opname.create');
        Route::post('/stock-opname', [StockOpnameController::class, 'store'])->name('stock-opname.store');
        Route::post('/stock-opname/{id}', [StockOpnameController::class, 'update'])->name('stock-opname.update');

    });

    // ----------------------------------------------------
    // KHUSUS ADMIN PERSETUJUAN
    // ----------------------------------------------------
    Route::middleware('can:is-persetujuan')->group(function () {
        
        // Approval Tahap 2
        Route::post('/peminjaman/{peminjaman}/approve-persetujuan', [ApprovalController::class, 'approvePersetujuan'])->name('approve.persetujuan');
        
        // Daftar Antrean Persetujuan
        Route::get('/persetujuan', [PersetujuanController::class, 'index'])->name('persetujuan.index');
        Route::get('/persetujuan/{id}', [PersetujuanController::class, 'show'])->name('persetujuan.show');
        Route::post('/persetujuan/{id}/approve', [PersetujuanController::class, 'approve'])->name('persetujuan.approve');
        Route::post('/persetujuan/{id}/reject', [PersetujuanController::class, 'reject'])->name('persetujuan.reject');

        // Keputusan atas Laporan Kerusakan
        Route::post('/laporan/{laporan}/keputusan', [ApprovalController::class, 'keputusanLaporan'])->name('laporan.keputusan');

    });

});