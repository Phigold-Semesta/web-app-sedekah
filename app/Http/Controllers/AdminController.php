<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

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
     * Menampilkan aktivitas log user (siapa melakukan apa) untuk keamanan sistem.
     */
    public function audit(): View
    {
        return view('admin.audit');
    }
}