<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; 
use App\Models\DonasiBarang; 
use App\Models\DonasiUang;   
use App\Models\AuditLog; // Import Model AuditLog untuk Audit System
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
        $data = [
            'totalAsetYayasan'    => 1250000000, 
            'totalDonasiTahunIni' => 450000000,
            'targetTahunan'        => 1000000000,
            'jumlahDonaturTetap'  => 85,
            'persentaseTarget'    => 45,
            'pertumbuhan'          => 12.5,
            'chartLabels'          => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
            'chartData'            => [15, 28, 22, 35, 30, 45]
        ];
        
        return view('direktur.dashboard', $data);
    }

    /**
     * Fitur Export Terpadu (Excel & PDF) untuk Logistik Donasi Barang
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
     * FITUR BARU: Export Terpadu (Excel & PDF) untuk Laporan Keuangan
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
     * Menampilkan daftar user dengan fitur Search, Pagination, dan Filter Baris.
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

    public function riwayat_donatur() { return view('direktur.riwayat_donatur.index'); }
    
    /**
     * MENYEMPURNAKAN: Menu Laporan Keuangan (DonasiUang)
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

        if ($perPage === 'all') {
            $keuangan_list = $query->get();
        } else {
            $keuangan_list = $query->paginate($perPage)->withQueryString();
        }

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

        if ($perPage === 'all') {
            $logistik_list = $query->get();
        } else {
            $logistik_list = $query->paginate($perPage)->withQueryString();
        }

        return view('direktur.logistik.index', compact('logistik_list'));
    }

    /**
     * MENYEMPURNAKAN: Menu Audit System menggunakan Model AuditLog
     */
    public function audit(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);

        // Mengambil riwayat log dengan relasi user sesuai model yang sudah dibuat
        $query = AuditLog::with('user')->orderBy('waktu_log', 'desc');

        if ($search) {
            $query->where('aksi_log', 'like', "%{$search}%");
        }

        if ($perPage === 'all') {
            $audit_list = $query->get();
        } else {
            $audit_list = $query->paginate($perPage)->withQueryString();
        }

        // Folder view disesuaikan dengan screenshot: direktur/audit_log/index.blade.php
        return view('direktur.audit_log.index', compact('audit_list'));
    }
}