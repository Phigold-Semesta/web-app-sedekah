<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Donatur | SEDEKAH - Yayasan Rumah Harapan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap');
        
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            background: linear-gradient(180deg, #ecfdf5 0%, #d1fae5 100%);
            position: relative;
            overflow-x: hidden;
        }

        /* Dekorasi Background Konsisten */
        .dots-left { position: absolute; top: 20px; left: 20px; width: 180px; height: 120px; background-image: radial-gradient(#6ee7b7 1.5px, transparent 1.5px); background-size: 20px 20px; opacity: .6; }
        .dots-right { position: absolute; top: 180px; right: 40px; width: 180px; height: 180px; background-image: radial-gradient(#6ee7b7 1.5px, transparent 1.5px); background-size: 20px 20px; opacity: .6; }
        .wave { position: absolute; left: 0; bottom: 0; width: 100%; height: 260px; overflow: hidden; z-index: 0; }
        .wave::before { content: ""; position: absolute; width: 140%; height: 220px; left: -20%; bottom: 60px; background: rgba(16, 185, 129, .1); border-radius: 50%; }
        .wave::after { content: ""; position: absolute; width: 140%; height: 180px; left: -20%; bottom: -20px; background: rgba(5, 150, 105, .2); border-radius: 50%; }

        .form-card { background: #fff; border-radius: 22px; box-shadow: 0 10px 30px rgba(6, 78, 59,.08), 0 20px 60px rgba(6, 78, 59,.12); }
        .input-pill { height: 50px; border: 1px solid #cbd5e1; border-radius: 12px; transition: .25s; }
        .input-pill:focus { outline: none; border-color: #059669; box-shadow: 0 0 0 3px rgba(5,150,105,.15); }
        .register-btn { background: linear-gradient(90deg, #059669, #065f46); transition: .3s; }
        .register-btn:hover { background: linear-gradient(90deg, #065f46, #064e3b); }
    </style>
</head>

<body class="flex items-center justify-center min-h-screen p-4">

    <div class="dots-left"></div>
    <div class="dots-right"></div>
    <div class="wave"></div>

    <div class="w-full max-w-md relative z-10">
        <div class="p-8 form-card border border-emerald-50">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-black text-emerald-900 uppercase tracking-tighter italic">Registrasi</h2>
                <p class="text-emerald-600/70 text-[10px] font-black uppercase tracking-[0.3em] mt-2">Jadilah bagian dari kebaikan kami</p>
            </div>

            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl text-center">
                    <ul class="text-[10px] font-bold text-red-600">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('donatur.signup.proses') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <input type="text" name="name" placeholder="Nama Lengkap" value="{{ old('name') }}" required
                        class="input-pill w-full px-6 text-sm font-bold text-emerald-900 placeholder:text-emerald-400">
                </div>
                <div>
                    <input type="email" name="email" placeholder="Email Address" value="{{ old('email') }}" required
                        class="input-pill w-full px-6 text-sm font-bold text-emerald-900 placeholder:text-emerald-400">
                </div>
                <div>
                    <input type="text" name="no_hp" placeholder="Nomor HP / WhatsApp" value="{{ old('no_hp') }}" required
                        class="input-pill w-full px-6 text-sm font-bold text-emerald-900 placeholder:text-emerald-400">
                </div>
                <div>
                    <input type="text" name="alamat" placeholder="Alamat Lengkap" value="{{ old('alamat') }}" required
                        class="input-pill w-full px-6 text-sm font-bold text-emerald-900 placeholder:text-emerald-400">
                </div>
                <div>
                    <input type="password" name="password" placeholder="Password" required
                        class="input-pill w-full px-6 text-sm font-bold text-emerald-900 placeholder:text-emerald-400">
                </div>
                <div>
                    <input type="password" name="password_confirmation" placeholder="Konfirmasi Password" required
                        class="input-pill w-full px-6 text-sm font-bold text-emerald-900 placeholder:text-emerald-400">
                </div>
                
                <button type="submit" 
                    class="register-btn w-full py-4 mt-2 text-xs font-black tracking-[0.3em] text-white uppercase rounded-xl shadow-lg hover:shadow-emerald-200 active:scale-95 transition-all">
                    Daftar Akun
                </button>
            </form>

            <div class="mt-8 text-center">
                <p class="text-xs text-gray-500 font-bold uppercase tracking-widest">
                    Sudah punya akun? 
                    <a href="{{ route('donatur.login') }}" class="text-emerald-600 font-black underline italic hover:text-emerald-800">Masuk di sini</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>