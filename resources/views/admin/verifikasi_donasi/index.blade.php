@extends('layouts.app')

@section('title', 'Verifikasi Donasi | SEDEKAH')

@section('content')
<div class="space-y-8 animate__animated animate__fadeIn">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-4xl font-black text-slate-800 dark:text-white tracking-tighter uppercase italic">
                Verifikasi <span class="text-[#008f5d] dark:text-emerald-400">Donasi</span>
            </h1>
            <div class="flex items-center gap-2 mt-1">
                <span class="h-1 w-8 bg-[#008f5d] dark:bg-emerald-500 rounded-full"></span>
                <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-[0.3em]">
                    Otorisasi dan validasi transaksi donasi yang tertunda
                </p>
            </div>
        </div>
    </div>

    {{-- Filter & Search Section --}}
    <div class="bg-white dark:bg-slate-800 p-6 rounded-[2.5rem] border border-emerald-50 dark:border-slate-700 shadow-sm transition-colors duration-300">
        <form action="{{ route('admin.verifikasi.index') }}" method="GET" class="flex flex-col lg:flex-row gap-4">
            <div class="flex-1 relative group">
                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-[#008f5d] transition-colors">
                    <i class="fas fa-search text-sm"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari nama donatur atau ID..." 
                       class="w-full pl-12 pr-4 py-4 bg-slate-50 dark:bg-slate-900/50 border-0 rounded-2xl text-xs font-bold focus:ring-2 focus:ring-[#008f5d]/20 dark:text-slate-200 transition-all placeholder:text-slate-400">
            </div>
            <button type="submit" class="px-8 py-4 bg-[#008f5d] text-white text-xs font-black uppercase tracking-widest rounded-2xl hover:bg-emerald-700 transition-all shadow-lg active:scale-95">
                <i class="fas fa-filter mr-2"></i> Filter
            </button>
        </form>
    </div>

    {{-- Table Section --}}
    <div class="overflow-x-auto custom-scrollbar pb-4">
        <table class="w-full border-separate border-spacing-y-4">
            <thead>
                <tr class="text-[#008f5d] dark:text-emerald-400 text-[10px] font-black uppercase tracking-[0.3em] italic">
                    <th class="px-8 py-2 text-left">Donatur Identity</th>
                    <th class="px-8 py-2 text-left">Jenis Donasi</th>
                    <th class="px-8 py-2 text-left">Tanggal</th>
                    <th class="px-8 py-2 text-left">Status</th>
                    <th class="px-8 py-2 text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($donasi_list as $donasi)
                <tr class="bg-white dark:bg-slate-900 shadow-xl shadow-emerald-900/5 transition-all hover:scale-[1.01]">
                    <td class="px-8 py-6 rounded-l-[2.5rem] border-y border-l border-emerald-50 dark:border-slate-800">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-gradient-to-tr from-emerald-100 to-white rounded-2xl flex items-center justify-center text-emerald-600 font-black italic uppercase shadow-sm border border-emerald-50">
                                {{ substr($donasi->kunjungan->donatur->nama_donatur ?? 'N', 0, 1) }}
                            </div>
                            <div class="flex flex-col">
                                <span class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-tight">{{ $donasi->kunjungan->donatur->nama_donatur ?? 'Hamba Allah' }}</span>
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest italic">Donasi ID #{{ str_pad($donasi->id_donasi, 4, '0', STR_PAD_LEFT) }}</span>
                            </div>
                        </div>
                    </td>
                    <td class="px-8 py-6 border-y border-emerald-50 dark:border-slate-800">
                        <span class="text-[11px] font-black text-[#008f5d] bg-emerald-50 px-3 py-1.5 rounded-xl border border-emerald-100 uppercase">
                            {{ $donasi->donasi_uang ? 'Uang' : 'Barang' }}
                        </span>
                    </td>
                    <td class="px-8 py-6 border-y border-emerald-50 dark:border-slate-800">
                        <span class="text-xs font-bold text-slate-600 dark:text-slate-300">{{ $donasi->created_at->format('d F Y') }}</span>
                    </td>
                    <td class="px-8 py-6 border-y border-emerald-50 dark:border-slate-800">
                        <span class="inline-flex items-center text-[9px] font-black uppercase tracking-[0.15em] px-4 py-2 rounded-xl border bg-amber-50 text-amber-600 border-amber-100 italic shadow-sm">
                            {{ $donasi->status_donasi }}
                        </span>
                    </td>
                    <td class="px-8 py-6 rounded-r-[2.5rem] border-y border-r border-emerald-50 dark:border-slate-800 text-center">
                        <form action="{{ route('admin.verifikasi.update', $donasi->id_donasi) }}" method="POST">
                            @csrf
                            <select name="status" onchange="this.form.submit()" class="bg-slate-50 border-0 text-[10px] font-black uppercase tracking-widest rounded-xl p-3 cursor-pointer text-emerald-800 hover:bg-emerald-50 transition">
                                <option value="Pending" selected>Pending</option>
                                <option value="Selesai">Selesai</option>
                                <option value="Ditolak">Ditolak</option>
                            </select>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-8 py-20 text-center text-slate-400 font-black uppercase tracking-widest">Tidak ada data pending</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { height: 6px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #008f5d22; border-radius: 10px; }
</style>
@endsection