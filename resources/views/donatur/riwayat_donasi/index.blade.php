@extends('layouts.donatur_app')

@section('page_title', 'Dashboard Donatur')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    @php
        $stats = [
            ['Total Donasi Uang', 'Rp ' . number_format($totalDonasi, 0, ',', '.'), 'fa-wallet', 'text-emerald-700 dark:text-emerald-400'], 
            ['Donasi Berhasil', $donasiBerhasil, 'fa-check-circle', 'text-emerald-500'], 
            ['Kunjungan', $kunjungan, 'fa-door-open', 'text-blue-500'], 
            ['Status', 'Aktif', 'fa-user-check', 'text-amber-500']
        ];
    @endphp
    @foreach($stats as $stat)
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

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2 bg-white dark:bg-slate-900 p-8 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-black text-slate-800 dark:text-white uppercase tracking-tight">Tren Donasi Anda</h3>
            <span class="text-[10px] bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-400 px-3 py-1 rounded-full font-bold">TAHUN {{ $tahun }}</span>
        </div>
        <canvas id="donasiChart" height="100"></canvas>
    </div>

    <div class="bg-white dark:bg-slate-900 p-8 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800">
        <h3 class="text-lg font-black text-slate-800 dark:text-white uppercase mb-6 tracking-tight">Aktivitas Terakhir</h3>
        <div class="space-y-6">
            @forelse($aktivitas as $item)
                @php
                    $statusMentah = strtolower(trim($item->status_donasi));
                    $isBerhasil = in_array($statusMentah, ['donasi berhasil terkirim', 'berhasil', 'settlement']);
                    $isPending = in_array($statusMentah, ['pending', 'belum bayar']);
                    $dotColor = $isBerhasil ? 'bg-emerald-500' : ($isPending ? 'bg-orange-500' : 'bg-red-500');
                    $statusTampil = $statusMentah == 'belum bayar' ? 'pending' : $statusMentah;
                @endphp
                <div class="flex items-center gap-4">
                    <div class="w-2 h-2 rounded-full {{ $dotColor }}"></div>
                    <div>
                        <p class="text-sm font-bold text-slate-800 dark:text-white capitalize">Donasi {{ $item->jenis_donasi }}</p>
                        <p class="text-[10px] text-slate-400 font-bold uppercase">{{ \Carbon\Carbon::parse($item->tgl_donasi)->diffForHumans() }} - {{ ucwords($statusTampil) }}</p>
                    </div>
                </div>
            @empty
                <p class="text-center text-slate-400 dark:text-slate-600 text-sm font-medium py-10">Belum ada data aktivitas</p>
            @endforelse
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('donasiChart').getContext('2d');
    const donasiChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
            datasets: [{
                label: 'Jumlah Donasi (Rp)',
                data: {!! json_encode($chartData) !!},
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
</script>
@endsection