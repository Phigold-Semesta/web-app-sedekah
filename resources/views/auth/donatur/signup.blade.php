<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Donatur | SEDEKAH</title>
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
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .input-pill {
            background: #ecfdf5; /* emerald-50 */
            border-radius: 9999px; 
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .input-pill:focus {
            box-shadow: 0 0 0 4px rgba(8, 189, 128, 0.2);
            border-color: #10b981;
            outline: none;
            background: #ffffff;
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4">
    <div class="blob" style="top: -10%; left: -10%;"></div>
    <div class="blob" style="bottom: -10%; right: -10%; animation-delay: -5s;"></div>

    <div class="w-full max-w-md">
        <div class="p-8 shadow-2xl glass-card rounded-[2.5rem]">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-black text-emerald-900 uppercase tracking-tighter italic">Registrasi</h2>
                <p class="text-emerald-600/70 text-[10px] font-black uppercase tracking-[0.3em] mt-2">Jadilah bagian dari kebaikan kami</p>
            </div>

            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-2xl text-center">
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
                        class="w-full px-6 py-4 text-sm font-bold text-emerald-900 input-pill placeholder:text-emerald-400">
                </div>
                <div>
                    <input type="email" name="email" placeholder="Email Address" value="{{ old('email') }}" required
                        class="w-full px-6 py-4 text-sm font-bold text-emerald-900 input-pill placeholder:text-emerald-400">
                </div>
                <div>
                    <input type="password" name="password" placeholder="Password" required
                        class="w-full px-6 py-4 text-sm font-bold text-emerald-900 input-pill placeholder:text-emerald-400">
                </div>
                <div>
                    <input type="password" name="password_confirmation" placeholder="Konfirmasi Password" required
                        class="w-full px-6 py-4 text-sm font-bold text-emerald-900 input-pill placeholder:text-emerald-400">
                </div>
                
                <button type="submit" 
                    class="w-full py-4 mt-4 text-xs font-black tracking-[0.3em] text-white uppercase transition-all duration-500 transform rounded-full shadow-2xl bg-emerald-900 hover:bg-emerald-600 hover:-translate-y-1 active:scale-95">
                    Daftar Akun
                </button>
            </form>

            <div class="mt-8 text-center">
                <p class="text-[10px] text-emerald-600 font-bold uppercase tracking-widest">
                    Sudah punya akun? 
                    <a href="{{ route('donatur.login') }}" class="text-emerald-900 font-black underline italic hover:text-emerald-600">Masuk di sini</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>