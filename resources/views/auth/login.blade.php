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
            background-color: #067259; /* Dark Emerald Background */
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
            <div class="inline-block p-4 mb-4 shadow-2xl glass-card rounded-3xl rotate-3 border-emerald-500/30">
                <i class="fa-solid fa-hand-holding-heart text-5xl text-emerald-400"></i>
            </div>
            <h1 class="text-4xl font-extrabold tracking-tighter text-white">
                SEDEKAH
            </h1>
            <p class="mt-2 text-[10px] font-black text-emerald-300 uppercase tracking-[0.4em]">
                Yayasan Rumah Harapan
            </p>
        </div>

        <div class="p-8 shadow-2xl glass-card rounded-[2.5rem]">
            <h2 class="mb-2 text-2xl font-black text-center text-white">Akses Internal</h2>
            <p class="mb-8 text-center text-emerald-200/50 text-xs font-bold uppercase tracking-widest">Silakan Masuk ke Akun Anda</p>

            @if($errors->any())
                <div class="mb-6 p-4 rounded-2xl alert-glass flex items-center space-x-3 text-red-400">
                    <i class="fa-solid fa-circle-exclamation text-lg"></i>
                    <span class="text-xs font-bold uppercase tracking-tight">{{ $errors->first() }}</span>
                </div>
            @endif

            <form action="{{ route('login.proses') }}" method="POST" class="space-y-5">
                @csrf
                <input type="hidden" name="ip_address" value="{{ request()->ip() }}">
                
                <div>
                    <label class="block mb-2 ml-5 text-[10px] font-black text-emerald-400 uppercase tracking-[0.2em]">Username</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-5 text-emerald-900/50">
                            <i class="fa-solid fa-circle-user"></i>
                        </span>
                        <input type="text" name="username" value="{{ old('username') }}" required
                            class="w-full py-4 pl-12 pr-6 text-sm font-bold text-emerald-900 input-pill placeholder:text-emerald-900/30" 
                            placeholder="Ketik username admin...">
                    </div>
                </div>

                <div>
                    <label class="block mb-2 ml-5 text-[10px] font-black text-emerald-400 uppercase tracking-[0.2em]">Password</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-5 text-emerald-900/50">
                            <i class="fa-solid fa-shield-halved"></i>
                        </span>
                        <input type="password" name="password" required
                            class="w-full py-4 pl-12 pr-6 text-sm font-bold text-emerald-900 input-pill placeholder:text-emerald-900/30" 
                            placeholder="••••••••">
                    </div>
                </div>

                <button type="submit" 
                    class="w-full py-4 mt-4 text-xs font-black tracking-[0.3em] text-white uppercase transition-all duration-500 transform rounded-full shadow-2xl bg-emerald-900 hover:bg-emerald-600 hover:-translate-y-1 active:scale-95 border border-emerald-500/20">
                    Login
                </button>
            </form>
        </div>

        <p class="mt-10 text-center text-emerald-500/40 text-[10px] font-black uppercase tracking-[0.3em]">
            &copy; 2026 Yayasan Rumah Harapan &bull; SEDEKAH Application
        </p>
    </div>
</body>
</html>