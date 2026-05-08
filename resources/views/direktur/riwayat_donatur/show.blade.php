@extends('layouts.app')

@section('title', 'Detail Donatur')
@section('page_title', 'Detail & Riwayat Donatur')

@section('content')
<div class="space-y-6">
    {{-- Header Profil Donatur --}}
    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] p-8 shadow-sm border border-slate-100 dark:border-slate-800">
        <div class="flex flex-col md:flex-row gap-8 items-center">
            <div class="relative">
                {{-- PERBAIKAN: Menggunakan nama_donatur sesuai field DB --}}
                <img src="https://ui-avatars.com/api/?name={{ urlencode($donatur->nama_donatur) }}&background=008f5d&color=fff&size=128&bold=true" 
                     class="w-32 h-32 rounded-[2rem] shadow-2xl border-4 border-white dark:border-slate-700">
                <div class="absolute -bottom-2 -right-2 bg-emerald-500 text-white p-2 rounded-lg shadow-lg">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
            
            <div class="flex-1 text-center md:text-left">
                {{-- PERBAIKAN: Field nama_donatur --}}
                <h3 class="text-3xl font-black text-slate-800 dark:text-white uppercase tracking-tighter italic">{{ $donatur->nama_donatur }}</h3>
                <p class="text-emerald-600 font-bold tracking-widest text-xs uppercase mb-4">{{ $donatur->email ?? 'Donatur Tetap SEDEKAH' }}</p>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="p-4 bg-slate-50 dark:bg-white/5 rounded-2xl border border-slate-100 dark:border-white/5">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">WhatsApp/Telepon</label>
                        <span class="text-sm font-bold text-slate-700 dark:text-emerald-50">
                            <i class="fab fa-whatsapp text-emerald-500 mr-2"></i>{{ $donatur->no_hp }}
                        </span>
                    </div>
                    <div class="p-4 bg-slate-50 dark:bg-white/5 rounded-2xl border border-slate-100 dark:border-white/5">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Alamat</label>
                        <span class="text-sm font-bold text-slate-700 dark:text-emerald-50">
                            <i class="fas fa-map-marker-alt text-red-500 mr-2"></i>{{ $donatur->alamat }}
                        </span>
                    </div>
                    <div class="p-4 bg-slate-50 dark:bg-white/5 rounded-2xl border border-slate-100 dark:border-white/5">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Bergabung Sejak</label>
                        <span class="text-sm font-bold text-slate-700 dark:text-emerald-50">
                            <i class="fas fa-calendar-alt text-blue-500 mr-2"></i>
                            {{ $donatur->created_at ? $donatur->created_at->format('d F Y') : '-' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        {{-- Riwayat Donasi Uang --}}
        <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden">
            <div class="p-6 border-b border-slate-50 dark:border-white/5 flex items-center justify-between">
                <h4 class="font-black text-slate-800 dark:text-white text-sm uppercase tracking-widest">
                    <i class="fas fa-wallet text-emerald-500 mr-2"></i>Riwayat Donasi Uang
                </h4>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-slate-50 dark:bg-white/5">
                        <tr>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Tanggal</th>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Nominal</th>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 dark:divide-white/5">
                        {{-- PERBAIKAN: Menggunakan variabel $riwayat_uang dari Controller --}}
                        @forelse($riwayat_uang as $uang)
                        <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                            <td class="px-6 py-4 text-xs font-bold">{{ $uang->created_at->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 text-xs font-black text-emerald-600">Rp {{ number_format($uang->nominal, 0, ',', '.') }}</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400 rounded-full text-[9px] font-black uppercase">Lunas</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-10 text-center text-xs font-bold text-slate-400 italic">Belum ada riwayat donasi uang</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Riwayat Donasi Barang --}}
        <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden">
            <div class="p-6 border-b border-slate-50 dark:border-white/5 flex items-center justify-between">
                <h4 class="font-black text-slate-800 dark:text-white text-sm uppercase tracking-widest">
                    <i class="fas fa-box-open text-orange-500 mr-2"></i>Riwayat Donasi Barang
                </h4>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-slate-50 dark:bg-white/5">
                        <tr>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Tanggal</th>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama Barang</th>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 dark:divide-white/5">
                        {{-- PERBAIKAN: Menggunakan variabel $riwayat_barang dari Controller --}}
                        @forelse($riwayat_barang as $barang)
                        <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                            <td class="px-6 py-4 text-xs font-bold">{{ $barang->created_at->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 text-xs font-black text-slate-700 dark:text-emerald-50">{{ $barang->nama_barang }}</td>
                            <td class="px-6 py-4 text-xs font-bold">{{ $barang->jumlah }} {{ $barang->satuan }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-10 text-center text-xs font-bold text-slate-400 italic">Belum ada riwayat donasi barang</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    {{-- Tombol Kembali --}}
    <div class="flex justify-start pt-4">
        <a href="{{ route('direktur.riwayat_donatur.index') }}" 
           class="px-8 py-3 bg-slate-800 text-white rounded-2xl font-black text-[10px] tracking-[0.2em] uppercase hover:bg-slate-700 transition-all shadow-lg">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Monitoring
        </a>
    </div>
</div>
@endsection