<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DirekturController;
use App\Http\Controllers\DonaturController; 

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

// Aktor Donatur: Akses Publik (Form Kunjungan & Donasi via QR Code)
Route::get('/kunjungan/form', [DonaturController::class, 'createKunjungan'])->name('donatur.kunjungan.create');
Route::post('/kunjungan/simpan', [DonaturController::class, 'storeKunjungan'])->name('donatur.kunjungan.store');

// --- SISTEM AUTENTIKASI ---

// Autentikasi Internal (Admin & Direktur)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('login.proses');
});

// --- GUEST (TAMU) ---
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('login.proses');
    Route::get('/donatur/login', [DonaturController::class, 'showLogin'])->name('donatur.login');
    Route::post('/donatur/login', [DonaturController::class, 'login'])->name('donatur.login.proses');
    Route::get('/donatur/signup', [DonaturController::class, 'showSignup'])->name('donatur.signup');
    Route::post('/donatur/signup', [DonaturController::class, 'signup'])->name('donatur.signup.proses');
});

// --- AKSES DONATUR (TERPROTEKSI) ---
Route::middleware(['auth:donatur', 'no-cache'])->prefix('donatur')->name('donatur.')->group(function () {
    Route::get('/dashboard', [DonaturController::class, 'dashboard'])->name('dashboard');
    // ... (rute donatur lainnya tetap di sini)
});

