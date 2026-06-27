@extends('layouts.donatur_app')

@section('page_title', 'Donasi')

@section('content')
<div class="max-w-5xl mx-auto px-4 lg:px-0">
    <div class="text-center mb-12">
        <h2 class="text-3xl lg:text-4xl font-black text-slate-800 dark:text-white uppercase tracking-tighter">Pilih Jenis Donasi</h2>
        <p class="text-slate-500 dark:text-slate-400 font-medium mt-2 text-sm lg:text-base">Salurkan kebaikan Anda melalui metode yang tersedia di bawah ini.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 justify-items-center">
        
        <div class="w-full max-w-sm bg-white dark:bg-slate-900 p-8 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-sm hover:shadow-2xl hover:border-emerald-500 dark:hover:border-emerald-500 transition-all duration-300">
            <div class="w-16 h-16 bg-emerald-50 dark:bg-emerald-900/30 rounded-2xl flex items-center justify-center text-emerald-600 dark:text-emerald-400 mb-6">
                <i class="fas fa-hand-holding-heart text-3xl"></i>
            </div>
            <h3 class="text-xl font-black text-slate-800 dark:text-white mb-2">Donasi Uang</h3>
            <p class="text-slate-500 dark:text-slate-400 text-sm mb-6 leading-relaxed">Bantu operasional dan kebutuhan mendesak melalui bantuan tunai secara transparan.</p>
            <a href="{{ route('donatur.donasi.create', ['jenis' => 'uang']) }}" 
               class="block w-full text-center bg-emerald-700 text-white hover:bg-emerald-800 py-3 rounded-xl font-black uppercase text-xs transition-all active:scale-95">
                Donasi Sekarang
            </a>
        </div>

        <div class="w-full max-w-sm bg-white dark:bg-slate-900 p-8 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-sm hover:shadow-2xl hover:border-amber-500 dark:hover:border-amber-500 transition-all duration-300">
            <div class="w-16 h-16 bg-amber-50 dark:bg-amber-900/30 rounded-2xl flex items-center justify-center text-amber-600 dark:text-amber-400 mb-6">
                <i class="fas fa-box-open text-3xl"></i>
            </div>
            <h3 class="text-xl font-black text-slate-800 dark:text-white mb-2">Donasi Barang</h3>
            <p class="text-slate-500 dark:text-slate-400 text-sm mb-6 leading-relaxed">Salurkan bantuan berupa sembako, pakaian, atau kebutuhan pokok lainnya kepada yang membutuhkan.</p>
            <a href="{{ route('donatur.donasi.create', ['jenis' => 'barang']) }}" 
               class="block w-full text-center bg-amber-600 text-white hover:bg-amber-700 py-3 rounded-xl font-black uppercase text-xs transition-all active:scale-95">
                Donasi Sekarang
            </a>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if(session('success'))
    <script>
        Swal.fire({
            title: 'Jazakalloh Khoiron!',
            text: '{{ session("success") }}',
            icon: 'success',
            confirmButtonText: 'Tutup',
            confirmButtonColor: '#059669', // Emerald-600
            background: '#ffffff',
            color: '#1e293b',
            customClass: {
                title: 'font-black uppercase tracking-widest',
                confirmButton: 'font-black uppercase'
            }
        });
    </script>
@endif
@endsection