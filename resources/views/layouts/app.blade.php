<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#2563eb">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="description" content="SIPAS - Sistem Informasi Peminjaman Alat Sekolah SMK Negeri 5 Surakarta. Layanan digital peminjaman alat dan perlengkapan praktik kejuruan.">
    <title>@yield('title', 'SIPAS') | SMK Negeri 5 Surakarta</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-800 antialiased font-sans selection:bg-blue-900 selection:text-white min-h-screen flex flex-col">

    <!-- Main Navigation Bar -->
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-slate-200/90 shadow-2xs">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-14 sm:h-16 lg:h-20">
                
                <!-- Brand Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-2 sm:gap-3 group focus:outline-none shrink-0 min-w-0">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 lg:w-11 lg:h-11 rounded-xl bg-blue-600 flex items-center justify-center text-white border border-blue-500 shadow-xs group-hover:bg-blue-700 transition-colors shrink-0">
                        <img src="{{ asset('storage/images/1.png') }}" alt="Logo SIPAS" class="w-full h-full rounded-xl object-contain">
                    </div>
                    <div class="flex flex-col justify-center min-w-0">
                        <!-- Desktop Brand View (Inline badge) -->
                        <div class="hidden sm:flex items-center gap-2">
                            <span class="text-xl sm:text-2xl font-black tracking-tight text-slate-900 group-hover:text-blue-600 transition-colors leading-none">SIPAS</span>
                            <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-md bg-blue-50 text-blue-700 border border-blue-200 shrink-0">SMKN 5 SURAKARTA</span>
                        </div>
                        <p class="text-[10px] sm:text-[11px] font-medium text-slate-500 hidden sm:block truncate mt-0.5">Sistem Informasi Peminjaman Alat Sekolah</p>

                        <!-- Mobile Brand View (Stacked 2-baris agar tidak menabrak tombol auth) -->
                        <div class="sm:hidden flex flex-col items-start leading-none gap-0.5">
                            <span class="text-base font-black tracking-tight text-slate-900 group-hover:text-blue-600 transition-colors leading-none">SIPAS</span>
                            <span class="text-[8.5px] font-bold uppercase tracking-wider px-1 py-0.5 rounded bg-blue-50 text-blue-700 border border-blue-200 leading-none">SMKN 5 SURAKARTA</span>
                        </div>
                    </div>
                </a>

                <!-- Desktop Nav Links -->
                <nav class="hidden lg:flex items-center gap-1.5">
                    @auth
                        @if(Auth::user()->isAdmin())
                            <!-- Navigation Specifically for Admin PJ -->
                            <a href="{{ route('admin.loans.index') }}" class="px-3.5 py-2 text-sm font-semibold rounded-xl transition-all {{ request()->routeIs('admin.loans.*') ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-700 hover:text-blue-600 hover:bg-blue-50' }}">
                                Verifikasi Peminjaman
                            </a>
                            <a href="{{ route('admin.tools.index') }}" class="px-3.5 py-2 text-sm font-semibold rounded-xl transition-all {{ request()->routeIs('admin.tools.*') ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-700 hover:text-blue-600 hover:bg-blue-50' }}">
                                Kelola Alat Praktik
                            </a>
                            <a href="{{ route('admin.messages.index') }}" class="px-3.5 py-2 text-sm font-semibold rounded-xl transition-all {{ request()->routeIs('admin.messages.*') ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-700 hover:text-blue-600 hover:bg-blue-50' }}">
                                Kirim Pesan
                            </a>
                            <a href="{{ route('catalog.index') }}" class="px-3.5 py-2 text-sm font-semibold rounded-xl transition-all {{ request()->routeIs('catalog.*') ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-700 hover:text-blue-600 hover:bg-blue-50' }}">
                                Pratinjau Katalog
                            </a>
                            <a href="{{ route('about.pj') }}" class="px-3.5 py-2 text-sm font-semibold rounded-xl transition-all {{ request()->routeIs('about.pj') ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-700 hover:text-blue-600 hover:bg-blue-50' }}">
                                Tim PJ Alat
                            </a>
                        @else
                            <!-- Navigation for Borrower (Siswa & Guru) -->
                            <a href="{{ route('home') }}" class="px-3.5 py-2 text-sm font-semibold rounded-xl transition-all {{ request()->routeIs('home') ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-700 hover:text-blue-600 hover:bg-blue-50' }}">
                                Beranda
                            </a>
                            <a href="{{ route('catalog.index') }}" class="px-3.5 py-2 text-sm font-semibold rounded-xl transition-all {{ request()->routeIs('catalog.*') ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-700 hover:text-blue-600 hover:bg-blue-50' }}">
                                Katalog Alat
                            </a>
                            <a href="{{ route('loans.index') }}" class="px-3.5 py-2 text-sm font-semibold rounded-xl transition-all {{ request()->routeIs('loans.index') ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-700 hover:text-blue-600 hover:bg-blue-50' }}">
                                Riwayat
                            </a>
                            @php
                                $unreadTotal = Auth::user()->unreadMessagesCount();
                            @endphp
                            <a href="{{ route('messages.index') }}" class="px-3.5 py-2 text-sm font-semibold rounded-xl transition-all flex items-center gap-1.5 {{ request()->routeIs('messages.*') ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-700 hover:text-blue-600 hover:bg-blue-50' }}">
                                <span>Pemberitahuan</span>
                                @if($unreadTotal > 0)
                                    <span class="px-1.5 py-0.2 rounded-full text-[10px] font-bold {{ request()->routeIs('messages.*') ? 'bg-white text-blue-600' : 'bg-rose-600 text-white' }}">
                                        {{ $unreadTotal }}
                                    </span>
                                @endif
                            </a>
                            <a href="{{ route('about.pj') }}" class="px-3.5 py-2 text-sm font-semibold rounded-xl transition-all {{ request()->routeIs('about.pj') ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-700 hover:text-blue-600 hover:bg-blue-50' }}">
                                Tim PJ Alat
                            </a>
                        @endif
                    @else
                        <!-- Navigation for Guests -->
                        <a href="{{ route('home') }}" class="px-3.5 py-2 text-sm font-semibold rounded-xl transition-all {{ request()->routeIs('home') ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-700 hover:text-blue-600 hover:bg-blue-50' }}">
                            Beranda
                        </a>
                        <a href="{{ route('catalog.index') }}" class="px-3.5 py-2 text-sm font-semibold rounded-xl transition-all {{ request()->routeIs('catalog.*') ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-700 hover:text-blue-600 hover:bg-blue-50' }}">
                            Katalog Alat
                        </a>
                        <a href="{{ route('about.pj') }}" class="px-3.5 py-2 text-sm font-semibold rounded-xl transition-all {{ request()->routeIs('about.pj') ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-700 hover:text-blue-600 hover:bg-blue-50' }}">
                            Tim PJ Alat
                        </a>
                    @endauth
                </nav>

                <!-- Auth Action Controls (Desktop & Mobile) -->
                <div class="flex items-center gap-2 sm:gap-3">
                    @auth
                        <!-- Mobile Header Actions (Active on < lg) -->
                        <div class="lg:hidden flex items-center">
                            <!-- Mobile Profile Sheet Trigger Button -->
                            <button type="button" 
                                    onclick="openMobileProfileDrawer()" 
                                    class="flex items-center gap-1.5 py-1 pl-1 pr-2 rounded-full bg-slate-100 hover:bg-slate-200 border border-slate-200/90 active:scale-95 transition-all focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    aria-label="Buka Profil & Menu">
                                <div class="w-7 h-7 rounded-full bg-blue-600 text-white font-extrabold text-xs flex items-center justify-center shadow-2xs">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <span class="text-[11px] font-bold text-slate-800 max-w-[70px] truncate leading-tight">
                                    {{ explode(' ', Auth::user()->name)[0] }}
                                </span>
                                <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                        </div>

                        <!-- Desktop User Info & Logout (Visible on sm/lg) -->
                        <div class="hidden lg:flex items-center gap-3 pl-3 border-l border-slate-200">
                            <div class="text-right">
                                <span class="block text-xs font-bold text-slate-800">{{ Auth::user()->name }}</span>
                                <span class="text-[10px] text-slate-500 font-medium">
                                    {{ ucfirst(Auth::user()->role) }} • {{ Auth::user()->class_or_dept ?? 'SMKN 5' }}
                                </span>
                            </div>

                            <!-- Desktop Logout Button -->
                            <form action="{{ route('logout') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" title="Keluar" class="p-2 text-slate-400 hover:text-rose-600 rounded-xl hover:bg-rose-50 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                </button>
                            </form>
                        </div>
                    @else
                        <!-- Guest Auth Buttons -->
                        <div class="flex items-center gap-1 sm:gap-2 shrink-0">
                            <a href="{{ route('login') }}" class="px-2.5 sm:px-4 py-1.5 sm:py-2 text-xs font-bold text-slate-700 hover:bg-blue-50 hover:text-blue-600 rounded-xl transition-colors">
                                Masuk
                            </a>
                            <a href="{{ route('register') }}" class="px-3 sm:px-4 py-1.5 sm:py-2 text-xs font-bold text-white bg-blue-600 hover:bg-blue-800 rounded-xl shadow-xs hover:shadow-md transition-all">
                                <span>Daftar</span><span class="hidden sm:inline"> Akun</span>
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Flash Alerts Container -->
    <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 mt-3 sm:mt-4 w-full">
        @if(session('success'))
            <div class="flash-alert-item p-3.5 sm:p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs sm:text-sm font-medium flex items-center justify-between shadow-2xs transition-all">
                <div class="flex items-center gap-2.5 min-w-0">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="truncate sm:whitespace-normal">{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.closest('.flash-alert-item').remove()" class="text-emerald-500 hover:text-emerald-800 p-1 font-bold text-base leading-none">&times;</button>
            </div>
        @endif

        @if(session('error'))
            <div class="flash-alert-item p-3.5 sm:p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-900 text-xs sm:text-sm font-medium flex items-center justify-between shadow-2xs transition-all">
                <div class="flex items-center gap-2.5 min-w-0">
                    <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="truncate sm:whitespace-normal">{{ session('error') }}</span>
                </div>
                <button type="button" onclick="this.closest('.flash-alert-item').remove()" class="text-rose-500 hover:text-rose-800 p-1 font-bold text-base leading-none">&times;</button>
            </div>
        @endif

        @if(session('info'))
            <div class="flash-alert-item p-3.5 sm:p-4 rounded-2xl bg-blue-50 border border-blue-200 text-blue-900 text-xs sm:text-sm font-medium flex items-center justify-between shadow-2xs transition-all">
                <div class="flex items-center gap-2.5 min-w-0">
                    <svg class="w-5 h-5 text-blue-800 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="truncate sm:whitespace-normal">{{ session('info') }}</span>
                </div>
                <button type="button" onclick="this.closest('.flash-alert-item').remove()" class="text-blue-500 hover:text-blue-800 p-1 font-bold text-base leading-none">&times;</button>
            </div>
        @endif
    </div>

    <!-- Main Content -->
    <main class="flex-grow pb-28 sm:pb-32 lg:pb-12">
        @yield('content')
    </main>

    <!-- Global Footer -->
    <footer class="bg-slate-950 text-slate-400 text-xs pt-8 sm:pt-14 pb-8 sm:pb-12 border-t border-slate-800 mt-12 sm:mt-16 mb-20 lg:mb-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Mobile Footer (Phone Screens) -->
            <div class="md:hidden space-y-4">
                <!-- Brand & School Identity -->
                <div class="flex items-center justify-between pb-3 border-b border-slate-800/80">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-blue-600 flex items-center justify-center text-white font-black text-sm shadow-xs">
                            S
                        </div>
                        <div>
                            <div class="flex items-center gap-1.5">
                                <span class="text-sm font-black tracking-tight text-white">SIPAS</span>
                                <span class="text-[9px] font-bold text-blue-400 bg-blue-950/70 border border-blue-800/80 px-1.5 py-0.5 rounded">SMKN 5 SURAKARTA</span>
                            </div>
                            <p class="text-[10px] text-slate-400 mt-0.5">Sistem Informasi Peminjaman Alat Sekolah</p>
                        </div>
                    </div>
                </div>

                <!-- Quick Navigation (2x2 Grid) -->
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-2">Akses Cepat</p>
                    <div class="grid grid-cols-2 gap-2 text-xs">
                        <a href="{{ route('home') }}" class="flex items-center gap-2 px-3 py-2 rounded-xl bg-slate-900 border border-slate-800/80 text-slate-300 active:bg-slate-800 hover:text-white transition-colors">
                            <svg class="w-3.5 h-3.5 text-blue-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            <span class="truncate">Beranda</span>
                        </a>
                        <a href="{{ route('catalog.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-xl bg-slate-900 border border-slate-800/80 text-slate-300 active:bg-slate-800 hover:text-white transition-colors">
                            <svg class="w-3.5 h-3.5 text-blue-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                            <span class="truncate">Katalog Alat</span>
                        </a>
                        <a href="{{ route('loans.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-xl bg-slate-900 border border-slate-800/80 text-slate-300 active:bg-slate-800 hover:text-white transition-colors">
                            <svg class="w-3.5 h-3.5 text-blue-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            <span class="truncate">Status Pinjam</span>
                        </a>
                        <a href="{{ route('about.pj') }}" class="flex items-center gap-2 px-3 py-2 rounded-xl bg-slate-900 border border-slate-800/80 text-slate-300 active:bg-slate-800 hover:text-white transition-colors">
                            <svg class="w-3.5 h-3.5 text-blue-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            <span class="truncate">Tim PJ Alat</span>
                        </a>
                    </div>
                </div>

                <!-- Info Compact Card: Lokasi & Jam -->
                <div class="p-3 rounded-2xl bg-slate-900/90 border border-slate-800/80 space-y-2">
                    <div class="flex items-start gap-2 text-[11px] text-slate-300">
                        <svg class="w-4 h-4 text-slate-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span>Jl. Adi Sucipto No. 42, Kerten, Kec. Laweyan, Surakarta 57143</span>
                    </div>
                    <div class="flex items-center justify-between pt-1 border-t border-slate-800/60 text-[10.5px]">
                        <span class="text-slate-400 flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 inline-block"></span>
                            Jam Layanan: 07.00 - 15.30 WIB
                        </span>
                        <span class="text-slate-500 font-mono text-[10px]">Senin - Jumat</span>
                    </div>
                </div>

                <!-- Bottom Micro Copyright -->
                <div class="pt-2 text-center text-slate-500 text-[10.5px]">
                    &copy; 2026 SIPAS • SMK Negeri 5 Surakarta
                </div>
            </div>

            <!-- Desktop Footer (Tablet / Desktop Screens) -->
            <div class="hidden md:block">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8 pb-10 border-b border-slate-800">
                    <div class="md:col-span-2 space-y-3">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-xl bg-blue-900 flex items-center justify-center text-white font-black text-sm border border-blue-800">
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
                            <li><a href="{{ route('loans.index') }}" class="hover:text-white transition-colors">Status &amp; Riwayat Peminjaman</a></li>
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
        </div>
    </footer>

    <!-- Android/iOS Modern Bottom Navigation Bar (Mobile / Small Screens Only) -->
    <nav aria-label="Navigasi Bawah Aplikasi" class="fixed bottom-0 inset-x-0 z-40 bg-white/95 backdrop-blur-md border-t border-slate-200/90 shadow-[0_-4px_25px_rgba(0,0,0,0.06)] lg:hidden px-1 sm:px-2 pt-1 pb-[max(0.6rem,env(safe-area-inset-bottom))] transition-all">
        <div class="max-w-md mx-auto flex items-center justify-around">
            @auth
                @if(Auth::user()->isAdmin())
                    <!-- Admin Tab: Verifikasi Pinjam -->
                    <a href="{{ route('admin.loans.index') }}" class="flex-1 flex flex-col items-center justify-center py-1 select-none active:scale-95 transition-transform">
                        <div class="w-11 sm:w-13 h-7 sm:h-8 flex items-center justify-center rounded-full transition-all {{ request()->routeIs('admin.loans.*') ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-500 hover:text-slate-800' }}">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <span class="text-[9.5px] sm:text-[10px] tracking-tight mt-0.5 leading-tight text-center {{ request()->routeIs('admin.loans.*') ? 'font-bold text-blue-700' : 'font-medium text-slate-500' }}">
                            Verifikasi
                        </span>
                    </a>

                    <!-- Admin Tab: Kelola Alat -->
                    <a href="{{ route('admin.tools.index') }}" class="flex-1 flex flex-col items-center justify-center py-1 select-none active:scale-95 transition-transform">
                        <div class="w-11 sm:w-13 h-7 sm:h-8 flex items-center justify-center rounded-full transition-all {{ request()->routeIs('admin.tools.*') ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-500 hover:text-slate-800' }}">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        </div>
                        <span class="text-[9.5px] sm:text-[10px] tracking-tight mt-0.5 leading-tight text-center {{ request()->routeIs('admin.tools.*') ? 'font-bold text-blue-700' : 'font-medium text-slate-500' }}">
                            Alat
                        </span>
                    </a>

                    <!-- Admin Tab: Kirim Pesan -->
                    @php
                        $adminUnreadCount = Auth::user()->unreadMessagesCount();
                    @endphp
                    <a href="{{ route('admin.messages.index') }}" class="flex-1 flex flex-col items-center justify-center py-1 select-none active:scale-95 transition-transform relative">
                        <div class="w-11 sm:w-13 h-7 sm:h-8 flex items-center justify-center rounded-full transition-all relative {{ request()->routeIs('admin.messages.*') ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-500 hover:text-slate-800' }}">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                            @if($adminUnreadCount > 0)
                                <span class="absolute top-0 right-1 sm:right-2 min-w-3.5 h-3.5 px-0.5 rounded-full bg-rose-600 text-white text-[8px] font-black flex items-center justify-center shadow-xs">
                                    {{ $adminUnreadCount > 9 ? '9+' : $adminUnreadCount }}
                                </span>
                            @endif
                        </div>
                        <span class="text-[9.5px] sm:text-[10px] tracking-tight mt-0.5 leading-tight text-center {{ request()->routeIs('admin.messages.*') ? 'font-bold text-blue-700' : 'font-medium text-slate-500' }}">
                            Pesan
                        </span>
                    </a>

                    <!-- Admin Tab: Pratinjau Katalog -->
                    <a href="{{ route('catalog.index') }}" class="flex-1 flex flex-col items-center justify-center py-1 select-none active:scale-95 transition-transform">
                        <div class="w-11 sm:w-13 h-7 sm:h-8 flex items-center justify-center rounded-full transition-all {{ request()->routeIs('catalog.*') ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-500 hover:text-slate-800' }}">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                        </div>
                        <span class="text-[9.5px] sm:text-[10px] tracking-tight mt-0.5 leading-tight text-center {{ request()->routeIs('catalog.*') ? 'font-bold text-blue-700' : 'font-medium text-slate-500' }}">
                            Katalog
                        </span>
                    </a>
                @else
                    <!-- Borrower Tab: Beranda -->
                    <a href="{{ route('home') }}" class="flex-1 flex flex-col items-center justify-center py-1 select-none active:scale-95 transition-transform">
                        <div class="w-11 sm:w-13 h-7 sm:h-8 flex items-center justify-center rounded-full transition-all {{ request()->routeIs('home') ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-500 hover:text-slate-800' }}">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        </div>
                        <span class="text-[9.5px] sm:text-[10px] tracking-tight mt-0.5 leading-tight text-center {{ request()->routeIs('home') ? 'font-bold text-blue-700' : 'font-medium text-slate-500' }}">
                            Beranda
                        </span>
                    </a>

                    <!-- Borrower Tab: Katalog Alat -->
                    <a href="{{ route('catalog.index') }}" class="flex-1 flex flex-col items-center justify-center py-1 select-none active:scale-95 transition-transform">
                        <div class="w-11 sm:w-13 h-7 sm:h-8 flex items-center justify-center rounded-full transition-all {{ request()->routeIs('catalog.*') || request()->routeIs('loans.create') ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-500 hover:text-slate-800' }}">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                        </div>
                        <span class="text-[9.5px] sm:text-[10px] tracking-tight mt-0.5 leading-tight text-center {{ request()->routeIs('catalog.*') || request()->routeIs('loans.create') ? 'font-bold text-blue-700' : 'font-medium text-slate-500' }}">
                            Katalog
                        </span>
                    </a>

                    <!-- Borrower Tab: Riwayat Peminjaman -->
                    <a href="{{ route('loans.index') }}" class="flex-1 flex flex-col items-center justify-center py-1 select-none active:scale-95 transition-transform">
                        <div class="w-11 sm:w-13 h-7 sm:h-8 flex items-center justify-center rounded-full transition-all {{ request()->routeIs('loans.index') ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-500 hover:text-slate-800' }}">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        </div>
                        <span class="text-[9.5px] sm:text-[10px] tracking-tight mt-0.5 leading-tight text-center {{ request()->routeIs('loans.index') ? 'font-bold text-blue-700' : 'font-medium text-slate-500' }}">
                            Riwayat
                        </span>
                    </a>

                    <!-- Borrower Tab: Pemberitahuan & Pesan -->
                    @php
                        $borrowerUnreadCount = Auth::user()->unreadMessagesCount();
                    @endphp
                    <a href="{{ route('messages.index') }}" class="flex-1 flex flex-col items-center justify-center py-1 select-none active:scale-95 transition-transform relative">
                        <div class="w-11 sm:w-13 h-7 sm:h-8 flex items-center justify-center rounded-full transition-all relative {{ request()->routeIs('messages.*') ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-500 hover:text-slate-800' }}">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            @if($borrowerUnreadCount > 0)
                                <span class="absolute top-0 right-1 sm:right-2 min-w-3.5 h-3.5 px-0.5 rounded-full bg-rose-600 text-white text-[8px] font-black flex items-center justify-center shadow-xs">
                                    {{ $borrowerUnreadCount > 9 ? '9+' : $borrowerUnreadCount }}
                                </span>
                            @endif
                        </div>
                        <span class="text-[9.5px] sm:text-[10px] tracking-tight mt-0.5 leading-tight text-center {{ request()->routeIs('messages.*') ? 'font-bold text-blue-700' : 'font-medium text-slate-500' }}">
                            Pesan
                        </span>
                    </a>
                @endif
            @else
                <!-- Guest Tab: Beranda -->
                <a href="{{ route('home') }}" class="flex-1 flex flex-col items-center justify-center py-1 select-none active:scale-95 transition-transform">
                    <div class="w-12 sm:w-14 h-7 sm:h-8 flex items-center justify-center rounded-full transition-all {{ request()->routeIs('home') ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-500 hover:text-slate-800' }}">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    </div>
                    <span class="text-[10px] sm:text-[11px] tracking-tight mt-0.5 leading-tight text-center {{ request()->routeIs('home') ? 'font-bold text-blue-700' : 'font-medium text-slate-500' }}">
                        Beranda
                    </span>
                </a>

                <!-- Guest Tab: Katalog -->
                <a href="{{ route('catalog.index') }}" class="flex-1 flex flex-col items-center justify-center py-1 select-none active:scale-95 transition-transform">
                    <div class="w-12 sm:w-14 h-7 sm:h-8 flex items-center justify-center rounded-full transition-all {{ request()->routeIs('catalog.*') ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-500 hover:text-slate-800' }}">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    </div>
                    <span class="text-[10px] sm:text-[11px] tracking-tight mt-0.5 leading-tight text-center {{ request()->routeIs('catalog.*') ? 'font-bold text-blue-700' : 'font-medium text-slate-500' }}">
                        Katalog
                    </span>
                </a>

                <!-- Guest Tab: Tim PJ -->
                <a href="{{ route('about.pj') }}" class="flex-1 flex flex-col items-center justify-center py-1 select-none active:scale-95 transition-transform">
                    <div class="w-12 sm:w-14 h-7 sm:h-8 flex items-center justify-center rounded-full transition-all {{ request()->routeIs('about.pj') ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-500 hover:text-slate-800' }}">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <span class="text-[10px] sm:text-[11px] tracking-tight mt-0.5 leading-tight text-center {{ request()->routeIs('about.pj') ? 'font-bold text-blue-700' : 'font-medium text-slate-500' }}">
                        Tim PJ
                    </span>
                </a>
            @endauth
        </div>
    </nav>

    @auth
        <!-- Mobile Profile & Menu Drawer (Bottom Sheet) -->
        <div id="mobileProfileDrawer" class="fixed inset-0 z-50 pointer-events-none transition-all duration-300 opacity-0" aria-hidden="true" role="dialog" aria-modal="true">
            <!-- Backdrop overlay -->
            <div id="mobileProfileBackdrop" onclick="closeMobileProfileDrawer()" class="absolute inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity duration-300 opacity-0"></div>

            <!-- Sheet Panel -->
            <div id="mobileProfilePanel" class="absolute bottom-0 inset-x-0 w-full max-w-lg mx-auto bg-white rounded-t-3xl border-t border-slate-200 shadow-2xl p-5 sm:p-6 transform translate-y-full transition-transform duration-300 ease-out max-h-[85vh] overflow-y-auto no-scrollbar pointer-events-auto flex flex-col space-y-4">
                
                <!-- Drag Handle -->
                <div class="w-12 h-1.5 bg-slate-300 rounded-full mx-auto shrink-0 mb-1"></div>

                <!-- Sheet Header -->
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h2 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        <span>Akun &amp; Menu SIPAS</span>
                    </h2>
                    <button type="button" onclick="closeMobileProfileDrawer()" class="p-1.5 rounded-full text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors" aria-label="Tutup Menu">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- User Identity Card -->
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50/50 rounded-2xl p-4 border border-blue-100/80 flex items-center gap-3.5">
                    <div class="w-12 h-12 rounded-2xl bg-blue-600 text-white font-extrabold text-lg flex items-center justify-center shrink-0 shadow-xs border-2 border-white">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <span class="text-sm font-extrabold text-slate-900 truncate block">{{ Auth::user()->name }}</span>
                        <div class="flex items-center gap-1.5 mt-1 flex-wrap">
                            <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-md {{ Auth::user()->isAdmin() ? 'bg-amber-100 text-amber-800 border border-amber-200' : (Auth::user()->isGuru() ? 'bg-purple-100 text-purple-800 border border-purple-200' : 'bg-blue-100 text-blue-800 border border-blue-200') }}">
                                {{ Auth::user()->role }}
                            </span>
                            <span class="text-[11px] font-mono text-slate-500 font-medium">
                                {{ Auth::user()->identifier ? (Auth::user()->isGuru() ? 'NIP: ' : 'NISN: ') . Auth::user()->identifier : '-' }}
                            </span>
                        </div>
                        @if(Auth::user()->class_or_dept)
                            <p class="text-[11px] text-slate-600 font-medium mt-1">
                                {{ Auth::user()->class_or_dept }}
                            </p>
                        @endif
                    </div>
                </div>

                <!-- Quick Navigation Links -->
                <div class="space-y-1 pt-1">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 px-1">Menu Navigasi</span>
                    
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('admin.loans.index') }}" class="flex items-center justify-between p-3 rounded-2xl hover:bg-slate-50 border border-transparent hover:border-slate-200 text-slate-700 hover:text-blue-600 transition-all">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                </div>
                                <span class="text-xs font-bold">Verifikasi Peminjaman</span>
                            </div>
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                        <a href="{{ route('admin.tools.index') }}" class="flex items-center justify-between p-3 rounded-2xl hover:bg-slate-50 border border-transparent hover:border-slate-200 text-slate-700 hover:text-blue-600 transition-all">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                </div>
                                <span class="text-xs font-bold">Kelola Alat Praktik</span>
                            </div>
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                        <a href="{{ route('admin.messages.index') }}" class="flex items-center justify-between p-3 rounded-2xl hover:bg-slate-50 border border-transparent hover:border-slate-200 text-slate-700 hover:text-blue-600 transition-all">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                                </div>
                                <span class="text-xs font-bold">Kirim Pesan / Pengumuman</span>
                            </div>
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                        <a href="{{ route('admin.loans.report.print') }}" target="_blank" class="flex items-center justify-between p-3 rounded-2xl hover:bg-slate-50 border border-transparent hover:border-slate-200 text-slate-700 hover:text-blue-600 transition-all">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                </div>
                                <span class="text-xs font-bold">Cetak Rekap Laporan PDF</span>
                            </div>
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </a>
                    @else
                        <a href="{{ route('catalog.index') }}" class="flex items-center justify-between p-3 rounded-2xl hover:bg-slate-50 border border-transparent hover:border-slate-200 text-slate-700 hover:text-blue-600 transition-all">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                </div>
                                <span class="text-xs font-bold">Eksplor Katalog Alat</span>
                            </div>
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                        <a href="{{ route('loans.index') }}" class="flex items-center justify-between p-3 rounded-2xl hover:bg-slate-50 border border-transparent hover:border-slate-200 text-slate-700 hover:text-blue-600 transition-all">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                </div>
                                <span class="text-xs font-bold">Riwayat &amp; Status Pinjam</span>
                            </div>
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                        <a href="{{ route('messages.index') }}" class="flex items-center justify-between p-3 rounded-2xl hover:bg-slate-50 border border-transparent hover:border-slate-200 text-slate-700 hover:text-blue-600 transition-all">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                                </div>
                                <span class="text-xs font-bold">Pesan &amp; Pemberitahuan</span>
                            </div>
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    @endif

                    <a href="{{ route('about.pj') }}" class="flex items-center justify-between p-3 rounded-2xl hover:bg-slate-50 border border-transparent hover:border-slate-200 text-slate-700 hover:text-blue-600 transition-all">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            </div>
                            <span class="text-xs font-bold">Tim PJ Alat &amp; Informasi</span>
                        </div>
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>

                <!-- School Location & Jam Layanan Info Card -->
                <div class="p-3 rounded-2xl bg-slate-50 border border-slate-200 text-[11px] text-slate-600 flex items-center justify-between">
                    <div>
                        <span class="font-bold text-slate-800 block">Ruang Alat SMKN 5 Surakarta</span>
                        <span class="text-slate-500">Jam Layanan: 07.00 - 15.30 WIB</span>
                    </div>
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 shrink-0"></span>
                </div>

                <!-- Logout Action -->
                <div class="pt-2">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full py-3 px-4 rounded-2xl bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 font-bold text-xs flex items-center justify-center gap-2 transition-colors active:scale-95">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            <span>Keluar dari Akun</span>
                        </button>
                    </form>
                </div>

            </div>
        </div>
    @endauth

    <!-- Mobile Drawer & Flash Toast JavaScript Controller -->
    <script>
        function openMobileProfileDrawer() {
            const drawer = document.getElementById('mobileProfileDrawer');
            const backdrop = document.getElementById('mobileProfileBackdrop');
            const panel = document.getElementById('mobileProfilePanel');
            if (!drawer || !backdrop || !panel) return;

            drawer.classList.remove('pointer-events-none');
            drawer.classList.add('pointer-events-auto');
            drawer.setAttribute('aria-hidden', 'false');

            requestAnimationFrame(() => {
                drawer.classList.remove('opacity-0');
                drawer.classList.add('opacity-100');
                backdrop.classList.remove('opacity-0');
                backdrop.classList.add('opacity-100');
                panel.classList.remove('translate-y-full');
                panel.classList.add('translate-y-0');
            });
            document.body.classList.add('overflow-hidden');
        }

        function closeMobileProfileDrawer() {
            const drawer = document.getElementById('mobileProfileDrawer');
            const backdrop = document.getElementById('mobileProfileBackdrop');
            const panel = document.getElementById('mobileProfilePanel');
            if (!drawer || !backdrop || !panel) return;

            drawer.classList.remove('opacity-100');
            drawer.classList.add('opacity-0');
            backdrop.classList.remove('opacity-100');
            backdrop.classList.add('opacity-0');
            panel.classList.remove('translate-y-0');
            panel.classList.add('translate-y-full');

            setTimeout(() => {
                drawer.classList.remove('pointer-events-auto');
                drawer.classList.add('pointer-events-none');
                drawer.setAttribute('aria-hidden', 'true');
                document.body.classList.remove('overflow-hidden');
            }, 300);
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeMobileProfileDrawer();
            }
        });

        // Flash Alert Auto-dismiss after 6 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.flash-alert-item');
            if (alerts.length > 0) {
                setTimeout(() => {
                    alerts.forEach(alert => {
                        alert.classList.add('opacity-0');
                        setTimeout(() => alert.remove(), 400);
                    });
                }, 6000);
            }
        });
    </script>
</body>
</html>
