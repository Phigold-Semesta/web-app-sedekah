@extends('layouts.donatur_app')

@section('page_title', 'Lacak Donasi')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    {{-- Header Section --}}
    <div class="mb-10 text-left">
        <h1 class="text-3xl md:text-4xl font-black text-slate-800 dark:text-white uppercase tracking-tighter">Lacak Donasi</h1>
        <p class="text-slate-500 dark:text-slate-400 font-medium mt-2">Pantau perjalanan barang donasi Anda secara real-time.</p>
    </div>

    {{-- List Donasi Barang --}}
    <div class="space-y-4">
        @forelse($donasiBarang as $donasi)
            <div class="group bg-white dark:bg-slate-900 p-6 rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-sm hover:shadow-xl hover:border-emerald-500 transition-all duration-300 flex flex-col md:flex-row md:items-center justify-between gap-6">
                
                {{-- Info Donasi --}}
                <div class="flex items-center gap-5">
                    <div class="w-16 h-16 rounded-2xl bg-emerald-50 dark:bg-emerald-900/30 flex items-center justify-center text-emerald-600 dark:text-emerald-400 shrink-0">
                        <i class="fas fa-box-open text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="font-black text-slate-800 dark:text-white text-lg uppercase tracking-tight">{{ $donasi->nama_barang }}</h3>
                        <div class="flex items-center gap-3 mt-1">
                            <span class="text-[10px] font-black uppercase tracking-widest text-slate-400 bg-slate-100 dark:bg-slate-800 px-2 py-1 rounded-lg">ID: {{ $donasi->id_donasi }}</span>
                            <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">
                                {{ \Carbon\Carbon::parse($donasi->tgl_donasi)->format('d M Y') }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Status Badge & Action --}}
                <div class="flex items-center justify-between md:justify-end gap-6 w-full md:w-auto">
                    <div class="px-4 py-2 rounded-full text-[10px] font-black uppercase tracking-widest 
                        @if($donasi->status_donasi == 'menunggu penjemputan') bg-amber-100 text-amber-700 
                        @elseif($donasi->status_donasi == 'sedang dikirim') bg-blue-100 text-blue-700 
                        @else bg-emerald-100 text-emerald-700 @endif">
                        {{ $donasi->status_donasi }}
                    </div>

                    <a href="{{ route('donatur.lacak.show', $donasi->id_donasi) }}" 
                       class="inline-flex items-center justify-center bg-emerald-600 hover:bg-emerald-700 text-white w-12 h-12 rounded-2xl shadow-lg transition-all active:scale-95">
                        <i class="fas fa-location-arrow"></i>
                    </a>
                </div>
            </div>
        @empty
            <div class="text-center py-20 bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-100 dark:border-slate-800">
                <div class="w-20 h-20 bg-slate-50 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                    <i class="fas fa-box-open text-3xl"></i>
                </div>
                <h3 class="font-black text-slate-800 dark:text-white uppercase tracking-tight">Belum Ada Donasi</h3>
                <p class="text-slate-500 text-sm mt-2">Anda belum memiliki riwayat donasi barang yang sedang diproses.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection