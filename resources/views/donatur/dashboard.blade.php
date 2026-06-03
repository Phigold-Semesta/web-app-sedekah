@extends('layouts.donatur_app')

@section('title', 'Dashboard Donatur')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-white p-6 rounded-2xl shadow-lg border-l-8 border-pln-blue">
        <p class="text-sm text-gray-500 font-bold uppercase">Total Donasi</p>
        <h3 class="text-2xl font-black text-slate-800">Rp 0</h3>
    </div>
    
    <div class="bg-white p-6 rounded-2xl shadow-lg border-l-8 border-pln-yellow">
        <p class="text-sm text-gray-500 font-bold uppercase">Status Akun</p>
        <h3 class="text-2xl font-black text-slate-800">Aktif</h3>
    </div>
</div>

<div class="mt-8 bg-white p-8 rounded-2xl shadow-xl">
    <h2 class="text-xl font-black text-pln-blue uppercase mb-4">Selamat Datang</h2>
    <p class="text-gray-600 font-medium">
        Terima kasih telah bergabung dengan SOWAN V2. Silakan gunakan menu di samping untuk mengelola donasi dan kunjungan Anda.
    </p>
</div>
@endsection