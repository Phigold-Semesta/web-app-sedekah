<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kunjungan;
use App\Models\Donasi;
use App\Models\Donatur;
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

        if (Auth::guard('donatur')->attempt($credentials)) {
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
            'email' => 'required|email|unique:donatur,email',
            'password' => 'required|min:8|confirmed',
            'no_hp' => 'required|string|max:20',
            'alamat' => 'required|string|max:255',
        ]);

        $donatur = Donatur::create([
            'nama_donatur' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
        ]);

        Auth::guard('donatur')->login($donatur);
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

    public function indexDonasi() {
        return view('donatur.donasi.index');
    }

    // Perbaikan: Menentukan view berdasarkan parameter jenis
    public function createDonasi(Request $request) {
        $jenis = $request->query('jenis');

        if ($jenis === 'barang') {
            return view('donatur.donasi.create_barang');
        }

        // Default ke uang
        return view('donatur.donasi.create_uang');
    }

    // Perbaikan: Menyimpan data donasi yang fleksibel untuk uang/barang
    public function storeDonasi(Request $request) {
        $request->validate([
            'jenis_donasi' => 'required|in:uang,barang',
            'jumlah' => 'required_if:jenis_donasi,uang|nullable|numeric',
            'nama_barang' => 'required_if:jenis_donasi,barang|nullable|string',
            'jumlah_barang' => 'required_if:jenis_donasi,barang|nullable|numeric',
            'satuan' => 'required_if:jenis_donasi,barang|nullable|string',
        ]);

        Donasi::create([
            'id_donatur'   => Auth::guard('donatur')->id(),
            'jenis_donasi' => $request->jenis_donasi,
            'jumlah'       => $request->jumlah,
            'nama_barang'  => $request->nama_barang,
            'jumlah_barang'=> $request->jumlah_barang,
            'satuan'       => $request->satuan,
            'status'       => 'pending',
            'tgl_donasi'   => now(),
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