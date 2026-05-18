@extends('layouts.app')

@section('title', 'Create New Category | SEDEKAH')

@section('content')
{{-- CSS Khusus untuk estetika Premium --}}
<style>
    /* Menghilangkan fitur auto-fill browser yang mengganggu desain */
    input::-ms-reveal, input::-ms-clear { display: none; }
    input::-webkit-contacts-auto-fill-button, 
    input::-webkit-credentials-auto-fill-button {
        visibility: hidden;
        display: none !important;
        pointer-events: none;
    }

    /* Animasi halus untuk focus ring */
    .custom-focus:focus {
        border-color: #10b981 !important; /* emerald-500 */
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
    }
</style>

<div class="p-4 md:p-10 bg-slate-50 dark:bg-slate-950 min-h-screen flex justify-center items-center animate__animated animate__fadeIn">
    <div class="w-full max-w-2xl bg-white dark:bg-slate-900 p-8 md:p-16 rounded-[3rem] md:rounded-[5rem] shadow-2xl border border-slate-100 dark:border-slate-800 relative overflow-hidden">
        
        {{-- Dekorasi Latar Belakang (Subtle) --}}
        <div class="absolute -top-24 -right-24 w-64 h-64 bg-emerald-500/5 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-emerald-500/5 rounded-full blur-3xl"></div>

        <div class="relative z-10">
            {{-- Header Section --}}
            <div class="mb-12 text-center md:text-left">
                <h2 class="text-3xl md:text-4xl font-black text-emerald-900 dark:text-emerald-400 uppercase italic tracking-tighter leading-none">
                    Kategori Barang <span class="text-slate-900 dark:text-white">Baru</span>
                </h2>
                <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-[0.4em] mt-3">
                    SEDEKAH • Inventory Classification Protocol
                </p>
            </div>
            
            <form action="{{ route('admin.kategori_barang.store') }}" method="POST" class="space-y-8" autocomplete="off">
                @csrf
                
                {{-- Category Name Input --}}
                <div class="group space-y-2">
                    <label class="text-[10px] font-black text-slate-400 dark:text-emerald-600/50 uppercase tracking-[0.3em] ml-2 group-focus-within:text-emerald-500 transition-colors">
                        Nama Kategori Barang <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none text-slate-300 group-focus-within:text-emerald-500 transition-colors">
                            <i class="fas fa-tags"></i>
                        </div>
                        <input type="text" name="nama_kategori" value="{{ old('nama_kategori') }}" required autofocus
                            class="w-full pl-14 pr-6 py-5 bg-slate-50 dark:bg-slate-800/50 border-2 @error('nama_kategori') border-red-500 @else border-transparent @enderror rounded-3xl text-sm font-bold text-slate-700 dark:text-white custom-focus transition-all placeholder:normal-case"
                            placeholder="Contoh: Sembako, Pakaian Layak Pakai, Obat-obatan">
                    </div>
                    @error('nama_kategori')
                        <p class="text-xs font-semibold text-red-500 ml-2 animate__animated animate__headShake">
                            <i class="fas fa-exclamation-triangle mr-1"></i> {{ $message }}
                        </p>
                    @enderror
                    <p class="text-[11px] font-medium text-slate-400 dark:text-slate-500 italic leading-relaxed pl-2 mt-2">
                        Pastikan penulisan nama kategori bersifat tunggal, jelas, dan mewakili klasifikasi fisik logistik bantuan di gudang.
                    </p>
                </div>

                {{-- Action Buttons --}}
                <div class="pt-10 flex flex-col md:flex-row gap-4">
                    <button type="submit" id="btn-submit" 
                        class="flex-[2] bg-emerald-600 text-white py-5 rounded-[2rem] font-black text-[11px] uppercase tracking-[0.2em] shadow-xl shadow-emerald-900/20 hover:bg-emerald-700 hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center justify-center gap-3">
                        <i class="fas fa-cloud-arrow-up"></i> Simpan Kategori
                    </button>
                    <a href="{{ route('admin.kategori_barang.index') }}" 
                        class="flex-1 px-8 py-5 bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500 rounded-[2rem] font-black text-[11px] uppercase tracking-[0.2em] hover:bg-red-500 hover:text-white dark:hover:bg-red-600 dark:hover:text-white transition-all text-center flex items-center justify-center">
                        Batalkan
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    /**
     * Loading State pada Submit Button
     */
    const form = document.querySelector('form');
    const btnSubmit = document.getElementById('btn-submit');

    form.onsubmit = function() {
        btnSubmit.disabled = true;
        btnSubmit.classList.add('opacity-70', 'cursor-not-allowed');
        btnSubmit.innerHTML = `
            <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Processing System...
        `;
    };
</script>
@endsection