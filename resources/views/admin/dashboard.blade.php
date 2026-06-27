@extends('layouts.app')

@section('title', 'Admin Dashboard Operasional')

@section('content')
<div class="p-4 md:p-8 transition-colors duration-500 bg-slate-50 dark:bg-slate-950 min-h-screen">
    {{-- 1. Header Section --}}
    <div class="mb-10 flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
        <div>
            <h1 class="text-4xl font-black text-emerald-900 dark:text-emerald-400 tracking-tighter uppercase italic drop-shadow-sm">
                Operasional SEDEKAH
            </h1>
            <p class="text-slate-500 dark:text-slate-400 font-bold mt-1 tracking-wide">
                Selamat datang, <span class="text-emerald-600 dark:text-emerald-300">{{ Auth::user()->nama_user }}</span>! 
                <span class="ml-2 px-3 py-1 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 text-[10px] rounded-full italic animate-pulse border border-emerald-200 dark:border-emerald-800">
                    ⚡ Sistem Filantropi Aktif
                </span>
            </p>
        </div>
        <div class="hidden md:block text-right">
            <p class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.3em]">Monitoring Server</p>
            <p class="text-sm font-bold text-slate-700 dark:text-slate-300">{{ now()->format('d M Y | H:i') }} WIB</p>
        </div>
    </div>

    {{-- 2. Baris Statistik Utama (Data Dinamis) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        {{-- Total Donasi Masuk --}}
        <div class="group bg-white dark:bg-slate-900 p-6 rounded-[2.5rem] shadow-2xl shadow-emerald-900/5 dark:shadow-black/20 transition-all duration-500 hover:-translate-y-2 border border-slate-100 dark:border-slate-800">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-slate-400 dark:text-slate-500 text-[10px] uppercase font-black tracking-[0.2em] mb-1">Donasi Masuk (Bulan Ini)</p>
                    <h3 class="text-2xl font-black text-emerald-900 dark:text-emerald-100 tracking-tighter">Rp {{ number_format($totalDonasiBulanIni, 0, ',', '.') }}</h3>
                </div>
                <div class="bg-emerald-50 dark:bg-emerald-900/20 p-4 rounded-2xl text-2xl group-hover:bg-emerald-600 group-hover:text-white transition-all duration-500">💰</div>
            </div>
            <div class="mt-4 flex items-center gap-2 bg-emerald-50 dark:bg-emerald-900/40 p-1.5 rounded-lg w-fit text-[9px] text-emerald-700 dark:text-emerald-400 font-bold uppercase">
                <i class="fa-solid fa-arrow-up mr-1"></i> Verifikasi Berhasil
            </div>
        </div>

        {{-- Total Donatur --}}
        <div class="group bg-white dark:bg-slate-900 p-6 rounded-[2.5rem] shadow-2xl shadow-emerald-900/5 dark:shadow-black/20 transition-all duration-500 hover:-translate-y-2 border border-slate-100 dark:border-slate-800">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-slate-400 dark:text-slate-500 text-[10px] uppercase font-black tracking-[0.2em] mb-1">Donatur Terdaftar</p>
                    <h3 class="text-4xl font-black text-slate-800 dark:text-slate-200 tracking-tighter">{{ number_format($jumlahDonatur) }}</h3>
                </div>
                <div class="bg-slate-50 dark:bg-slate-800 p-4 rounded-2xl text-2xl group-hover:bg-emerald-900 group-hover:text-white transition-all duration-500">🤝</div>
            </div>
            <div class="mt-4 flex items-center gap-2 bg-slate-100 dark:bg-slate-800 p-1.5 rounded-lg w-fit text-[9px] text-slate-500 dark:text-slate-400 font-bold uppercase">
                Database Muhsinin
            </div>
        </div>

        {{-- Kunjungan Hari Ini --}}
        <div class="group bg-white dark:bg-slate-900 p-6 rounded-[2.5rem] shadow-2xl shadow-emerald-900/5 dark:shadow-black/20 transition-all duration-500 hover:-translate-y-2 border-b-4 border-emerald-500">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-emerald-500 text-[10px] uppercase font-black tracking-[0.2em] mb-1">Kunjungan Donatur</p>
                    <h3 class="text-4xl font-black text-emerald-600 dark:text-emerald-400 tracking-tighter">{{ $kunjunganHariIni }}</h3>
                </div>
                <div class="bg-emerald-50 dark:bg-emerald-900/30 p-4 rounded-2xl text-2xl group-hover:rotate-12 transition-all duration-500">🏠</div>
            </div>
            <p class="mt-4 text-[9px] text-emerald-400 font-bold uppercase italic tracking-wider">Tamu On-site Hari Ini</p>
        </div>

        {{-- Rating Layanan --}}
        <div class="group bg-emerald-900 dark:bg-emerald-950 p-6 rounded-[2.5rem] shadow-2xl shadow-emerald-900/20 dark:shadow-black/40 transition-all duration-500 hover:-translate-y-2 border-b-4 border-emerald-400">
            <div class="flex justify-between items-start mb-2">
                <div>
                    <p class="text-emerald-300 text-[10px] uppercase font-black tracking-[0.2em] mb-1">Rating Pengurus</p>
                    <h3 class="text-2xl font-black text-white tracking-tighter">{{ number_format($avgRating, 1) }} / 5.0</h3>
                </div>
                <div class="text-4xl group-hover:scale-125 transition-transform duration-500">⭐</div>
            </div>
            <div class="w-full bg-emerald-800 dark:bg-slate-800 h-2 rounded-full mt-4 overflow-hidden">
                <div class="bg-gradient-to-r from-emerald-400 to-emerald-200 h-full transition-all duration-1000 ease-out" style="width: {{ ($avgRating/5)*100 }}%"></div>
            </div>
        </div>
    </div>

    {{-- 3. Baris Antrean & Visualisasi --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10 items-stretch">
        <div class="lg:col-span-2 flex">
            <div class="w-full bg-white dark:bg-slate-900 p-8 rounded-[3rem] shadow-2xl shadow-emerald-900/5 dark:shadow-black/20 border border-slate-50 dark:border-slate-800 flex flex-col">
                <div class="flex justify-between items-center mb-8">
                    <h3 class="text-emerald-900 dark:text-emerald-400 font-black uppercase text-sm tracking-widest flex items-center gap-2">
                        <span class="w-8 h-8 bg-emerald-900 dark:bg-emerald-500 text-white rounded-lg flex items-center justify-center text-xs">🔔</span>
                        Verifikasi Donasi Tertunda
                    </h3>
                    <span class="text-[10px] font-black text-emerald-600 tracking-widest uppercase">{{ $antreanDonasi->count() }} Antrean</span>
                </div>
                <div class="space-y-4">
                    @forelse($antreanDonasi as $item)
                    <div class="group flex items-center justify-between p-5 rounded-3xl bg-slate-50 dark:bg-slate-800/50 border border-transparent hover:border-emerald-500/30 transition-all duration-300">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-emerald-100 dark:bg-emerald-900/50 text-emerald-600 flex items-center justify-center font-black">{{ substr($item->kunjungan->donatur->nama_donatur ?? '?', 0, 1) }}</div>
                            <div>
                                <p class="text-sm font-black text-slate-800 dark:text-white">{{ $item->jenis_donasi == 'uang' ? 'Donasi Uang' : $item->nama_barang }}</p>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tight">Oleh: {{ $item->kunjungan->donatur->nama_donatur ?? 'Anonim' }} • {{ $item->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.verifikasi.index') }}" class="px-5 py-2.5 rounded-xl bg-emerald-600 text-white text-[10px] font-black uppercase tracking-widest hover:bg-emerald-700 shadow-lg shadow-emerald-600/20 transition-all active:scale-95">Periksa</a>
                    </div>
                    @empty
                    <p class="text-center text-slate-400 font-bold py-10">Tidak ada donasi tertunda!</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="flex flex-col gap-6">
            <div class="w-full bg-white dark:bg-slate-900 p-8 rounded-[3rem] shadow-2xl border border-slate-50 dark:border-slate-800 flex flex-col">
                <h3 class="text-emerald-900 font-black uppercase text-sm tracking-widest mb-4">📊 Tren Donasi (7 Hari)</h3>
                <div class="h-[150px] relative"><canvas id="lineChart"></canvas></div>
            </div>
            <div class="w-full bg-white dark:bg-slate-900 p-8 rounded-[3rem] shadow-2xl border border-slate-50 dark:border-slate-800 flex flex-col">
                <h3 class="text-emerald-900 font-black uppercase text-sm tracking-widest mb-4">🍩 METODE PEMBAYARAN</h3>
                <div class="h-[150px] relative"><canvas id="methodChart"></canvas></div>
            </div>
        </div>
    </div>

    {{-- 4. Footer Banner --}}
    <div class="bg-gradient-to-r from-emerald-900 to-emerald-700 dark:from-emerald-950 dark:to-emerald-800 rounded-[2.5rem] p-8 text-white shadow-2xl shadow-emerald-900/20 border border-emerald-500/30">
        <div class="flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-5">
                <div class="w-16 h-16 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center text-3xl">🛡️</div>
                <div>
                    <h4 class="text-xl font-black tracking-tight uppercase italic text-white">Log Audit Operasional</h4>
                    <p class="text-emerald-100/80 text-sm font-medium">Memantau setiap perubahan data aset dan status donasi demi transparansi yayasan.</p>
                </div>
            </div>
            <a href="{{ route('admin.audit_log.index') }}" class="px-8 py-4 bg-white dark:bg-emerald-400 text-emerald-900 dark:text-emerald-950 font-black rounded-2xl hover:scale-105 active:scale-95 transition-all shadow-xl text-sm uppercase tracking-wider">Lihat Jejak Audit →</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const isDark = document.documentElement.classList.contains('dark');
    
    // Pie Chart (DUMMY DATA - E-Wallet DANA & Bank)
    new Chart(document.getElementById('methodChart'), {
        type: 'pie',
        data: {
            labels: ['E-Wallet', 'Transfer Bank'],
            datasets: [{ 
                data: [65, 35], 
                backgroundColor: ['#059669', '#34d399'] 
            }]
        },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
    });

    // Line Chart (DINAMIS - Tren 7 Hari)
    new Chart(document.getElementById('lineChart'), {
        type: 'line',
        data: {
            labels: {!! json_encode($tren->pluck('date')) !!},
            datasets: [{ 
                label: 'Rp', 
                data: {!! json_encode($tren->pluck('total')) !!}, 
                borderColor: '#059669', 
                fill: true, 
                backgroundColor: 'rgba(5, 150, 105, 0.1)' 
            }]
        },
        options: { plugins: { legend: { display: false } } }
    });
</script>
@endsection