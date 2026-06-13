@extends('layouts.app')

@section('title', 'Audit Log Admin | SEDEKAH')

@section('content')
<div class="space-y-8 animate__animated animate__fadeIn">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-4xl font-black text-slate-800 dark:text-white tracking-tighter uppercase italic">
                Audit <span class="text-[#008f5d] dark:text-emerald-400">Log Admin</span>
            </h1>
            <div class="flex items-center gap-2 mt-1">
                <span class="h-1 w-8 bg-[#008f5d] dark:bg-emerald-500 rounded-full"></span>
                <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-[0.3em]">
                    Log Aktivitas Ekosistem Aplikasi SEDEKAH Yayasan Rumah Harapan Karawang
                </p>
            </div>
        </div>
        
        {{-- Statistik Badge --}}
        <div class="bg-white dark:bg-slate-800 px-6 py-4 rounded-[2rem] border border-emerald-50 dark:border-slate-700 shadow-xl shadow-emerald-900/5 flex items-center gap-4 shrink-0 transition-all hover:scale-105">
            <div class="w-10 h-10 bg-emerald-50 dark:bg-emerald-900/30 rounded-xl flex items-center justify-center text-[#008f5d] dark:text-emerald-400">
                <i class="fas fa-fingerprint text-sm"></i>
            </div>
            <div>
                <p class="text-[9px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest">Total Record</p>
                <p class="text-xl font-black text-slate-800 dark:text-white leading-none tracking-tighter">{{ number_format($audit_list->total()) }}</p>
            </div>
        </div>
    </div>

    {{-- Filter & Search Section --}}
    <div class="bg-white dark:bg-slate-800 p-5 rounded-[2.5rem] border border-emerald-50 dark:border-slate-700 shadow-sm transition-colors duration-300">
        <form action="{{ route('admin.audit_log.index') }}" method="GET" class="flex flex-col lg:flex-row gap-4">
            <div class="flex-1 relative group">
                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-[#008f5d] transition-colors">
                    <i class="fas fa-search text-sm"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari aktivitas atau IP address..." 
                       class="w-full pl-12 pr-4 py-4 bg-slate-50 dark:bg-slate-900/50 border-0 rounded-2xl text-xs font-bold focus:ring-2 focus:ring-[#008f5d]/20 dark:text-slate-200 transition-all placeholder:text-slate-400">
            </div>

            <div class="flex flex-wrap gap-3">
                <div class="relative">
                    <select name="per_page" onchange="this.form.submit()"
                            class="pl-6 pr-10 py-4 bg-slate-50 dark:bg-slate-900/50 border-0 rounded-2xl text-[10px] font-black uppercase tracking-widest focus:ring-2 focus:ring-[#008f5d]/20 dark:text-slate-200 appearance-none cursor-pointer">
                        <option value="5" {{ request('per_page') == '5' ? 'selected' : '' }}>5 Baris</option>
                        <option value="10" {{ request('per_page') == '10' ? 'selected' : '' }}>10 Baris</option>
                        <option value="25" {{ request('per_page') == '25' ? 'selected' : '' }}>25 Baris</option>
                        <option value="all" {{ request('per_page') == 'all' ? 'selected' : '' }}>Semua Data</option>
                    </select>
                    <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-[10px] text-slate-400 pointer-events-none"></i>
                </div>

                <button type="submit" class="px-8 py-4 bg-emerald-100 dark:bg-emerald-900/30 text-[#008f5d] dark:text-emerald-400 text-[10px] font-black uppercase tracking-widest rounded-2xl hover:bg-[#008f5d] hover:text-white transition-all duration-300 shadow-sm active:scale-95">
                    <i class="fas fa-filter mr-2"></i> Apply Filter
                </button>

                @if(request()->anyFilled(['search', 'per_page']))
                    <a href="{{ route('admin.audit_log.index') }}" class="w-12 h-12 flex items-center justify-center bg-slate-100 dark:bg-slate-900/50 text-slate-500 rounded-2xl hover:bg-slate-200 transition-all">
                        <i class="fas fa-sync-alt text-xs"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Table Section --}}
    <div class="overflow-x-auto custom-scrollbar pb-4">
        <table class="w-full border-separate border-spacing-y-4">
            <thead>
                <tr class="text-[#008f5d] dark:text-emerald-400 text-[10px] font-black uppercase tracking-[0.3em] italic">
                    <th class="px-8 py-2 text-left">Timeline</th>
                    <th class="px-8 py-2 text-left">Actor Identity</th>
                    <th class="px-8 py-2 text-left">Deskripsi Aktivitas</th>
                    <th class="px-8 py-2 text-left">IP Address</th>
                </tr>
            </thead>
            <tbody>
                @forelse($audit_list as $log)
                <tr class="bg-white dark:bg-slate-900 shadow-xl shadow-emerald-900/5 group transition-all hover:scale-[1.01]">
                    <td class="px-8 py-6 rounded-l-[2.5rem] border-y border-l border-emerald-50 dark:border-slate-800">
                        <span class="text-sm font-black text-slate-800 dark:text-white">{{ \Carbon\Carbon::parse($log->waktu_log)->format('d M Y') }}</span>
                        <span class="block text-[9px] font-bold text-emerald-500 uppercase tracking-widest italic">{{ \Carbon\Carbon::parse($log->waktu_log)->format('H:i:s') }} WIB</span>
                    </td>
                    <td class="px-8 py-6 border-y border-emerald-50 dark:border-slate-800">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-gradient-to-tr from-[#008f5d] to-emerald-400 rounded-xl flex items-center justify-center text-white text-xs font-black shadow-lg shadow-emerald-500/20">
                                {{ strtoupper(substr($log->user->nama_user ?? 'A', 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-[11px] font-black text-slate-800 dark:text-white uppercase">{{ $log->user->nama_user ?? 'System' }}</p>
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest italic">{{ $log->user->role ?? 'Internal' }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-8 py-6 border-y border-emerald-50 dark:border-slate-800">
                        <p class="text-[12px] font-bold text-slate-700 dark:text-slate-300 italic">{{ $log->deskripsi ?? $log->aksi_log }}</p>
                    </td>
                    <td class="px-8 py-6 rounded-r-[2.5rem] border-y border-r border-emerald-50 dark:border-slate-800">
                        {{-- IP Address dengan highlight emerald green dan icon sidik jari --}}
                        <span class="text-[10px] font-mono font-black text-[#008f5d] bg-emerald-50 dark:bg-emerald-900/30 dark:text-emerald-300 px-4 py-2 rounded-xl border border-emerald-100 dark:border-emerald-800">
                            <i class="fas fa-fingerprint mr-1"></i> {{ $log->ip_address ?? 'N/A' }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-8 py-20 text-center text-slate-400 text-xs font-black uppercase tracking-widest">
                        Data log tidak ditemukan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination Section --}}
    @if(method_exists($audit_list, 'hasPages') && $audit_list->hasPages())
    <div class="flex flex-col md:flex-row items-center justify-between gap-4 mt-6 bg-white dark:bg-slate-800 p-6 rounded-[2.5rem] border border-emerald-50 dark:border-slate-700 shadow-sm transition-colors duration-300">
        <div class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest italic">
            Trace log {{ $audit_list->firstItem() }} - {{ $audit_list->lastItem() }} of {{ $audit_list->total() }} Events
        </div>
        <div class="pagination-container">
            {{ $audit_list->appends(request()->query())->links('pagination::tailwind') }}
        </div>
    </div>
    @endif
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { height: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #008f5d22; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #008f5d; }

    .pagination-container nav > div:first-child { display: none; }
    .pagination-container nav span[aria-current="page"] > span {
        background-color: #008f5d !important;
        color: white !important;
        border-radius: 0.75rem;
    }
    .pagination-container nav a {
        border-radius: 0.75rem;
        margin: 0 3px;
        padding: 8px 14px !important;
        font-size: 10px;
        font-weight: 800;
        background-color: #f8fafc;
        transition: all 0.3s ease;
    }
</style>
@endsection