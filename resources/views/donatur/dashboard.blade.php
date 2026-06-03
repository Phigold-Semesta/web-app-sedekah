@extends('layouts.donatur_app')

@section('page_title', 'Dashboard Donatur')

@section('content')
<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    @foreach([['Total Donasi', 'Rp 0', 'fa-wallet', 'emerald-primary'], ['Donasi Berhasil', '0', 'fa-check-circle', 'emerald-500'], ['Kunjungan', '0', 'fa-door-open', 'blue-500'], ['Status', 'Aktif', 'fa-user-check', 'gold-accent']] as $stat)
    <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800 flex items-center gap-4 transition-all hover:scale-[1.02]">
        <div class="w-12 h-12 rounded-2xl bg-slate-50 dark:bg-slate-800 flex items-center justify-center text-{{ $stat[3] }}">
            <i class="fas {{ $stat[2] }} text-xl"></i>
        </div>
        <div>
            <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest">{{ $stat[0] }}</p>
            <h3 class="text-lg font-black text-slate-800 dark:text-white">{{ $stat[1] }}</h3>
        </div>
    </div>
    @endforeach
</div>

<!-- Main Visualization Section -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Chart Section -->
    <div class="lg:col-span-2 bg-white dark:bg-slate-900 p-8 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-black text-slate-800 dark:text-white uppercase tracking-tight">Tren Donasi Anda</h3>
            <span class="text-[10px] bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full font-bold">TAHUN 2026</span>
        </div>
        <canvas id="donasiChart" height="100"></canvas>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white dark:bg-slate-900 p-8 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800">
        <h3 class="text-lg font-black text-slate-800 dark:text-white uppercase mb-6 tracking-tight">Aktivitas Terakhir</h3>
        <div class="space-y-6">
            <p class="text-center text-slate-400 text-sm font-medium py-10">Belum ada data aktivitas</p>
        </div>
    </div>
</div>

<!-- Chart.js Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('donasiChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
            datasets: [{
                label: 'Jumlah Donasi (Rp)',
                data: [0, 0, 0, 0, 0, 0],
                borderColor: '#065f46',
                backgroundColor: 'rgba(6, 95, 70, 0.1)',
                borderWidth: 4,
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, grid: { display: false } }, x: { grid: { display: false } } }
        }
    });
</script>
@endsection