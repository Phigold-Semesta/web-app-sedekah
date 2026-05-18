@extends('layouts.app')

@section('title', 'Modify Category | ' . $kategori->nama_kategori)

@section('content')
{{-- CSS Khusus untuk estetika Premium & Clean --}}
<style>
    input::-ms-reveal, input::-ms-clear { display: none; }
    input::-webkit-contacts-auto-fill-button, 
    input::-webkit-credentials-auto-fill-button {
        visibility: hidden; display: none !important; pointer-events: none;
    }

    .custom-focus:focus {
        border-color: #008f5d !important;
        box-shadow: 0 0 0 4px rgba(0, 143, 93, 0.1);
    }
</style>

<div class="max-w-4xl mx-auto space-y-8 p-4 md:p-10 animate__animated animate__fadeInUp">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl md:text-4xl font-black text-slate-800 dark:text-white tracking-tighter uppercase italic leading-none">
                Category <span class="text-[#008f5d]">Modification</span>
            </h1>
            <p class="text-[10px] font-bold text-slate-400 dark:text-emerald-400/60 uppercase tracking-[0.3em] mt-3">
                SEDEKAH • Identity Classification Management Protocol
            </p>
        </div>
        <a href="{{ route('admin.kategori_barang.index') }}" 
            class="group flex items-center justify-center gap-3 px-6 py-3 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest hover:border-[#008f5d] hover:text-[#008f5d] transition-all shadow-sm">
            <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform text-xs"></i> Kembali ke Master List
        </a>
    </div>

    {{-- Main Form Card --}}
    <div class="bg-white dark:bg-slate-900 rounded-[3rem] border border-emerald-50 dark:border-emerald-900/20 shadow-2xl shadow-emerald-900/5 dark:shadow-none overflow-hidden relative">
        {{-- Subtle background decoration --}}
        <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-500/5 rounded-full -mr-16 -mt-16 blur-3xl"></div>

        {{-- Form Tag: Method wajib POST, dengan penegasan @method('PUT') di bawahnya --}}
        <form action="{{ route('admin.kategori_barang.update', $kategori->id_kategori_barang) }}" method="POST" class="p-8 md:p-12 space-y-10 relative z-10" autocomplete="off">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                {{-- ID Badge Indicator --}}
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-slate-50 dark:bg-slate-800 rounded-full border border-slate-100 dark:border-slate-700">
                    <span class="text-[9px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-wider">SYSTEM ID RECORD</span>
                    <span class="text-xs font-black text-[#008f5d]">#{{ $kategori->id_kategori_barang }}</span>
                </div>

                {{-- Category Name Input --}}
                <div class="group space-y-2">
                    <label class="text-[10px] font-black text-slate-400 dark:text-emerald-600/50 uppercase tracking-[0.3em] ml-4 group-focus-within:text-[#008f5d] transition-colors">
                        Nama Kategori Barang <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none text-slate-300 group-focus-within:text-[#008f5d] transition-colors">
                            <i class="fas fa-tags text-sm"></i>
                        </div>
                        <input type="text" name="nama_kategori" value="{{ old('nama_kategori', $kategori->nama_kategori) }}" required autofocus
                            class="w-full pl-14 pr-6 py-5 bg-slate-50 dark:bg-slate-800/50 border-2 @error('nama_kategori') border-red-500 @else border-transparent @enderror rounded-3xl text-sm font-bold text-slate-700 dark:text-white custom-focus transition-all">
                    </div>
                    @error('nama_kategori')
                        <p class="text-xs font-semibold text-red-500 ml-4 animate__animated animate__headShake">
                            <i class="fas fa-exclamation-triangle mr-1"></i> {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            {{-- Informational Override Section --}}
            <div class="pt-6 border-t border-slate-100 dark:border-slate-800">
                <div class="flex items-center">
                    <p class="text-[11px] font-medium text-slate-400 italic leading-relaxed pl-4 border-l-2 border-emerald-500">
                        Perubahan nama akan langsung memperbarui identitas dan relasi pengelompokan pada seluruh entitas barang donasi yang terhubung secara realtime di database Project SEDEKAH.
                    </p>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="pt-6 flex flex-col md:flex-row gap-4">
                <button type="submit" id="btn-update" 
                    class="flex-[2] bg-[#008f5d] text-white py-5 rounded-[2rem] font-black text-[11px] uppercase tracking-[0.2em] shadow-xl shadow-emerald-900/20 hover:bg-emerald-700 hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center justify-center gap-3">
                    <i class="fas fa-sync-alt"></i> Perbarui Kategori
                </button>
                <a href="{{ route('admin.kategori_barang.index') }}" 
                    class="flex-1 px-8 py-5 bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500 rounded-[2rem] font-black text-[11px] uppercase tracking-[0.2em] hover:bg-red-500 hover:text-white transition-all text-center flex items-center justify-center">
                    Batalkan Perubahan
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    /**
     * Loading State saat Submit Form
     */
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.querySelector('form');
        const btnUpdate = document.getElementById('btn-update');

        if(form && btnUpdate) {
            form.onsubmit = function() {
                btnUpdate.disabled = true;
                btnUpdate.classList.add('opacity-70', 'cursor-not-allowed');
                btnUpdate.innerHTML = `
                    <i class="fas fa-circle-notch fa-spin"></i> Synchronizing Category...
                `;
            };
        }
    });
</script>
@endsection