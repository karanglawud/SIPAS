<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SIPAS - Sistem Informasi Peminjaman Alat Sekolah SMK Negeri 5 Surakarta. Layanan digital peminjaman alat dan perlengkapan praktik kejuruan.">
    <title>@yield('title', 'SIPAS') | SMK Negeri 5 Surakarta</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-800 antialiased font-sans selection:bg-blue-600 selection:text-white min-h-screen flex flex-col">

    <!-- Main Navigation Bar -->
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-slate-200/90 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                
                <!-- Brand Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-3 group focus:outline-none">
                    <div class="w-11 h-11 rounded-xl bg-gradient-to-tr from-blue-700 via-blue-600 to-indigo-600 flex items-center justify-center text-white shadow-md shadow-blue-600/20 group-hover:scale-105 transition-transform">
                        <img src="{{ asset('storage/images/1.png') }}" alt="Logo SIPAS" class="w-full h-full rounded-2xl object-contain">
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-2xl font-extrabold tracking-tight bg-gradient-to-r from-blue-700 via-indigo-700 to-slate-900 bg-clip-text text-transparent">SIPAS</span>
                            <span class="text-[10px] font-bold uppercase tracking-wider px-1.5 py-0.5 rounded bg-blue-100 text-blue-700">SMKN 5 SURAKARTA</span>
                        </div>
                        <p class="text-[11px] font-medium text-slate-500">Sistem Informasi Peminjaman Alat Sekolah</p>
                    </div>
                </a>

                <!-- Desktop Nav Links -->
                <nav class="hidden lg:flex items-center gap-1">
                    <a href="{{ route('home') }}" class="px-3.5 py-2 text-sm font-semibold rounded-xl transition-colors {{ request()->routeIs('home') ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:text-blue-600 hover:bg-slate-50' }}">
                        Beranda
                    </a>
                    <a href="{{ route('catalog.index') }}" class="px-3.5 py-2 text-sm font-semibold rounded-xl transition-colors {{ request()->routeIs('catalog.*') ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:text-blue-600 hover:bg-slate-50' }}">
                        Katalog Alat
                    </a>
                    <a href="{{ route('loans.create') }}" class="px-3.5 py-2 text-sm font-semibold rounded-xl transition-colors {{ request()->routeIs('loans.create') ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:text-blue-600 hover:bg-slate-50' }}">
                        Formulir Pinjam
                    </a>
                    <a href="{{ route('loans.index') }}" class="px-3.5 py-2 text-sm font-semibold rounded-xl transition-colors {{ request()->routeIs('loans.index') ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:text-blue-600 hover:bg-slate-50' }}">
                        Riwayat
                    </a>
                    
                    @auth
                        @php
                            $unreadTotal = Auth::user()->unreadMessagesCount();
                        @endphp
                        <a href="{{ route('messages.index') }}" class="px-3.5 py-2 text-sm font-semibold rounded-xl transition-colors flex items-center gap-1.5 {{ request()->routeIs('messages.*') ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:text-blue-600 hover:bg-slate-50' }}">
                            <span>Pemberitahuan</span>
                            @if($unreadTotal > 0)
                                <span class="px-1.5 py-0.2 rounded-full text-[10px] font-bold bg-rose-500 text-white">
                                    {{ $unreadTotal }}
                                </span>
                            @endif
                        </a>

                        @if(Auth::user()->isAdmin())
                            <a href="{{ route('admin.loans.index') }}" class="ml-2 px-3.5 py-2 text-xs font-bold rounded-xl bg-amber-500 text-white hover:bg-amber-600 transition-colors shadow-xs flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-white animate-pulse"></span>
                                Verifikasi Admin
                            </a>
                        @endif
                    @endauth

                    <a href="{{ route('about.pj') }}" class="px-3.5 py-2 text-sm font-semibold rounded-xl transition-colors {{ request()->routeIs('about.pj') ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:text-blue-600 hover:bg-slate-50' }}">
                        Tim PJ Alat
                    </a>
                </nav>

                <!-- Auth Action Controls -->
                <div class="hidden sm:flex items-center gap-3">
                    @auth
                        <div class="flex items-center gap-3 pl-3 border-l border-slate-200">
                            <div class="text-right">
                                <span class="block text-xs font-bold text-slate-800">{{ Auth::user()->name }}</span>
                                <span class="text-[10px] text-slate-500 font-medium">
                                    {{ ucfirst(Auth::user()->role) }} • {{ Auth::user()->class_or_dept ?? 'SMKN 5' }}
                                </span>
                            </div>

                            <form action="{{ route('logout') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" title="Keluar" class="p-2 text-slate-400 hover:text-rose-600 rounded-xl hover:bg-rose-50 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                </button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 text-xs font-bold text-slate-700 hover:bg-slate-100 rounded-xl transition-colors">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="px-4 py-2 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-xs transition-colors">
                            Daftar Akun
                        </a>
                    @endauth
                </div>

                <!-- Mobile Hamburger Toggle -->
                <div class="flex items-center gap-2 lg:hidden">
                    <button id="mobileNavToggle" type="button" class="p-2 text-slate-700 hover:bg-slate-100 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/></svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Nav Menu -->
        <div id="mobileNavMenu" class="hidden lg:hidden border-t border-slate-200 bg-white px-4 py-3 space-y-1 shadow-lg">
            <a href="{{ route('home') }}" class="block px-3 py-2 text-sm font-semibold rounded-lg {{ request()->routeIs('home') ? 'bg-blue-50 text-blue-700' : 'text-slate-700' }}">Beranda</a>
            <a href="{{ route('catalog.index') }}" class="block px-3 py-2 text-sm font-semibold rounded-lg {{ request()->routeIs('catalog.*') ? 'bg-blue-50 text-blue-700' : 'text-slate-700' }}">Katalog Alat</a>
            <a href="{{ route('loans.create') }}" class="block px-3 py-2 text-sm font-semibold rounded-lg {{ request()->routeIs('loans.create') ? 'bg-blue-50 text-blue-700' : 'text-slate-700' }}">Formulir Pinjam</a>
            <a href="{{ route('loans.index') }}" class="block px-3 py-2 text-sm font-semibold rounded-lg {{ request()->routeIs('loans.index') ? 'bg-blue-50 text-blue-700' : 'text-slate-700' }}">Riwayat Peminjaman</a>
            @auth
                <a href="{{ route('messages.index') }}" class="block px-3 py-2 text-sm font-semibold rounded-lg {{ request()->routeIs('messages.*') ? 'bg-blue-50 text-blue-700' : 'text-slate-700' }}">Pemberitahuan</a>
                @if(Auth::user()->isAdmin())
                    <a href="{{ route('admin.loans.index') }}" class="block px-3 py-2 text-sm font-bold bg-amber-500 text-white rounded-lg">Panel Verifikasi Admin</a>
                @endif
                <div class="pt-2 border-t border-slate-100">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full text-left px-3 py-2 text-sm font-semibold text-rose-600 hover:bg-rose-50 rounded-lg">Keluar</button>
                    </form>
                </div>
            @else
                <div class="pt-2 border-t border-slate-100 flex flex-col gap-2">
                    <a href="{{ route('login') }}" class="w-full text-center py-2 text-sm font-bold text-slate-700 border border-slate-200 rounded-xl">Masuk</a>
                    <a href="{{ route('register') }}" class="w-full text-center py-2 text-sm font-bold text-white bg-blue-600 rounded-xl">Daftar Akun</a>
                </div>
            @endauth
        </div>
    </header>

    <!-- Flash Alerts Container -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
        @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs sm:text-sm font-medium flex items-center justify-between shadow-xs">
                <div class="flex items-center gap-2.5">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800 text-sm font-bold">&times;</button>
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-900 text-xs sm:text-sm font-medium flex items-center justify-between shadow-xs">
                <div class="flex items-center gap-2.5">
                    <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-800 text-sm font-bold">&times;</button>
            </div>
        @endif

        @if(session('info'))
            <div class="p-4 rounded-2xl bg-blue-50 border border-blue-200 text-blue-900 text-xs sm:text-sm font-medium flex items-center justify-between shadow-xs">
                <div class="flex items-center gap-2.5">
                    <svg class="w-5 h-5 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('info') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-blue-500 hover:text-blue-800 text-sm font-bold">&times;</button>
            </div>
        @endif
    </div>

    <!-- Main Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Global Footer -->
    <footer class="bg-slate-950 text-slate-400 text-xs pt-14 pb-10 border-t border-slate-800 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 pb-10 border-b border-slate-800">
                <div class="md:col-span-2 space-y-3">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-xl bg-blue-600 flex items-center justify-center text-white font-black text-sm">
                            S
                        </div>
                        <span class="text-lg font-black tracking-tight text-white">SIPAS • SMKN 5 Surakarta</span>
                    </div>
                    <p class="text-slate-400 leading-relaxed text-xs max-w-md">
                        Sistem Informasi Peminjaman Alat Sekolah (SIPAS). Layanan digital terpadu untuk pencatatan dan pengelolaan peminjaman peralatan praktik kejuruan di SMK Negeri 5 Surakarta.
                    </p>
                </div>

                <div class="space-y-2">
                    <h4 class="font-bold text-white text-xs uppercase tracking-wider">Navigasi Utama</h4>
                    <ul class="space-y-1.5 text-xs text-slate-400">
                        <li><a href="{{ route('home') }}" class="hover:text-white transition-colors">Beranda</a></li>
                        <li><a href="{{ route('catalog.index') }}" class="hover:text-white transition-colors">Katalog Alat Praktik</a></li>
                        <li><a href="{{ route('loans.create') }}" class="hover:text-white transition-colors">Formulir Peminjaman</a></li>
                        <li><a href="{{ route('loans.index') }}" class="hover:text-white transition-colors">Status & Riwayat Tiket</a></li>
                        <li><a href="{{ route('about.pj') }}" class="hover:text-white transition-colors">Penanggung Jawab Alat</a></li>
                    </ul>
                </div>

                <div class="space-y-2">
                    <h4 class="font-bold text-white text-xs uppercase tracking-wider">Lokasi Ruang Alat</h4>
                    <p class="text-slate-400 leading-relaxed text-xs">
                        SMK Negeri 5 Surakarta<br>
                        Jl. Adi Sucipto No. 42, Kerten, Kec. Laweyan, Surakarta, Jawa Tengah 57143
                    </p>
                    <p class="text-[11px] text-slate-500 pt-1">
                        Jam Layanan: 07.00 - 15.30 WIB
                    </p>
                </div>
            </div>

            <div class="pt-6 flex flex-col sm:flex-row items-center justify-between gap-3 text-slate-500 text-[11px]">
                <p>&copy; 2026 SIPAS - SMK Negeri 5 Surakarta. Hak cipta dilindungi.</p>
                <div class="flex items-center gap-3">
                    <span class="text-slate-400 font-medium">SIPAS (Sistem Informasi Peminjaman Alat Sekolah)</span>
                </div>
            </div>
        </div>
    </footer>

    <script>
        document.getElementById('mobileNavToggle')?.addEventListener('click', function() {
            document.getElementById('mobileNavMenu')?.classList.toggle('hidden');
        });
    </script>
</body>
</html>
