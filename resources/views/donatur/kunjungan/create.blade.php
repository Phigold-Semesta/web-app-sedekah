<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SEDEKAH | Yayasan Rumah Harapan Karawang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
    
    <style>
        body { background: linear-gradient(135deg, #f0fdfa 0%, #ccfbf1 50%, #fefce8 100%); }
        .glass-card { background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.5); }
        
        #modal-sukses {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            backdrop-filter: blur(6px);
            z-index: 9999;
            align-items: center;
            justify-content: center;
        }
        #modal-sukses.show { display: flex; }

        @keyframes popIn {
            0% { transform: scale(0.7); opacity: 0; }
            80% { transform: scale(1.05); }
            100% { transform: scale(1); opacity: 1; }
        }
        .modal-box { animation: popIn 0.4s ease forwards; }
    </style>
</head>
<body class="min-h-screen p-4 md:p-8 flex items-center justify-center">

{{-- ========== MODAL SUKSES ========== --}}
<div id="modal-sukses">
    <div class="modal-box bg-white rounded-[2.5rem] shadow-2xl p-10 max-w-sm w-full mx-4 text-center">
        <div class="w-24 h-24 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-14 h-14 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <h2 class="text-2xl font-black text-emerald-800 mb-2">Jazakallah Khairan!</h2>
        <p class="text-emerald-700 font-semibold mb-1" id="modal-nama-donatur"></p>
        <p class="text-slate-500 text-sm mb-2" id="modal-pesan">Donasi Anda telah berhasil dikirim.</p>
        <p class="text-slate-400 text-xs mb-8">Semoga menjadi amal jariyah yang terus mengalir 🤲</p>
        <button onclick="tutupModal()" 
            class="w-full bg-emerald-600 text-white py-4 rounded-2xl font-black uppercase tracking-widest text-sm hover:bg-emerald-700 transition-all">
            Selesai
        </button>
    </div>
</div>
{{-- ========== END MODAL ========== --}}

<div class="w-full max-w-xl">
    <div class="text-center mb-8">
        <h1 class="text-4xl font-extrabold text-emerald-900 tracking-tight italic">SEDEKAH</h1>
        <p class="text-emerald-700/80 font-medium">Yayasan Rumah Harapan Karawang</p>
    </div>

    <form id="main_form" action="{{ route('donatur.kunjungan.store') }}" method="POST" enctype="multipart/form-data" 
          class="glass-card p-8 md:p-10 rounded-[2.5rem] shadow-2xl space-y-6">
        @csrf
        <input type="hidden" name="jenis_donasi" id="jenis_donasi_input" value="">

        <div class="space-y-4">
            <h3 class="text-[11px] font-black text-emerald-800 uppercase tracking-widest pl-2">Informasi Tamu</h3>
            <input type="email" name="email" id="input_email" class="w-full bg-white/50 border border-emerald-100 rounded-3xl p-4 text-sm font-medium focus:ring-4 focus:ring-emerald-200 outline-none transition-all" placeholder="Alamat Email (Opsional)">
            <input type="text" name="nama_donatur" id="input_nama" required class="w-full bg-white/50 border border-emerald-100 rounded-3xl p-4 text-sm font-medium focus:ring-4 focus:ring-emerald-200 outline-none transition-all" placeholder="Nama Lengkap">
            <input type="text" name="no_hp" id="input_nohp" required class="w-full bg-white/50 border border-emerald-100 rounded-3xl p-4 text-sm font-medium focus:ring-4 focus:ring-emerald-200 outline-none transition-all" placeholder="Nomor WhatsApp">
            <textarea name="alamat" id="input_alamat" required class="w-full bg-white/50 border border-emerald-100 rounded-3xl p-4 text-sm font-medium focus:ring-4 focus:ring-emerald-200 outline-none transition-all" placeholder="Alamat Lengkap"></textarea>
        </div>

        <select name="tujuan_kunjungan" id="input_tujuan" required class="w-full bg-white/50 border border-emerald-100 rounded-3xl p-4 text-sm font-medium focus:ring-4 focus:ring-emerald-200 outline-none transition-all text-slate-500">
            <option value="" disabled selected>Pilih Tujuan Kunjungan</option>
            <option value="Silaturahmi">Silaturahmi</option>
            <option value="Donasi">Donasi</option>
            <option value="Lainnya">Lainnya</option>
        </select>

        <div class="space-y-2">
            <p class="text-[10px] font-bold text-emerald-800 uppercase tracking-widest pl-2">Opsional: Jika Ingin Berdonasi</p>
            <div class="grid grid-cols-2 gap-4">
                <button type="button" onclick="toggleDonasi('uang')" class="py-4 bg-emerald-600 text-white rounded-2xl font-bold text-sm shadow-lg shadow-emerald-200 hover:bg-emerald-700 transition-all">Donasi Uang</button>
                <button type="button" onclick="toggleDonasi('barang')" class="py-4 bg-amber-500 text-white rounded-2xl font-bold text-sm shadow-lg shadow-amber-200 hover:bg-amber-600 transition-all">Donasi Barang</button>
            </div>
        </div>

        <div id="form_uang" class="hidden space-y-4 p-6 bg-emerald-50 rounded-3xl border border-emerald-100">
            <input type="number" name="nominal" oninput="checkNominal(this.value)" class="w-full bg-white rounded-2xl p-4 text-sm shadow-inner border border-emerald-100" placeholder="Nominal Uang (Rp)">
            <div id="payment_info_box" class="hidden mt-4 p-6 bg-emerald-700 rounded-3xl text-white shadow-xl">
                <p class="text-[10px] uppercase tracking-widest opacity-80">Total Donasi Anda</p>
                <h2 id="display_amount" class="text-2xl font-black my-2">Rp 0</h2>
                <button type="button" onclick="processPayment(event)" class="w-full bg-white text-emerald-700 py-3 rounded-2xl font-black uppercase text-xs hover:bg-emerald-50 transition-all">
                    BAYAR SEKARANG
                </button>
            </div>
        </div>

        <div id="form_barang" class="hidden space-y-4 p-6 bg-amber-50 rounded-3xl border border-amber-100">
            <input type="text" name="nama_barang" class="w-full bg-white rounded-2xl p-4 text-sm border border-amber-100" placeholder="Nama Barang">
            <select name="id_kategori_barang" class="w-full bg-white rounded-2xl p-4 text-sm text-slate-500 border border-amber-100">
                <option value="">Pilih Jenis Barang</option>
                <option value="1">Sembako</option>
                <option value="2">Pakaian</option>
                <option value="3">Peralatan</option>
            </select>
            <div class="grid grid-cols-2 gap-4">
                <input type="number" name="jumlah_barang" class="w-full bg-white rounded-2xl p-4 text-sm border border-amber-100" placeholder="Jumlah">
                <input type="text" name="satuan_barang" class="w-full bg-white rounded-2xl p-4 text-sm border border-amber-100" placeholder="Satuan (Kg/Pcs)">
            </div>
            <label class="block text-[10px] font-bold text-amber-800 uppercase pl-2">Foto Bukti Barang</label>
            <input type="file" name="foto_barang" accept="image/*" capture="environment" class="w-full p-4 bg-white rounded-2xl border-2 border-dashed border-amber-200 text-sm cursor-pointer">
        </div>

        <button type="submit" id="btn-submit" class="w-full bg-emerald-700 text-white py-5 rounded-3xl font-black uppercase tracking-widest text-sm hover:bg-emerald-800 hover:scale-[1.02] active:scale-[0.98] transition-all shadow-xl shadow-emerald-200">
            Simpan Data Kunjungan
        </button>
    </form>
