<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SOWAN v2 - @yield('title')</title>
    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome untuk Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Font: Inter & Montserrat -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&family=Montserrat:wght@800;900&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-luxury { font-family: 'Montserrat', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 dark:bg-[#06201b]">

    <div class="flex min-h-screen">
        <!-- SIDEBAR MANUAL (Tanpa Component) -->
        <aside class="w-72 bg-emerald-950 text-white hidden lg:flex flex-col shadow-2xl border-r border-emerald-800">
            <div class="p-8">
                <h2 class="text-2xl font-black tracking-tighter text-emerald-400 font-luxury uppercase italic">SOWAN <span class="text-white">v2</span></h2>
                <p class="text-[10px] font-bold text-emerald-600 tracking-[0.2em] uppercase mt-1">Digital Guest Book</p>
            </div>

            <nav class="flex-1 px-4 space-y-2">
                <!-- Nav Item: Dashboard -->
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center space-x-3 p-4 rounded-2xl transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-900/50' : 'text-emerald-300 hover:bg-emerald-900' }}">
                    <i class="fa-solid fa-chart-pie text-lg"></i>
                    <span class="font-bold text-sm uppercase tracking-wide">Monitoring</span>
                </a>

                <!-- Nav Item: Verifikasi -->
                <a href="{{ route('admin.verifikasi') }}" 
                   class="flex items-center space-x-3 p-4 rounded-2xl transition-all {{ request()->routeIs('admin.verifikasi') ? 'bg-emerald-500 text-white shadow-lg' : 'text-emerald-300 hover:bg-emerald-900' }}">
                    <i class="fa-solid fa-file-circle-check text-lg"></i>
                    <span class="font-bold text-sm uppercase tracking-wide">Verifikasi</span>
                </a>

                <!-- Nav Item: Donatur -->
                <a href="{{ route('admin.donatur') }}" 
                   class="flex items-center space-x-3 p-4 rounded-2xl transition-all {{ request()->routeIs('admin.donatur') ? 'bg-emerald-500 text-white shadow-lg' : 'text-emerald-300 hover:bg-emerald-900' }}">
                    <i class="fa-solid fa-users text-lg"></i>
                    <span class="font-bold text-sm uppercase tracking-wide">Donatur</span>
                </a>
            </nav>

            <!-- Logout Section -->
            <div class="p-6 border-t border-emerald-900">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center space-x-2 p-3 rounded-xl bg-red-500/10 text-red-500 hover:bg-red-500 hover:text-white transition-all font-black text-xs uppercase tracking-widest">
                        <i class="fa-solid fa-power-off"></i>
                        <span>Keluar Sistem</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- MAIN CONTENT AREA -->
        <main class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <!-- Header/Navbar -->
            <header class="h-20 bg-white/80 dark:bg-emerald-950/50 backdrop-blur-md border-b border-slate-200 dark:border-emerald-800 flex items-center justify-between px-8 sticky top-0 z-40">
                <h2 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-widest">
                    @yield('page_title', 'Sistem SOWAN')
                </h2>
                
                <div class="flex items-center space-x-4">
                    <div class="text-right hidden sm:block">
                        <p class="text-xs font-black text-slate-800 dark:text-white">{{ Auth::user()->nama_user }}</p>
                        <p class="text-[10px] font-bold text-emerald-500 uppercase tracking-tighter">{{ Auth::user()->role }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-emerald-500 border-2 border-white shadow-lg flex items-center justify-center text-white font-black text-sm">
                        {{ substr(Auth::user()->nama_user, 0, 1) }}
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="p-8 overflow-y-auto">
                @yield('content')
            </div>
        </main>
    </div>

</body>
</html>