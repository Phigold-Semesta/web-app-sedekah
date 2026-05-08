@extends('layouts.app')

@section('title', 'Monitoring Donatur | SEDEKAH')

@section('content')
<div class="space-y-8 animate__animated animate__fadeIn">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-4xl font-black text-slate-800 dark:text-white tracking-tighter uppercase italic">
                Monitoring <span class="text-[#047857] dark:text-emerald-400">Donatur</span>
            </h1>
            <div class="flex items-center gap-2 mt-1">
                <span class="h-1 w-8 bg-[#047857] dark:bg-emerald-500 rounded-full"></span>
                <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-[0.3em]">
                    Sistem Evaluasi Donasi Ekonomi & Kemanusiaan Amanah (SEDEKAH)
                </p>
            </div>
        </div>

        {{-- Statistik Singkat --}}
        <div class="bg-white dark:bg-slate-800 px-8 py-4 rounded-[2rem] shadow-xl border border-emerald-50 dark:border-slate-700 flex items-center gap-4 group hover:scale-105 transition-all duration-300">
            <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-900/30 rounded-2xl flex items-center justify-center text-[#047857] dark:text-emerald-400">
                <i class="fas fa-users text-xl"></i>
            </div>
            <div>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Total Donatur</p>
                <h3 class="text-2xl font-black text-slate-800 dark:text-white leading-none">
                    {{ $donatur_list instanceof \Illuminate\Pagination\LengthAwarePaginator ? $donatur_list->total() : $donatur_list->count() }}
                </h3>
            </div>
        </div>
    </div>

    {{-- Filter & Search Section --}}
    <div class="bg-white dark:bg-slate-800 p-5 rounded-[2.5rem] border border-emerald-50 dark:border-slate-700 shadow-sm transition-colors duration-300">
        <form action="{{ route('direktur.riwayat_donatur.index') }}" method="GET" class="flex flex-col lg:flex-row gap-4">
            {{-- Search Input --}}
            <div class="flex-1 relative group">
                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-[#047857] transition-colors">
                    <i class="fas fa-search text-sm"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari nama atau nomor HP donatur..." 
                       class="w-full pl-12 pr-4 py-4 bg-slate-50 dark:bg-slate-900/50 border-0 rounded-2xl text-xs font-bold focus:ring-2 focus:ring-[#047857]/20 dark:text-slate-200 transition-all placeholder:text-slate-400">
            </div>

            <div class="flex flex-wrap gap-3">
                {{-- Filter Baris --}}
                <div class="relative">
                    <select name="per_page" onchange="this.form.submit()"
                            class="pl-6 pr-10 py-4 bg-slate-50 dark:bg-slate-900/50 border-0 rounded-2xl text-[10px] font-black uppercase tracking-widest focus:ring-2 focus:ring-[#047857]/20 dark:text-slate-200 appearance-none cursor-pointer">
                        <option value="10" {{ request('per_page') == '10' ? 'selected' : '' }}>10 Baris</option>
                        <option value="25" {{ request('per_page') == '25' ? 'selected' : '' }}>25 Baris</option>
                        <option value="50" {{ request('per_page') == '50' ? 'selected' : '' }}>50 Baris</option>
                        <option value="all" {{ request('per_page') == 'all' ? 'selected' : '' }}>Semua</option>
                    </select>
                    <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-[10px] text-slate-400 pointer-events-none"></i>
                </div>

                <button type="submit" class="px-8 py-4 bg-emerald-100 dark:bg-emerald-900/30 text-[#047857] dark:text-emerald-400 text-[10px] font-black uppercase tracking-widest rounded-2xl hover:bg-[#047857] hover:text-white transition-all duration-300 shadow-sm active:scale-95">
                    <i class="fas fa-filter mr-2"></i> Apply Filter
                </button>
            </div>
        </form>
    </div>

    {{-- Table Section --}}
    <div class="overflow-x-auto custom-scrollbar pb-4">
        <table class="w-full border-separate border-spacing-y-4">
            <thead>
                <tr class="text-[#047857] dark:text-emerald-400 text-[10px] font-black uppercase tracking-[0.3em] italic">
                    <th class="px-8 py-2 text-left">Identity</th>
                    <th class="px-8 py-2 text-left">Contact & Address</th>
                    <th class="px-8 py-2 text-center">Stats Count</th>
                    <th class="px-8 py-2 text-center">Activity Status</th>
                    <th class="px-8 py-2 text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($donatur_list as $donatur) 
                <tr class="bg-white dark:bg-slate-900 shadow-xl shadow-emerald-900/5 group transition-all hover:scale-[1.01]">
                    {{-- Identity --}}
                    <td class="px-8 py-6 rounded-l-[2.5rem] border-y border-l border-emerald-50 dark:border-slate-800">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-gradient-to-tr from-emerald-100 to-white dark:from-emerald-900 dark:to-slate-800 rounded-2xl flex items-center justify-center text-[#047857] font-black italic uppercase shadow-sm border border-emerald-50 dark:border-slate-700">
                                {{ substr($donatur->nama_donatur, 0, 1) }}
                            </div>
                            <div class="flex flex-col">
                                <span class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-tight group-hover:text-[#047857] transition-colors">
                                    {{ $donatur->nama_donatur }}
                                </span>
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest italic">DON-{{ str_pad($donatur->id_donatur, 4, '0', STR_PAD_LEFT) }}</span>
                            </div>
                        </div>
                    </td>

                    {{-- Contact & Address --}}
                    <td class="px-8 py-6 border-y border-emerald-50 dark:border-slate-800">
                        <div class="flex flex-col gap-1">
                            <div class="flex items-center gap-2">
                                <i class="fab fa-whatsapp text-emerald-500 text-xs"></i>
                                <span class="text-[11px] font-black text-slate-700 dark:text-slate-200">{{ $donatur->no_hp }}</span>
                            </div>
                            <span class="text-[10px] text-slate-400 font-medium italic">
                                <i class="fas fa-map-marker-alt mr-1"></i> {{ \Illuminate\Support\Str::limit($donatur->alamat, 35) }}
                            </span>
                        </div>
                    </td>

                    {{-- Stats Count --}}
                    <td class="px-8 py-6 border-y border-emerald-50 dark:border-slate-800 text-center">
                        <div class="flex flex-col gap-2 items-center">
                            <span class="inline-flex items-center text-[9px] font-black uppercase px-3 py-1 rounded-lg bg-slate-50 dark:bg-slate-800 text-slate-600 dark:text-slate-400 border border-slate-100 dark:border-slate-700 italic w-max">
                                <i class="fas fa-walking mr-2 text-[#047857]"></i> {{ $donatur->kunjungan_count }} Kunjungan
                            </span>
                            <span class="inline-flex items-center text-[9px] font-black uppercase px-3 py-1 rounded-lg bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-800/30 italic w-max">
                                <i class="fas fa-hand-holding-heart mr-2"></i> {{ (int)$donatur->donasi_uang_count + (int)$donatur->donasi_barang_count }} Donasi
                            </span>
                        </div>
                    </td>

                    {{-- Activity Status --}}
                    <td class="px-8 py-6 border-y border-emerald-50 dark:border-slate-800 text-center">
                        @if((int)$donatur->donasi_uang_count > 0 || (int)$donatur->donasi_barang_count > 0)
                            <div class="flex flex-col items-center gap-1">
                                <span class="text-[10px] font-black text-[#047857] dark:text-emerald-400 uppercase italic">Donatur Aktif</span>
                                <span class="text-[8px] font-bold text-slate-400 uppercase tracking-tighter">
                                    Terakhir: {{ $donatur->updated_at ? $donatur->updated_at->diffForHumans() : 'Aktif' }}
                                </span>
                            </div>
                        @else
                            <span class="text-[10px] font-bold text-slate-300 uppercase italic tracking-widest">Belum Berdonasi</span>
                        @endif
                    </td>

                    {{-- Actions --}}
                    <td class="px-8 py-6 rounded-r-[2.5rem] border-y border-r border-emerald-50 dark:border-slate-800 text-center">
                        <div class="flex justify-center gap-3">
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $donatur->no_hp) }}" 
                               target="_blank"
                               title="Hubungi WhatsApp"
                               class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-50 dark:bg-slate-800 text-emerald-500 hover:bg-emerald-500 hover:text-white transition-all shadow-sm active:scale-90">
                                <i class="fab fa-whatsapp text-xs"></i>
                            </a>
                            <a href="{{ route('direktur.riwayat_donatur.show', $donatur->id_donatur) }}" 
                               title="Lihat Profil"
                               class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-50 dark:bg-slate-800 text-slate-400 hover:bg-[#047857] hover:text-white transition-all shadow-sm active:scale-90">
                                <i class="fas fa-user-circle text-xs"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-8 py-20 text-center">
                        <div class="flex flex-col items-center">
                            <div class="w-20 h-20 bg-slate-50 dark:bg-slate-900/50 rounded-3xl flex items-center justify-center text-slate-200 dark:text-slate-700 mb-4 border border-slate-100 dark:border-slate-800">
                                <i class="fas fa-database text-3xl"></i>
                            </div>
                            <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Database donatur masih kosong</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Custom Pagination Section --}}
    @if($donatur_list instanceof \Illuminate\Pagination\LengthAwarePaginator && $donatur_list->hasPages())
    <div class="flex flex-col md:flex-row items-center justify-between gap-4 mt-6 bg-white dark:bg-slate-800 p-6 rounded-[2.5rem] border border-emerald-50 dark:border-slate-700 shadow-sm transition-colors duration-300">
        <div class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest italic">
            Showing <span class="text-[#047857] dark:text-emerald-400">{{ $donatur_list->firstItem() }}</span> to <span class="text-[#047857] dark:text-emerald-400">{{ $donatur_list->lastItem() }}</span> of {{ $donatur_list->total() }} Donatur
        </div>
        <div class="pagination-container">
            {{ $donatur_list->appends(request()->query())->links('pagination::tailwind') }}
        </div>
    </div>
    @endif

    {{-- Footer Info --}}
    <div class="text-center mt-12 pb-8">
        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.4em] flex items-center justify-center gap-3">
            <span class="h-px w-8 bg-slate-200 dark:bg-slate-700"></span>
            <i class="fas fa-shield-alt text-emerald-500"></i> Internal Monitoring System • SEDEKAH v2.0
            <span class="h-px w-8 bg-slate-200 dark:bg-slate-700"></span>
        </p>
    </div>
</div>

<style>
    /* Custom Scrollbar */
    .custom-scrollbar::-webkit-scrollbar { height: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #04785722; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #047857; }

    /* Custom Pagination Styling */
    .pagination-container nav > div:first-child { display: none; }
    
    .pagination-container nav span[aria-current="page"] > span {
        background-color: #047857 !important;
        border-color: #047857 !important;
        color: white !important;
        border-radius: 0.75rem;
        box-shadow: 0 4px 12px rgba(4,120,87,0.2);
    }

    .pagination-container nav a, 
    .pagination-container nav span:not([aria-current="page"] > span) {
        border-radius: 0.75rem;
        margin: 0 3px;
        padding: 8px 14px !important;
        font-weight: 800;
        font-size: 10px;
        text-transform: uppercase;
        border: none !important;
        background-color: #f8fafc;
        color: #64748b;
        transition: all 0.3s ease;
    }

    .dark .pagination-container nav a,
    .dark .pagination-container nav span:not([aria-current="page"] > span) {
        background-color: #0f172a;
        color: #94a3b8;
    }

    .pagination-container nav a:hover {
        background-color: #ecfdf5;
        color: #047857;
        transform: translateY(-2px);
    }
</style>
@endsection