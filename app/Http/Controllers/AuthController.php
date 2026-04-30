<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Menampilkan halaman login SEDEKAH.
     */
    public function index(): View
    {
        return view('auth.login');
    }

    /**
     * Menangani proses autentikasi Administrator & Direktur.
     */
    public function authenticate(Request $request): RedirectResponse
    {
        // 1. Validasi input dari form
        $request->validate([
            'username' => ['required', 'string'], 
            'password' => ['required'],
        ]);

        /**
         * 2. FIXING: Menggunakan 'username' sesuai struktur DB di image_29ee01.png
         */
        $credentials = [
            'username' => $request->username, // Menyesuaikan dengan kolom di tabel 'user'
            'password' => $request->password,
        ];

        // 3. Proses login manual
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Ambil data user untuk pengecekan role
            $user = Auth::user();
            
            // Redirect sesuai role: administrator atau direktur
            if ($user->role === 'administrator') {
                return Redirect::intended('admin/dashboard');
            } elseif ($user->role === 'direktur') {
                return Redirect::intended('direktur/dashboard');
            }

            return Redirect::to('/');
        }

        // 4. Jika gagal, kembalikan dengan pesan error
        return Redirect::back()
            ->withErrors([
                'username' => 'Kredensial yang Bos masukkan tidak terdaftar di sistem kami.',
            ])
            ->withInput($request->only('username'));
    }

    /**
     * Menangani proses keluar (logout) manual.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/login');
    }
}