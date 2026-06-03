<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Donatur | SEDEKAH - Yayasan Rumah Harapan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap');
        
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #067259;
            overflow: hidden;
        }

        .blob {
            position: absolute;
            width: 500px;
            height: 500px;
            background: linear-gradient(180deg, rgba(16, 185, 129, 0.4) 0%, rgba(5, 150, 105, 0.4) 100%);
            filter: blur(80px);
            border-radius: 50%;
            z-index: -1;
            animation: move 25s infinite alternate;
        }

        @keyframes move {
            from { transform: translate(-10%, -10%); }
            to { transform: translate(20%, 20%); }
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .input-pill {
            background: rgba(255, 255, 255, 1);
            border-radius: 9999px; 
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .input-pill:focus {
            box-shadow: 0 0 0 4px rgba(8, 189, 128, 0.2);
            border-color: #10b981;
            outline: none;
        }

        .alert-glass {
            background: rgba(220, 38, 38, 0.1);
            border: 1px solid rgba(220, 38, 38, 0.2);
            backdrop-filter: blur(10px);
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4">
    <div class="blob" style="top: -10%; left: -10%;"></div>
    <div class="blob" style="bottom: -10%; right: -10%; animation-delay: -5s;"></div>

    <div class="w-full max-w-md">
        <div class="mb-10 text-center transform -rotate-1">
            <div class="inline-block p-6 mb-4 shadow-2xl glass-card rounded-3xl rotate-3 border-emerald-500/30">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-emerald-400" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                </svg>
            </div>
            <h1 class="text-4xl font-extrabold tracking-tighter text-white">SEDEKAH</h1>
            <p class="mt-2 text-[10px] font-black text-emerald-300 uppercase tracking-[0.4em]">Yayasan Rumah Harapan</p>
        </div>

        <div class="p-8 shadow-2xl glass-card rounded-[2.5rem]">
            <h2 class="mb-2 text-2xl font-black text-center text-white">Login Donatur</h2>
            <p class="mb-8 text-center text-emerald-200/50 text-xs font-bold uppercase tracking-widest">Selamat Datang Kembali</p>

            @if($errors->any())
                <div class="mb-6 p-4 rounded-2xl alert-glass flex items-center space-x-3 text-red-400">
                    <span class="text-xs font-bold uppercase tracking-tight">{{ $errors->first() }}</span>
                </div>
            @endif

            <form action="{{ route('donatur.login.proses') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="block mb-2 ml-5 text-[10px] font-black text-emerald-400 uppercase tracking-[0.2em]">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="w-full py-4 px-6 text-sm font-bold text-emerald-900 input-pill placeholder:text-emerald-900/30" 
                        placeholder="Ketik email donatur...">
                </div>

                <div>
                    <label class="block mb-2 ml-5 text-[10px] font-black text-emerald-400 uppercase tracking-[0.2em]">Password</label>
                    <input type="password" name="password" required
                        class="w-full py-4 px-6 text-sm font-bold text-emerald-900 input-pill placeholder:text-emerald-900/30" 
                        placeholder="••••••••">
                </div>

                <button type="submit" 
                    class="w-full py-4 mt-6 text-xs font-black tracking-[0.3em] text-white uppercase transition-all duration-500 transform rounded-full shadow-2xl bg-emerald-900 hover:bg-emerald-600 hover:-translate-y-1 active:scale-95 border border-emerald-500/20">
                    Masuk Sekarang
                </button>
            </form>

            <div class="mt-8 text-center">
                <p class="text-[10px] text-emerald-100/70 font-bold uppercase tracking-widest">
                    Belum punya akun? 
                    <a href="{{ route('donatur.signup') }}" class="text-white font-black underline hover:text-emerald-300">Daftar Sekarang</a>
                </p>
            </div>
        </div>

        <p class="mt-10 text-center text-emerald-500/40 text-[10px] font-black uppercase tracking-[0.3em]">
            &copy; 2026 LPSE Karawang &bull; SOWAN V2 DNA
        </p>
    </div>
</body>
</html>