@extends('layouts.donatur_app')

@section('page_title', 'Dashboard Donatur')

@section('content')
<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    @foreach([
        ['Total Donasi', 'Rp 0', 'fa-wallet', 'text-emerald-700 dark:text-emerald-400'], 
        ['Donasi Berhasil', '0', 'fa-check-circle', 'text-emerald-500'], 
        ['Kunjungan', '0', 'fa-door-open', 'text-blue-500'], 
        ['Status', 'Aktif', 'fa-user-check', 'text-amber-500']
    ] as $stat)
    <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800 flex items-center gap-4 transition-all hover:scale-[1.02]">
        <div class="w-12 h-12 rounded-2xl bg-slate-50 dark:bg-slate-800 flex items-center justify-center {{ $stat[3] }}">
            <i class="fas {{ $stat[2] }} text-xl"></i>
        </div>
        <div>
            <p class="text-[10px] text-slate-400 dark:text-slate-500 font-black uppercase tracking-widest">{{ $stat[0] }}</p>
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
            <span class="text-[10px] bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-400 px-3 py-1 rounded-full font-bold">TAHUN 2026</span>
        </div>
        <canvas id="donasiChart" height="100"></canvas>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white dark:bg-slate-900 p-8 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800">
        <h3 class="text-lg font-black text-slate-800 dark:text-white uppercase mb-6 tracking-tight">Aktivitas Terakhir</h3>
        <div class="space-y-6">
            <p class="text-center text-slate-400 dark:text-slate-600 text-sm font-medium py-10">Belum ada data aktivitas</p>
        </div>
    </div>
</div>

<!-- Chart.js Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Memantau perubahan tema untuk update chart
    function updateChartTheme(chart) {
        const isDark = document.documentElement.classList.contains('dark');
        chart.options.scales.x.ticks.color = isDark ? '#94a3b8' : '#64748b';
        chart.options.scales.y.ticks.color = isDark ? '#94a3b8' : '#64748b';
        chart.update();
    }

    const ctx = document.getElementById('donasiChart').getContext('2d');
    const donasiChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
            datasets: [{
                label: 'Jumlah Donasi (Rp)',
                data: [0, 0, 0, 0, 0, 0],
                borderColor: '#059669',
                backgroundColor: 'rgba(5, 150, 105, 0.1)',
                borderWidth: 4,
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { 
                y: { beginAtZero: true, grid: { display: false }, ticks: { color: '#64748b' } }, 
                x: { grid: { display: false }, ticks: { color: '#64748b' } } 
            }
        }
    });

    // Jalankan sinkronisasi tema saat tombol di layout ditekan
    window.addEventListener('click', () => updateChartTheme(donasiChart));
</script>
@endsection