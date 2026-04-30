@extends('layouts.app')

@section('title', 'Executive Dashboard | SEDEKAH')

@section('content')
<div class="p-4 md:p-10 bg-slate-50 dark:bg-slate-950 min-h-screen transition-all duration-500">
    {{-- 1. Header Section --}}
    <div class="mb-12 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div class="group">
            <h1 class="text-5xl font-black text-emerald-900 dark:text-emerald-400 tracking-tighter uppercase italic drop-shadow-xl transition-all duration-500 group-hover:tracking-normal">
                Executive Overview
            </h1>
            <p class="text-slate-500 dark:text-slate-400 font-bold text-xs uppercase tracking-[0.4em] mt-3 flex items-center gap-2">
                <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                Yayasan SEDEKAH • <span class="text-emerald-600">Strategic Monitoring System</span>
            </p>
        </div>
        
        <div class="flex items-center gap-5 bg-white dark:bg-slate-900 px-8 py-4 rounded-[2rem] shadow-2xl shadow-emerald-900/10 border border-slate-100 dark:border-slate-800 transition-all hover:scale-105">
            <div class="relative flex h-4 w-4">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-4 w-4 bg-emerald-500"></span>
            </div>
            <div class="flex flex-col">
                <span class="text-[10px] font-black uppercase text-slate-400 tracking-[0.2em]">Server Status</span>
                <span class="text-xs font-black text-emerald-900 dark:text-emerald-400 uppercase italic">Real-time Financial Sync</span>
            </div>
        </div>
    </div>

    {{-- 2. Strategic KPI Cards --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
        
        {{-- Valuasi Aset Yayasan --}}
        <div class="group relative bg-white dark:bg-slate-900 p-10 rounded-[4rem] shadow-2xl shadow-emerald-900/5 border border-slate-50 dark:border-slate-800 overflow-hidden transition-all duration-500 hover:-translate-y-3 hover:shadow-emerald-500/10">
            <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-50 dark:bg-emerald-900/10 rounded-bl-[5rem] -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
            <div class="relative z-10">
                <div class="flex justify-between items-start mb-8">
                    <p class="text-slate-400 dark:text-slate-500 text-xs font-black uppercase tracking-[0.2em]">Total Valuasi Aset</p>
                    <span class="text-3xl filter grayscale group-hover:grayscale-0 transition-all duration-500">🏛️</span>
                </div>
                <h2 class="text-4xl font-black text-slate-900 dark:text-white tracking-tighter mb-4">
                    Rp 1.250.000.000
                </h2>
                <div class="flex items-center gap-3">
                    <div class="px-4 py-1.5 bg-emerald-50 dark:bg-emerald-400/10 text-emerald-600 dark:text-emerald-400 rounded-full text-[10px] font-black uppercase italic border border-emerald-100 dark:border-emerald-400/20">
                        ↑ 12.5% Growth
                    </div>
                    <span class="text-[10px] text-slate-400 font-bold uppercase italic tracking-tighter">vs FY 2025</span>
                </div>
            </div>
        </div>

        {{-- Target Donasi Tahunan --}}
        <div class="relative overflow-hidden bg-emerald-900 dark:bg-emerald-950 p-10 rounded-[4rem] shadow-2xl shadow-emerald-900/30 text-white transition-all duration-500 hover:-translate-y-3">
            <div class="relative z-10">
                <div class="flex justify-between items-start mb-8">
                    <p class="text-emerald-400 text-xs font-black uppercase tracking-[0.2em]">Capaian Donasi 2026</p>
                    <span class="text-3xl animate-bounce">🎯</span>
                </div>
                <h2 class="text-5xl font-black tracking-tighter mb-8 flex items-baseline gap-3">
                    45% <span class="text-lg font-bold text-emerald-400/80 italic tracking-normal">Target</span>
                </h2>
                <div class="w-full bg-emerald-950/50 dark:bg-slate-900 h-4 rounded-full overflow-hidden mb-6 border border-emerald-800/50">
                    <div class="bg-gradient-to-r from-emerald-400 via-emerald-300 to-emerald-200 h-full transition-all duration-[2s] ease-out shadow-[0_0_20px_rgba(52,211,153,0.5)]" style="width: 45%"></div>
                </div>
                <div class="flex justify-between items-center">
                    <p class="text-[10px] font-black text-emerald-300 uppercase tracking-widest italic">Rp 450 JT Terkumpul</p>
                    <p class="text-[10px] font-black text-emerald-500 uppercase tracking-widest">Goal: 1 Miliar</p>
                </div>
            </div>
            <div class="absolute -right-20 -bottom-20 w-64 h-64 bg-emerald-500 rounded-full blur-[100px] opacity-20"></div>
        </div>

        {{-- Donatur Tetap (Loyalty Meter) --}}
        <div class="group bg-white dark:bg-slate-900 p-10 rounded-[4rem] shadow-2xl shadow-emerald-900/5 border-t-8 border-emerald-600 dark:border-emerald-500 flex flex-col justify-center items-center text-center transition-all duration-500 hover:-translate-y-3">
            <p class="text-slate-400 dark:text-slate-500 text-xs font-black uppercase tracking-[0.2em] mb-6">Donatur Tetap Aktif</p>
            <div class="relative inline-block">
                <h2 class="text-8xl font-black text-emerald-900 dark:text-emerald-400 tracking-tighter italic transition-transform group-hover:scale-110 duration-500">85</h2>
                <div class="absolute -top-4 -right-10 bg-orange-500 text-white text-[10px] font-black px-3 py-1 rounded-xl uppercase tracking-tighter shadow-2xl rotate-12 group-hover:rotate-0 transition-all">LOYAL</div>
            </div>
            <p class="text-slate-400 dark:text-slate-500 text-[10px] font-black uppercase mt-8 tracking-[0.4em] leading-relaxed">
                Entitas Muhsinin<br>Terverifikasi
            </p>
        </div>
    </div>

    {{-- 3. Analytics & Global Activity --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Growth Chart Analytics --}}
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-slate-900 p-12 rounded-[5rem] shadow-2xl shadow-emerald-900/5 border border-slate-50 dark:border-slate-800 h-full relative overflow-hidden group">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-12 gap-6 relative z-10">
                    <div>
                        <h3 class="text-emerald-900 dark:text-emerald-400 font-black uppercase text-base tracking-[0.3em] flex items-center gap-4">
                            <span class="w-3 h-10 bg-emerald-600 rounded-full shadow-lg shadow-emerald-500/50"></span>
                            Financial Growth Analysis
                        </h3>
                        <p class="text-[10px] text-slate-400 font-bold uppercase mt-2 ml-7">Trend Arus Kas Semester I - 2026</p>
                    </div>
                    <div class="flex gap-2">
                        <select class="bg-slate-100 dark:bg-slate-800 border-none text-[10px] font-black uppercase tracking-widest rounded-2xl px-6 py-3 text-slate-600 dark:text-slate-300 focus:ring-2 focus:ring-emerald-500 transition-all cursor-pointer">
                            <option>Semester I - 2026</option>
                            <option>Semester II - 2025</option>
                        </select>
                    </div>
                </div>
                <div class="h-[400px] relative z-10">
                    <canvas id="executiveChart"></canvas>
                </div>
                {{-- Decorative background text --}}
                <div class="absolute bottom-10 right-10 text-9xl font-black text-slate-500/5 select-none pointer-events-none italic">GROWTH</div>
            </div>
        </div>

        {{-- Recent Executive Logs --}}
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-slate-900 p-10 rounded-[5rem] shadow-2xl shadow-emerald-900/5 border border-slate-50 dark:border-slate-800 flex flex-col h-full group">
                <h3 class="text-emerald-900 dark:text-emerald-400 font-black uppercase text-sm tracking-[0.2em] mb-10 flex items-center gap-3">
                    <span class="p-2 bg-emerald-100 dark:bg-emerald-900/50 rounded-xl">🛡️</span>
                    Strategic Actions
                </h3>
                
                <div class="space-y-8 overflow-y-auto max-h-[450px] pr-4 custom-scrollbar">
                    {{-- Action 1 --}}
                    <div class="relative pl-10 pb-2 group/item">
                        <div class="absolute left-0 top-0 h-full w-[2px] bg-slate-100 dark:bg-slate-800"></div>
                        <div class="absolute -left-[7px] top-0 w-4 h-4 rounded-full bg-white dark:bg-slate-900 border-4 border-emerald-500 shadow-[0_0_15px_rgba(16,185,129,0.5)] group-hover/item:scale-125 transition-transform"></div>
                        <p class="text-[10px] font-black text-emerald-600 uppercase italic tracking-widest mb-2">Today, 11:24 WIB</p>
                        <p class="text-sm font-black text-slate-800 dark:text-slate-100 mb-1 group-hover/item:text-emerald-600 transition-colors uppercase tracking-tight">Persetujuan Audit Kuartal I</p>
                        <p class="text-xs text-slate-400 dark:text-slate-500 leading-relaxed">Verifikasi laporan keuangan oleh Direktur Utama berhasil disahkan melalui sistem.</p>
                    </div>

                    {{-- Action 2 --}}
                    <div class="relative pl-10 pb-2 group/item">
                        <div class="absolute left-0 top-0 h-full w-[2px] bg-slate-100 dark:bg-slate-800"></div>
                        <div class="absolute -left-[7px] top-0 w-4 h-4 rounded-full bg-white dark:bg-slate-900 border-4 border-slate-300 dark:border-slate-700"></div>
                        <p class="text-[10px] font-black text-slate-400 uppercase italic tracking-widest mb-2">28 Apr 2026</p>
                        <p class="text-sm font-black text-slate-800 dark:text-slate-100 mb-1 group-hover/item:text-emerald-600 transition-colors uppercase tracking-tight">Laporan Distribusi Sembako</p>
                        <p class="text-xs text-slate-400 dark:text-slate-500 leading-relaxed">Status operasional: Berhasil didistribusikan ke 5 wilayah binaan yayasan.</p>
                    </div>

                    {{-- Action 3 --}}
                    <div class="relative pl-10 group/item">
                        <div class="absolute -left-[7px] top-0 w-4 h-4 rounded-full bg-white dark:bg-slate-900 border-4 border-slate-300 dark:border-slate-700"></div>
                        <p class="text-[10px] font-black text-slate-400 uppercase italic tracking-widest mb-2">25 Apr 2026</p>
                        <p class="text-sm font-black text-slate-800 dark:text-slate-100 mb-1 group-hover/item:text-emerald-600 transition-colors uppercase tracking-tight">Rapat Kerja Tahunan</p>
                        <p class="text-xs text-slate-400 dark:text-slate-500 leading-relaxed">Penetapan Key Performance Indicator (KPI) untuk Semester II - 2026.</p>
                    </div>
                </div>
                
                <button class="mt-10 group-hover:translate-x-2 transition-transform text-left text-[10px] font-black text-emerald-600 dark:text-emerald-400 uppercase tracking-[0.3em] flex items-center gap-2">
                    View Audit Trail <span class="text-lg">→</span>
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Scripts Visualisasi --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const isDark = document.documentElement.classList.contains('dark');
    Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
    Chart.defaults.color = isDark ? '#94a3b8' : '#64748b';
    
    const ctx = document.getElementById('executiveChart').getContext('2d');
    
    // Gradient Background for Line Chart
    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(16, 185, 129, 0.3)');
    gradient.addColorStop(0.5, 'rgba(16, 185, 129, 0.1)');
    gradient.addColorStop(1, 'rgba(16, 185, 129, 0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['JAN', 'FEB', 'MAR', 'APR', 'MEI', 'JUN'],
            datasets: [{
                label: 'Arus Kas (Jutaan)',
                data: [15, 28, 22, 35, 30, 45],
                borderColor: '#10b981',
                backgroundColor: gradient,
                borderWidth: 6,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: isDark ? '#064e3b' : '#ffffff',
                pointBorderColor: '#10b981',
                pointBorderWidth: 4,
                pointRadius: 8,
                pointHoverRadius: 12,
                pointHoverBorderWidth: 4,
                pointHoverBackgroundColor: '#10b981'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { intersect: false, mode: 'index' },
            plugins: { 
                legend: { display: false },
                tooltip: {
                    backgroundColor: isDark ? '#1e293b' : '#ffffff',
                    titleColor: isDark ? '#ffffff' : '#0f172a',
                    bodyColor: '#10b981',
                    bodyFont: { size: 14, weight: '900' },
                    padding: 20,
                    cornerRadius: 15,
                    displayColors: false,
                    borderColor: '#10b981',
                    borderWidth: 1,
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + context.parsed.y + '.000.000';
                        }
                    }
                }
            },
            scales: {
                y: { 
                    beginAtZero: true, 
                    grid: { color: isDark ? 'rgba(255,255,255,0.03)' : 'rgba(0,0,0,0.03)', drawBorder: false },
                    ticks: { font: { weight: 'bold', size: 11 }, padding: 10 }
                },
                x: { 
                    grid: { display: false }, 
                    ticks: { font: { weight: 'black', size: 11 }, padding: 10 } 
                }
            }
        }
    });
</script>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { 
        background: linear-gradient(to bottom, #10b981, #064e3b); 
        border-radius: 20px; 
    }
</style>
@endsection