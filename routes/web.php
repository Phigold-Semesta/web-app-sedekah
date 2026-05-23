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
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $role = $user->role;
        
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
        
        // --- REVISI & PENYEMPURNAAN UTAMA: GRUP RUTE MASTER KATEGORI BARANG ---
        // PERBAIKAN: Mengubah prefix menjadi 'kategori-barang', name menjadi 'kategori_barang.', dan memisahkannya menjadi CRUD halaman terpisah
        Route::prefix('kategori-barang')->name('kategori_barang.')->group(function () {
            Route::get('/', [AdminController::class, 'kategori'])->name('index');
            Route::get('/create', [AdminController::class, 'kategoriCreate'])->name('create');
            Route::post('/', [AdminController::class, 'kategoriStore'])->name('store');
            Route::get('/edit/{id_kategori_barang}', [AdminController::class, 'kategoriEdit'])->name('edit');
            Route::put('/update/{id_kategori_barang}', [AdminController::class, 'kategoriUpdate'])->name('update');
            Route::delete('/destroy/{id_kategori_barang}', [AdminController::class, 'kategoriDestroy'])->name('destroy');
        });
        
        // --- REVISI & PENYEMPURNAAN UTAMA: GRUP RUTE KELOLA RIWAYAT DONASI KESELURUHAN ---
        // PERBAIKAN: Menambahkan ->name('riwayat_donasi.') dan mengubah nama sub-route menjadi 'index' agar klop dengan pemanggilan view Blade
        Route::prefix('riwayat-donasi')->name('riwayat_donasi.')->group(function () {
            Route::get('/', [AdminController::class, 'riwayat'])->name('index');
            
            // PERBAIKAN DISKRESI: Menambahkan rute ekspor dan diletakkan sebelum parameter dinamis {id_donasi} agar tidak bentrok
            Route::get('/export', [AdminController::class, 'export'])->name('export');
            
            Route::get('/show/{id_donasi}', [AdminController::class, 'riwayat_show'])->name('show');
            Route::put('/update-status/{id_donasi}', [AdminController::class, 'riwayat_update_status'])->name('update_status');
        });
        
        // --- REVISI & PENYEMPURNAAN UTAMA: GRUP RUTE KELOLA DATA DONATUR ---
        // Menyelaraskan name, prefix, dan parameter {id_donatur} agar klop dengan AdminController
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
        // Menghubungkan form rating layanan ulasan donatur dari scan QR Code secara real-time
        Route::get('/rating-kunjungan', [AdminController::class, 'rating_kunjungan'])->name('rating_kunjungan.index');
        
        // PERBAIKAN & PENYEMPURNAAN: Menambahkan rute POST untuk memproses form simpan tanggapan/respon ulasan admin agar klop dengan form modal Blade
        Route::post('/rating-kunjungan/{id_rating}/tanggapan', [AdminController::class, 'simpan_tanggapan'])->name('rating_kunjungan.tanggapan');

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