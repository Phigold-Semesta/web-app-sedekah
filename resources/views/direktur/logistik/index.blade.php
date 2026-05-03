@extends('layouts.app')

@section('title', 'Monitoring Logistik | Project SEDEKAH')
@section('page_title', 'Monitoring Logistik')

@section('content')
<div class="space-y-8 animate-fade-in">
    <!-- Header Statistik Ringkas: Luxurious Emerald Theme (Project SEDEKAH) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Card 1: Total Variasi Item -->
        <div class="bg-white dark:bg-slate-900 p-7 rounded-[2.5rem] shadow-sm border border-slate-100 dark:border-white/5 flex items-center gap-6 group hover:border-emerald-500/30 transition-all duration-500">
            <div class="w-16 h-16 bg-emerald-50 dark:bg-emerald-500/10 rounded-[1.5rem] flex items-center justify-center text-emerald-600 dark:text-emerald-400 group-hover:scale-110 transition-transform">
                <i class="fas fa-layer-group text-3xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Variasi Barang</p>
                <h3 class="text-3xl font-black text-slate-800 dark:text-white tracking-tighter">{{ $logistik_list->count() }} <span class="text-sm font-bold text-slate-400">Jenis</span></h3>
            </div>
        </div>

        <!-- Card 2: Total Kuantitas Seluruh Barang (Revisi stok kritis menjadi Total Donasi Barang) -->
        <div class="bg-white dark:bg-slate-900 p-7 rounded-[2.5rem] shadow-sm border border-slate-100 dark:border-white/5 flex items-center gap-6 group hover:border-blue-500/30 transition-all duration-500">
            <div class="w-16 h-16 bg-blue-50 dark:bg-blue-500/10 rounded-[1.5rem] flex items-center justify-center text-blue-500 group-hover:scale-110 transition-transform">
                <i class="fas fa-boxes-packing text-3xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Total Stok Unit</p>
                <h3 class="text-3xl font-black text-slate-800 dark:text-white tracking-tighter">
                    {{ number_format($logistik_list->sum('jumlah_barang'), 0, ',', '.') }}
                </h3>
            </div>
        </div>

        <!-- Card 3: Aktivitas Terakhir -->
        <div class="bg-gradient-to-br from-emerald-600 to-emerald-900 p-7 rounded-[2.5rem] shadow-xl shadow-emerald-900/20 flex items-center gap-6 group">
            <div class="w-16 h-16 bg-white/10 backdrop-blur-md rounded-[1.5rem] flex items-center justify-center text-emerald-50 group-hover:rotate-12 transition-transform">
                <i class="fas fa-hand-holding-heart text-3xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-emerald-200/60 uppercase tracking-[0.2em] mb-1">Donasi Masuk Terbaru</p>
                <h3 class="text-xl font-black text-white tracking-tight uppercase">
                    @if($logistik_list->first())
                        {{ \Carbon\Carbon::parse($logistik_list->first()->created_at)->diffForHumans() }}
                    @else
                        Belum Ada Data
                    @endif
                </h3>
            </div>
        </div>
    </div>

    <!-- Main Toolbar: Filter & Export Unified -->
    <div class="bg-white dark:bg-slate-900 p-4 rounded-[3rem] shadow-sm border border-slate-100 dark:border-white/5">
        <form action="{{ route('direktur.logistik.index') }}" method="GET" class="flex flex-col xl:flex-row items-center gap-4">
            
            <!-- Search Section -->
            <div class="relative w-full xl:flex-1">
                <span class="absolute inset-y-0 left-0 flex items-center pl-6 text-slate-400">
                    <i class="fas fa-search text-sm"></i>
                </span>
                <input type="text" name="search" value="{{ request('search') }}"
                    class="w-full bg-slate-50 dark:bg-slate-800/50 border-none rounded-full py-4 pl-14 pr-6 text-xs font-bold text-slate-600 dark:text-white placeholder:text-slate-400 focus:ring-2 focus:ring-emerald-500/20" 
                    placeholder="Cari nama barang donasi...">
            </div>

            <!-- Unified Filters Group -->
            <div class="flex flex-wrap items-center justify-center gap-3 w-full xl:w-auto">
                <!-- Date Filter -->
                <div class="relative group">
                    <input type="date" name="tgl_hari" value="{{ request('tgl_hari') }}" 
                        class="bg-slate-50 dark:bg-slate-800/50 border-none rounded-full py-4 px-6 text-[10px] font-black uppercase text-slate-600 dark:text-white focus:ring-2 focus:ring-emerald-500/20 cursor-pointer">
                </div>

                <!-- Row Limit -->
                <div class="relative">
                    <select name="per_page" class="bg-slate-50 dark:bg-slate-800/50 border-none rounded-full py-4 pl-6 pr-12 text-[10px] font-black uppercase tracking-widest text-slate-600 dark:text-slate-300 appearance-none focus:ring-2 focus:ring-emerald-500/20 cursor-pointer min-w-[120px]">
                        <option value="5" {{ request('per_page') == '5' ? 'selected' : '' }}>5 Baris</option>
                        <option value="10" {{ request('per_page') == '10' ? 'selected' : '' }}>10 Baris</option>
                        <option value="all" {{ request('per_page') == 'all' ? 'selected' : '' }}>Semua</option>
                    </select>
                    <i class="fas fa-list absolute right-6 top-1/2 -translate-y-1/2 text-[10px] text-slate-400 pointer-events-none"></i>
                </div>

                <!-- Apply Button -->
                <button type="submit" class="bg-emerald-600 text-white px-8 py-4 rounded-full text-[10px] font-black uppercase tracking-[0.15em] flex items-center gap-2 hover:bg-emerald-700 transition-all active:scale-95 shadow-lg shadow-emerald-600/20">
                    <i class="fas fa-filter text-[10px]"></i>
                    Apply
                </button>

                <!-- Divider -->
                <div class="hidden xl:block w-px h-8 bg-slate-100 dark:bg-white/10 mx-2"></div>

                <!-- Export Action Dropdown -->
                <div class="relative group">
                    <button type="button" class="bg-slate-800 dark:bg-emerald-500/10 text-white dark:text-emerald-400 px-8 py-4 rounded-full text-[10px] font-black uppercase tracking-[0.15em] flex items-center gap-3 hover:bg-slate-900 transition-all">
                        <i class="fas fa-file-export"></i>
                        Export
                        <i class="fas fa-chevron-down text-[8px] opacity-50"></i>
                    </button>
                    <!-- Dropdown Menu -->
                    <div class="absolute right-0 mt-3 w-48 bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-slate-100 dark:border-white/5 overflow-hidden z-50 invisible group-hover:visible opacity-0 group-hover:opacity-100 transition-all duration-300 translate-y-2 group-hover:translate-y-0">
                        <a href="{{ route('direktur.logistik.export', array_merge(request()->query(), ['format' => 'excel'])) }}" class="flex items-center gap-3 px-6 py-4 text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-emerald-50 dark:hover:bg-emerald-500/10 hover:text-emerald-600 transition-colors">
                            <i class="fas fa-file-excel text-emerald-600"></i> Format Excel
                        </a>
                        <a href="{{ route('direktur.logistik.export', array_merge(request()->query(), ['format' => 'pdf'])) }}" class="flex items-center gap-3 px-6 py-4 text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-red-50 dark:hover:bg-red-500/10 hover:text-red-600 transition-colors">
                            <i class="fas fa-file-pdf text-red-600"></i> Format PDF
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Tabel Logistik: Luxurious Separated Rows -->
    <div class="overflow-x-auto pb-6">
        <table class="w-full border-separate border-spacing-y-4">
            <thead>
                <tr class="text-left text-slate-400">
                    <th class="px-8 py-2 text-[10px] font-black uppercase tracking-[0.2em]">Donasi Info</th>
                    <th class="px-8 py-2 text-[10px] font-black uppercase tracking-[0.2em]">Kategori</th>
                    <th class="px-8 py-2 text-[10px] font-black uppercase tracking-[0.2em]">Tanggal Masuk</th>
                    <th class="px-8 py-2 text-[10px] font-black uppercase tracking-[0.2em]">Jumlah</th>
                    <th class="px-8 py-2 text-[10px] font-black uppercase tracking-[0.2em]">Satuan</th>
                    <th class="px-8 py-2 text-[10px] font-black uppercase tracking-[0.2em]">Kondisi Stok</th>
                    <th class="px-8 py-2 text-right text-[10px] font-black uppercase tracking-[0.2em]">Opsi</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                @forelse($logistik_list as $item)
                <tr class="bg-white dark:bg-slate-900 transition-all hover:scale-[1.01] hover:shadow-2xl group">
                    <td class="px-8 py-6 rounded-l-[2.5rem] border-y border-l border-slate-50 dark:border-white/5">
                        <div class="flex items-center gap-5">
                            <div class="w-12 h-12 bg-emerald-50 dark:bg-emerald-500/5 rounded-2xl flex items-center justify-center border border-emerald-100 dark:border-emerald-500/10 text-emerald-600">
                                <i class="fas fa-gift"></i>
                            </div>
                            <div>
                                <h4 class="font-black text-slate-700 dark:text-emerald-50 uppercase tracking-tight">{{ $item->nama_barang }}</h4>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">ID: DB-{{ str_pad($item->id_donasi_barang ?? $item->id, 4, '0', STR_PAD_LEFT) }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-8 py-6 border-y border-slate-50 dark:border-white/5 font-bold text-slate-500 dark:text-slate-400 uppercase text-[11px]">
                        {{ $item->kategori_barang->nama_kategori ?? 'Umum' }}
                    </td>
                    <td class="px-8 py-6 border-y border-slate-50 dark:border-white/5 font-bold text-slate-600 dark:text-slate-400">
                        {{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d M Y') }}
                    </td>
                    <td class="px-8 py-6 border-y border-slate-50 dark:border-white/5 font-black text-2xl text-emerald-600">
                        {{ $item->jumlah_barang }}
                    </td>
                    <td class="px-8 py-6 border-y border-slate-50 dark:border-white/5">
                        <span class="px-3 py-1 bg-slate-100 dark:bg-slate-800 rounded-lg text-[10px] font-black uppercase text-slate-500 dark:text-slate-400">
                            {{ $item->satuan_barang }}
                        </span>
                    </td>
                    <td class="px-8 py-6 border-y border-slate-50 dark:border-white/5">
                        @if($item->jumlah_barang > 5)
                        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-emerald-500/10 text-emerald-600 text-[9px] font-black uppercase tracking-widest border border-emerald-500/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            Tersedia
                        </span>
                        @else
                        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-amber-500/10 text-amber-600 text-[9px] font-black uppercase tracking-widest border border-amber-500/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-bounce"></span>
                            Stok Rendah
                        </span>
                        @endif
                    </td>
                    <td class="px-8 py-6 rounded-r-[2.5rem] border-y border-r border-slate-50 dark:border-white/5 text-right">
                        <button class="w-10 h-10 rounded-xl hover:bg-emerald-50 dark:hover:bg-emerald-500/10 text-slate-400 hover:text-emerald-600 transition-all">
                            <i class="fas fa-ellipsis-h"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-20">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-heart-crack text-5xl text-slate-200 mb-4"></i>
                            <p class="text-slate-400 font-bold uppercase text-[10px] tracking-widest">Belum ada donasi barang masuk</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if(method_exists($logistik_list, 'links'))
    <div class="mt-4">
        {{ $logistik_list->appends(request()->query())->links() }}
    </div>
    @endif
</div>

<style>
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fade-in 0.6s ease-out forwards;
    }
    input[type="date"]::-webkit-calendar-picker-indicator {
        cursor: pointer;
        filter: invert(0.5);
    }
</style>
@endsection