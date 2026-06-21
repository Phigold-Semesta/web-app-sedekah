<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Models\AuditLog;

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
         * 2. FIXING: Menggunakan 'username' sesuai struktur DB
         */
        $credentials = [
            'username' => $request->username,
            'password' => $request->password,
        ];

        // 3. Proses login manual
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Ambil data user
            $user = Auth::user();
            
            // Sempurnakan: Deskripsi dinamis berdasarkan role user
            $roleLabel = ucfirst($user->role); // Mengubah 'administrator' jadi 'Administrator', dst.
            $deskripsiLog = "User dengan role {$roleLabel} ({$user->nama_user}) berhasil masuk ke sistem.";

            // Pencatatan Audit Log
            AuditLog::create([
                'id_user'    => Auth::id(),
                'aksi_log'   => 'LOGIN SISTEM',
                'deskripsi'  => $deskripsiLog,
                'ip_address' => $request->ip(),
                'waktu_log'  => now(),
            ]);

            // Redirect sesuai role
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
                'username' => 'akun yang anda masukkan tidak terdaftar di sistem kami.',
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