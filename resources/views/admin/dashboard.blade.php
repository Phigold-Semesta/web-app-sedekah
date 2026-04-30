@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('page_title', 'Monitoring Operasional')

@section('content')
<div class="space-y-8">
    <!-- GREETING SECTION -->
    <div class="relative overflow-hidden p-8 rounded-3xl bg-emerald-900 text-white shadow-2xl">
        <div class="relative z-10">
            {{-- FIX: Menggunakan nama_user sesuai dengan gambar database image_29ee01.png --}}
            <h1 class="text-3xl font-black tracking-tight">Selamat Datang, {{ Auth::user()->nama_user }}!</h1>
            <p class="mt-2 text-emerald-200 font-medium tracking-wide uppercase text-xs">Panel Kendali Operasional Yayasan Rumah Harapan</p>
        </div>
        <!-- Decorative Circle -->
        <div class="absolute top-[-50px] right-[-50px] w-64 h-64 bg-emerald-800/50 rounded-full blur-3xl"></div>
    </div>

    <!-- QUICK STATS (Monitoring Dashboard Operasional) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Donasi Perlu Verifikasi -->
        <div class="p-6 rounded-3xl bg-white dark:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-800/30 shadow-xl transition-transform hover:scale-105">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 rounded-2xl bg-orange-100 text-orange-600">
                    <i class="fa-solid fa-file-circle-check text-xl"></i>
                </div>
                <span class="text-[10px] font-black text-orange-500 uppercase tracking-widest">Pending</span>
            </div>
            <h3 class="text-3xl font-black text-slate-800 dark:text-white">12</h3>
            <p class="text-sm font-bold text-slate-400 uppercase tracking-tighter">Verifikasi Donasi</p>
        </div>

        <!-- Total Donatur Terdaftar -->
        <div class="p-6 rounded-3xl bg-white dark:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-800/30 shadow-xl transition-transform hover:scale-105">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 rounded-2xl bg-emerald-100 text-emerald-600">
                    <i class="fa-solid fa-users text-xl"></i>
                </div>
                <span class="text-[10px] font-black text-emerald-500 uppercase tracking-widest">Master Data</span>
            </div>
            <h3 class="text-3xl font-black text-slate-800 dark:text-white">1,204</h3>
            <p class="text-sm font-bold text-slate-400 uppercase tracking-tighter">Total Donatur</p>
        </div>

        <!-- Distribusi Hibah Hari Ini -->
        <div class="p-6 rounded-3xl bg-white dark:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-800/30 shadow-xl transition-transform hover:scale-105">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 rounded-2xl bg-blue-100 text-blue-600">
                    <i class="fa-solid fa-truck-ramp-box text-xl"></i>
                </div>
                <span class="text-[10px] font-black text-blue-500 uppercase tracking-widest">Logistik</span>
            </div>
            <h3 class="text-3xl font-black text-slate-800 dark:text-white">8</h3>
            <p class="text-sm font-bold text-slate-400 uppercase tracking-tighter">Penyaluran Hibah</p>
        </div>

        <!-- Audit Log Terakhir -->
        <div class="p-6 rounded-3xl bg-white dark:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-800/30 shadow-xl transition-transform hover:scale-105">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 rounded-2xl bg-purple-100 text-purple-600">
                    <i class="fa-solid fa-fingerprint text-xl"></i>
                </div>
                <span class="text-[10px] font-black text-purple-500 uppercase tracking-widest">Keamanan</span>
            </div>
            <h3 class="text-3xl font-black text-slate-800 dark:text-white">154</h3>
            <p class="text-sm font-bold text-slate-400 uppercase tracking-tighter">Aktivitas Sistem</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- TERBARU: VERIFIKASI DONASI -->
        <div class="lg:col-span-2 p-8 rounded-3xl bg-white dark:bg-emerald-900/10 border border-emerald-100 dark:border-emerald-800/30 shadow-2xl">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-xl font-black text-slate-800 dark:text-white uppercase tracking-tight">Antrean Verifikasi Donasi</h3>
                <a href="{{ route('admin.verifikasi') }}" class="text-xs font-bold text-emerald-500 hover:underline tracking-widest uppercase">Lihat Semua</a>
            </div>
            
            <div class="space-y-4">
                <!-- Row Item -->
                <div class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 dark:bg-emerald-800/10 border border-transparent hover:border-emerald-500/30 transition-all">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 rounded-xl bg-emerald-900 flex items-center justify-center text-white font-bold">
                            U
                        </div>
                        <div>
                            <p class="text-sm font-extrabold text-slate-800 dark:text-white">Donasi Uang - Rp 500.000</p>
                            <p class="text-[10px] font-bold text-slate-400 uppercase">Dari: Hamba Allah • 2 menit yang lalu</p>
                        </div>
                    </div>
                    <button class="px-4 py-2 rounded-lg bg-emerald-500 text-white text-[10px] font-black uppercase tracking-widest hover:bg-emerald-600 transition-colors">
                        Verifikasi
                    </button>
                </div>
            </div>
        </div>

        <!-- RECENT AUDIT LOG -->
        <div class="p-8 rounded-3xl bg-white dark:bg-emerald-900/10 border border-emerald-100 dark:border-emerald-800/30 shadow-2xl">
            <h3 class="text-xl font-black text-slate-800 dark:text-white uppercase tracking-tight mb-8">Jejak Audit</h3>
            <div class="relative border-l-2 border-emerald-500/20 ml-3 space-y-8">
                <div class="relative pl-8">
                    <div class="absolute left-[-9px] top-0 w-4 h-4 rounded-full bg-emerald-500 border-4 border-white dark:border-emerald-900"></div>
                    <p class="text-xs font-black text-emerald-500 uppercase">Login</p>
                    <p class="text-sm font-bold text-slate-600 dark:text-emerald-100/70 leading-relaxed mt-1">Admin telah masuk ke sistem operasional.</p>
                    <span class="text-[10px] text-slate-400 font-bold uppercase mt-2 block">10:45 WIB</span>
                </div>
                <div class="relative pl-8">
                    <div class="absolute left-[-9px] top-0 w-4 h-4 rounded-full bg-blue-500 border-4 border-white dark:border-emerald-900"></div>
                    <p class="text-xs font-black text-blue-500 uppercase">Input Master</p>
                    <p class="text-sm font-bold text-slate-600 dark:text-emerald-100/70 leading-relaxed mt-1">Kategori barang baru "Peralatan Medis" ditambahkan.</p>
                    <span class="text-[10px] text-slate-400 font-bold uppercase mt-2 block">09:12 WIB</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection