<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DonaturController;

/*
|--------------------------------------------------------------------------
| API Routes 🌐
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Enjoy building your API!
|
*/

/**
 * 1. Webhook Midtrans 🔔
 * Digunakan oleh Midtrans untuk mengirim notifikasi status pembayaran
 * ke aplikasi Sedekah Anda secara otomatis.
 * * URL Callback di Midtrans Dashboard:
 * https://[id-ngrok].ngrok-free.dev/api/donatur/donasi/notification-handler
 */
Route::post('/donatur/donasi/notification-handler', [DonaturController::class, 'notificationHandler']);

/**
 * 2. User Authentication (Opsional)
 */
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');