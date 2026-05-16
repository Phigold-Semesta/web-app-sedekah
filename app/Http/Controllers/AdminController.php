<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\AuditLog; // Ditambahkan agar data log bisa dipanggil secara dinamis

class AdminController extends Controller
{
    /**
     * Dashboard Utama Administrator.
     * Menampilkan ringkasan statistik donasi dan kunjungan.
     */
    public function index(): View
    {
        return view('admin.dashboard');
    }

    /**
     * Validasi Bukti Transaksi.
     * Menampilkan daftar donasi yang masuk dan perlu diverifikasi admin.
     */
    public function verifikasi(): View
    {
        // Pastikan file resources/views/admin/verifikasi.blade.php sudah ada
        return view('admin.verifikasi');
    }

    /**
     * Penyaluran Hibah.
     * Mengelola distribusi bantuan berupa barang atau uang ke penerima.
     */
    public function distribusi(): View
    {
        return view('admin.distribusi');
    }

    /**
     * Riwayat Transaksi.
     * Menampilkan log keseluruhan donasi yang sudah selesai atau diproses.
     */
    public function riwayat(): View
    {
        return view('admin.riwayat');
    }

    /**
     * Master Data Donatur.
     * Fungsi untuk mengelola data profil donatur tetap maupun tidak tetap.
     */
    public function donatur(): View
    {
        return view('admin.donatur');
    }

    /**
     * Master Data Kategori.
     * Mengelola kategori donasi (Zakat, Infak, Sedekah, atau kategori barang).
     */
    public function kategori(): View
    {
        return view('admin.kategori');
    }

    /**
     * Monitoring Jejak Audit.
     * DISEMPURNAKAN & DIPERBAIKI: Menangani filter per_page, search, dan menyesuaikan nama variabel dengan Blade.
     */
    public function audit(Request $request): View
    {
        // 1. Ambil input pencarian dan filter baris dari form Blade
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10); // Default 10 baris sesuai form filter Anda

        // 2. Query dasar: Mengambil data log audit terbaru beserta data user pelaksana aksi
        $query = AuditLog::with('user')->orderBy('waktu_log', 'desc');

        // 3. Logika Fitur Pencarian (Search) agar form cari di view berfungsi
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('aksi_log', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('nama_user', 'like', '%' . $search . '%')
                               ->orWhere('role', 'like', '%' . $search . '%');
                  });
            });
        }

        // 4. Logika Penomoran Halaman (Pagination / All Data) sesuai pilihan select box
        if ($perPage === 'all') {
            $audit_list = $query->paginate($query->count() ?: 10)->appends($request->query());
        } else {
            $audit_list = $query->paginate((int)$perPage)->appends($request->query());
        }

        // PERBAIKAN UTAMA: Nama variabel disesuaikan menjadi 'audit_list' agar sinkron dengan view Blade Anda
        return view('admin.audit_log.index', compact('audit_list'));
    }
}