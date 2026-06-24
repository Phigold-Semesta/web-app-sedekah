@extends('layouts.donatur_app')

@section('page_title', 'Selesaikan Pembayaran')

@section('content')
<div class="w-full max-w-lg mx-auto px-4 py-12 text-center">
    <div class="bg-white dark:bg-slate-900 p-8 md:p-10 rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-2xl">
        
        <div class="w-20 h-20 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-receipt text-3xl"></i>
        </div>

        <h2 class="text-2xl font-black text-slate-800 dark:text-white uppercase tracking-tighter mb-2">Total Donasi</h2>
        <p class="text-4xl font-black text-emerald-600 mb-8">Rp {{ number_format($donasiUang->nominal, 0, ',', '.') }}</p>

        <button id="pay-button" 
                class="w-full py-5 bg-emerald-700 hover:bg-emerald-800 text-white rounded-2xl font-black uppercase tracking-widest text-xs transition-all shadow-lg shadow-emerald-700/20 active:scale-[0.98]">
            <i class="fas fa-credit-card mr-2"></i> Bayar Sekarang
        </button>

        <p class="text-[10px] text-slate-400 mt-6 uppercase tracking-widest">
            Pastikan koneksi Anda stabil saat melakukan pembayaran.
        </p>
    </div>
</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
<script>
    var payButton = document.getElementById('pay-button');
    payButton.addEventListener('click', function () {
        window.snap.pay('{{ $snapToken }}', {
            onSuccess: function(result){
                // Indikator loading untuk kesan aplikasi profesional
                payButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Memproses Verifikasi...';
                payButton.classList.add('opacity-75', 'cursor-wait');
                
                // Jeda 1 detik untuk memastikan webhook terproses di sisi server
                setTimeout(function() {
                    window.location.href = "{{ route('donatur.donasi.sukses', $donasiUang->id_donasi_uang) }}";
                }, 1000);
            },
            onPending: function(result){
                alert("Menunggu pembayaran Anda!");
            },
            onError: function(result){
                alert("Pembayaran gagal!");
            }
        });
    });
</script>
@endsection