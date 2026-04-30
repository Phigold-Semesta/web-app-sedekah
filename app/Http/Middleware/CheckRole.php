<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string[] ...$roles Daftar role aktor (administrator/direktur)
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Pastikan pengguna sudah login ke aplikasi SEDEKAH
        if (!Auth::check()) {
            return redirect('/login');
        }

        // 2. Ambil data user yang sedang login
        $user = Auth::user();

        /**
         * 3. Validasi apakah role user ada dalam parameter yang ditentukan di Route.
         * Contoh penggunaan di Route: ->middleware('checkrole:administrator')
         */
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        /**
         * 4. Jika role tidak sesuai (Misal: Admin mencoba buka halaman Direktur),
         * maka akses ditolak dengan pesan yang tegas.
         */
        abort(403, 'Bos, Anda tidak memiliki izin untuk mengakses halaman rahasia ini!');
    }
}