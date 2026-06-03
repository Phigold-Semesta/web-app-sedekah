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

// Autentikasi Donatur (Eksklusif di DonaturController)
Route::middleware('guest')->group(function () {
    Route::get('/donatur/login', [DonaturController::class, 'showLogin'])->name('donatur.login');
    Route::post('/donatur/login', [DonaturController::class, 'login'])->name('donatur.login.proses');
    Route::get('/donatur/signup', [DonaturController::class, 'showSignup'])->name('donatur.signup');
    Route::post('/donatur/signup', [DonaturController::class, 'signup'])->name('donatur.signup.proses');
});

// Logout (Umum untuk semua role)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

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
        
        // --- REVISI & PENYEMPURNAAN UTAMA: GRUP RUTE MASTER KATEGORI BARANG ---
        Route::prefix('kategori-barang')->name('kategori_barang.')->group(function () {
            Route::get('/', [AdminController::class, 'kategori'])->name('index');
            Route::get('/create', [AdminController::class, 'kategoriCreate'])->name('create');
            Route::post('/', [AdminController::class, 'kategoriStore'])->name('store');
            Route::get('/edit/{id_kategori_barang}', [AdminController::class, 'kategoriEdit'])->name('edit');
            Route::put('/update/{id_kategori_barang}', [AdminController::class, 'kategoriUpdate'])->name('update');
            Route::delete('/destroy/{id_kategori_barang}', [AdminController::class, 'kategoriDestroy'])->name('destroy');
        });
        
        // --- REVISI & PENYEMPURNAAN UTAMA: GRUP RUTE KELOLA RIWAYAT DONASI KESELURUHAN ---
        Route::prefix('riwayat-donasi')->name('riwayat_donasi.')->group(function () {
            Route::get('/', [AdminController::class, 'riwayat'])->name('index');
            
            // PERBAIKAN DISKRESI: Menambahkan rute ekspor dan diletakkan sebelum parameter dinamis {id_donasi} agar tidak bentrok
            Route::get('/export', [AdminController::class, 'export'])->name('export');
            
            Route::get('/show/{id_donasi}', [AdminController::class, 'riwayat_show'])->name('show');
            Route::put('/update-status/{id_donasi}', [AdminController::class, 'riwayat_update_status'])->name('update_status');
        });
        
        // --- REVISI & PENYEMPURNAAN UTAMA: GRUP RUTE KELOLA DATA DONATUR ---
        Route::prefix('donatur')->name('donatur.')->group(function () {
            Route::get('/', [AdminController::class, 'donatur'])->name('index');
            Route::get('/create', [AdminController::class, 'donatur_create'])->name('create');
            Route::post('/', [AdminController::class, 'donatur_store'])->name('store');
            Route::get('/show/{id_donatur}', [AdminController::class, 'donatur_show'])->name('show');
            Route::get('/edit/{id_donatur}', [AdminController::class, 'donatur_edit'])->name('edit');
            Route::put('/update/{id_donatur}', [AdminController::class, 'donatur_update'])->name('update');
            Route::delete('/destroy/{id_donatur}', [AdminController::class, 'donatur_destroy'])->name('destroy');
        });
        
        // PERBAIKAN: Menyelaraskan name route menjadi 'audit_log.index' agar klop dengan pemanggilan view dan layout sidebar admin
        Route::get('/audit-log', [AdminController::class, 'audit'])->name('audit_log.index');

        // --- TAMBAHAN BARU: MODUL MONITORING RATING KUNJUNGAN ---
        Route::get('/rating-kunjungan', [AdminController::class, 'rating_kunjungan'])->name('rating_kunjungan.index');
        
        // PERBAIKAN & PENYEMPURNAAN: Menambahkan rute POST untuk memproses form simpan tanggapan/respon ulasan admin agar klop dengan form modal Blade
        Route::post('/rating-kunjungan/{id_rating}/tanggapan', [AdminController::class, 'simpan_tanggapan'])->name('rating_kunjungan.tanggapan');

        // --- TAMBAHAN BARU & PENYEMPURNAAN LENGKAP: RUTE MANAJEMEN USER (AKTOR ADMINISTRATOR) ---
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

// --- GRUP RUTE DONATUR (TERPROTEKSI) ---
Route::middleware(['auth:donatur'])->prefix('donatur')->name('donatur.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DonaturController::class, 'dashboard'])->name('dashboard');

    // Fitur Donasi (Mengarah ke method di DonaturController)
    Route::prefix('donasi')->name('donasi.')->group(function () {
        Route::get('/', [DonaturController::class, 'indexDonasi'])->name('index');
        Route::get('/create', [DonaturController::class, 'createDonasi'])->name('create');
        Route::post('/store', [DonaturController::class, 'storeDonasi'])->name('store');
    });

    // Fitur Kunjungan
    Route::prefix('kunjungan')->name('kunjungan.')->group(function () {
        Route::get('/create', [DonaturController::class, 'createKunjungan'])->name('create');
        Route::post('/store', [DonaturController::class, 'storeKunjungan'])->name('store');
    });

    // Fitur Riwayat
    Route::get('/riwayat', [DonaturController::class, 'riwayat'])->name('riwayat.index');
});
