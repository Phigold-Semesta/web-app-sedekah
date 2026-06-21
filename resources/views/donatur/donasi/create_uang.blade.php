@extends('layouts.donatur_app')

@section('page_title', 'Donasi Uang')

@section('content')
<div class="w-full max-w-2xl mx-auto px-4 py-8 md:py-12">
    
    <div class="mb-6 lg:mb-8">
        <a href="{{ route('donatur.donasi.index') }}" 
           class="inline-flex items-center px-5 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-2xl font-black uppercase tracking-widest text-[10px] transition-all duration-300 active:scale-95">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <div class="mb-8 lg:mb-12 text-center">
        <div class="inline-flex items-center justify-center w-16 h-16 md:w-20 md:h-20 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 rounded-2xl md:rounded-3xl mb-4 md:mb-6">
            <i class="fas fa-hand-holding-heart text-2xl md:text-4xl"></i>
        </div>
        <h1 class="text-2xl md:text-4xl font-black text-slate-800 dark:text-white uppercase tracking-tighter mb-2">Donasi Kebaikan</h1>
        <p class="text-slate-500 dark:text-slate-400 text-sm md:text-base font-medium px-2">Bantu operasional dan kebutuhan mendesak secara transparan.</p>
    </div>

    {{-- Form action sudah benar, pastikan di web.php route ini didefinisikan sebagai Route::post --}}
    <form action="{{ route('donatur.donasi.store') }}" method="POST" 
          class="bg-white dark:bg-slate-900 p-6 md:p-10 lg:p-12 rounded-[2rem] md:rounded-[2.5rem] shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800">
        @csrf
        <input type="hidden" name="jenis_donasi" value="uang">

        <div class="mb-8">
            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 block mb-3 ml-2">Masukkan Nominal (Rp)</label>
            <div class="relative group">
                <span class="absolute left-5 top-5 text-emerald-600 font-black text-xl md:text-2xl">Rp</span>
                <input type="number" name="jumlah" 
                       min="5000" 
                       class="w-full bg-slate-50 dark:bg-slate-800 border-2 border-slate-100 dark:border-slate-700 rounded-2xl md:rounded-3xl pl-16 py-4 md:py-5 focus:border-emerald-500 outline-none text-xl md:text-2xl text-slate-800 dark:text-white font-black transition-all" 
                       placeholder="100.000" 
                       required>
            </div>
        </div>

        <div class="flex gap-3 mb-10">
            <button type="button" onclick="document.querySelector('input[name=jumlah]').value='50000'" class="flex-1 py-3 bg-slate-100 dark:bg-slate-800 rounded-xl text-xs font-black uppercase text-slate-500 hover:bg-emerald-50 hover:text-emerald-700 transition-all">50rb</button>
            <button type="button" onclick="document.querySelector('input[name=jumlah]').value='100000'" class="flex-1 py-3 bg-slate-100 dark:bg-slate-800 rounded-xl text-xs font-black uppercase text-slate-500 hover:bg-emerald-50 hover:text-emerald-700 transition-all">100rb</button>
            <button type="button" onclick="document.querySelector('input[name=jumlah]').value='250000'" class="flex-1 py-3 bg-slate-100 dark:bg-slate-800 rounded-xl text-xs font-black uppercase text-slate-500 hover:bg-emerald-50 hover:text-emerald-700 transition-all">250rb</button>
        </div>

        <div class="mb-10">
            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 block mb-4 ml-2">Tersedia Melalui</label>
            <div class="grid grid-cols-4 gap-4 text-center">
                <div class="text-slate-400"><i class="fab fa-cc-visa text-2xl mb-1"></i><p class="text-[8px] uppercase">Card</p></div>
                <div class="text-slate-400"><i class="fas fa-university text-2xl mb-1"></i><p class="text-[8px] uppercase">Bank</p></div>
                <div class="text-slate-400"><i class="fas fa-qrcode text-2xl mb-1"></i><p class="text-[8px] uppercase">QRIS</p></div>
                <div class="text-slate-400"><i class="fas fa-wallet text-2xl mb-1"></i><p class="text-[8px] uppercase">E-Wallet</p></div>
            </div>
        </div>

        <button type="submit" 
                class="w-full py-4 md:py-5 bg-emerald-700 hover:bg-emerald-800 text-white rounded-2xl md:rounded-3xl font-black uppercase tracking-widest text-xs transition-all shadow-lg shadow-emerald-700/20 active:scale-[0.98]">
            <i class="fas fa-lock mr-2"></i> Lanjut Pembayaran Aman
        </button>
        
        <p class="text-[10px] text-center mt-6 text-slate-400 uppercase tracking-widest">
            <i class="fas fa-shield-alt mr-1"></i> Transaksi terenkripsi & aman via Midtrans
        </p>
    </form>
</div>
@endsection