<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DirekturController;

/*
|--------------------------------------------------------------------------
| Web Routes - Aplikasi SEDEKAH
|--------------------------------------------------------------------------
| Seluruh rute diatur secara eksplisit untuk mendukung sistem autentikasi 
| manual dan manajemen logistik inventaris yayasan.
*/

// --- PENGALIHAN AWAL ---
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/kunjungan/form', function () {
    return "Halaman Form Kunjungan & Donasi (Segera Hadir)";
})->name('donasi.form');

// --- SISTEM AUTENTIKASI MANUAL ---
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('login.proses');
});

// Logout harus melalui middleware auth agar secure
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// --- AKSES INTERNAL TERPROTEKSI ---
Route::middleware(['auth'])->group(function () {

    /**
     * Logic pengalihan dashboard berdasarkan role.
     * Menggunakan pengecekan yang lebih fleksibel untuk 'admin' atau 'administrator'.
     */
    Route::get('/dashboard', function () {
        $role = auth()->user()->role;
        
        if (in_array($role, ['admin', 'administrator', 'petugas'])) { 
            return redirect()->route('admin.dashboard');
        } elseif ($role === 'direktur') {
            return redirect()->route('direktur.dashboard');
        }
        
        return redirect('/')->with('error', 'Role tidak dikenali oleh sistem.');
    })->name('dashboard');

    // --- GRUP RUTE ADMINISTRATOR / PETUGAS ---
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
        Route::get('/verifikasi', [AdminController::class, 'verifikasi'])->name('verifikasi');
        Route::get('/distribusi', [AdminController::class, 'distribusi'])->name('distribusi');
        Route::get('/riwayat', [AdminController::class, 'riwayat'])->name('riwayat');
        Route::get('/kategori', [AdminController::class, 'kategori'])->name('kategori');
        
        // --- REVISI & PENYEMPURNAAN UTAMA: GRUP RUTE KELOLA DATA DONATUR ---
        // Menyelaraskan name, prefix, dan parameter {id_donatur} agar klop dengan AdminController
        Route::prefix('donatur')->name('donatur.')->group(function () {
            Route::get('/', [AdminController::class, 'donatur'])->name('index');
            Route::get('/show/{id_donatur}', [AdminController::class, 'donatur_show'])->name('show');
            Route::delete('/destroy/{id_donatur}', [AdminController::class, 'donatur_destroy'])->name('destroy');
        });
        
        // PERBAIKAN: Menyelaraskan name route menjadi 'audit_log.index' agar klop dengan pemanggilan view dan layout sidebar admin
        Route::get('/audit-log', [AdminController::class, 'audit'])->name('audit_log.index');

        // --- TAMBAHAN BARU & PENYEMPURNAAN LENGKAP: RUTE MANAJEMEN USER (AKTOR ADMINISTRATOR) ---
        // Melengkapi seluruh rute aksi form agar desain halaman CRUD tidak pecah/melompat ke role lain
        Route::prefix('manajemen-user')->name('manajemen_user.')->group(function () {
            Route::get('/', [AdminController::class, 'user_index'])->name('index');
            Route::get('/create', [AdminController::class, 'user_create'])->name('create');
            
            // PERBAIKAN UTAMA: Mengubah URL '/store' menjadi '/' untuk mengamankan fallback GET 405 saat validasi gagal
            Route::post('/', [AdminController::class, 'user_store'])->name('store');
            
            // DISESUAIKAN: Parameter diselaraskan dari {id} menjadi {id_user} agar klop dengan Controller
            Route::get('/show/{id_user}', [AdminController::class, 'user_show'])->name('show');
            Route::get('/edit/{id_user}', [AdminController::class, 'user_edit'])->name('edit');
            Route::put('/update/{id_user}', [AdminController::class, 'user_update'])->name('update');
            Route::delete('/destroy/{id_user}', [AdminController::class, 'user_destroy'])->name('destroy');
        });
    });

    // --- GRUP RUTE DIREKTUR ---
    Route::prefix('direktur')->name('direktur.')->group(function () {
        Route::get('/dashboard', [DirekturController::class, 'index'])->name('dashboard');
        
        // --- PENYEMPURNAAN RUTE MONITORING DONATUR ---
        Route::prefix('monitoring-donatur')->name('riwayat_donatur.')->group(function () {
            Route::get('/', [DirekturController::class, 'riwayat_donatur'])->name('index');
            // DISESUAIKAN KLOP DB: Parameter diselaraskan menjadi {id_user} agar seragam mencari entitas profil user
            Route::get('/show/{id_user}', [DirekturController::class, 'donatur_show'])->name('show');
        });
        
        // --- PENYEMPURNAAN RUTE KEUANGAN (MODEL: DonasiUang) ---
        Route::prefix('monitoring-keuangan')->name('keuangan.')->group(function () {
            Route::get('/', [DirekturController::class, 'keuangan'])->name('index'); 
            Route::get('/export', [DirekturController::class, 'export_donasi_uang'])->name('export');
        });

        // --- PENYEMPURNAAN RUTE LOGISTIK (MODEL: DonasiBarang) ---
        Route::prefix('monitoring-logistik')->name('logistik.')->group(function () {
            Route::get('/', [DirekturController::class, 'logistik'])->name('index'); 
            Route::get('/export', [DirekturController::class, 'export_donasi_barang'])->name('export');
        });

        // Rute Audit System (AuditLog)
        Route::get('/audit-system', [DirekturController::class, 'audit'])->name('audit');
        
        // Rute Laporan Umum
        Route::get('/laporan-umum', [DirekturController::class, 'laporan'])->name('laporan');

        // --- MANAJEMEN USER (EKSLUSIF DIREKTUR) ---
        Route::prefix('manajemen-user')->name('manajemen_user.')->group(function () {
            Route::get('/', [DirekturController::class, 'user_index'])->name('index');
            Route::get('/create', [DirekturController::class, 'user_create'])->name('create');
            
            // PERBAIKAN UTAMA: Mengubah URL '/store' menjadi '/' untuk mengamankan fallback GET 405 saat validasi gagal
            Route::post('/', [DirekturController::class, 'user_store'])->name('store');
            
            // DISESUAIKAN: Parameter diselaraskan dari {id} menjadi {id_user} agar klop dengan Controller Eksklusif Direktur
            Route::get('/show/{id_user}', [DirekturController::class, 'user_show'])->name('show');
            Route::get('/edit/{id_user}', [DirekturController::class, 'user_edit'])->name('edit');
            Route::put('/update/{id_user}', [DirekturController::class, 'user_update'])->name('update');
            Route::delete('/destroy/{id_user}', [DirekturController::class, 'user_destroy'])->name('destroy');
        });
    });
});