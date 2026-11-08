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
<body class="bg-slate-50 text-slate-800 antialiased font-sans selection:bg-blue-900 selection:text-white min-h-screen flex flex-col">

    <!-- Main Navigation Bar -->
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-slate-200/90 shadow-2xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 sm:h-20">
                
                <!-- Brand Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-2.5 sm:gap-3 group focus:outline-none">
                    <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl bg-blue-600 flex items-center justify-center text-white border border-blue-500 shadow-xs group-hover:bg-blue-700 transition-colors shrink-0">
                        <img src="{{ asset('storage/images/1.png') }}" alt="Logo SIPAS" class="w-full h-full rounded-xl object-contain">
                    </div>
                    <div>
                        <div class="flex items-center gap-1.5 sm:gap-2">
                            <span class="text-xl sm:text-2xl font-black tracking-tight text-slate-900 group-hover:text-blue-600 transition-colors">SIPAS</span>
                            <span class="text-[9px] sm:text-[10px] font-bold uppercase tracking-wider px-1.5 sm:px-2 py-0.5 rounded-md bg-blue-50 text-blue-700 border border-blue-200">SMKN 5 SURAKARTA</span>
                        </div>
                        <p class="text-[10px] sm:text-[11px] font-medium text-slate-500 hidden sm:block">Sistem Informasi Peminjaman Alat Sekolah</p>
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
                            <a href="{{ route('catalog.index') }}" class="px-3 py-2 text-xs font-semibold text-slate-500 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all">
                                Pratinjau Katalog
                            </a>
                            <a href="{{ route('about.pj') }}" class="px-3 py-2 text-xs font-semibold text-slate-500 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all">
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
                        <div class="flex items-center gap-2 sm:gap-3 pl-2 sm:pl-3 border-l border-slate-200">
                            <!-- Desktop User Info -->
                            <div class="text-right hidden sm:block">
                                <span class="block text-xs font-bold text-slate-800">{{ Auth::user()->name }}</span>
                                <span class="text-[10px] text-slate-500 font-medium">
                                    {{ ucfirst(Auth::user()->role) }} • {{ Auth::user()->class_or_dept ?? 'SMKN 5' }}
                                </span>
                            </div>

                            <!-- Mobile User Badge with Role -->
                            <div class="sm:hidden flex items-center gap-1.5 bg-slate-100 pl-2 pr-1 py-1 rounded-xl border border-slate-200">
                                <div class="text-right">
                                    <span class="block text-[11px] font-bold text-slate-800 leading-tight max-w-[85px] truncate">{{ Auth::user()->name }}</span>
                                    <span class="text-[9px] text-blue-600 font-bold uppercase leading-none">{{ Auth::user()->role }}</span>
                                </div>
                                <form action="{{ route('logout') }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" title="Keluar" class="p-1 text-slate-400 hover:text-rose-600 rounded-lg hover:bg-rose-50 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                    </button>
                                </form>
                            </div>

                            <!-- Desktop Logout Button -->
                            <form action="{{ route('logout') }}" method="POST" class="hidden sm:inline">
                                @csrf
                                <button type="submit" title="Keluar" class="p-2 text-slate-400 hover:text-rose-600 rounded-xl hover:bg-rose-50 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="flex items-center gap-1.5 sm:gap-2">
                            <a href="{{ route('login') }}" class="px-3 sm:px-4 py-1.5 sm:py-2 text-xs font-bold text-slate-700 hover:bg-blue-50 hover:text-blue-600 rounded-xl transition-colors">
                                Masuk
                            </a>
                            <a href="{{ route('register') }}" class="px-3 sm:px-4 py-1.5 sm:py-2 text-xs font-bold text-white bg-blue-600 hover:bg-blue-800 rounded-xl shadow-xs transition-colors">
                                Daftar Akun
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Flash Alerts Container -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
        @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs sm:text-sm font-medium flex items-center justify-between shadow-2xs">
                <div class="flex items-center gap-2.5">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800 text-sm font-bold">&times;</button>
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-900 text-xs sm:text-sm font-medium flex items-center justify-between shadow-2xs">
                <div class="flex items-center gap-2.5">
                    <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-800 text-sm font-bold">&times;</button>
            </div>
        @endif

        @if(session('info'))
            <div class="p-4 rounded-2xl bg-blue-50 border border-blue-200 text-blue-900 text-xs sm:text-sm font-medium flex items-center justify-between shadow-2xs">
                <div class="flex items-center gap-2.5">
                    <svg class="w-5 h-5 text-blue-800 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('info') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-blue-500 hover:text-blue-800 text-sm font-bold">&times;</button>
            </div>
        @endif
    </div>

    <!-- Main Content -->
    <main class="flex-grow pb-24 lg:pb-0">
        @yield('content')
    </main>

    <!-- Global Footer -->
    <footer class="bg-slate-950 text-slate-400 text-xs pt-14 pb-12 border-t border-slate-800 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
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
    </footer>

    <!-- Android Style Bottom Navigation Bar (Mobile / Small Screens Only) -->
    <nav aria-label="Navigasi Bawah Aplikasi" class="fixed bottom-0 inset-x-0 z-40 bg-white/95 backdrop-blur-md border-t border-slate-200/90 shadow-[0_-4px_20px_rgba(0,0,0,0.06)] lg:hidden px-2 pt-1.5 pb-[max(0.6rem,env(safe-area-inset-bottom))] transition-all">
        <div class="max-w-md mx-auto flex items-center justify-around">
            @auth
                @if(Auth::user()->isAdmin())
                    <!-- Admin Tab: Verifikasi Pinjam -->
                    <a href="{{ route('admin.loans.index') }}" class="flex-1 flex flex-col items-center justify-center py-1 select-none active:scale-90 transition-transform group">
                        <div class="w-12 sm:w-14 h-8 flex items-center justify-center rounded-full transition-all {{ request()->routeIs('admin.loans.*') ? 'bg-blue-100 text-blue-700' : 'text-slate-500 hover:text-slate-800' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <span class="text-[10px] tracking-tight mt-0.5 leading-tight text-center {{ request()->routeIs('admin.loans.*') ? 'font-bold text-blue-700' : 'font-medium text-slate-500' }}">
                            Verifikasi
                        </span>
                    </a>

                    <!-- Admin Tab: Kelola Alat -->
                    <a href="{{ route('admin.tools.index') }}" class="flex-1 flex flex-col items-center justify-center py-1 select-none active:scale-90 transition-transform group">
                        <div class="w-12 sm:w-14 h-8 flex items-center justify-center rounded-full transition-all {{ request()->routeIs('admin.tools.*') ? 'bg-blue-100 text-blue-700' : 'text-slate-500 hover:text-slate-800' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <span class="text-[10px] tracking-tight mt-0.5 leading-tight text-center {{ request()->routeIs('admin.tools.*') ? 'font-bold text-blue-700' : 'font-medium text-slate-500' }}">
                            Kelola Alat
                        </span>
                    </a>

                    <!-- Admin Tab: Kirim Pesan -->
                    <a href="{{ route('admin.messages.index') }}" class="flex-1 flex flex-col items-center justify-center py-1 select-none active:scale-90 transition-transform group">
                        <div class="w-12 sm:w-14 h-8 flex items-center justify-center rounded-full transition-all {{ request()->routeIs('admin.messages.*') ? 'bg-blue-100 text-blue-700' : 'text-slate-500 hover:text-slate-800' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                        </div>
                        <span class="text-[10px] tracking-tight mt-0.5 leading-tight text-center {{ request()->routeIs('admin.messages.*') ? 'font-bold text-blue-700' : 'font-medium text-slate-500' }}">
                            Kirim Pesan
                        </span>
                    </a>

                    <!-- Admin Tab: Pratinjau Katalog -->
                    <a href="{{ route('catalog.index') }}" class="flex-1 flex flex-col items-center justify-center py-1 select-none active:scale-90 transition-transform group">
                        <div class="w-12 sm:w-14 h-8 flex items-center justify-center rounded-full transition-all {{ request()->routeIs('catalog.*') ? 'bg-blue-100 text-blue-700' : 'text-slate-500 hover:text-slate-800' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                        </div>
                        <span class="text-[10px] tracking-tight mt-0.5 leading-tight text-center {{ request()->routeIs('catalog.*') ? 'font-bold text-blue-700' : 'font-medium text-slate-500' }}">
                            Katalog
                        </span>
                    </a>

                    <!-- Admin Tab: Tim PJ -->
                    <a href="{{ route('about.pj') }}" class="flex-1 flex flex-col items-center justify-center py-1 select-none active:scale-90 transition-transform group">
                        <div class="w-12 sm:w-14 h-8 flex items-center justify-center rounded-full transition-all {{ request()->routeIs('about.pj') ? 'bg-blue-100 text-blue-700' : 'text-slate-500 hover:text-slate-800' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                        <span class="text-[10px] tracking-tight mt-0.5 leading-tight text-center {{ request()->routeIs('about.pj') ? 'font-bold text-blue-700' : 'font-medium text-slate-500' }}">
                            Tim PJ
                        </span>
                    </a>
                @else
                    <!-- Borrower Tab: Beranda -->
                    <a href="{{ route('home') }}" class="flex-1 flex flex-col items-center justify-center py-1 select-none active:scale-90 transition-transform group">
                        <div class="w-12 sm:w-14 h-8 flex items-center justify-center rounded-full transition-all {{ request()->routeIs('home') ? 'bg-blue-100 text-blue-700' : 'text-slate-500 hover:text-slate-800' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        </div>
                        <span class="text-[10px] tracking-tight mt-0.5 leading-tight text-center {{ request()->routeIs('home') ? 'font-bold text-blue-700' : 'font-medium text-slate-500' }}">
                            Beranda
                        </span>
                    </a>

                    <!-- Borrower Tab: Katalog Alat -->
                    <a href="{{ route('catalog.index') }}" class="flex-1 flex flex-col items-center justify-center py-1 select-none active:scale-90 transition-transform group">
                        <div class="w-12 sm:w-14 h-8 flex items-center justify-center rounded-full transition-all {{ request()->routeIs('catalog.*') || request()->routeIs('loans.create') ? 'bg-blue-100 text-blue-700' : 'text-slate-500 hover:text-slate-800' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                        </div>
                        <span class="text-[10px] tracking-tight mt-0.5 leading-tight text-center {{ request()->routeIs('catalog.*') || request()->routeIs('loans.create') ? 'font-bold text-blue-700' : 'font-medium text-slate-500' }}">
                            Katalog
                        </span>
                    </a>

                    <!-- Borrower Tab: Riwayat Peminjaman -->
                    <a href="{{ route('loans.index') }}" class="flex-1 flex flex-col items-center justify-center py-1 select-none active:scale-90 transition-transform group">
                        <div class="w-12 sm:w-14 h-8 flex items-center justify-center rounded-full transition-all {{ request()->routeIs('loans.index') ? 'bg-blue-100 text-blue-700' : 'text-slate-500 hover:text-slate-800' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                        </div>
                        <span class="text-[10px] tracking-tight mt-0.5 leading-tight text-center {{ request()->routeIs('loans.index') ? 'font-bold text-blue-700' : 'font-medium text-slate-500' }}">
                            Riwayat
                        </span>
                    </a>

                    <!-- Borrower Tab: Pemberitahuan & Pesan -->
                    @php
                        $mobileUnreadTotal = Auth::user()->unreadMessagesCount();
                    @endphp
                    <a href="{{ route('messages.index') }}" class="flex-1 flex flex-col items-center justify-center py-1 select-none active:scale-90 transition-transform group">
                        <div class="w-12 sm:w-14 h-8 flex items-center justify-center rounded-full transition-all relative {{ request()->routeIs('messages.*') ? 'bg-blue-100 text-blue-700' : 'text-slate-500 hover:text-slate-800' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            @if($mobileUnreadTotal > 0)
                                <span class="absolute top-0.5 right-1.5 sm:right-2.5 min-w-4 h-4 px-1 rounded-full bg-rose-600 text-white text-[9px] font-black flex items-center justify-center shadow-xs">
                                    {{ $mobileUnreadTotal > 9 ? '9+' : $mobileUnreadTotal }}
                                </span>
                            @endif
                        </div>
                        <span class="text-[10px] tracking-tight mt-0.5 leading-tight text-center {{ request()->routeIs('messages.*') ? 'font-bold text-blue-700' : 'font-medium text-slate-500' }}">
                            Pesan
                        </span>
                    </a>

                    <!-- Borrower Tab: Tim PJ Alat -->
                    <a href="{{ route('about.pj') }}" class="flex-1 flex flex-col items-center justify-center py-1 select-none active:scale-90 transition-transform group">
                        <div class="w-12 sm:w-14 h-8 flex items-center justify-center rounded-full transition-all {{ request()->routeIs('about.pj') ? 'bg-blue-100 text-blue-700' : 'text-slate-500 hover:text-slate-800' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                        <span class="text-[10px] tracking-tight mt-0.5 leading-tight text-center {{ request()->routeIs('about.pj') ? 'font-bold text-blue-700' : 'font-medium text-slate-500' }}">
                            Tim PJ
                        </span>
                    </a>
                @endif
            @else
                <!-- Guest Tab: Beranda -->
                <a href="{{ route('home') }}" class="flex-1 flex flex-col items-center justify-center py-1 select-none active:scale-90 transition-transform group">
                    <div class="w-12 sm:w-14 h-8 flex items-center justify-center rounded-full transition-all {{ request()->routeIs('home') ? 'bg-blue-100 text-blue-700' : 'text-slate-500 hover:text-slate-800' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    </div>
                    <span class="text-[10px] tracking-tight mt-0.5 leading-tight text-center {{ request()->routeIs('home') ? 'font-bold text-blue-700' : 'font-medium text-slate-500' }}">
                        Beranda
                    </span>
                </a>

                <!-- Guest Tab: Katalog -->
                <a href="{{ route('catalog.index') }}" class="flex-1 flex flex-col items-center justify-center py-1 select-none active:scale-90 transition-transform group">
                    <div class="w-12 sm:w-14 h-8 flex items-center justify-center rounded-full transition-all {{ request()->routeIs('catalog.*') ? 'bg-blue-100 text-blue-700' : 'text-slate-500 hover:text-slate-800' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    </div>
                    <span class="text-[10px] tracking-tight mt-0.5 leading-tight text-center {{ request()->routeIs('catalog.*') ? 'font-bold text-blue-700' : 'font-medium text-slate-500' }}">
                        Katalog
                    </span>
                </a>

                <!-- Guest Tab: Tim PJ -->
                <a href="{{ route('about.pj') }}" class="flex-1 flex flex-col items-center justify-center py-1 select-none active:scale-90 transition-transform group">
                    <div class="w-12 sm:w-14 h-8 flex items-center justify-center rounded-full transition-all {{ request()->routeIs('about.pj') ? 'bg-blue-100 text-blue-700' : 'text-slate-500 hover:text-slate-800' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <span class="text-[10px] tracking-tight mt-0.5 leading-tight text-center {{ request()->routeIs('about.pj') ? 'font-bold text-blue-700' : 'font-medium text-slate-500' }}">
                        Tim PJ
                    </span>
                </a>

                <!-- Guest Tab: Masuk -->
                <a href="{{ route('login') }}" class="flex-1 flex flex-col items-center justify-center py-1 select-none active:scale-90 transition-transform group">
                    <div class="w-12 sm:w-14 h-8 flex items-center justify-center rounded-full transition-all {{ request()->routeIs('login') || request()->routeIs('register') ? 'bg-blue-100 text-blue-700' : 'text-slate-500 hover:text-slate-800' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                    </div>
                    <span class="text-[10px] tracking-tight mt-0.5 leading-tight text-center {{ request()->routeIs('login') || request()->routeIs('register') ? 'font-bold text-blue-700' : 'font-medium text-slate-500' }}">
                        Masuk
                    </span>
                </a>
            @endauth
        </div>
    </nav>
</body>
</html>
