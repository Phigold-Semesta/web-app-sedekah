<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <title>SEDEKAH | Yayasan Rumah Harapan Karawang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background: linear-gradient(135deg, #f0fdfa 0%, #ccfbf1 50%, #fefce8 100%); }
        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
    </style>
    <script>
        function toggleDonasi(type) {
            const formUang = document.getElementById('form_uang');
            const formBarang = document.getElementById('form_barang');
            const inputJenis = document.getElementById('jenis_donasi_input');

            if (type === 'uang') {
                formUang.classList.remove('hidden');
                formBarang.classList.add('hidden');
                inputJenis.value = 'uang';
            } else if (type === 'barang') {
                formUang.classList.add('hidden');
                formBarang.classList.remove('hidden');
                inputJenis.value = 'barang';
            }
        }

        // Fungsi untuk menampilkan card dinamis saat nominal diisi
        function checkNominal(val) {
            const infoBox = document.getElementById('payment_info_box');
            const displayAmount = document.getElementById('display_amount');
            
            if (val > 0) {
                infoBox.classList.remove('hidden');
                // Format angka ke Rupiah
                displayAmount.innerText = 'Rp ' + parseInt(val).toLocaleString('id-ID');
            } else {
                infoBox.classList.add('hidden');
            }
        }
    </script>
</head>
<body class="min-h-screen p-4 md:p-8 flex items-center justify-center">

<div class="w-full max-w-xl">
    <div class="text-center mb-8">
        <h1 class="text-4xl font-extrabold text-emerald-900 tracking-tight italic">SEDEKAH</h1>
        <p class="text-emerald-700/80 font-medium">Yayasan Rumah Harapan Karawang</p>
    </div>

    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-100 text-red-700 rounded-3xl text-xs font-bold text-center">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('donatur.kunjungan.store') }}" method="POST" enctype="multipart/form-data" 
          class="glass-card p-8 md:p-10 rounded-[2.5rem] shadow-2xl space-y-6">
        @csrf
        
        <input type="hidden" name="jenis_donasi" id="jenis_donasi_input" value="">

        <div class="space-y-4">
            <h3 class="text-[11px] font-black text-emerald-800 uppercase tracking-widest pl-2">Informasi Tamu</h3>
            <input type="text" name="nama_donatur" required class="w-full bg-white/50 border border-emerald-100 rounded-3xl p-4 text-sm font-medium focus:ring-4 focus:ring-emerald-200 outline-none transition-all" placeholder="Nama Lengkap">
            <input type="text" name="no_hp" required class="w-full bg-white/50 border border-emerald-100 rounded-3xl p-4 text-sm font-medium focus:ring-4 focus:ring-emerald-200 outline-none transition-all" placeholder="Nomor WhatsApp">
            <textarea name="alamat" required class="w-full bg-white/50 border border-emerald-100 rounded-3xl p-4 text-sm font-medium focus:ring-4 focus:ring-emerald-200 outline-none transition-all" placeholder="Alamat Lengkap"></textarea>
        </div>

        <select name="tujuan_kunjungan" required class="w-full bg-white/50 border border-emerald-100 rounded-3xl p-4 text-sm font-medium focus:ring-4 focus:ring-emerald-200 outline-none transition-all text-slate-500">
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

        <div id="form_uang" class="hidden space-y-4 p-6 bg-emerald-50 rounded-3xl border border-emerald-100 animate-in fade-in duration-300">
            <input type="number" name="nominal" oninput="checkNominal(this.value)" class="w-full bg-white rounded-2xl p-4 text-sm shadow-inner border border-emerald-100" placeholder="Nominal Uang (Rp)">
            
            <div id="payment_info_box" class="hidden mt-4 p-6 bg-emerald-700 rounded-3xl text-white shadow-xl animate-in fade-in duration-300">
                <p class="text-[10px] uppercase tracking-widest opacity-80">Total Donasi Anda</p>
                <h2 id="display_amount" class="text-2xl font-black my-2">Rp 0</h2>
                <button type="button" class="w-full bg-white text-emerald-700 py-3 rounded-2xl font-black uppercase text-xs hover:bg-emerald-50 transition-all">
                    SIMPAN
                </button>
            </div>
        </div>

        <div id="form_barang" class="hidden space-y-4 p-6 bg-amber-50 rounded-3xl border border-amber-100 animate-in fade-in duration-300">
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

        <button type="submit" class="w-full bg-emerald-700 text-white py-5 rounded-3xl font-black uppercase tracking-widest text-sm hover:bg-emerald-800 hover:scale-[1.02] active:scale-[0.98] transition-all shadow-xl shadow-emerald-200">
            Simpan Data Kunjungan
        </button>
    </form>
</div>

</body>
</html>