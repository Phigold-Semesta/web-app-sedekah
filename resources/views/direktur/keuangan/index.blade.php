@extends('layouts.app')

@section('title', 'Laporan Keuangan | Project SEDEKAH')
@section('page_title', 'Laporan Keuangan')

@section('content')
<div class="space-y-8 animate-fade-in">
    <!-- Header Statistik Keuangan -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Card: Total Dana Masuk -->
        <div class="bg-white dark:bg-slate-900 p-7 rounded-[2.5rem] shadow-sm border border-slate-100 dark:border-white/5 flex items-center gap-6 group hover:border-emerald-500/30 transition-all duration-500">
            <div class="w-16 h-16 bg-emerald-50 dark:bg-emerald-500/10 rounded-[1.5rem] flex items-center justify-center text-emerald-600 dark:text-emerald-400 group-hover:scale-110 transition-transform">
                <i class="fas fa-wallet text-3xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Total Dana Masuk</p>
                <h3 class="text-3xl font-black text-slate-800 dark:text-white tracking-tighter">
                    Rp {{ number_format($keuangan_list->sum('nominal'), 0, ',', '.') }}
                </h3>
            </div>
        </div>

        <!-- Card: Transaksi Berhasil -->
        <div class="bg-white dark:bg-slate-900 p-7 rounded-[2.5rem] shadow-sm border border-slate-100 dark:border-white/5 flex items-center gap-6 group hover:border-blue-500/30 transition-all duration-500">
            <div class="w-16 h-16 bg-blue-50 dark:bg-blue-500/10 rounded-[1.5rem] flex items-center justify-center text-blue-500 group-hover:scale-110 transition-transform">
                <i class="fas fa-hand-holding-dollar text-3xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Total Transaksi</p>
                <h3 class="text-3xl font-black text-slate-800 dark:text-white tracking-tighter">{{ $keuangan_list->count() }} <span class="text-sm font-bold text-slate-400">Data</span></h3>
            </div>
        </div>

        <!-- Card: Aktivitas Terakhir -->
        <div class="bg-gradient-to-br from-emerald-600 to-emerald-900 p-7 rounded-[2.5rem] shadow-xl shadow-emerald-900/20 flex items-center gap-6 group">
            <div class="w-16 h-16 bg-white/10 backdrop-blur-md rounded-[1.5rem] flex items-center justify-center text-emerald-50 group-hover:rotate-12 transition-transform">
                <i class="fas fa-clock-rotate-left text-3xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-emerald-200/60 uppercase tracking-[0.2em] mb-1">Update Terakhir</p>
                <h3 class="text-xl font-black text-white tracking-tight uppercase">
                    @if($keuangan_list->first())
                        {{ \Carbon\Carbon::parse($keuangan_list->first()->created_at)->diffForHumans() }}
                    @else
                        No Data
                    @endif
                </h3>
            </div>
        </div>
    </div>

    <!-- Filter Toolbar -->
    <div class="bg-white dark:bg-slate-900 p-4 rounded-[3rem] shadow-sm border border-slate-100 dark:border-white/5">
        <form action="{{ route('direktur.keuangan.index') }}" method="GET" class="flex flex-col xl:flex-row items-center gap-4">
            <div class="relative w-full xl:flex-1">
                <span class="absolute inset-y-0 left-0 flex items-center pl-6 text-slate-400">
                    <i class="fas fa-search text-sm"></i>
                </span>
                <input type="text" name="search" value="{{ request('search') }}"
                    class="w-full bg-slate-50 dark:bg-slate-800/50 border-none rounded-full py-4 pl-14 pr-6 text-xs font-bold text-slate-600 dark:text-white placeholder:text-slate-400 focus:ring-2 focus:ring-emerald-500/20" 
                    placeholder="Cari Order ID...">
            </div>

            <div class="flex flex-wrap items-center justify-center gap-3 w-full xl:w-auto">
                <input type="date" name="tgl_hari" value="{{ request('tgl_hari') }}" 
                    class="bg-slate-50 dark:bg-slate-800/50 border-none rounded-full py-4 px-6 text-[10px] font-black uppercase text-slate-600 dark:text-white cursor-pointer">

                <!-- Filter Baris (Per Page) -->
                <select name="per_page" onchange="this.form.submit()" 
                    class="bg-slate-50 dark:bg-slate-800/50 border-none rounded-full py-4 px-8 text-[10px] font-black uppercase text-slate-600 dark:text-white cursor-pointer focus:ring-2 focus:ring-emerald-500/20">
                    <option value="5" {{ request('per_page') == '5' ? 'selected' : '' }}>5 Baris</option>
                    <option value="10" {{ request('per_page') == '10' ? 'selected' : '' }}>10 Baris</option>
                    <option value="all" {{ request('per_page') == 'all' ? 'selected' : '' }}>Semua</option>
                </select>

                <button type="submit" class="bg-emerald-600 text-white px-8 py-4 rounded-full text-[10px] font-black uppercase tracking-[0.15em] hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-600/20">
                    <i class="fas fa-filter mr-2"></i> Apply
                </button>

                <div class="relative group">
                    <button type="button" class="bg-slate-800 dark:bg-emerald-500/10 text-white dark:text-emerald-400 px-8 py-4 rounded-full text-[10px] font-black uppercase tracking-[0.15em] flex items-center gap-3">
                        <i class="fas fa-file-export"></i> Export
                    </button>
                    <div class="absolute right-0 mt-3 w-48 bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-slate-100 dark:border-white/5 overflow-hidden z-50 invisible group-hover:visible opacity-0 group-hover:opacity-100 transition-all duration-300">
                        <a href="{{ route('direktur.keuangan.export', array_merge(request()->query(), ['format' => 'excel'])) }}" class="flex items-center gap-3 px-6 py-4 text-xs font-bold text-slate-600 hover:bg-emerald-50 transition-colors">
                            <i class="fas fa-file-excel text-emerald-600"></i> Excel
                        </a>
                        <a href="{{ route('direktur.keuangan.export', array_merge(request()->query(), ['format' => 'pdf'])) }}" class="flex items-center gap-3 px-6 py-4 text-xs font-bold text-slate-600 hover:bg-red-50 transition-colors">
                            <i class="fas fa-file-pdf text-red-600"></i> PDF
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto pb-6">
        <table class="w-full border-separate border-spacing-y-4">
            <thead>
                <tr class="text-left text-slate-400">
                    <th class="px-8 py-2 text-[10px] font-black uppercase tracking-[0.2em]">Transaksi Info</th>
                    <th class="px-8 py-2 text-[10px] font-black uppercase tracking-[0.2em]">Nominal</th>
                    <th class="px-8 py-2 text-[10px] font-black uppercase tracking-[0.2em]">Tanggal Masuk</th>
                    <th class="px-8 py-2 text-[10px] font-black uppercase tracking-[0.2em]">Status</th>
                    <th class="px-8 py-2 text-right text-[10px] font-black uppercase tracking-[0.2em]">Opsi</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                @forelse($keuangan_list as $item)
                <tr class="bg-white dark:bg-slate-900 transition-all hover:scale-[1.01] hover:shadow-2xl group">
                    <td class="px-8 py-6 rounded-l-[2.5rem] border-y border-l border-slate-50 dark:border-white/5">
                        <div class="flex items-center gap-5">
                            <div class="w-12 h-12 bg-emerald-50 dark:bg-emerald-500/5 rounded-2xl flex items-center justify-center border border-emerald-100 dark:border-emerald-500/10 text-emerald-600">
                                <i class="fas fa-receipt"></i>
                            </div>
                            <div>
                                <h4 class="font-black text-slate-700 dark:text-emerald-50 uppercase tracking-tight">{{ $item->order_id }}</h4>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">ID: #DU-{{ $item->id_donasi_uang }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-8 py-6 border-y border-slate-50 dark:border-white/5 font-black text-xl text-emerald-600">
                        Rp {{ number_format($item->nominal, 0, ',', '.') }}
                    </td>
                    <td class="px-8 py-6 border-y border-slate-50 dark:border-white/5 font-bold text-slate-600 dark:text-slate-400">
                        {{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d M Y, H:i') }}
                    </td>
                    <td class="px-8 py-6 border-y border-slate-50 dark:border-white/5">
                        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-emerald-500/10 text-emerald-600 text-[9px] font-black uppercase tracking-widest border border-emerald-500/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            Terbayar
                        </span>
                    </td>
                    <td class="px-8 py-6 rounded-r-[2.5rem] border-y border-r border-slate-50 dark:border-white/5 text-right">
                        <button class="w-10 h-10 rounded-xl hover:bg-emerald-50 text-slate-400 hover:text-emerald-600 transition-all">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-20">
                        <i class="fas fa-money-bill-transfer text-5xl text-slate-200 mb-4"></i>
                        <p class="text-slate-400 font-bold uppercase text-[10px] tracking-widest">Belum ada donasi uang masuk</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if(method_exists($keuangan_list, 'links'))
        <div class="mt-4">{{ $keuangan_list->appends(request()->query())->links() }}</div>
    @endif
</div>
@endsection