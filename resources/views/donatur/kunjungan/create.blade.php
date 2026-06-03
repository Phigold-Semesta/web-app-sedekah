<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>SEDEKAH | Yayasan Rumah Harapan Karawang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: linear-gradient(135deg, #f0fdfa 0%, #ccfbf1 50%, #fefce8 100%);
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
    </style>
    <script>
        function toggleDonasi(type) {
            const formUang = document.getElementById('form_uang');
            const formBarang = document.getElementById('form_barang');
            formUang.classList.toggle('hidden', type !== 'uang');
            formBarang.classList.toggle('hidden', type !== 'barang');
        }
    </script>
</head>
<body class="min-h-screen p-4 md:p-8 flex items-center justify-center">

<div class="w-full max-w-xl">
    <div class="text-center mb-8">
        <h1 class="text-4xl font-extrabold text-emerald-900 tracking-tight italic">SEDEKAH</h1>
        <p class="text-emerald-700/80 font-medium">Yayasan Rumah Harapan Karawang</p>
    </div>

    <form action="{{ route('donatur.kunjungan.store') }}" method="POST" enctype="multipart/form-data" 
          class="glass-card p-8 md:p-10 rounded-[2.5rem] shadow-2xl space-y-6">
        @csrf
        
        <div class="space-y-4">
            <h3 class="text-[11px] font-black text-emerald-800 uppercase tracking-widest pl-2">Informasi Tamu</h3>
            <input type="text" name="nama_donatur" required class="w-full bg-white/50 border border-emerald-100 rounded-3xl p-4 text-sm font-medium focus:ring-4 focus:ring-emerald-200 outline-none transition-all" placeholder="Nama Lengkap">
            <input type="text" name="no_hp" required class="w-full bg-white/50 border border-emerald-100 rounded-3xl p-4 text-sm font-medium focus:ring-4 focus:ring-emerald-200 outline-none transition-all" placeholder="Nomor WhatsApp">
            <textarea name="alamat" required class="w-full bg-white/50 border border-emerald-100 rounded-3xl p-4 text-sm font-medium focus:ring-4 focus:ring-emerald-200 outline-none transition-all" placeholder="Alamat Lengkap"></textarea>
        </div>

        <textarea name="tujuan_kunjungan" required class="w-full bg-white/50 border border-emerald-100 rounded-3xl p-4 text-sm font-medium focus:ring-4 focus:ring-emerald-200 outline-none transition-all" placeholder="Tujuan Kunjungan (Silaturahmi/Donasi)..."></textarea>

        <div class="space-y-2">
            <p class="text-[10px] font-bold text-emerald-800 uppercase tracking-widest pl-2">Opsional: Jika Ingin Berdonasi</p>
            <div class="grid grid-cols-2 gap-4">
                <button type="button" onclick="toggleDonasi('uang')" class="py-4 bg-emerald-600 text-white rounded-2xl font-bold text-sm shadow-lg shadow-emerald-200 hover:bg-emerald-700 transition-all">Donasi Uang</button>
                <button type="button" onclick="toggleDonasi('barang')" class="py-4 bg-amber-500 text-white rounded-2xl font-bold text-sm shadow-lg shadow-amber-200 hover:bg-amber-600 transition-all">Donasi Barang</button>
            </div>
        </div>

        <div id="form_uang" class="hidden space-y-4 p-6 bg-emerald-50 rounded-3xl border border-emerald-100">
            <input type="number" name="nominal" class="w-full bg-white rounded-2xl p-4 text-sm shadow-inner" placeholder="Nominal Uang (Rp)">
            <div class="p-4 bg-emerald-900 text-white rounded-xl text-[10px] font-bold uppercase tracking-widest text-center">
                Metode Pembayaran: Midtrans (Snap Token)
            </div>
        </div>

        <div id="form_barang" class="hidden space-y-4 p-6 bg-amber-50 rounded-3xl border border-amber-100">
            <input type="text" name="nama_barang" class="w-full bg-white rounded-2xl p-4 text-sm" placeholder="Nama Barang">
            <select name="id_kategori_barang" class="w-full bg-white rounded-2xl p-4 text-sm text-slate-500">
                <option value="">Pilih Jenis Barang</option>
                <option value="1">Sembako</option>
                <option value="2">Pakaian</option>
                <option value="3">Peralatan</option>
            </select>
            <div class="grid grid-cols-2 gap-4">
                <input type="number" name="jumlah_barang" class="w-full bg-white rounded-2xl p-4 text-sm" placeholder="Jumlah">
                <input type="text" name="satuan_barang" class="w-full bg-white rounded-2xl p-4 text-sm" placeholder="Satuan (Kg/Pcs)">
            </div>
            <label class="block text-[10px] font-bold text-amber-800 uppercase pl-2">Foto Bukti Barang (Kamera)</label>
            <input type="file" name="foto_barang" accept="image/*" capture="environment" class="w-full p-4 bg-white rounded-2xl border-2 border-dashed border-amber-200 text-sm cursor-pointer">
        </div>

        <button type="submit" class="w-full bg-emerald-700 text-white py-5 rounded-3xl font-black uppercase tracking-widest text-sm hover:bg-emerald-800 hover:scale-[1.02] active:scale-[0.98] transition-all shadow-xl shadow-emerald-200">
            Simpan Data Kunjungan
        </button>
    </form>
</div>

</body>
</html>