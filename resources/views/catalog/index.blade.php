@extends('layouts.app')

@section('title', 'Katalog & Pencarian Alat Praktik')

@section('content')
<section class="py-6 sm:py-10 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 space-y-4 sm:space-y-8">
        
        <!-- Page Title & Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-3 sm:gap-4">
            <div>
                <nav class="flex items-center gap-1.5 sm:gap-2 text-[11px] sm:text-xs text-slate-400 mb-1.5">
                    <a href="{{ route('home') }}" class="hover:text-blue-900">Beranda</a>
                    <span>/</span>
                    <span class="text-slate-700 font-semibold">Katalog Alat</span>
                </nav>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Katalog &amp; Pencarian Alat Praktik</h1>
                <p class="text-slate-500 text-xs sm:text-sm mt-1">
                    Pilih alat kejuruan yang ingin Anda pinjam. Klik tombol <strong>"Pinjam Alat"</strong> pada alat pilihan Anda untuk membuka formulir peminjaman.
                </p>
            </div>
        </div>

        <!-- Filter & Search Controls Bar -->
        <div class="bg-white rounded-2xl sm:rounded-3xl border border-slate-200 p-3.5 sm:p-6 shadow-2xs space-y-3 sm:space-y-4">
            <form action="{{ route('catalog.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-2.5 sm:gap-3 items-center">
                
                <!-- Search Input -->
                <div class="md:col-span-8 relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 sm:pl-4 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input type="text" name="q" id="catalogSearchInput" value="{{ request('q') }}" placeholder="Cari nama alat, kode, atau spesifikasi (Las, Kamera, Multimeter)..." 
                        class="w-full pl-10 sm:pl-11 pr-3 sm:pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition-all">
                </div>

                <!-- Submit / Clear Buttons -->
                <div class="md:col-span-4 flex items-center gap-2">
                    <button type="submit" class="flex-1 py-2.5 px-4 bg-blue-600 hover:bg-blue-800 text-white rounded-2xl text-xs font-bold transition-all shadow-xs hover:shadow-md">
                        Cari Alat
                    </button>
                    @if(request('q') || request('category'))
                        <a href="{{ route('catalog.index') }}" class="py-2.5 px-4 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-2xl text-xs font-bold transition-colors">
                            Reset
                        </a>
                    @endif
                </div>

                <!-- Hidden category if set via query -->
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
            </form>

            <!-- Category Filter Pills (Horizontal scroll on mobile) -->
            <div class="flex items-center gap-1.5 sm:gap-2 pt-2.5 sm:pt-3 border-t border-slate-100 overflow-x-auto pb-1 sm:pb-0 no-scrollbar sm:flex-wrap">
                <span class="text-xs font-bold text-slate-400 mr-1 shrink-0">Kategori:</span>
                <a href="{{ route('catalog.index', ['q' => request('q')]) }}" 
                   class="px-3 sm:px-3.5 py-1.5 rounded-xl text-xs font-bold shrink-0 transition-all {{ !request('category') || request('category') === 'all' ? 'bg-blue-600 text-white shadow-2xs' : 'bg-white border border-slate-200 text-slate-700 hover:bg-blue-50 hover:text-blue-600 hover:border-blue-200' }}">
                    Semua ({{ \App\Models\Tool::count() }})
                </a>
                @foreach($categories as $cat)
                    <a href="{{ route('catalog.index', ['category' => $cat->slug, 'q' => request('q')]) }}" 
                       class="px-3 sm:px-3.5 py-1.5 rounded-xl text-xs font-bold shrink-0 transition-all {{ request('category') === $cat->slug ? 'bg-blue-600 text-white shadow-2xs' : 'bg-white border border-slate-200 text-slate-700 hover:bg-blue-50 hover:text-blue-600 hover:border-blue-200' }}">
                        {{ $cat->name }}
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Tool Cards Grid (2 items per row on mobile) -->
        @if($tools->isEmpty())
            <div class="text-center py-16 bg-white rounded-2xl sm:rounded-3xl border border-dashed border-slate-300 p-6 sm:p-8">
                <div class="w-14 h-14 sm:w-16 sm:h-16 bg-slate-100 text-slate-400 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-7 h-7 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="text-sm sm:text-base font-bold text-slate-800">Alat Tidak Ditemukan</h3>
                <p class="text-xs text-slate-500 max-w-sm mx-auto mt-1 mb-4">Tidak ada alat yang sesuai dengan kriteria pencarian Anda saat ini.</p>
                <a href="{{ route('catalog.index') }}" class="px-4 py-2 bg-blue-600 text-white rounded-xl text-xs font-bold hover:bg-blue-800 transition-colors inline-block shadow-xs">
                    Lihat Semua Alat
                </a>
            </div>
        @else
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2.5 sm:gap-6">
                @foreach($tools as $tool)
                    <div class="bg-white rounded-2xl sm:rounded-3xl border border-slate-200 overflow-hidden shadow-2xs hover:shadow-lg hover:border-slate-300 transition-all flex flex-col justify-between group">
                        
                        <!-- Tool Card Banner / Photo -->
                        @if($tool->image)
                            <div class="aspect-square relative overflow-hidden bg-slate-100">
                                <img src="{{ asset('storage/' . $tool->image) }}" alt="{{ $tool->name }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                <div class="absolute top-2 left-2 right-2 sm:top-3 sm:left-3 sm:right-3 flex items-center justify-between gap-1">
                                    <span class="text-[9px] sm:text-[10px] font-mono font-bold px-1.5 sm:px-2 py-0.5 rounded bg-white/95 text-slate-700 shadow-2xs truncate max-w-[55%]">
                                        {{ $tool->code }}
                                    </span>
                                    <span class="text-[9px] sm:text-[10px] font-bold px-1.5 sm:px-2 py-0.5 rounded shadow-2xs {{ $tool->available_stock > 0 ? 'bg-emerald-600 text-white' : 'bg-rose-600 text-white' }}">
                                        {{ $tool->available_stock > 0 ? 'Tersedia' : 'Habis' }}
                                    </span>
                                </div>
                            </div>
                        @else
                            <div class="h-24 sm:h-32 bg-slate-900 p-2.5 sm:p-4 relative flex flex-col justify-between text-white border-b border-slate-800 overflow-hidden">
                                <div class="absolute -right-3 -bottom-3 sm:-right-4 sm:-bottom-4 opacity-[0.07]">
                                    @switch($tool->category->icon ?? 'wrench')
                                        @case('wrench')
                                            <svg class="w-20 h-20 sm:w-28 sm:h-28" fill="currentColor" viewBox="0 0 24 24"><path d="M22.7 19l-9.1-9.1c.9-2.3.4-5-1.5-6.9-2-2-5-2.4-7.4-1.3L9 6 6 9 1.6 4.7C.4 7.1.9 10.1 2.9 12.1c1.9 1.9 4.6 2.4 6.9 1.5l9.1 9.1c.4.4 1 .4 1.4 0l2.3-2.3c.5-.4.5-1.1.1-1.4z"/></svg>
                                        @break
                                        @case('cpu')
                                            <svg class="w-20 h-20 sm:w-28 sm:h-28" fill="currentColor" viewBox="0 0 24 24"><path d="M9 2v2H7v2H5v2H3v2h2v2h2v2h2v2h2v2h2v-2h2v-2h2v-2h2v-2h-2V8h-2V6h-2V4h-2V2H9zm0 4h6v6H9V6z"/></svg>
                                        @break
                                        @case('camera')
                                            <svg class="w-20 h-20 sm:w-28 sm:h-28" fill="currentColor" viewBox="0 0 24 24"><path d="M12 15.2A3.2 3.2 0 1 0 12 8.8a3.2 3.2 0 0 0 0 6.4z"/><path d="M9 2L7.17 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2h-3.17L15 2H9zm3 15c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5z"/></svg>
                                        @break
                                        @default
                                            <svg class="w-20 h-20 sm:w-28 sm:h-28" fill="currentColor" viewBox="0 0 24 24"><path d="M19 5h-2V3H7v2H5c-1.1 0-2 .9-2 2v1c0 2.55 1.92 4.63 4.39 4.94A5.01 5.01 0 0 0 11 15.9V19H7v2h10v-2h-4v-3.1a5.01 5.01 0 0 0 3.61-2.96C19.08 12.63 21 10.55 21 8V7c0-1.1-.9-2-2-2zM5 8V7h2v3.82C5.84 10.4 5 9.3 5 8zm14 0c0 1.3-.84 2.4-2 2.82V7h2v1z"/></svg>
                                    @endswitch
                                </div>
                                <div class="flex items-center justify-between relative gap-1">
                                    <span class="text-[9px] sm:text-[10px] font-mono font-bold px-1.5 sm:px-2 py-0.5 rounded bg-slate-800 border border-slate-700 text-slate-200 truncate max-w-[55%]">
                                        {{ $tool->code }}
                                    </span>
                                    <span class="text-[9px] sm:text-[10px] font-bold px-1.5 sm:px-2 py-0.5 rounded {{ $tool->available_stock > 0 ? 'bg-emerald-950/80 text-emerald-300 border border-emerald-700/60' : 'bg-rose-950/80 text-rose-300 border border-rose-800' }}">
                                        {{ $tool->available_stock > 0 ? 'Tersedia' : 'Habis' }}
                                    </span>
                                </div>
                                <div class="relative">
                                    <span class="text-[10px] sm:text-[11px] font-semibold text-slate-400 truncate block">{{ $tool->category->name }}</span>
                                </div>
                            </div>
                        @endif

                        <!-- Card Body -->
                        <div class="p-2.5 sm:p-5 flex-grow flex flex-col justify-between gap-2.5 sm:gap-4">
                            <div class="space-y-1">
                                <h3 class="font-bold text-xs sm:text-base text-slate-900 group-hover:text-blue-600 transition-colors leading-snug line-clamp-2">
                                    {{ $tool->name }}
                                </h3>
                                <p class="text-[10px] sm:text-xs text-slate-500 line-clamp-1 sm:line-clamp-2">
                                    {{ $tool->spec }}
                                </p>
                            </div>

                            <!-- Stock Meter & Location -->
                            <div class="space-y-1.5 pt-2 border-t border-slate-100">
                                <div class="flex items-center justify-between text-[10px] sm:text-xs">
                                    <span class="text-slate-500 font-medium">Stok:</span>
                                    <span class="font-bold font-mono text-slate-800">{{ $tool->available_stock }} <span class="hidden sm:inline">/ {{ $tool->total_stock }}</span> Unit</span>
                                </div>
                                <div class="w-full bg-slate-100 h-1.5 sm:h-2 rounded-full overflow-hidden">
                                    @php
                                        $percent = $tool->total_stock > 0 ? round(($tool->available_stock / $tool->total_stock) * 100) : 0;
                                    @endphp
                                    <div class="h-full {{ $percent > 30 ? 'bg-emerald-600' : 'bg-amber-500' }} rounded-full" style="width: {{ $percent }}%"></div>
                                </div>
                                <div class="flex items-center justify-between text-[9px] sm:text-[11px] text-slate-400 gap-1">
                                    <span class="truncate">PJ: {{ $tool->category->pj_name }}</span>
                                    <span class="font-semibold text-rose-600 whitespace-nowrap">Rp {{ number_format($tool->fine_per_day, 0, ',', '.') }}/hr</span>
                                </div>
                            </div>

                            <!-- Action Button -->
                            <div class="pt-1">
                                @if($tool->available_stock > 0)
                                    <a href="{{ route('loans.create', $tool->id) }}" 
                                       class="w-full py-2 sm:py-2.5 px-2 sm:px-4 bg-blue-600 hover:bg-blue-800 text-white rounded-xl text-[11px] sm:text-xs font-bold transition-all flex items-center justify-center gap-1.5 shadow-2xs hover:shadow-md active:scale-95">
                                        <span>Pinjam Alat</span>
                                        <svg class="w-3.5 h-3.5 hidden sm:inline group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                    </a>
                                @else
                                    <button type="button" disabled class="w-full py-2 sm:py-2.5 px-2 sm:px-4 bg-slate-100 text-slate-400 rounded-xl text-[11px] sm:text-xs font-semibold cursor-not-allowed">
                                        Stok Habis
                                    </button>
                                @endif
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
        @endif

    </div>
</section>
@endsection
