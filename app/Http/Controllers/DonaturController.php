<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kunjungan;
use App\Models\Donasi;
use App\Models\DonasiUang;
use App\Models\DonasiBarang;
use App\Models\Donatur;
use App\Models\Penilaian; // <--- DITAMBAHKAN: Model untuk fitur Rating
use App\Services\MidtransService; 
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Midtrans\Notification;
use Midtrans\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator; 

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
        $donaturId = Auth::guard('donatur')->id();
        $tahun = date('Y');

        $totalDonasi = \App\Models\Donasi::where('id_donatur', $donaturId)
            ->where('jenis_donasi', 'uang')
            ->whereIn('status_donasi', ['donasi berhasil terkirim', 'berhasil', 'settlement'])
            ->sum('jumlah');

        $donasiBerhasil = \App\Models\Donasi::where('id_donatur', $donaturId)
            ->whereIn('status_donasi', ['donasi berhasil terkirim', 'berhasil', 'settlement'])
            ->count();

        $kunjungan = \App\Models\Kunjungan::where('id_donatur', $donaturId)->count();

        $chartData = [];
        for($i = 1; $i <= 6; $i++) {
            $totalBulanIni = \App\Models\Donasi::where('id_donatur', $donaturId)
                ->where('jenis_donasi', 'uang')
                ->whereIn('status_donasi', ['donasi berhasil terkirim', 'berhasil', 'settlement'])
                ->whereMonth('tgl_donasi', $i)
                ->whereYear('tgl_donasi', $tahun)
                ->sum('jumlah');
            
            $chartData[] = (int) $totalBulanIni;
        }

        $aktivitas = \App\Models\Donasi::where('id_donatur', $donaturId)
            ->latest('tgl_donasi')
            ->take(5)
            ->get();

        return view('donatur.dashboard', compact('totalDonasi', 'donasiBerhasil', 'kunjungan', 'chartData', 'aktivitas', 'tahun'));
    }

    public function riwayat() {
        $donaturId = Auth::guard('donatur')->id();
        $perPage = request('per_page', 10);

        // Diperbaiki: Menggunakan donasiUang (camelCase agar sesuai Model)
        $riwayatDonasi = Donasi::where('id_donatur', $donaturId)
            ->with(['Donasi_Uang', 'Donasi_Barang'])
            ->orderBy('tgl_donasi', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        return view('donatur.riwayat_donasi.index', compact('riwayatDonasi'));
    }

    // --- AREA RATING (FITUR BARU) ---
    public function indexRating() {
        $penilaian = Penilaian::where('id_donatur', Auth::guard('donatur')->id())
            ->orderBy('created_at', 'desc')
            ->get();
        return view('donatur.rating.index', compact('penilaian'));
    }

    public function storeRating(Request $request) {
        $request->validate([
            'skor_rating'  => 'required|integer|min:1|max:5',
            'saran'        => 'required|string',
            'id_kunjungan' => 'nullable|exists:kunjungan,id_kunjungan',
        ]);

        Penilaian::create([
            'id_donatur'   => Auth::guard('donatur')->id(),
            'id_kunjungan' => $request->id_kunjungan,
            'skor_rating'  => $request->skor_rating,
            'saran'        => $request->saran,
            'status'       => 'pending', // Default saat baru kirim
        ]);

        return redirect()->back()->with('success', 'Terima kasih atas penilaian Anda!');
    }

    // --- AREA DONASI ONLINE (MENU SIDEBAR) ---

    public function indexDonasi() {
        return view('donatur.donasi.index');
    }

    public function createDonasi(Request $request) {
        $jenis = $request->query('jenis');
        
        $kategori = \App\Models\KategoriBarang::all(); 

        if ($jenis === 'barang') {
            return view('donatur.donasi.create_barang', compact('kategori'));
        }
        return view('donatur.donasi.create_uang');
    }

    public function storeDonasi(Request $request) 
    {
        $request->validate([
            'jenis_donasi'  => 'required|in:uang,barang',
            'jumlah'        => 'required_if:jenis_donasi,uang|nullable|numeric|min:5000',
            'nama_barang'   => 'required_if:jenis_donasi,barang|nullable|string',
            'jumlah_barang' => 'required_if:jenis_donasi,barang|nullable|numeric',
            'satuan'        => 'required_if:jenis_donasi,barang|nullable|string',
            'foto_barang'   => 'required_if:jenis_donasi,barang|nullable|image|max:2048', 
        ]);

        return DB::transaction(function () use ($request) {
            
            $buktiPath = null;
            if ($request->hasFile('foto_barang')) {
                $buktiPath = $request->file('foto_barang')->store('bukti_donasi', 'public');
            }

            $donasi = Donasi::create([
                'id_user'       => 999, 
                'id_donatur'    => Auth::guard('donatur')->id(), 
                'id_kunjungan'  => $request->id_kunjungan ?? null,
                'jenis_donasi'  => $request->jenis_donasi,
                'jumlah'        => $request->jumlah,
                'nama_barang'   => $request->nama_barang,
                'jumlah_barang' => $request->jumlah_barang,
                'satuan'        => $request->satuan,
                'bukti_donasi'  => $buktiPath,
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
            else {
                \App\Models\DonasiBarang::create([
                    'id_donasi'          => $donasi->id_donasi,
                    'id_kategori_barang' => $request->id_kategori_barang,
                    'nama_barang'        => $request->nama_barang,
                    'jumlah_barang'      => $request->jumlah_barang,
                    'satuan_barang'      => $request->satuan, 
                ]);
                
                return redirect()->route('donatur.donasi.index')->with('success', 'Donasi barang berhasil diajukan!');
            }
        });
    }

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

            $donasiUang = DonasiUang::where('order_id', $orderId)->first();

            if (!$donasiUang) {
                Log::warning("Webhook error: Order ID $orderId tidak ditemukan di database!");
                return response()->json(['message' => 'Order not found, but request acknowledged'], 200);
            }

            if (in_array($transaction, ['capture', 'settlement'])) {
                $statusTujuan = 'donasi berhasil terkirim';
            } elseif (in_array($transaction, ['cancel', 'deny', 'expire'])) {
                $statusTujuan = 'donasi gagal';
            } else {
                $statusTujuan = 'pending';
            }
            
            if ($donasiUang->status !== $statusTujuan) {
                $donasiUang->update(['status' => $statusTujuan]);
                
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
            return response()->json(['message' => 'Server Error: ' . $e->getMessage()], 500);
        }
    }

  

    public function createKunjungan() {
    // ✅ Kirim data kategori barang ke view agar select dinamis dari DB
    $kategoriBarang = \App\Models\KategoriBarang::all();
    return view('donatur.kunjungan.create', compact('kategoriBarang'));
}

public function storeKunjungan(Request $request) {
    $validator = Validator::make($request->all(), [
        'nama_donatur'       => 'required|string|max:255',
        'no_hp'              => 'required|string',
        'tujuan_kunjungan'   => 'required|string',
        'nominal'            => 'required_if:jenis_donasi,uang|nullable|numeric|min:5000',
        // ✅ Validasi FK — pastikan id_kategori_barang benar-benar ada di DB
        'id_kategori_barang' => 'nullable|exists:kategori_barang,id_kategori_barang',
        'nama_barang'        => 'nullable|string|max:255',
        'jumlah_barang'      => 'nullable|numeric',
        'satuan_barang'      => 'nullable|string',
    ]);

    if ($validator->fails()) {
        return response()->json(['message' => 'Validasi gagal: ' . $validator->errors()->first()], 422);
    }

    try {
        return DB::transaction(function () use ($request) {

            $donatur = Donatur::create([
                'nama_donatur' => $request->nama_donatur,
                'no_hp'        => $request->no_hp,
                'alamat'       => $request->alamat ?? '-',
                'email'        => $request->email ?? '',
            ]);

            $kunjungan = Kunjungan::create([
                'id_donatur'       => $donatur->id_donatur,
                'tgl_kunjungan'    => now(),
                'tujuan_kunjungan' => $request->tujuan_kunjungan,
                'nama'             => $request->nama_donatur,
                'no_hp'            => $request->no_hp,
                'tujuan'           => $request->tujuan_kunjungan,
                'status'           => 'belum dilayani',
            ]);

            if ($request->filled('jenis_donasi')) {
                $donasi = Donasi::create([
                    'id_kunjungan'  => $kunjungan->id_kunjungan,
                    'id_donatur'    => $donatur->id_donatur,
                    'jenis_donasi'  => $request->jenis_donasi,
                    'status_donasi' => $request->jenis_donasi == 'uang' ? 'belum bayar' : 'berhasil',
                    'tgl_donasi'    => now(),
                ]);

                if ($request->jenis_donasi == 'uang') {
                    $orderId    = 'SED-' . time() . '-' . $donasi->id_donasi;
                    $donasiUang = DonasiUang::create([
                        'id_donasi' => $donasi->id_donasi,
                        'nominal'   => $request->nominal,
                        'order_id'  => $orderId,
                        'status'    => 'pending',
                    ]);

                    $params = [
                        'transaction_details' => [
                            'order_id'     => $orderId,
                            'gross_amount' => (int)$request->nominal,
                        ],
                        'customer_details' => [
                            'first_name' => $request->nama_donatur,
                            'phone'      => $request->no_hp,
                            'email'      => $request->email ?? '',
                        ],
                        // ✅ Callback finish agar Midtrans redirect ke halaman create
                        // dengan ?status=success yang ditangkap JS untuk tampilkan modal
                        'callbacks' => [
                            'finish' => url('/donatur/kunjungan/create') . '?status=success&order_id=' . $orderId,
                        ],
                    ];

                    $snapToken = $this->midtransService->getSnapToken($params);
                    $donasiUang->update(['snap_token' => $snapToken]);

                    return response()->json(['snap_token' => $snapToken]);

                } else {
                    // ✅ Donasi barang — gunakan ?: null agar string kosong tidak masuk FK
                    DonasiBarang::create([
                        'id_donasi'          => $donasi->id_donasi,
                        'id_kategori_barang' => $request->id_kategori_barang ?: null,
                        'nama_barang'        => $request->nama_barang,
                        'jumlah_barang'      => $request->jumlah_barang,
                        'satuan_barang'      => $request->satuan_barang,
                    ]);
                }
            }

            return response()->json(['message' => 'Berhasil disimpan!']);
        });
    } catch (\Exception $e) {
        Log::error("Error Store Kunjungan: " . $e->getMessage());
        return response()->json(['message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()], 500);
    }
}
}