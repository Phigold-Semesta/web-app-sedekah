<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DirekturController;

/*
|--------------------------------------------------------------------------
| Web Routes - SOWAN v2 (Project SEDEKAH)
|--------------------------------------------------------------------------
*/

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

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// --- AKSES INTERNAL TERPROTEKSI ---
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', function () {
        $role = auth()->user()->role;
        if ($role === 'administrator') {
            return redirect()->route('admin.dashboard');
        } elseif ($role === 'direktur') {
            return redirect()->route('direktur.dashboard');
        }
        return redirect('/')->with('error', 'Role tidak dikenali oleh sistem.');
    })->name('dashboard');

    // --- GRUP RUTE ADMINISTRATOR ---
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
        Route::get('/verifikasi', [AdminController::class, 'verifikasi'])->name('verifikasi');
        Route::get('/distribusi', [AdminController::class, 'distribusi'])->name('distribusi');
        Route::get('/riwayat', [AdminController::class, 'riwayat'])->name('riwayat');
        Route::get('/donatur', [AdminController::class, 'donatur'])->name('donatur');
        Route::get('/kategori', [AdminController::class, 'kategori'])->name('kategori');
        Route::get('/audit', [AdminController::class, 'audit'])->name('audit');
    });

    // --- GRUP RUTE DIREKTUR ---
    Route::prefix('direktur')->name('direktur.')->group(function () {
        Route::get('/dashboard', [DirekturController::class, 'index'])->name('dashboard');
        Route::get('/monitoring-donatur', [DirekturController::class, 'riwayat_donatur'])->name('riwayat_donatur');
        Route::get('/laporan-keuangan', [DirekturController::class, 'laporan'])->name('laporan');
        Route::get('/monitoring-logistik', [DirekturController::class, 'logistik'])->name('logistik');
        Route::get('/audit-system', [DirekturController::class, 'audit'])->name('audit');

        // --- MANAJEMEN USER ---
        Route::prefix('manajemen-user')->name('manajemen_user.')->group(function () {
            Route::get('/', [DirekturController::class, 'user_index'])->name('index');
            Route::get('/create', [DirekturController::class, 'user_create'])->name('create');
            Route::post('/', [DirekturController::class, 'user_store'])->name('store');
            Route::get('/{id}', [DirekturController::class, 'user_show'])->name('show');
            Route::get('/{id}/edit', [DirekturController::class, 'user_edit'])->name('edit');
            Route::put('/{id}', [DirekturController::class, 'user_update'])->name('update');
            Route::delete('/{id}', [DirekturController::class, 'user_destroy'])->name('destroy');
        });
    });
});