<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; 
use App\Models\DonasiBarang; // Import Model DonasiBarang untuk project SEDEKAH
use App\Exports\DonasiBarangExport; // Import Class Export Excel
use Maatwebsite\Excel\Facades\Excel; // Import Facade Excel
use Barryvdh\DomPDF\Facade\Pdf; // Import Facade PDF
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class DirekturController extends Controller
{
    /**
     * Menampilkan Dashboard Direktur dengan data statistik.
     */
    public function index()
    {
        // Data statistik disesuaikan untuk Project SEDEKAH
        $data = [
            'totalAsetYayasan'    => 1250000000, 
            'totalDonasiTahunIni' => 450000000,
            'targetTahunan'       => 1000000000,
            'jumlahDonaturTetap'  => 85,
            'persentaseTarget'    => 45,
            'pertumbuhan'         => 12.5,
            'chartLabels'         => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
            'chartData'           => [15, 28, 22, 35, 30, 45]
        ];
        
        return view('direktur.dashboard', $data);
    }

    /**
     * Fitur Export Terpadu (Excel & PDF) untuk Logistik Donasi Barang
     * Disempurnakan agar mendukung format PDF dan filter pencarian.
     */
    public function export_donasi_barang(Request $request)
    {
        $format = $request->get('format', 'excel'); // Default ke excel jika tidak ada parameter
        $timestamp = date('Ymd_His');

        // Logic Export Excel
        if ($format === 'excel') {
            $fileName = 'Laporan_Stok_SEDEKAH_' . $timestamp . '.xlsx';
            return Excel::download(new DonasiBarangExport, $fileName);
        }

        // Logic Export PDF
        if ($format === 'pdf') {
            // Kita ambil data terbaru untuk dilaporkan ke PDF
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
     * Menampilkan daftar user dengan fitur Search, Pagination, dan Filter Baris.
     */
    public function user_index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 5); 

        // Query dasar: ambil data user dan urutkan berdasarkan yang terbaru
        $query = User::query()->orderBy('created_at', 'desc');

        // Logika Searching: cari berdasarkan nama_user atau username
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_user', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }

        // Logika Filter Baris & Pagination dengan withQueryString agar filter tidak hilang saat navigasi
        if ($perPage === 'all') {
            $user_list = $query->get();
        } else {
            $user_list = $query->paginate($perPage)->withQueryString();
        }

        return view('direktur.manajemen_user.index', compact('user_list'));
    }

    public function user_create()
    {
        return view('direktur.manajemen_user.create');
    }

    /**
     * Menyimpan user baru ke database.
     */
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

    /**
     * Memperbarui data user yang sudah ada.
     */
    public function user_update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'nama_user' => 'required|string|max:255',
            // Gunakan id_user sebagai primary key sesuai struktur database Anda
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

    /**
     * Menghapus user (Soft Delete jika model mendukung, atau Hard Delete).
     */
    public function user_destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('direktur.manajemen_user.index')
                         ->with('success', 'User archived.');
    }

    // Pemetaan view untuk menu Direktur lainnya
    public function riwayat_donatur() { return view('direktur.riwayat_donatur.index'); }
    public function laporan()         { return view('direktur.laporan.index'); }
    
    /**
     * Menampilkan daftar logistik (Project SEDEKAH)
     * Ditambahkan fitur filter tanggal dan pencarian agar konsisten dengan view yang sudah dibuat.
     */
    public function logistik(Request $request)
    {
        $search = $request->input('search');
        $tgl_hari = $request->input('tgl_hari');
        $perPage = $request->input('per_page', 10);

        $query = DonasiBarang::with('kategori_barang')->orderBy('created_at', 'desc');

        // Filter Pencarian Nama Barang
        if ($search) {
            $query->where('nama_barang', 'like', "%{$search}%");
        }

        // Filter Berdasarkan Tanggal
        if ($tgl_hari) {
            $query->whereDate('created_at', $tgl_hari);
        }

        // Eksekusi Query
        if ($perPage === 'all') {
            $logistik_list = $query->get();
        } else {
            $logistik_list = $query->paginate($perPage)->withQueryString();
        }

        return view('direktur.logistik.index', compact('logistik_list'));
    }

    public function audit()           { return view('direktur.audit.index'); }
}