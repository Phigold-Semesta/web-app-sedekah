@extends('layouts.app')

@section('title', 'Riwayat Kelola Donasi Keseluruhan | SEDEKAH')

@section('content')
<div class="space-y-8 animate__animated animate__fadeIn">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-4xl font-black text-slate-800 dark:text-white tracking-tighter uppercase italic">
                Riwayat Kelola <span class="text-[#008f5d] dark:text-emerald-400">Donasi Keseluruhan</span>
            </h1>
            <div class="flex items-center gap-2 mt-1">
                <span class="h-1 w-8 bg-[#008f5d] dark:bg-emerald-500 rounded-full"></span>
                <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-[0.3em]">
                    Otorisasi, filter, dan monitoring logistik serta finansial yayasan secara terpusat
                </p>
            </div>
        </div>

        {{-- Export Button Dropdown / Action --}}
        <div class="relative inline-block text-left shrink-0">
            <button type="button" id="exportDropdownButton" data-dropdown-toggle="exportDropdown"
                    class="inline-flex items-center px-8 py-4 bg-[#008f5d] dark:bg-emerald-600 text-white text-xs font-black uppercase tracking-widest rounded-[2rem] hover:bg-emerald-700 dark:hover:bg-emerald-500 hover:shadow-[0_15px_30px_rgba(0,143,93,0.3)] transition-all duration-300 group shadow-xl">
                <i class="fas fa-download mr-2 group-hover:scale-110 transition-transform"></i>
                Export Data
                <i class="fas fa-chevron-down ml-2 text-[10px]"></i>
            </button>
            {{-- Dropdown Menu --}}
            <div id="exportDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-emerald-50 dark:border-slate-700 overflow-hidden z-50">
                <a href="{{ route('admin.riwayat_donasi.export', ['format' => 'pdf'] + request()->query()) }}" class="flex items-center px-5 py-3.5 text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-900/50 hover:text-[#008f5d] dark:hover:text-emerald-400 transition-colors">
                    <i class="fas fa-file-pdf mr-2.5 text-red-500 text-sm"></i> Export PDF
                </a>
                <a href="{{ route('admin.riwayat_donasi.export', ['format' => 'excel'] + request()->query()) }}" class="flex items-center px-5 py-3.5 text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-900/50 hover:text-[#008f5d] dark:hover:text-emerald-400 transition-colors">
                    <i class="fas fa-file-excel mr-2.5 text-emerald-500 text-sm"></i> Export Excel
                </a>
                <a href="{{ route('admin.riwayat_donasi.export', ['format' => 'csv'] + request()->query()) }}" class="flex items-center px-5 py-3.5 text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-900/50 hover:text-[#008f5d] dark:hover:text-emerald-400 transition-colors">
                    <i class="fas fa-file-csv mr-2.5 text-blue-500 text-sm"></i> Export CSV
                </a>
            </div>
        </div>
    </div>

    {{-- Filter & Search Section --}}
    <div class="bg-white dark:bg-slate-800 p-6 rounded-[2.5rem] border border-emerald-50 dark:border-slate-700 shadow-sm transition-colors duration-300">
        <form action="{{ route('admin.riwayat_donasi.index') }}" method="GET" class="space-y-4">
            
            {{-- Baris Atas: Search & Limit Baris --}}
            <div class="flex flex-col lg:flex-row gap-4">
                {{-- Search Input --}}
                <div class="flex-1 relative group">
                    <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-[#008f5d] transition-colors">
                        <i class="fas fa-search text-sm"></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari nama donatur atau ID..." 
                           class="w-full pl-12 pr-4 py-4 bg-slate-50 dark:bg-slate-900/50 border-0 rounded-2xl text-xs font-bold focus:ring-2 focus:ring-[#008f5d]/20 dark:text-slate-200 transition-all placeholder:text-slate-400">
                </div>

                {{-- Filter Baris --}}
                <div class="relative min-w-[140px]">
                    <select name="per_page" onchange="this.form.submit()"
                            class="w-full pl-6 pr-10 py-4 bg-slate-50 dark:bg-slate-900/50 border-0 rounded-2xl text-[10px] font-black uppercase tracking-widest focus:ring-2 focus:ring-[#008f5d]/20 dark:text-slate-200 appearance-none cursor-pointer">
                        <option value="5" {{ request('per_page') == '5' ? 'selected' : '' }}>5 Baris</option>
                        <option value="10" {{ request('per_page') == '10' || !request('per_page') ? 'selected' : '' }}>10 Baris</option>
                        <option value="25" {{ request('per_page') == '25' ? 'selected' : '' }}>25 Baris</option>
                        <option value="all" {{ request('per_page') == 'all' ? 'selected' : '' }}>Semua Data</option>
                    </select>
                    <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-[10px] text-slate-400 pointer-events-none"></i>
                </div>
            </div>

            {{-- Baris Bawah: Dropdowns & Date Pickers --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 items-center">
                
                {{-- Jenis Donasi --}}
                <div class="relative">
                    <select name="jenis_donasi" class="w-full pl-6 pr-10 py-4 bg-slate-50 dark:bg-slate-900/50 border-0 rounded-2xl text-[10px] font-black uppercase tracking-widest focus:ring-2 focus:ring-[#008f5d]/20 dark:text-slate-200 appearance-none cursor-pointer">
                        <option value="">Semua Jenis</option>
                        <option value="uang" {{ request('jenis_donasi') == 'uang' ? 'selected' : '' }}>Uang</option>
                        <option value="barang" {{ request('jenis_donasi') == 'barang' ? 'selected' : '' }}>Barang</option>
                    </select>
                    <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-[10px] text-slate-400 pointer-events-none"></i>
                </div>

                {{-- Status Otorisasi --}}
                <div class="relative">
                    <select name="status" class="w-full pl-6 pr-10 py-4 bg-slate-50 dark:bg-slate-900/50 border-0 rounded-2xl text-[10px] font-black uppercase tracking-widest focus:ring-2 focus:ring-[#008f5d]/20 dark:text-slate-200 appearance-none cursor-pointer">
                        <option value="">Semua Status</option>
                        <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="proses" {{ request('status') == 'proses' ? 'selected' : '' }}>Proses</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    </select>
                    <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-[10px] text-slate-400 pointer-events-none"></i>
                </div>

                {{-- Tanggal Mulai --}}
                <div class="relative">
                    <input type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}"
                           class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-900/50 border-0 rounded-2xl text-[10px] font-black uppercase tracking-widest focus:ring-2 focus:ring-[#008f5d]/20 dark:text-slate-200 cursor-pointer">
                </div>

                {{-- Tanggal Selesai --}}
                <div class="relative">
                    <input type="date" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}"
                           class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-900/50 border-0 rounded-2xl text-[10px] font-black uppercase tracking-widest focus:ring-2 focus:ring-[#008f5d]/20 dark:text-slate-200 cursor-pointer">
                </div>

                {{-- Tombol Aksi (Filter & Reset) --}}
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 py-4 bg-emerald-100 dark:bg-emerald-900/30 text-[#008f5d] dark:text-emerald-400 text-[10px] font-black uppercase tracking-widest rounded-2xl hover:bg-[#008f5d] hover:text-white transition-all duration-300 shadow-sm active:scale-95">
                        <i class="fas fa-filter mr-2"></i> Filter
                    </button>
                    
                    <a href="{{ route('admin.riwayat_donasi.index') }}" class="w-12 h-12 flex items-center justify-center bg-slate-100 dark:bg-slate-900/50 text-slate-500 dark:text-slate-400 rounded-2xl hover:bg-red-500 hover:text-white transition-all duration-300 shadow-sm active:scale-95" title="Reset Filter">
                        <i class="fas fa-redo-alt text-xs"></i>
                    </a>
                </div>

            </div>
        </form>
    </div>

    {{-- Table Section --}}
    <div class="overflow-x-auto custom-scrollbar pb-4">
        <table class="w-full border-separate border-spacing-y-4">
            <thead>
                <tr class="text-[#008f5d] dark:text-emerald-400 text-[10px] font-black uppercase tracking-[0.3em] italic">
                    <th class="px-8 py-2 text-left">Donatur Identity</th>
                    <th class="px-8 py-2 text-left">Jenis Donasi</th>
                    <th class="px-8 py-2 text-left">Tanggal Donasi</th>
                    <th class="px-8 py-2 text-left">Status</th>
                    <th class="px-8 py-2 text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($riwayatDonasi as $donasi) 
                <tr class="bg-white dark:bg-slate-900 shadow-xl shadow-emerald-900/5 group transition-all hover:scale-[1.01]">
                    
                    {{-- Avatar & Nama Donatur --}}
                    <td class="px-8 py-6 rounded-l-[2.5rem] border-y border-l border-emerald-50 dark:border-slate-800">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-gradient-to-tr from-emerald-100 to-white dark:from-emerald-900 dark:to-slate-800 rounded-2xl flex items-center justify-center text-emerald-600 font-black italic uppercase shadow-sm border border-emerald-50 dark:border-slate-700">
                                {{ substr($donasi->kunjungan->donatur->nama_donatur ?? 'N', 0, 1) }}
                            </div>
                            <div class="flex flex-col">
                                <span class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-tight group-hover:text-[#008f5d] transition-colors">
                                    {{ $donasi->kunjungan->donatur->nama_donatur ?? 'Hamba Allah' }}
                                </span>
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest italic">Donasi ID #{{ str_pad($donasi->id_donasi, 4, '0', STR_PAD_LEFT) }}</span>
                            </div>
                        </div>
                    </td>

                    {{-- Jenis Donasi --}}
                    <td class="px-8 py-6 border-y border-emerald-50 dark:border-slate-800">
                        <span class="text-[11px] font-black text-[#008f5d] dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/20 px-3 py-1.5 rounded-xl border border-emerald-100 dark:border-emerald-800/30 w-fit uppercase">
                            <i class="{{ $donasi->donasi_uang ? 'fas fa-money-bill-wave' : 'fas fa-box' }} mr-1.5 opacity-80"></i>
                            {{ $donasi->donasi_uang ? 'Uang' : 'Barang' }}
                        </span>
                    </td>

                    {{-- Tanggal Donasi --}}
                    <td class="px-8 py-6 border-y border-emerald-50 dark:border-slate-800">
                        <div class="flex flex-col">
                            <span class="text-xs font-bold text-slate-600 dark:text-slate-300">
                                {{ \Carbon\Carbon::parse($donasi->tgl_donasi)->translatedFormat('d F Y') }}
                            </span>
                        </div>
                    </td>

                    {{-- Status Badge --}}
                    <td class="px-8 py-6 border-y border-emerald-50 dark:border-slate-800">
                        @php
                            $statusClasses = [
                                'selesai' => 'bg-emerald-50 text-emerald-600 border-emerald-100 dark:bg-emerald-900/20 dark:text-emerald-400 dark:border-emerald-800/30',
                                'proses' => 'bg-amber-50 text-amber-600 border-amber-100 dark:bg-amber-900/20 dark:text-amber-400 dark:border-amber-800/30',
                                'pending' => 'bg-red-50 text-red-600 border-red-100 dark:bg-red-900/20 dark:text-red-400 dark:border-red-800/30',
                            ];
                            $class = $statusClasses[strtolower($donasi->status_donasi)] ?? 'bg-slate-50 text-slate-600 border-slate-100';
                        @endphp
                        <span class="inline-flex items-center text-[9px] font-black uppercase tracking-[0.15em] px-4 py-2 rounded-xl border {{ $class }} italic shadow-sm">
                            {{ $donasi->status_donasi }}
                        </span>
                    </td>

                    {{-- Action Buttons --}}
                    <td class="px-8 py-6 rounded-r-[2.5rem] border-y border-r border-emerald-50 dark:border-slate-800 text-center">
                        <div class="flex justify-center gap-3">
                            <a href="{{ route('admin.riwayat_donasi.show', $donasi->id_donasi) }}" 
                               title="Detail Donasi"
                               class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-50 dark:bg-slate-800 text-slate-400 hover:bg-[#008f5d] hover:text-white transition-all shadow-sm active:scale-90">
                                <i class="fas fa-eye text-xs"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-8 py-20 text-center">
                        <div class="flex flex-col items-center">
                            <div class="w-20 h-20 bg-slate-50 dark:bg-slate-900/50 rounded-3xl flex items-center justify-center text-slate-200 dark:text-slate-700 mb-4 border border-slate-100 dark:border-slate-800">
                                <i class="fas fa-box-open text-3xl"></i>
                            </div>
                            <p class="text-xs font-black text-slate-400 uppercase tracking-widest">No donation records found</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Custom Pagination Section --}}
    @if(method_exists($riwayatDonasi, 'hasPages') && $riwayatDonasi->hasPages())
    <div class="flex flex-col md:flex-row items-center justify-between gap-4 mt-6 bg-white dark:bg-slate-800 p-6 rounded-[2.5rem] border border-emerald-50 dark:border-slate-700 shadow-sm transition-colors duration-300">
        <div class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest italic">
            Showing <span class="text-[#008f5d] dark:text-emerald-400">{{ $riwayatDonasi->firstItem() }}</span> to <span class="text-[#008f5d] dark:text-emerald-400">{{ $riwayatDonasi->lastItem() }}</span> of {{ $riwayatDonasi->total() }} Data
        </div>
        <div class="pagination-container">
            {{ $riwayatDonasi->appends(request()->query())->links('pagination::tailwind') }}
        </div>
    </div>
    @elseif(request('per_page') == 'all')
    <div class="mt-6 bg-white dark:bg-slate-800 p-6 rounded-[2.5rem] border border-emerald-50 dark:border-slate-700 text-center">
        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Menampilkan Semua Data ({{ $riwayatDonasi->count() }} Record)</p>
    </div>
    @endif
</div>

<style>
    /* Custom Scrollbar */
    .custom-scrollbar::-webkit-scrollbar { height: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #008f5d22; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #008f5d; }

    /* Gaya Pagination Kustom */
    .pagination-container nav > div:first-child { display: none; }
    
    .pagination-container nav span[aria-current="page"] > span {
        background-color: #008f5d !important;
        border-color: #008f5d !important;
        color: white !important;
        border-radius: 0.75rem;
        box-shadow: 0 4px 12px rgba(0,143,93,0.2);
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
        color: #008f5d;
        transform: translateY(-2px);
    }

    .dark .pagination-container nav a:hover {
        background-color: #064e3b;
        color: #34d399;
    }
</style>

<script>
    // Script sederhana untuk handling Dropdown Export Flowbite/Tailwind manual
    document.addEventListener('DOMContentLoaded', function() {
        const btn = document.getElementById('exportDropdownButton');
        const menu = document.getElementById('exportDropdown');
        
        if(btn && menu) {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                menu.classList.toggle('hidden');
            });
            
            document.addEventListener('click', function() {
                menu.classList.add('hidden');
            });
        }
    });
</script>
@endsection