</div>

<script>
    // ========== SIMPAN DATA FORM KE SESSIONSTORAGE ==========
    function simpanFormKeSession() {
        sessionStorage.setItem('saved_form', JSON.stringify({
            email        : document.getElementById('input_email').value,
            nama_donatur : document.getElementById('input_nama').value,
            no_hp        : document.getElementById('input_nohp').value,
            alamat       : document.getElementById('input_alamat').value,
            tujuan       : document.getElementById('input_tujuan').value,
        }));
    }

    // ========== RESTORE DATA FORM DARI SESSIONSTORAGE ==========
    function restoreFormDariSession() {
        const saved = sessionStorage.getItem('saved_form');
        if (!saved) return;
        const f = JSON.parse(saved);
        if (f.email)        document.getElementById('input_email').value   = f.email;
        if (f.nama_donatur) document.getElementById('input_nama').value    = f.nama_donatur;
        if (f.no_hp)        document.getElementById('input_nohp').value    = f.no_hp;
        if (f.alamat)       document.getElementById('input_alamat').value  = f.alamat;
        if (f.tujuan)       document.getElementById('input_tujuan').value  = f.tujuan;
    }

    // ========== CEK SAAT HALAMAN LOAD ==========
    document.addEventListener('DOMContentLoaded', function () {
        // Selalu restore form dulu supaya tidak kosong
        restoreFormDariSession();

        const urlParams = new URLSearchParams(window.location.search);
        const status    = urlParams.get('status');
        const orderId   = urlParams.get('order_id');

        // Tampilkan modal jika redirect dari Midtrans dengan ?status=success
        if (status === 'success' && orderId) {
            const nama = sessionStorage.getItem('nama_donatur_pending') || 'Donatur';
            sessionStorage.removeItem('nama_donatur_pending');
            sessionStorage.removeItem('saved_form');
            tampilkanModal(nama, 'Donasi uang Anda telah berhasil dikirim.');
            window.history.replaceState({}, document.title, window.location.pathname);
        }
    });

    function toggleDonasi(type) {
        const formUang  = document.getElementById('form_uang');
        const formBarang = document.getElementById('form_barang');
        const inputJenis = document.getElementById('jenis_donasi_input');
        if (type === 'uang') {
            formUang.classList.remove('hidden');
            formBarang.classList.add('hidden');
            inputJenis.value = 'uang';
        } else {
            formUang.classList.add('hidden');
            formBarang.classList.remove('hidden');
            inputJenis.value = 'barang';
        }
    }

    function checkNominal(val) {
        const infoBox      = document.getElementById('payment_info_box');
        const displayAmount = document.getElementById('display_amount');
        if (val > 0) {
            infoBox.classList.remove('hidden');
            displayAmount.innerText = 'Rp ' + parseInt(val).toLocaleString('id-ID');
        } else {
            infoBox.classList.add('hidden');
        }
    }

    function tampilkanModal(nama, pesan) {
        document.getElementById('modal-nama-donatur').innerText = nama || '';
        document.getElementById('modal-pesan').innerText = pesan || 'Data Anda telah berhasil disimpan.';
        document.getElementById('modal-sukses').classList.add('show');
    }

    function tutupModal() {
        document.getElementById('modal-sukses').classList.remove('show');
        // Hapus session & reset form setelah user klik Selesai
        sessionStorage.removeItem('saved_form');
        sessionStorage.removeItem('nama_donatur_pending');
        document.getElementById('main_form').reset();
        document.getElementById('form_uang').classList.add('hidden');
        document.getElementById('form_barang').classList.add('hidden');
        document.getElementById('payment_info_box').classList.add('hidden');
        document.getElementById('jenis_donasi_input').value = '';
    }

    // ========== PROSES DONASI UANG (MIDTRANS) ==========
    async function processPayment(event) {
        const btn = event.target;
        btn.disabled  = true;
        btn.innerText = "MEMPROSES...";

        const form        = document.getElementById('main_form');
        const formData    = new FormData(form);
        const namaDonatur = document.getElementById('input_nama').value;

        try {
            const response = await fetch("{{ route('donatur.kunjungan.store') }}", {
                method  : 'POST',
                body    : formData,
                headers : { 
                    'X-CSRF-TOKEN' : document.querySelector('meta[name="csrf-token"]').content,
                    'Accept'       : 'application/json' 
                }
            });
            
            const data = await response.json();

            if (response.ok && data.snap_token) {
                // ✅ Simpan nama & data form ke sessionStorage sebelum buka Midtrans
                sessionStorage.setItem('nama_donatur_pending', namaDonatur);
                simpanFormKeSession();

                window.snap.pay(data.snap_token, {
                    onSuccess: function(result) {
                        // Beberapa metode tidak redirect, langsung trigger onSuccess
                        sessionStorage.removeItem('nama_donatur_pending');
                        sessionStorage.removeItem('saved_form');
                        tampilkanModal(namaDonatur, 'Donasi uang Anda telah berhasil dikirim.');
                    },
                    onPending: function(result) {
                        tampilkanModal(namaDonatur, 'Pembayaran sedang diproses. Silakan selesaikan pembayaran Anda.');
                    },
                    onError: function(result) {
                        sessionStorage.removeItem('nama_donatur_pending');
                        sessionStorage.removeItem('saved_form');
                        alert("Pembayaran gagal. Silakan coba lagi.");
                    },
                    onClose: function() {
                        // User tutup tanpa bayar — form tetap terisi dari sessionStorage
                    }
                });
            } else {
                alert("Gagal: " + (data.message || "Data tidak valid."));
            }
        } catch (error) {
            alert("Terjadi kesalahan sistem: " + error.message);
        } finally {
            btn.disabled  = false;
            btn.innerText = "BAYAR SEKARANG";
        }
    }

    // ========== SUBMIT FORM BIASA (kunjungan / donasi barang) ==========
    document.getElementById('main_form').addEventListener('submit', async function(e) {
        e.preventDefault();

        const jenisDonasi = document.getElementById('jenis_donasi_input').value;
        if (jenisDonasi === 'uang') return; // dihandle processPayment

        const btn         = document.getElementById('btn-submit');
        btn.disabled      = true;
        btn.innerText     = "MENYIMPAN...";

        const formData    = new FormData(this);
        const namaDonatur = document.getElementById('input_nama').value;

        try {
            const response = await fetch("{{ route('donatur.kunjungan.store') }}", {
                method  : 'POST',
                body    : formData,
                headers : {
                    'X-CSRF-TOKEN' : document.querySelector('meta[name="csrf-token"]').content,
                    'Accept'       : 'application/json'
                }
            });

            const data = await response.json();

            if (response.ok) {
                const pesan = jenisDonasi === 'barang'
                    ? 'Donasi barang Anda telah berhasil dicatat.'
                    : 'Data kunjungan Anda telah berhasil disimpan.';
                tampilkanModal(namaDonatur, pesan);
            } else {
                alert("Gagal: " + (data.message || "Terjadi kesalahan."));
            }
        } catch (error) {
            alert("Terjadi kesalahan sistem: " + error.message);
        } finally {
            btn.disabled  = false;
            btn.innerText = "Simpan Data Kunjungan";
        }
    });
</script>

</body>
</html>