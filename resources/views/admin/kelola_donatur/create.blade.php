@extends('layouts.app')

@section('title', 'Tambah Entitas Donatur Baru')

@section('content')
<div class="min-h-screen bg-slate-50/50 flex items-center justify-center p-4 antialiased">
    <div class="w-full max-w-2xl bg-white rounded-[2rem] shadow-2xl shadow-slate-200/80 border border-slate-100 p-8 sm:p-10 relative overflow-hidden">
        
        <div class="absolute top-0 left-0 right-0 h-2 bg-gradient-to-r from-emerald-600 via-teal-500 to-emerald-700"></div>

        <div class="mb-8 text-center sm:text-left">
            <h1 class="text-2xl sm:text-3xl font-black text-emerald-950 tracking-tight uppercase italic">
                Pencatatan <span class="text-emerald-600 font-extrabold not-italic">Donatur Baru</span>
            </h1>
            <p class="text-xs sm:text-sm font-medium text-slate-400 mt-1 uppercase tracking-widest">
                SEDEKAH • DATABASE MASTER ENTITY REGISTRY
            </p>
        </div>

        @if ($errors->any())
            <div class="mb-6 p-4 bg-rose-50 border border-rose-100 rounded-2xl flex items-center gap-3 text-rose-600">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <span class="text-xs sm:text-sm font-bold tracking-wide">Gagal memproses data! Silakan periksa kembali isian database Anda.</span>
            </div>
        @endif

        <form action="{{ route('admin.donatur.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="space-y-2">
                <label for="nama_donatur" class="block text-[11px] font-black text-slate-400 uppercase tracking-widest pl-1">
                    Nama Donatur <span class="text-rose-500">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-emerald-600 transition-colors duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <input type="text" name="nama_donatur" id="nama_donatur" 
                        class="w-full pl-12 pr-4 py-3.5 bg-slate-50 border border-slate-100 rounded-full text-sm font-bold text-slate-700 placeholder-slate-300 focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 focus:bg-white transition-all duration-300"
                        value="{{ old('nama_donatur') }}" placeholder="Masukkan nama lengkap donatur..." required>
                </div>
                @error('nama_donatur')
                    <p class="text-xs font-bold text-rose-500 pl-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-2">
                <label for="no_hp" class="block text-[11px] font-black text-slate-400 uppercase tracking-widest pl-1">
                    Nomor HP <span class="text-rose-500">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-emerald-600 transition-colors duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                    </div>
                    <input type="text" name="no_hp" id="no_hp" 
                        class="w-full pl-12 pr-4 py-3.5 bg-slate-50 border border-slate-100 rounded-full text-sm font-bold text-slate-700 placeholder-slate-300 focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 focus:bg-white transition-all duration-300"
                        value="{{ old('no_hp') }}" placeholder="Contoh: 081234567xxx" required>
                </div>
                @error('no_hp')
                    <p class="text-xs font-bold text-rose-500 pl-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-2">
                <label for="alamat" class="block text-[11px] font-black text-slate-400 uppercase tracking-widest pl-1">
                    Alamat <span class="text-rose-500">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute top-4 left-0 pl-4 pointer-events-none text-slate-400 group-focus-within:text-emerald-600 transition-colors duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <textarea name="alamat" id="alamat" rows="3" 
                        class="w-full pl-12 pr-4 py-3.5 bg-slate-50 border border-slate-100 rounded-[1.5rem] text-sm font-bold text-slate-700 placeholder-slate-300 focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 focus:bg-white transition-all duration-300 resize-none"
                        placeholder="Tuliskan alamat lengkap domisili donatur..." required>{{ old('alamat') }}</textarea>
                </div>
                @error('alamat')
                    <p class="text-xs font-bold text-rose-500 pl-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-4 flex flex-col sm:flex-row items-center justify-end gap-3">
                <a href="{{ route('admin.donatur.index') }}" 
                    class="w-full sm:w-auto text-center px-8 py-3.5 bg-slate-100 hover:bg-slate-200/80 rounded-full text-xs font-black text-slate-400 hover:text-slate-500 uppercase tracking-widest transition-all duration-200 order-2 sm:order-1">
                    Kembali
                </a>
                <button type="submit" 
                    class="w-full sm:w-auto px-8 py-3.5 bg-emerald-600 hover:bg-emerald-700 active:scale-[0.98] rounded-full text-xs font-black text-white uppercase tracking-widest shadow-xl shadow-emerald-600/20 hover:shadow-emerald-700/30 transition-all duration-200 order-1 sm:order-2 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                    Tambahkan Donatur
                </button>
            </div>
        </form>
    </div>
</div>
@endsection