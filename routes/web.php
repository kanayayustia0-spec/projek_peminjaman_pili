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
// ROUTE AUTENTIKASI (LOGIN, LOGOUT, REGISTER)
// ==========================================
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// ==========================================
// HALAMAN INFORMASI (Cara Kerja - Tanpa Login)
// ==========================================
Route::get('/cara-kerja', [PeminjamanController::class, 'caraKerja'])->name('cara-kerja');

// ==========================================
// ROUTES UNTUK PEMINJAM (USER) - WAJIB LOGIN
// ==========================================
Route::middleware(['auth'])->group(function () {
    
    // Dashboard & Beranda User
    // Dashboard User / Beranda
    Route::get('/dashboard', [PeminjamanController::class, 'index'])->name('dashboard');

    // Katalog Barang 
    Route::get('/katalog-barang', [PeminjamanController::class, 'katalog'])->name('peminjaman.katalog');

    // Riwayat Peminjaman
    Route::get('/riwayat-peminjaman', [PeminjamanController::class, 'riwayat'])->name('peminjaman.riwayat');

    // Alur Peminjaman
    Route::get('/peminjaman/buat', [PeminjamanController::class, 'create'])->name('peminjaman.create');
    Route::post('/peminjaman', [PeminjamanController::class, 'store'])->name('peminjaman.store');
    Route::get('/peminjaman/export-excel', [PeminjamanController::class, 'exportExcel'])->name('peminjaman.export');
    Route::get('/peminjaman/{peminjaman}', [PeminjamanController::class, 'show'])->name('peminjaman.show')->withoutMiddleware(['auth'])->middleware(['auth:web,admin']);
    Route::get('/peminjaman/{peminjaman}/struk', [PeminjamanController::class, 'struk'])->name('peminjaman.struk')->withoutMiddleware(['auth'])->middleware(['auth:web,admin']);

    // Terima Aset / Konfirmasi Ambil di Gudang
    Route::post('/peminjaman/{peminjaman}/terima', [PeminjamanController::class, 'terimaAset'])->name('peminjaman.terima');
    Route::post('/peminjaman/{peminjaman}/ambil', [PeminjamanController::class, 'konfirmasiAmbil'])->name('peminjaman.ambil');

    // Pengembalian Aset (Sinkron dengan formPengembalian di Controller)
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
    Route::get('/laporan-peminjaman/export-excel', [DashboardController::class, 'exportExcel'])->name('rekap.export');

    // Aksi Persetujuan Bersama (Bisa diakses Pengelola maupun Persetujuan)
    Route::post('/peminjaman/{peminjaman}/approve-langsung', [ApprovalController::class, 'approveLangsung'])->name('approve.langsung');
    Route::post('/peminjaman/{peminjaman}/reset-approval', [ApprovalController::class, 'resetApproval'])->name('approve.reset');

    // Aset & Inventaris (Bisa diakses kedua role admin)
    Route::resource('barang', BarangController::class);

    // Menu Stock Opname (Bisa diakses kedua role admin)
    Route::get('/stock-opname', [StockOpnameController::class, 'index'])->name('stock-opname.index');
    Route::get('/stock-opname/create', [StockOpnameController::class, 'create'])->name('stock-opname.create');
    Route::post('/stock-opname', [StockOpnameController::class, 'store'])->name('stock-opname.store');
    Route::post('/stock-opname/{id}', [StockOpnameController::class, 'update'])->name('stock-opname.update');

    // BASTA (Serah Terima)
    Route::get('/peminjaman/{peminjaman}/basta', [BastaController::class, 'create'])->name('basta.create');
    Route::post('/peminjaman/{peminjaman}/basta', [BastaController::class, 'store'])->name('basta.store');

    // Verifikasi Pengembalian
    Route::get('/peminjaman/{peminjaman}/verifikasi-kembali', [ApprovalController::class, 'formVerifikasiPengembalian'])->name('pengembalian.verifikasi.form');
    Route::post('/peminjaman/{peminjaman}/verifikasi-kembali', [ApprovalController::class, 'verifikasiPengembalian'])->name('pengembalian.verifikasi');

    // Laporan Kerusakan & Keputusan
    Route::get('/peminjaman/{peminjaman}/laporan-masalah', [LaporanController::class, 'create'])->name('laporan.create');
    Route::post('/peminjaman/{peminjaman}/laporan-masalah', [LaporanController::class, 'store'])->name('laporan.store');
    Route::post('/laporan/{laporan}/keputusan', [ApprovalController::class, 'keputusanLaporan'])->name('laporan.keputusan');

    // Approval Tahap 1 & Tahap 2
    Route::post('/peminjaman/{peminjaman}/approve-pengelola', [ApprovalController::class, 'approvePengelola'])->name('approve.pengelola');
    Route::post('/peminjaman/{peminjaman}/approve-persetujuan', [ApprovalController::class, 'approvePersetujuan'])->name('approve.persetujuan');

    // Daftar Antrean Persetujuan & Detail
    Route::get('/persetujuan', [PersetujuanController::class, 'index'])->name('persetujuan.index');
    Route::get('/persetujuan/{id}', [PersetujuanController::class, 'show'])->name('persetujuan.show');
    Route::post('/persetujuan/{id}/approve', [PersetujuanController::class, 'approve'])->name('persetujuan.approve');
    Route::post('/persetujuan/{id}/reject', [PersetujuanController::class, 'reject'])->name('persetujuan.reject');

    // Tambah Admin
    Route::get('/register-admin', [AuthController::class, 'showAdminRegisterForm'])->name('register-admin');
    Route::post('/register-admin', [AuthController::class, 'registerAdmin'])->name('register-admin.post');

});