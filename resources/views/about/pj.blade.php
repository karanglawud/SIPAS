@extends('layouts.app')

@section('title', 'Penanggung Jawab (PJ) Kategori Alat - SIPAS SMKN 5')

@section('content')
<section class="py-6 sm:py-10 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 space-y-4 sm:space-y-8">
        
        <!-- Header -->
        <div>
            <nav class="flex items-center gap-1.5 sm:gap-2 text-[11px] sm:text-xs text-slate-400 mb-1.5 sm:mb-2">
                <a href="{{ route('home') }}" class="hover:text-blue-600">Beranda</a>
                <span>/</span>
                <span class="text-slate-700 font-semibold">Tim PJ Alat</span>
            </nav>
            <h1 class="text-xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Struktur Penanggung Jawab (PJ) Alat</h1>
            <p class="text-slate-500 text-xs sm:text-sm mt-1">
                SMK Negeri 5 Surakarta membagi tanggung jawab pengelolaan alat kejuruan per kategori untuk memastikan kondisi, stok unit, dan verifikasi peminjaman.
            </p>
        </div>

        <!-- 4 Categories Cards (Berjejer 2x2 di Mobile, 2 Kolom di Desktop) -->
        <div class="grid grid-cols-2 lg:grid-cols-2 gap-2.5 sm:gap-6">
            @foreach($categories as $cat)
                <div class="bg-white rounded-2xl sm:rounded-3xl border border-slate-200 p-3 sm:p-7 shadow-xs hover:border-slate-400 transition-all flex flex-col justify-between space-y-2.5 sm:space-y-5">
                    
                    <div class="space-y-1.5 sm:space-y-3">
                        <div class="flex items-center justify-between gap-1 flex-wrap">
                            <span class="px-2 sm:px-3 py-0.5 sm:py-1 rounded-md sm:rounded-full text-[9px] sm:text-xs font-bold uppercase tracking-wider bg-slate-100 text-slate-800 border border-slate-200">
                                {{ $cat->badge }}
                            </span>
                            <span class="text-[9.5px] sm:text-xs font-mono font-bold text-slate-500">
                                {{ $cat->tools_count }} Alat
                            </span>
                        </div>

                        <div>
                            <h3 class="text-xs sm:text-xl font-bold text-slate-900 leading-snug">{{ $cat->name }}</h3>
                            <p class="text-[11px] sm:text-xs text-slate-600 mt-1 sm:mt-2 leading-relaxed line-clamp-2 sm:line-clamp-none">{{ $cat->description }}</p>
                        </div>
                    </div>

                    <div class="space-y-2 sm:space-y-3">
                        <!-- PJ Profile Box -->
                        <div class="bg-slate-50 p-2 sm:p-4 rounded-xl sm:rounded-2xl border border-slate-200 flex items-center gap-2 sm:gap-3.5">
                            <div class="w-8 h-8 sm:w-11 sm:h-11 rounded-lg sm:rounded-xl bg-blue-600 text-white flex items-center justify-center font-bold text-xs sm:text-sm shadow-2xs shrink-0">
                                PJ
                            </div>
                            <div class="min-w-0 flex-1">
                                <span class="text-[8.5px] sm:text-[10px] text-slate-400 uppercase font-bold block truncate">{{ $cat->pj_role_title }}</span>
                                <h4 class="text-xs sm:text-sm font-bold text-slate-900 truncate">{{ $cat->pj_name }}</h4>
                                <p class="text-[10px] text-slate-500 hidden sm:block truncate">Penanggung Jawab Kategori</p>
                            </div>
                        </div>

                        <!-- Quick Action Button -->
                        <a href="{{ route('catalog.index', ['category' => $cat->slug]) }}" class="w-full py-1.5 sm:py-2.5 px-2.5 sm:px-4 rounded-xl bg-blue-50 hover:bg-blue-600 text-blue-700 hover:text-white font-bold text-[10.5px] sm:text-xs flex items-center justify-center gap-1 transition-all text-center active:scale-95">
                            <span>Lihat Alat</span>
                            <span class="hidden sm:inline">di Kategori Ini</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    </div>

                </div>
            @endforeach
        </div>

        <!-- Operating Hours & Office Location Banner (Berjejer 3 Kolom di Mobile) -->
        <div class="bg-slate-900 rounded-2xl sm:rounded-3xl p-4 sm:p-7 text-white space-y-3 sm:space-y-4 shadow-md border border-slate-800">
            <div class="flex items-center justify-between">
                <h3 class="text-xs sm:text-lg font-bold">Jam Layanan &amp; Ruang Alat</h3>
                <span class="inline-flex items-center gap-1.5 text-[9.5px] sm:text-[11px] font-semibold px-2 py-0.5 rounded-full bg-emerald-950/70 text-emerald-300 border border-emerald-700/60">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                    Aktif
                </span>
            </div>
            
            <div class="grid grid-cols-3 gap-1.5 sm:gap-4 text-xs text-slate-300">
                <div class="bg-slate-800/90 p-2 sm:p-4 rounded-xl sm:rounded-2xl border border-slate-700 text-center sm:text-left">
                    <span class="text-slate-400 block text-[9px] sm:text-[11px]">Hari</span>
                    <strong class="text-white text-[11px] sm:text-sm block truncate">Senin - Jumat</strong>
                </div>
                <div class="bg-slate-800/90 p-2 sm:p-4 rounded-xl sm:rounded-2xl border border-slate-700 text-center sm:text-left">
                    <span class="text-slate-400 block text-[9px] sm:text-[11px]">Waktu</span>
                    <strong class="text-white text-[11px] sm:text-sm block truncate">07.00 - 15.30</strong>
                </div>
                <div class="bg-slate-800/90 p-2 sm:p-4 rounded-xl sm:rounded-2xl border border-slate-700 text-center sm:text-left">
                    <span class="text-slate-400 block text-[9px] sm:text-[11px]">Lokasi</span>
                    <strong class="text-white text-[11px] sm:text-sm block truncate">Ruang Alat</strong>
                </div>
            </div>
        </div>

    </div>
</section>
@endsection
