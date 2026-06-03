<!DOCTYPE html>
<html lang="id" x-data="{ 
    darkMode: localStorage.getItem('theme') === 'dark',
    toggleTheme() {
        this.darkMode = !this.darkMode;
        localStorage.setItem('theme', this.darkMode ? 'dark' : 'light');
    }
}" :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('page_title', 'Dashboard') | AKSARA</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        #main-sidebar { width: 88px; transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); }
        .nav-item { display: flex; align-items: center; padding: 1rem; position: relative; transition: all 0.3s ease; color: rgba(255, 255, 255, 0.6); }
        .sidebar-active { color: #ffffff !important; font-weight: 800; background-color: rgba(255, 255, 255, 0.1); }
        .sidebar-active::before { content: ''; position: absolute; left: 0; top: 25%; height: 50%; width: 4px; background: #FFF200; border-radius: 0 4px 4px 0; }
        
        @media (min-width: 1024px) {
            #main-sidebar:hover { width: 288px; }
            #main-sidebar .nav-text, #main-sidebar .logo-full { opacity: 0; visibility: hidden; display: none; }
            #main-sidebar:hover .nav-text, #main-sidebar:hover .logo-full { opacity: 1; visibility: visible; display: flex; }
            #main-sidebar:hover .nav-item { justify-content: flex-start; padding-left: 2rem; }
        }
    </style>
</head>
<body class="antialiased text-slate-800 bg-[#f8fafc] dark:bg-[#020d0b] dark:text-emerald-50 transition-colors duration-300">

    <div class="flex min-h-screen relative overflow-hidden">
        <aside id="main-sidebar" class="bg-[#065f46] h-screen text-white flex flex-col z-40 shadow-2xl shrink-0 overflow-hidden group">
            <div class="p-6 h-24 flex items-center shrink-0 border-b border-white/5">
                <div class="logo-full flex items-center gap-3">
                    <div class="bg-white p-2 rounded-xl text-emerald-700"><i class="fas fa-hand-holding-heart text-lg"></i></div>
                    <span class="font-extrabold text-lg uppercase leading-none">AKSARA<br><span class="text-emerald-300 text-[9px] tracking-[0.2em]">DONATUR</span></span>
                </div>
            </div>

            <nav class="flex-1 mt-8">
                <a href="{{ route('donatur.dashboard') }}" class="nav-item {{ request()->routeIs('donatur.dashboard') ? 'sidebar-active' : 'hover:text-white hover:bg-white/5' }}">
                    <i class="fas fa-desktop w-6 text-center"></i>
                    <span class="nav-text ml-4 text-[11px] font-black uppercase tracking-widest">Dashboard</span>
                </a>
               <a href="{{ route('donatur.donasi.index') }}" class="nav-item {{ request()->routeIs('donatur.donasi.*') ? 'sidebar-active' : 'hover:text-white hover:bg-white/5' }}">
    <i class="fas fa-heart w-6 text-center"></i>
    <span class="nav-text ml-4 text-[11px] font-black uppercase tracking-widest">Donasi</span>
</a>

<a href="{{ route('donatur.riwayat.index') }}" class="nav-item {{ request()->routeIs('donatur.riwayat.index') ? 'sidebar-active' : 'hover:text-white hover:bg-white/5' }}">
    <i class="fas fa-history w-6 text-center"></i>
    <span class="nav-text ml-4 text-[11px] font-black uppercase tracking-widest">Riwayat Donasi</span>
</a>
            </nav>

            <div class="p-4 mb-4">
                <button onclick="confirmLogout(event)" class="nav-item w-full text-red-300 hover:bg-red-500/10 rounded-2xl p-4">
                    <i class="fas fa-power-off w-6 text-center"></i>
                    <span class="nav-text ml-4 text-[11px] font-black uppercase tracking-widest">Keluar</span>
                </button>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
            </div>
        </aside>

        <main class="flex-1 flex flex-col h-screen overflow-hidden">
            <header class="h-20 bg-white/80 dark:bg-slate-900/80 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center px-8 shrink-0">
                <h2 class="text-slate-800 dark:text-white font-black text-xl uppercase italic tracking-tighter">
                    @yield('page_title', 'Dashboard')
                </h2>
                
                <div class="flex items-center gap-4">
                    <button @click="toggleTheme()" class="text-slate-400 hover:text-emerald-500 text-lg">
                        <i class="fa-solid" :class="darkMode ? 'fa-sun' : 'fa-moon'"></i>
                    </button>
                    
                    <div class="flex items-center gap-3 pl-4 border-l border-slate-200 dark:border-slate-700">
                        <div class="text-right hidden sm:block">
                            <p class="text-xs font-bold text-slate-800 dark:text-white">{{ Auth::user()->name ?? 'Donatur' }}</p>
                            <p class="text-[10px] text-emerald-600 dark:text-emerald-400 font-bold uppercase">Donatur</p>
                        </div>
                        <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name ?? 'User' }}&background=065f46&color=fff" class="w-10 h-10 rounded-xl border-2 border-emerald-500 shadow-md">
                    </div>
                </div>
            </header>

            <div class="flex-1 overflow-y-auto p-10 bg-[#f8fafc] dark:bg-[#041a16]">
                @yield('content')
            </div>
        </main>
    </div>

    <script>
        function confirmLogout(event) {
            event.preventDefault();
            Swal.fire({
                title: 'KELUAR AKSARA?',
                icon: 'question',
                confirmButtonColor: '#065f46',
                showCancelButton: true,
                confirmButtonText: 'YA',
                cancelButtonText: 'BATAL'
            }).then((result) => { if (result.isConfirmed) document.getElementById('logout-form').submit(); });
        }
    </script>
</body>
</html>