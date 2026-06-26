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
use Illuminate\Support\Facades\DB;

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
    // 1. Ambil ID Donatur yang login
    $donaturId = Auth::guard('donatur')->id();

    // 2. Ambil nilai per_page dari URL, default-nya 10
    $perPage = request('per_page', 10);

    // 3. Gunakan paginate() alih-alih get()
    // withQueryString() sangat penting agar parameter 'per_page' tidak hilang 
    // saat kita klik pindah halaman (page 2, 3, dst)
    $riwayatDonasi = Donasi::where('id_donatur', $donaturId)
        ->with('donasiUang')
        ->orderBy('tgl_donasi', 'desc')
        ->paginate($perPage)
        ->withQueryString();

    return view('donatur.riwayat_donasi.index', compact('riwayatDonasi'));
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

        // --- MULAI TRANSAKSI ---
        return DB::transaction(function () use ($request) {
            // PERBAIKAN: id_user = 999 (Sistem), id_donatur = Donatur Login
            $donasi = Donasi::create([
                'id_user'       => 999, 
                'id_donatur'    => Auth::guard('donatur')->id(), 
                'id_kunjungan'  => $request->id_kunjungan ?? null,
                'jenis_donasi'  => $request->jenis_donasi,
                'jumlah'        => $request->jumlah,
                'nama_barang'   => $request->nama_barang,
                'jumlah_barang' => $request->jumlah_barang,
                'satuan'        => $request->satuan,
                'status_donasi' => 'berhasil',
                'tgl_donasi'    => now(),
            ]);

            if ($request->jenis_donasi === 'uang') {
                $orderId = 'SED-' . time() . '-' . $donasi->id_donasi;
                
                $donasiUang = DonasiUang::create([
                    'id_donasi' => $donasi->id_donasi,
                    'nominal'   => $request->jumlah,
                    'order_id'  => $orderId,
                    'status'    => 'berhasil',
                ]);

                $params = [
                    'transaction_details' => [
                        'order_id' => $orderId,
                        'gross_amount' => (int)$request->jumlah,
                    ],
                    'callbacks' => [
                        'finish' => route('donatur.donasi.sukses', $donasiUang->id_donasi_uang)
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
        });
    }

    // --- AREA PEMBAYARAN & CALLBACK MIDTRANS ---

    public function pembayaran($id) {
        $donasiUang = DonasiUang::where('id_donasi_uang', $id)->firstOrFail();
        $snapToken = $donasiUang->snap_token;
        return view('donatur.donasi.bayar', compact('snapToken', 'donasiUang'));
    }

    public function sukses($id) {
        $donasiUang = DonasiUang::where('id_donasi_uang', $id)->firstOrFail();
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

            // PERBAIKAN 1: Cari dengan lebih aman
            $donasiUang = DonasiUang::where('order_id', $orderId)->first();

            // PERBAIKAN 2: Jangan return 404 agar Midtrans tidak terus melakukan retry 
            // yang membebani server Anda. Cukup log error-nya.
            if (!$donasiUang) {
                Log::warning("Webhook error: Order ID $orderId tidak ditemukan di database!");
                return response()->json(['message' => 'Order not found, but request acknowledged'], 200);
            }

            // Mapping status
            if (in_array($transaction, ['capture', 'settlement'])) {
                $statusTujuan = 'donasi berhasil terkirim';
            } elseif (in_array($transaction, ['cancel', 'deny', 'expire'])) {
                $statusTujuan = 'donasi gagal';
            } else {
                $statusTujuan = 'pending';
            }
            
            // Perbaikan Logika: Pencegahan update berulang dan penanganan null
            // Pastikan kita tidak melakukan update jika status sudah sama
            if ($donasiUang->status !== $statusTujuan) {
                $donasiUang->update(['status' => $statusTujuan]);
                
                // Update tabel Donasi induk dengan pengecekan apakah donasi ada
                $donasi = Donasi::where('id_donasi', $donasiUang->id_donasi)->first();
                if ($donasi) {
                    $donasi->update(['status_donasi' => $statusTujuan]);
                }
                
                Log::info("Status updated for OrderID: $orderId to $statusTujuan");
            } else {
                Log::info("Status already up-to-date for OrderID: $orderId, no action taken.");
            }

            return response()->json(['message' => 'Success'], 200);

        } catch (\Exception $e) {
            Log::error("Midtrans Notification Error: " . $e->getMessage());
            // Berikan response 500 hanya jika benar-benar ada kesalahan server, 
            // bukan karena data tidak ketemu.
            return response()->json(['message' => 'Server Error: ' . $e->getMessage()], 500);
        }
    }

    public function createKunjungan() {
        return view('donatur.kunjungan.create');
    }

    public function storeKunjungan(Request $request) {
        return back()->with('success', 'Terima kasih telah berkunjung dan berdonasi!');
    }
}