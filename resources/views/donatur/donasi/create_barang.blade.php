@extends('layouts.donatur_app')

@section('page_title', 'Donasi Barang')

@section('content')
<div class="w-full max-w-2xl mx-auto px-4 py-8 md:py-12">
    
    <div class="mb-6 lg:mb-8">
        <a href="{{ route('donatur.donasi.index') }}" 
           class="inline-flex items-center px-5 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-2xl font-black uppercase tracking-widest text-[10px] transition-all duration-300 active:scale-95">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <div class="mb-8 text-center">
        <div class="inline-flex items-center justify-center w-16 h-16 md:w-20 md:h-20 bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 rounded-2xl md:rounded-3xl mb-4 md:mb-6">
            <i class="fas fa-box-open text-2xl md:text-4xl"></i>
        </div>
        <h1 class="text-2xl md:text-4xl font-black text-slate-800 dark:text-white uppercase tracking-tighter mb-2">Donasi Barang</h1>
        <p class="text-slate-500 dark:text-slate-400 text-sm md:text-base font-medium px-2">Salurkan bantuan fisik berupa sembako atau barang kebutuhan pokok lainnya.</p>
    </div>

    {{-- Notifikasi Error (Penting agar tahu jika ada data yang salah) --}}
    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-2xl">
            <p class="font-bold text-xs uppercase">Perhatian:</p>
            <ul class="text-sm list-disc ml-4">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('donatur.donasi.store') }}" method="POST" enctype="multipart/form-data"
          class="bg-white dark:bg-slate-900 p-6 md:p-10 lg:p-12 rounded-[2rem] md:rounded-[2.5rem] shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800">
        @csrf
        <input type="hidden" name="jenis_donasi" value="barang">

        <div class="space-y-6">
            {{-- Nama Barang --}}
            <div>
                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 block mb-2 ml-2">Nama Barang</label>
                <input type="text" name="nama_barang" value="{{ old('nama_barang') }}"
                       class="w-full bg-slate-50 dark:bg-slate-800 border-2 border-slate-100 dark:border-slate-700 rounded-2xl md:rounded-3xl px-5 py-4 md:py-5 focus:border-amber-500 outline-none text-base text-slate-800 dark:text-white font-bold transition-all" 
                       placeholder="Contoh: Beras 5kg" required>
            </div>

            {{-- Grid Responsif --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 block mb-2 ml-2">Jumlah</label>
                    <input type="number" name="jumlah_barang" value="{{ old('jumlah_barang') }}"
                           class="w-full bg-slate-50 dark:bg-slate-800 border-2 border-slate-100 dark:border-slate-700 rounded-2xl md:rounded-3xl px-5 py-4 md:py-5 focus:border-amber-500 outline-none text-base text-slate-800 dark:text-white font-bold transition-all" 
                           placeholder="0" required>
                </div>
                <div>
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 block mb-2 ml-2">Satuan</label>
                    <select name="satuan" class="w-full bg-slate-50 dark:bg-slate-800 border-2 border-slate-100 dark:border-slate-700 rounded-2xl md:rounded-3xl px-5 py-4 md:py-5 focus:border-amber-500 outline-none text-base text-slate-800 dark:text-white font-bold transition-all appearance-none cursor-pointer" required>
                        <option value="" disabled selected>Pilih Satuan</option>
                        <option value="kg">Kilogram (Kg)</option>
                        <option value="pcs">Pcs / Buah</option>
                        <option value="liter">Liter</option>
                        <option value="dus">Dus / Karton</option>
                        <option value="karung">Karung</option>
                        <option value="pak">Pak / Pack</option>
                        <option value="set">Set</option>
                    </select>
                </div>
            </div>

            {{-- Kategori (FIXED NAME) --}}
            <div>
                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 block mb-2 ml-2">Kategori Barang</label>
                <select name="id_kategori_barang" class="w-full bg-slate-50 dark:bg-slate-800 border-2 border-slate-100 dark:border-slate-700 rounded-2xl md:rounded-3xl px-5 py-4 md:py-5 focus:border-amber-500 outline-none text-base text-slate-800 dark:text-white font-bold transition-all appearance-none cursor-pointer" required>
                    <option value="" disabled selected>Pilih Kategori</option>
                    @foreach($kategori as $kat)
                        <option value="{{ $kat->id_kategori_barang }}">{{ $kat->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Foto Barang (FIXED NAME) --}}
            <div>
                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 block mb-2 ml-2">Foto Bukti Barang</label>
                <input type="file" name="bukti_donasi" accept="image/*"
                       class="w-full bg-slate-50 dark:bg-slate-800 border-2 border-dashed border-slate-200 dark:border-slate-700 rounded-2xl md:rounded-3xl px-4 py-4 md:py-5 text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-black file:bg-amber-100 file:text-amber-700 hover:file:bg-amber-200 transition-all cursor-pointer" required>
            </div>
        </div>

        <button type="submit" class="w-full mt-10 py-5 bg-amber-600 hover:bg-amber-700 text-white rounded-3xl font-black uppercase tracking-widest text-sm transition-all shadow-lg active:scale-[0.98]">
            Ajukan Donasi Barang
        </button>
    </form>
</div>
@endsection