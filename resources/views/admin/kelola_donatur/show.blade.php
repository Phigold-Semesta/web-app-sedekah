@extends('layouts.app')

@section('title', 'Detail Profil Ringkasan Donatur')

@section('content')
<div class="min-h-screen bg-slate-50/50 flex items-center justify-center p-4 antialiased">
    <div class="w-full max-w-2xl bg-white rounded-[2rem] shadow-2xl shadow-slate-200/80 border border-slate-100 p-8 sm:p-10 relative overflow-hidden">
        
        <div class="absolute top-0 left-0 right-0 h-2 bg-gradient-to-r from-emerald-800 via-emerald-600 to-teal-600"></div>

        <div class="mb-8 text-center sm:text-left flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-slate-100 pb-6">
            <div class="space-y-1">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 text-emerald-700 text-[10px] font-black tracking-widest uppercase rounded-full border border-emerald-200/40">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Security Verified Record
                </span>
                <h1 class="text-2xl sm:text-3xl font-black text-emerald-950 tracking-tight uppercase italic mt-1">
                    Profil <span class="text-emerald-600 font-extrabold not-italic">Donatur</span>
                </h1>
                <p class="text-xs font-medium text-slate-400 uppercase tracking-widest">
                    SEDEKAH • DATABASE MASTER ENTITY REGISTRY
                </p>
            </div>
            <div class="flex items-center justify-center">
                <div class="w-16 h-16 bg-emerald-50 rounded-2xl border border-emerald-100 flex items-center justify-center text-emerald-600 shadow-sm">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2v-5M16.5 3.5a2.121 2.121 0 113 3L12 15l-4 1 1-4 7.5-7.5z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-gradient-to-r from-slate-50 to-slate-100/50 rounded-2xl p-4 flex items-center justify-between border border-slate-100">
                <span class="text-[11px] font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16"/>
                    </svg> Primary Entity ID
                </span>
                <span class="text-xs font-black bg-white px-3 py-1 rounded-lg text-emerald-800 font-mono shadow-sm border border-slate-100">
                    #DNTR-{{ str_pad($donatur->id_donatur, 5, '0', STR_PAD_LEFT) }}
                </span>
            </div>

            <div class="p-4 rounded-2xl border border-slate-100 hover:border-slate-200/60 bg-white transition-all duration-200 space-y-1.5">
                <span class="block text-[11px] font-black text-slate-400 uppercase tracking-widest">nama_donatur</span>
                <p class="text-lg font-bold text-slate-800 tracking-tight">{{ $donatur->nama_donatur }}</p>
            </div>

            <div class="p-4 rounded-2xl border border-slate-100 hover:border-slate-200/60 bg-white transition-all duration-200 space-y-1.5">
                <span class="block text-[11px] font-black text-slate-400 uppercase tracking-widest">no_hp</span>
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $donatur->no_hp) }}" target="_blank" 
                    class="inline-flex items-center gap-2 text-emerald-600 font-extrabold hover:text-emerald-700 transition-colors duration-150 group">
                    <svg class="w-4 h-4 text-emerald-500 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                    <span class="underline decoration-emerald-500/30 group-hover:decoration-emerald-600 transition-all">{{ $donatur->no_hp }}</span>
                </a>
            </div>

            <div class="p-4 rounded-2xl border border-slate-100 hover:border-slate-200/60 bg-white transition-all duration-200 space-y-1.5">
                <span class="block text-[11px] font-black text-slate-400 uppercase tracking-widest">alamat</span>
                <p class="text-sm font-semibold text-slate-600 leading-relaxed bg-slate-50/50 p-3 rounded-xl border border-slate-100/50">
                    {{ $donatur->alamat }}
                </p>
            </div>
        </div>

        <div class="mt-10 pt-6 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-end gap-3">
            <a href="{{ route('admin.donatur.index') }}" 
                class="w-full sm:w-auto text-center px-8 py-3.5 bg-slate-100 hover:bg-slate-200/80 rounded-full text-xs font-black text-slate-400 hover:text-slate-500 uppercase tracking-widest transition-all duration-200 order-2 sm:order-1">
                Kembali
            </a>
            <a href="{{ route('admin.donatur.edit', $donatur->id_donatur) }}" 
                class="w-full sm:w-auto text-center px-8 py-3.5 bg-emerald-600 hover:bg-emerald-700 active:scale-[0.98] rounded-full text-xs font-black text-white uppercase tracking-widest shadow-xl shadow-emerald-600/20 hover:shadow-emerald-700/30 transition-all duration-200 order-1 sm:order-2 flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Buka Mode Edit
            </a>
        </div>
    </div>
</div>
@endsection