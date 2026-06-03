<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Donatur | SEDEKAH</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-emerald-800 to-emerald-950 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md">
        <div class="bg-white/95 backdrop-blur-xl p-8 rounded-[2.5rem] shadow-2xl border border-white/20">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-black text-emerald-900 uppercase tracking-tighter italic">Login Donatur</h2>
                <p class="text-emerald-600 text-xs font-bold uppercase tracking-[0.2em] mt-1">Selamat datang kembali di SEDEKAH</p>
            </div>

            @if($errors->any())
                <div class="mb-6 p-4 bg-red-100 text-red-700 rounded-full text-center text-xs font-bold">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('donatur.login.proses') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <input type="email" name="email" placeholder="Email Address" required
                        class="w-full px-6 py-4 rounded-full bg-emerald-50 border-0 focus:ring-2 focus:ring-emerald-500 text-sm font-bold text-emerald-900 placeholder:text-emerald-400 outline-none">
                </div>
                <div>
                    <input type="password" name="password" placeholder="Password" required
                        class="w-full px-6 py-4 rounded-full bg-emerald-50 border-0 focus:ring-2 focus:ring-emerald-500 text-sm font-bold text-emerald-900 placeholder:text-emerald-400 outline-none">
                </div>
                
                <button type="submit" 
                    class="w-full py-4 bg-emerald-900 text-white rounded-full font-black uppercase tracking-widest hover:bg-emerald-800 transition-all shadow-lg active:scale-95">
                    Masuk Sekarang
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-xs text-emerald-600 font-bold">
                    Belum punya akun? 
                    <a href="{{ route('donatur.signup') }}" class="text-emerald-900 font-black underline italic">Daftar Sekarang</a>
                </p>
            </div>
        </div>
    </div>

</body>
</html>