<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; 
use App\Models\Donatur; 
use App\Models\DonasiBarang; 
use App\Models\DonasiUang;   
use App\Models\AuditLog; 
use App\Models\Kunjungan; 
use App\Exports\DonasiBarangExport; 
use App\Exports\DonasiUangExport;    
use Maatwebsite\Excel\Facades\Excel; 
use Barryvdh\DomPDF\Facade\Pdf; 
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class DirekturController extends Controller
{
    /**
     * Menampilkan Dashboard Direktur dengan data statistik.
     */
 public function index()
{
    $year = Carbon::now()->year;

    // 1. Statistik Dasar - Ganti 'jumlah' menjadi 'nominal'
    $totalDonasiTahunIni = \App\Models\DonasiUang::whereYear('created_at', $year)->sum('nominal');
    $targetTahunan = 1000000000; 
    $jumlahDonaturTetap = \App\Models\Donatur::count();
    $persentaseTarget = ($totalDonasiTahunIni / $targetTahunan) * 100;
    
    // 2. Data Grafik (Per Bulan) - Ganti 'jumlah' menjadi 'nominal'
    $monthlyData = \App\Models\DonasiUang::select(DB::raw('MONTH(created_at) as month'), DB::raw('SUM(nominal) as total'))
        ->whereYear('created_at', $year)
        ->groupBy('month')
        ->pluck('total', 'month');

    $chartLabels = ['JAN', 'FEB', 'MAR', 'APR', 'MEI', 'JUN'];
    $chartData = [];
    for ($i = 1; $i <= 6; $i++) {
        // Dibagi 1.000.000 agar sesuai skala visual chart
        $chartData[] = ($monthlyData->get($i, 0)) / 1000000; 
    }

    // 3. Log Aktivitas
    $logs = \App\Models\AuditLog::with('user')->latest()->take(3)->get();

    return view('direktur.dashboard', compact(
        'totalDonasiTahunIni', 'targetTahunan', 'jumlahDonaturTetap', 
        'persentaseTarget', 'chartLabels', 'chartData', 'logs'
    ));
}
    /**
     * Fitur Export Terpadu untuk Logistik Donasi Barang
     */
    public function export_donasi_barang(Request $request)
    {
        $format = $request->get('format', 'excel'); 
        $timestamp = date('Ymd_His');

        if ($format === 'excel') {
            $fileName = 'Laporan_Stok_SEDEKAH_' . $timestamp . '.xlsx';
            return Excel::download(new DonasiBarangExport($request->all()), $fileName);
        }

        if ($format === 'pdf') {
            $logistik_list = DonasiBarang::with('kategori_barang')
                            ->orderBy('created_at', 'desc')
                            ->get();

            $pdf = Pdf::loadView('direktur.logistik.pdf', compact('logistik_list'))
                      ->setPaper('a4', 'portrait');

            return $pdf->download('Laporan_Stok_SEDEKAH_' . $timestamp . '.pdf');
        }
        
        return redirect()->back()->with('error', 'Format export tidak didukung.');
    }

    /**
     * Export Terpadu untuk Laporan Keuangan
     */
    public function export_donasi_uang(Request $request)
    {
        $format = $request->get('format', 'excel');
        $timestamp = date('Ymd_His');

        if ($format === 'excel') {
            $fileName = 'Laporan_Keuangan_SEDEKAH_' . $timestamp . '.xlsx';
            return Excel::download(new DonasiUangExport($request->all()), $fileName);
        }

        if ($format === 'pdf') {
            $keuangan_list = DonasiUang::orderBy('created_at', 'desc')->get();

            $pdf = Pdf::loadView('direktur.keuangan.pdf', compact('keuangan_list'))
                      ->setPaper('a4', 'portrait');

            return $pdf->download('Laporan_Keuangan_SEDEKAH_' . $timestamp . '.pdf');
        }

        return redirect()->back()->with('error', 'Format export tidak didukung.');
    }

    /**
     * Manajemen User: Index
     */
    public function user_index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 5); 

        $query = User::query()->orderBy('created_at', 'desc');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_user', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }

        $user_list = ($perPage === 'all') ? $query->get() : $query->paginate($perPage)->withQueryString();

        return view('direktur.manajemen_user.index', compact('user_list'));
    }

    public function user_create() { return view('direktur.manajemen_user.create'); }

    public function user_store(Request $request)
    {
        $request->validate([
            'nama_user' => 'required|string|max:255', 
            'username'  => 'required|string|alpha_dash|unique:user,username', 
            'password'  => 'required|min:6|confirmed',
            'role'      => 'required|in:direktur,admin,petugas'
        ]);

        User::create([
            'nama_user' => $request->nama_user, 
            'username'  => $request->username,
            'password'  => Hash::make($request->password),
            'role'      => $request->role,
        ]);

        return redirect()->route('direktur.manajemen_user.index')
                         ->with('success', 'User authorized successfully!');
    }

    public function user_show($id)
    {
        $user = User::findOrFail($id);
        return view('direktur.manajemen_user.show', compact('user'));
    }

    public function user_edit($id)
    {
        $user = User::findOrFail($id);
        return view('direktur.manajemen_user.edit', compact('user'));
    }

    public function user_update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'nama_user' => 'required|string|max:255',
            'username'  => ['required', 'string', 'alpha_dash', Rule::unique('user', 'username')->ignore($id, 'id_user')],
            'role'      => 'required|in:direktur,admin,petugas',
            'password'  => 'nullable|min:6|confirmed'
        ]);

        $user->nama_user = $request->nama_user;
        $user->username = $request->username;
        $user->role = $request->role;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('direktur.manajemen_user.index')
                         ->with('success', 'Identity updated!');
    }

    public function user_destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('direktur.manajemen_user.index')
                         ->with('success', 'User archived.');
    }

    /**
     * DISEMPURNAKAN: Menampilkan Monitoring Donatur (Daftar Riwayat)
     */
    public function riwayat_donatur(Request $request) 
    { 
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);

        $query = Donatur::query()
            ->withCount('kunjungan')
            ->addSelect(['donasi_uang_count' => \App\Models\DonasiUang::selectRaw('count(*)')
                ->whereIn('id_donasi', function($q) {
                    $q->select('id_donasi')->from('donasi')
                      ->whereIn('id_kunjungan', function($sq) {
                          $sq->select('id_kunjungan')->from('kunjungan')
                             ->whereColumn('id_donatur', 'donatur.id_donatur');
                      });
                })
            ])
            ->addSelect(['donasi_barang_count' => \App\Models\DonasiBarang::selectRaw('count(*)')
                ->whereIn('id_donasi', function($q) {
                    $q->select('id_donasi')->from('donasi')
                      ->whereIn('id_kunjungan', function($sq) {
                          $sq->select('id_kunjungan')->from('kunjungan')
                             ->whereColumn('id_donatur', 'donatur.id_donatur');
                      });
                })
            ])
            ->orderBy('created_at', 'desc');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_donatur', 'like', "%{$search}%")
                  ->orWhere('no_hp', 'like', "%{$search}%");
            });
        }

        $donatur_list = ($perPage === 'all') ? $query->get() : $query->paginate($perPage)->withQueryString();

        return view('direktur.riwayat_donatur.index', compact('donatur_list')); 
    }

    /**
     * DISEMPURNAKAN: Menampilkan Detail Profil Donatur Beserta Riwayat Lengkap
     * Perbaikan: Mengumpulkan data donasi uang dan barang agar bisa diloop di view.
     */
    public function donatur_show($id)
    {
        // 1. Ambil data donatur dengan relasi mendalam
        $donatur = Donatur::with([
            'kunjungan.donasi.donasi_uang', 
            'kunjungan.donasi.donasi_barang'
        ])->findOrFail($id);

        // 2. Kita kumpulkan semua riwayat donasi uang dan barang ke dalam Collection tersendiri
        // Agar di file show.blade.php Anda bisa langsung melakukan @foreach($riwayat_uang as $uang)
        $riwayat_uang = collect();
        $riwayat_barang = collect();

        foreach($donatur->kunjungan as $kunjungan) {
            foreach($kunjungan->donasi as $donasi) {
                if ($donasi->donasi_uang) {
                    $riwayat_uang->push($donasi->donasi_uang);
                }
                if ($donasi->donasi_barang) {
                    $riwayat_barang->push($donasi->donasi_barang);
                }
            }
        }

        // 3. Kirim variabel donatur, riwayat_uang, dan riwayat_barang ke view
        return view('direktur.riwayat_donatur.show', compact('donatur', 'riwayat_uang', 'riwayat_barang'));
    }
    
    /**
     * Menu Laporan Keuangan (DonasiUang)
     */
    public function keuangan(Request $request)
    {
        $search = $request->input('search');
        $tgl_hari = $request->input('tgl_hari');
        $perPage = $request->input('per_page', 10);

        $query = DonasiUang::orderBy('created_at', 'desc');

        if ($search) {
            $query->where('order_id', 'like', "%{$search}%");
        }

        if ($tgl_hari) {
            $query->whereDate('created_at', $tgl_hari);
        }

        $keuangan_list = ($perPage === 'all') ? $query->get() : $query->paginate($perPage)->withQueryString();

        return view('direktur.keuangan.index', compact('keuangan_list'));
    }
    
    /**
     * Menampilkan daftar logistik (DonasiBarang)
     */
    public function logistik(Request $request)
    {
        $search = $request->input('search');
        $tgl_hari = $request->input('tgl_hari');
        $perPage = $request->input('per_page', 10);

        $query = DonasiBarang::with('kategori_barang')->orderBy('created_at', 'desc');

        if ($search) {
            $query->where('nama_barang', 'like', "%{$search}%");
        }

        if ($tgl_hari) {
            $query->whereDate('created_at', $tgl_hari);
        }

        $logistik_list = ($perPage === 'all') ? $query->get() : $query->paginate($perPage)->withQueryString();

        return view('direktur.logistik.index', compact('logistik_list'));
    }

    /**
     * Menu Audit System menggunakan Model AuditLog
     */
    public function audit(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);

        $query = AuditLog::with('user')->orderBy('waktu_log', 'desc');

        if ($search) {
            $query->where('aksi_log', 'like', "%{$search}%");
        }

        $audit_list = ($perPage === 'all') ? $query->get() : $query->paginate($perPage)->withQueryString();

        return view('direktur.audit_log.index', compact('audit_list'));
    }

    /**
     * Menu Laporan Umum
     */
    public function laporan()
    {
        return view('direktur.laporan.index');
    }
}