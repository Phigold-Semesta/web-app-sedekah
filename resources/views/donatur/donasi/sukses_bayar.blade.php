@extends('layouts.donatur_app')

@section('content')
<div class="container mx-auto px-3 sm:px-4 py-6 sm:py-12">
    <div class="max-w-2xl mx-auto bg-white shadow-2xl rounded-2xl sm:rounded-3xl overflow-hidden border border-gray-100">
        
        <div class="bg-emerald-700 p-5 sm:p-8 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 sm:w-20 sm:h-20 bg-amber-400 rounded-full mb-4 sm:mb-6 shadow-lg">
                <svg class="w-8 h-8 sm:w-10 sm:h-10 text-emerald-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-white mb-2 tracking-tight">Sedekah Diterima</h1>
            <p class="text-amber-100 font-medium italic text-sm sm:text-base px-2">"Berbagi kebahagiaan, menyemai kebaikan."</p>
        </div>

        <div class="p-5 sm:p-8">
            <div class="bg-emerald-50 rounded-xl sm:rounded-2xl p-4 sm:p-6 mb-6 sm:mb-8 border-l-4 border-emerald-600">
                <h2 class="text-xs sm:text-sm font-bold text-emerald-800 mb-3 sm:mb-4 uppercase tracking-widest">Detail Donasi</h2>
                <div class="space-y-3 sm:space-y-4">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-1 sm:gap-0 border-b border-emerald-100 pb-2">
                        <span class="text-gray-600 font-medium text-sm sm:text-base">Order ID</span>
                        <span class="font-bold text-gray-900 text-sm sm:text-base break-all">{{ $donasiUang->order_id }}</span>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-1 sm:gap-0 border-b border-emerald-100 pb-2">
                        <span class="text-gray-600 font-medium text-sm sm:text-base">Nominal Donasi</span>
                        <span class="font-bold text-emerald-700 text-base sm:text-lg break-words">Rp {{ number_format($donasiUang->nominal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-1 sm:gap-0">
                        <span class="text-gray-600 font-medium text-sm sm:text-base">Status</span>
                        <span class="self-start sm:self-auto px-3 sm:px-4 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-bold uppercase tracking-wider">Berhasil</span>
                    </div>
                </div>
            </div>

            <div class="flex flex-col gap-3 sm:gap-4">
                <a href="{{ route('donatur.riwayat.index') }}" 
                   class="w-full text-center py-3.5 sm:py-4 bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-sm sm:text-base rounded-xl transition duration-300 shadow-lg transform hover:-translate-y-1">
                    Lihat Riwayat Donasi
                </a>
                <a href="{{ route('donatur.dashboard') }}" 
                   class="w-full text-center py-3.5 sm:py-4 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-sm sm:text-base rounded-xl transition duration-300">
                    Kembali ke Dashboard
                </a>
            </div>
        </div>

        <div class="h-3 bg-amber-400"></div>
    </div>
</div>
@endsection