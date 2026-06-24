<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kunjungan;
use App\Models\Donasi;
use App\Models\DonasiUang;
use App\Models\Donatur;
use App\Services\MidtransService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Midtrans\Notification;
use Midtrans\Config;

class DonaturController extends Controller
{
    protected $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

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

    public function createDonasi(Request $request) {
        $jenis = $request->query('jenis');
        if ($jenis === 'barang') {
            return view('donatur.donasi.create_barang');
        }
        return view('donatur.donasi.create_uang');
    }

    public function storeDonasi(Request $request) {
        $request->validate([
            'jenis_donasi' => 'required|in:uang,barang',
            'jumlah' => 'required_if:jenis_donasi,uang|nullable|numeric|min:5000',
            'nama_barang' => 'required_if:jenis_donasi,barang|nullable|string',
            'jumlah_barang' => 'required_if:jenis_donasi,barang|nullable|numeric',
            'satuan' => 'required_if:jenis_donasi,barang|nullable|string',
        ]);

        $donasi = Donasi::create([
            'id_user'      => 999, 
            'id_kunjungan'  => null, 
            'status_donasi' => 'belum bayar',
            'tgl_donasi'    => now(),
        ]);

        if ($request->jenis_donasi === 'uang') {
            $orderId = 'SED-' . time() . '-' . $donasi->id_donasi;
            
            $donasiUang = DonasiUang::create([
                'id_donasi' => $donasi->id_donasi,
                'nominal'   => $request->jumlah,
                'order_id'  => $orderId,
                'status'    => 'belum bayar',
            ]);

            $params = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => (int)$request->jumlah,
                ],
                'customer_details' => [
                    'first_name' => Auth::guard('donatur')->user()->nama_donatur,
                    'email' => Auth::guard('donatur')->user()->email,
                ],
            ];

            $snapToken = $this->midtransService->getSnapToken($params);
            $donasiUang->update(['snap_token' => $snapToken]);

            return view('donatur.donasi.bayar', compact('snapToken', 'donasiUang'));
        }

        return redirect()->route('donatur.donasi.index')->with('success', 'Donasi barang berhasil diajukan!');
    }

    // --- AREA PEMBAYARAN & CALLBACK MIDTRANS ---

    public function pembayaran($id) {
        $donasiUang = DonasiUang::findOrFail($id);
        $snapToken = $donasiUang->snap_token;
        return view('donatur.donasi.bayar', compact('snapToken', 'donasiUang'));
    }

    public function sukses($id) {
        $donasiUang = DonasiUang::findOrFail($id);
        return view('donatur.donasi.sukses_bayar', compact('donasiUang'));
    }

    public function notificationHandler(Request $request) {
        try {
            Config::$serverKey = config('services.midtrans.server_key');
            Config::$isProduction = config('services.midtrans.is_production', false);
            Config::$isSanitized = true;
            Config::$is3ds = true;
            
            $notif = new Notification();
            
            $transaction = $notif->transaction_status;
            $orderId = $notif->order_id;

            Log::info("Midtrans Notification received - OrderID: $orderId, Status: $transaction");

            $donasiUang = DonasiUang::where('order_id', $orderId)->first();

            if (!$donasiUang) {
                return response()->json(['message' => 'Order not found'], 404);
            }

            // Mapping status
            if (in_array($transaction, ['capture', 'settlement'])) {
                $statusTujuan = 'donasi berhasil terkirim';
            } elseif (in_array($transaction, ['cancel', 'deny', 'expire'])) {
                $statusTujuan = 'donasi gagal';
            } else {
                $statusTujuan = 'pending';
            }
            
            // Update status jika ada perubahan
            if ($donasiUang->status !== $statusTujuan) {
                $donasiUang->update(['status' => $statusTujuan]);
                
                Donasi::where('id_donasi', $donasiUang->id_donasi)
                      ->update(['status_donasi' => $statusTujuan]);
                
                Log::info("Status updated for OrderID: $orderId to $statusTujuan");
            }

            return response()->json(['message' => 'Success'], 200);

        } catch (\Exception $e) {
            Log::error("Midtrans Notification Error: " . $e->getMessage());
            return response()->json(['message' => 'Server Error'], 500);
        }
    }

    public function createKunjungan() {
        return view('donatur.kunjungan.create');
    }

    public function storeKunjungan(Request $request) {
        return back()->with('success', 'Terima kasih telah berkunjung dan berdonasi!');
    }
}