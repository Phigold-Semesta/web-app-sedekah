<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DirekturController;

/*
|--------------------------------------------------------------------------
| Web Routes - SOWAN v2 (DNA Project SEDEKAH)
|--------------------------------------------------------------------------
*/

// --- PENGALIHAN HALAMAN UTAMA ---
Route::get('/', function () {
    return redirect()->route('login');
});

// Form Kunjungan & Donasi tetap bisa diakses (Tamu via QR Code)
Route::get('/kunjungan/form', function () {
    return "Halaman Form Kunjungan & Donasi (Segera Hadir)";
})->name('donasi.form');


// --- SISTEM AUTENTIKASI MANUAL ---
Route::middleware('guest')->group(function () {
    // Menampilkan halaman login
    Route::get('/login', [AuthController::class, 'index'])->name('login');
    
    // Memproses data login (Menggunakan username sesuai struktur database)
    Route::post('/login', [AuthController::class, 'authenticate'])->name('login.proses');
});

// Logout harus terproteksi middleware auth
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');


// --- AKSES INTERNAL TERPROTEKSI (SOWAN V2) ---
Route::middleware(['auth'])->group(function () {

    /**
     * FIX: AUTOMATIC ROLE REDIRECT
     * Mengarahkan user ke dashboard yang tepat berdasarkan kolom 'role' di database.
     * Ini menggantikan AuthController::dashboard yang menyebabkan error 500.
     */
    Route::get('/dashboard', function () {
        $role = auth()->user()->role;

        if ($role === 'administrator') {
            return redirect()->route('admin.dashboard');
        } elseif ($role === 'direktur') {
            return redirect()->route('direktur.dashboard');
        }

        return redirect('/')->with('error', 'Role tidak dikenali.');
    })->name('dashboard');

    // GRUP RUTE ADMINISTRATOR (Luxurious Emerald Theme)
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
        Route::get('/verifikasi', [AdminController::class, 'verifikasi'])->name('verifikasi');
        Route::get('/distribusi', [AdminController::class, 'distribusi'])->name('distribusi');
        Route::get('/riwayat', [AdminController::class, 'riwayat'])->name('riwayat');
        Route::get('/donatur', [AdminController::class, 'donatur'])->name('donatur');
        Route::get('/kategori', [AdminController::class, 'kategori'])->name('kategori');
        Route::get('/audit', [AdminController::class, 'audit'])->name('audit');
    });

    // GRUP RUTE DIREKTUR
    Route::prefix('direktur')->name('direktur.')->group(function () {
        Route::get('/dashboard', [DirekturController::class, 'index'])->name('dashboard');
        Route::get('/laporan', [DirekturController::class, 'laporan'])->name('laporan');
        Route::get('/audit', [DirekturController::class, 'audit'])->name('audit');
    });
});