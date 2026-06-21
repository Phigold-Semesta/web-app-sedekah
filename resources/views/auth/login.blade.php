<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | SEDEKAH - Yayasan Rumah Harapan</title>
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

        /* Dekorasi Background */
        .dots-left { position: absolute; top: 20px; left: 20px; width: 180px; height: 120px; background-image: radial-gradient(#6ee7b7 1.5px, transparent 1.5px); background-size: 20px 20px; opacity: .6; }
        .dots-right { position: absolute; top: 180px; right: 40px; width: 180px; height: 180px; background-image: radial-gradient(#6ee7b7 1.5px, transparent 1.5px); background-size: 20px 20px; opacity: .6; }
        .wave { position: absolute; left: 0; bottom: 0; width: 100%; height: 260px; overflow: hidden; z-index: 0; }
        .wave::before { content: ""; position: absolute; width: 140%; height: 220px; left: -20%; bottom: 60px; background: rgba(16, 185, 129, .1); border-radius: 50%; }
        .wave::after { content: ""; position: absolute; width: 140%; height: 180px; left: -20%; bottom: -20px; background: rgba(5, 150, 105, .2); border-radius: 50%; }

        .login-card { background: #fff; border-radius: 22px; box-shadow: 0 10px 30px rgba(6, 78, 59,.08), 0 20px 60px rgba(6, 78, 59,.12); }
        .input-field { height: 50px; border: 1px solid #cbd5e1; border-radius: 12px; transition: .25s; }
        .input-field:focus { outline: none; border-color: #059669; box-shadow: 0 0 0 3px rgba(5,150,105,.15); }
        .login-btn { background: linear-gradient(90deg, #059669, #065f46); transition: .3s; }
        .login-btn:hover { background: linear-gradient(90deg, #065f46, #064e3b); }
    </style>
</head>

<body class="flex items-center justify-center min-h-screen p-4">

    <div class="dots-left"></div>
    <div class="dots-right"></div>
    <div class="wave"></div>

    <div class="w-full max-w-md relative z-10">
        <div class="mb-8 text-center">
            <div class="inline-block p-4 mb-4 bg-white shadow-xl rounded-3xl border border-emerald-100">
                <i class="fa-solid fa-hand-holding-heart text-5xl text-emerald-600"></i>
            </div>
            <h1 class="text-4xl font-extrabold tracking-tighter text-slate-800">SEDEKAH</h1>
            <p class="mt-2 text-xs font-bold text-emerald-600 uppercase tracking-[0.3em]">Yayasan Rumah Harapan</p>
        </div>

        <div class="p-8 login-card border border-emerald-50">
            <h2 class="mb-1 text-2xl font-black text-center text-slate-800">Akses Internal</h2>
            <p class="mb-8 text-center text-gray-500 text-xs font-semibold uppercase tracking-widest">Silakan Masuk ke Akun Anda</p>

            @if($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 flex items-center space-x-3 text-red-600">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <span class="text-xs font-bold uppercase">{{ $errors->first() }}</span>
                </div>
            @endif

            <form action="{{ route('login.proses') }}" method="POST" class="space-y-5">
                @csrf
                <input type="hidden" name="ip_address" value="{{ request()->ip() }}">
                
                <div>
                    <label class="block mb-2 text-xs font-bold text-gray-700 uppercase tracking-wider ml-1">Username</label>
                    <div class="relative">
                        <input type="text" name="username" value="{{ old('username') }}" required
                            class="input-field w-full px-4 pl-12" placeholder="Ketik username admin...">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-emerald-500">
                            <i class="fa-solid fa-user"></i>
                        </span>
                    </div>
                </div>

                <div>
                    <label class="block mb-2 text-xs font-bold text-gray-700 uppercase tracking-wider ml-1">Password</label>
                    <div class="relative">
                        <input type="password" name="password" required
                            class="input-field w-full px-4 pl-12" placeholder="••••••••">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-emerald-500">
                            <i class="fa-solid fa-lock"></i>
                        </span>
                    </div>
                </div>

                <button type="submit" 
                    class="login-btn w-full py-4 mt-2 text-sm font-black tracking-[0.2em] text-white uppercase rounded-xl shadow-lg hover:shadow-emerald-200 active:scale-95 transition-all">
                    Login
                </button>
            </form>
        </div>

        <p class="mt-8 text-center text-gray-400 text-[10px] font-bold uppercase tracking-[0.3em]">
            &copy; 2026 Yayasan Rumah Harapan &bull; SEDEKAH Application
        </p>
    </div>
</body>
</html>