// --- AKSES INTERNAL (DIREKTUR & ADMIN) ---
// Perhatikan: Kita bungkus dengan 'no-cache' dan middleware role
Route::middleware(['auth', 'no-cache'])->group(function () {
    
    // Role Direktur
    Route::middleware(['checkrole:direktur'])->prefix('direktur')->name('direktur.')->group(function () {
        Route::get('/dashboard', [DirekturController::class, 'index'])->name('dashboard');
        // ... (rute direktur lainnya)
    });

    // Role Admin
    Route::middleware(['checkrole:admin,administrator'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
        // ... (rute admin lainnya)
    });
});

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// --- AKSES INTERNAL TERPROTEKSI ---
Route::middleware(['auth'])->group(function () {

    /**
     * Logic pengalihan dashboard berdasarkan role.
     * Hanya menangani aktor: Administrator, Direktur, dan Donatur.
     */
    Route::get('/dashboard', function () {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $role = $user->role;
        
        // Cek khusus untuk Aktor Internal
        if (in_array($role, ['admin', 'administrator'])) { 
            return redirect()->route('admin.dashboard');
        } elseif ($role === 'direktur') {
            return redirect()->route('direktur.dashboard');
        } 
        
        // Cek khusus untuk Aktor Eksternal
        elseif ($role === 'donatur') {
            return redirect()->route('donatur.dashboard');
        }
        
        return redirect('/')->with('error', 'Role tidak dikenali oleh sistem.');
    })->name('dashboard');

    // --- GRUP RUTE ADMINISTRATOR ---
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
        
        // --- MENU VERIFIKASI DONASI ---
        Route::prefix('verifikasi')->name('verifikasi.')->group(function () {
            Route::get('/', [AdminController::class, 'verifikasi_index'])->name('index');
            Route::post('/update/{id_donasi}', [AdminController::class, 'verifikasi_update'])->name('update');
        });
        
        // --- GRUP RUTE MASTER KATEGORI BARANG ---
        Route::prefix('kategori-barang')->name('kategori_barang.')->group(function () {
            Route::get('/', [AdminController::class, 'kategori'])->name('index');
            Route::get('/create', [AdminController::class, 'kategoriCreate'])->name('create');
            Route::post('/', [AdminController::class, 'kategoriStore'])->name('store');
            Route::get('/edit/{id_kategori_barang}', [AdminController::class, 'kategoriEdit'])->name('edit');
            Route::put('/update/{id_kategori_barang}', [AdminController::class, 'kategoriUpdate'])->name('update');
            Route::delete('/destroy/{id_kategori_barang}', [AdminController::class, 'kategoriDestroy'])->name('destroy');
        });
        
        // --- GRUP RUTE KELOLA RIWAYAT DONASI KESELURUHAN ---
        Route::prefix('riwayat-donasi')->name('riwayat_donasi.')->group(function () {
            Route::get('/', [AdminController::class, 'riwayat'])->name('index');
            Route::get('/export', [AdminController::class, 'export'])->name('export');
            Route::get('/show/{id_donasi}', [AdminController::class, 'riwayat_show'])->name('show');
            Route::put('/update-status/{id_donasi}', [AdminController::class, 'riwayat_update_status'])->name('update_status');
        });
        
        // --- GRUP RUTE KELOLA DATA DONATUR ---
        Route::prefix('donatur')->name('donatur.')->group(function () {
            Route::get('/', [AdminController::class, 'donatur'])->name('index');
            Route::get('/create', [AdminController::class, 'donatur_create'])->name('create');
            Route::post('/', [AdminController::class, 'donatur_store'])->name('store');
            Route::get('/show/{id_donatur}', [AdminController::class, 'donatur_show'])->name('show');
            Route::get('/edit/{id_donatur}', [AdminController::class, 'donatur_edit'])->name('edit');
            Route::put('/update/{id_donatur}', [AdminController::class, 'donatur_update'])->name('update');
            Route::delete('/destroy/{id_donatur}', [AdminController::class, 'donatur_destroy'])->name('destroy');
        });
        
        Route::get('/audit-log', [AdminController::class, 'audit'])->name('audit_log.index');
        Route::get('/rating-kunjungan', [AdminController::class, 'rating_kunjungan'])->name('rating_kunjungan.index');
        Route::post('/rating-kunjungan/{id_rating}/tanggapan', [AdminController::class, 'simpan_tanggapan'])->name('rating_kunjungan.tanggapan');

        // --- RUTE MANAJEMEN USER (AKTOR ADMINISTRATOR) ---
        Route::prefix('manajemen-user')->name('manajemen_user.')->group(function () {
            Route::get('/', [AdminController::class, 'user_index'])->name('index');
            Route::get('/create', [AdminController::class, 'user_create'])->name('create');
            Route::post('/', [AdminController::class, 'user_store'])->name('store');
            Route::get('/show/{id_user}', [AdminController::class, 'user_show'])->name('show');
            Route::get('/edit/{id_user}', [AdminController::class, 'user_edit'])->name('edit');
            Route::put('/update/{id_user}', [AdminController::class, 'user_update'])->name('update');
            Route::delete('/destroy/{id_user}', [AdminController::class, 'user_destroy'])->name('destroy');
        });
    });

    // --- GRUP RUTE DIREKTUR ---
    Route::prefix('direktur')->name('direktur.')->group(function () {
        Route::get('/dashboard', [DirekturController::class, 'index'])->name('dashboard');
        
        Route::prefix('monitoring-donatur')->name('riwayat_donatur.')->group(function () {
            Route::get('/', [DirekturController::class, 'riwayat_donatur'])->name('index');
            Route::get('/show/{id_user}', [DirekturController::class, 'donatur_show'])->name('show');
        });
        
        Route::prefix('monitoring-keuangan')->name('keuangan.')->group(function () {
            Route::get('/', [DirekturController::class, 'keuangan'])->name('index'); 
            Route::get('/export', [DirekturController::class, 'export_donasi_uang'])->name('export');
        });

        Route::prefix('monitoring-logistik')->name('logistik.')->group(function () {
            Route::get('/', [DirekturController::class, 'logistik'])->name('index'); 
            Route::get('/export', [DirekturController::class, 'export_donasi_barang'])->name('export');
        });

        Route::get('/audit-system', [DirekturController::class, 'audit'])->name('audit');
        Route::get('/laporan-umum', [DirekturController::class, 'laporan'])->name('laporan');

        // --- MANAJEMEN USER (EKSLUSIF DIREKTUR) ---
        Route::prefix('manajemen-user')->name('manajemen_user.')->group(function () {
            Route::get('/', [DirekturController::class, 'user_index'])->name('index');
            Route::get('/create', [DirekturController::class, 'user_create'])->name('create');
            Route::post('/', [DirekturController::class, 'user_store'])->name('store');
            Route::get('/show/{id_user}', [DirekturController::class, 'user_show'])->name('show');
            Route::get('/edit/{id_user}', [DirekturController::class, 'user_edit'])->name('edit');
            Route::put('/update/{id_user}', [DirekturController::class, 'user_update'])->name('update');
            Route::delete('/destroy/{id_user}', [DirekturController::class, 'user_destroy'])->name('destroy');
        });
    });
}); 

// --- GRUP RUTE DONATUR (TERPROTEKSI) ---
Route::middleware(['auth:donatur'])->prefix('donatur')->name('donatur.')->group(function () {
    
    Route::get('/dashboard', [DonaturController::class, 'dashboard'])->name('dashboard');

    Route::prefix('donasi')->name('donasi.')->group(function () {
        Route::get('/', [DonaturController::class, 'indexDonasi'])->name('index');
        Route::get('/create', [DonaturController::class, 'createDonasi'])->name('create');
        Route::post('/store', [DonaturController::class, 'storeDonasi'])->name('store');
        Route::get('/bayar/{id}', [DonaturController::class, 'pembayaran'])->name('bayar');
        // Rute ini tetap konsisten, pastikan ID di Controller menggunakan id_donasi_uang
        Route::get('/sukses/{id}', [DonaturController::class, 'sukses'])->name('sukses');
    });

    Route::prefix('kunjungan')->name('kunjungan.')->group(function () {
        Route::get('/create', [DonaturController::class, 'createKunjungan'])->name('create');
        Route::post('/store', [DonaturController::class, 'storeKunjungan'])->name('store');
    });

    Route::get('/riwayat', [DonaturController::class, 'riwayat'])->name('riwayat.index');
});