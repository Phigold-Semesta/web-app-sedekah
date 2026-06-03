<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kunjungan;
use App\Models\Donasi;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class DonaturController extends Controller
{
    // --- AREA AUTH (ONLINE) ---
    
    public function showLogin() {
        return view('auth.donatur.login');
    }

    public function login(Request $request) {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/donatur/dashboard');
        }

        return back()->withErrors(['email' => 'Email atau password salah.']);
    }

    public function showSignup() {
        return view('auth.donatur.signup');
    }

    public function signup(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'donatur'
        ]);

        Auth::login($user);
        return redirect('/donatur/dashboard');
    }

    // --- AREA DASHBOARD & MEMBER (ONLINE) ---

    public function dashboard() {
        return view('donatur.dashboard');
    }

    public function riwayat() {
        return view('donatur.riwayat_donasi.index');
    }

    // --- AREA DONASI ONLINE (MENU SIDEBAR) ---

    // Menampilkan daftar/index halaman donasi
    public function indexDonasi() {
        return view('donatur.donasi.index');
    }

    // Menampilkan form untuk membuat donasi baru
    public function createDonasi() {
        return view('donatur.donasi.create');
    }

    // Proses penyimpanan donasi online
    public function storeDonasi(Request $request) {
        $request->validate([
            'jenis_donasi' => 'required',
            'jumlah' => 'required|numeric',
        ]);

        Donasi::create([
            'user_id' => Auth::id(),
            'jenis_donasi' => $request->jenis_donasi,
            'jumlah' => $request->jumlah,
            'status' => 'pending',
            'tgl_donasi' => now(),
        ]);

        return redirect()->route('donatur.donasi.index')->with('success', 'Donasi berhasil diajukan!');
    }

    // --- AREA PUBLIK (ONSITE / QR CODE) ---

    public function createKunjungan() {
        return view('donatur.kunjungan.create');
    }

    public function storeKunjungan(Request $request) {
        return back()->with('success', 'Terima kasih telah berkunjung dan berdonasi!');
    }
}