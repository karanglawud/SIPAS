@extends('layouts.app')

@section('title', 'Katalog & Pencarian Alat Praktik')

@section('content')
<section class="py-10 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Page Title & Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <nav class="flex items-center gap-2 text-xs text-slate-400 mb-2">
                    <a href="{{ route('home') }}" class="hover:text-blue-600">Beranda</a>
                    <span>/</span>
                    <span class="text-slate-700 font-semibold">Katalog Alat</span>
                </nav>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Katalog & Pencarian Alat Praktik</h1>
                <p class="text-slate-500 text-xs sm:text-sm mt-1">
                    Pilih alat kejuruan yang ingin Anda pinjam. Klik tombol <strong>"Pinjam Alat Ini"</strong> untuk mengisi durasi dan lampiran permohonan.
                </p>
            </div>
            
            <a href="{{ route('loans.create') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-500/20 transition-all shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Buat Formulir Pinjam
            </a>
        </div>

        <!-- Filter & Search Controls Bar -->
        <div class="bg-white rounded-3xl border border-slate-200 p-4 sm:p-6 shadow-xs space-y-4">
            <form action="{{ route('catalog.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center">
                
                <!-- Search Input -->
                <div class="md:col-span-8 relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input type="text" name="q" id="catalogSearchInput" value="{{ request('q') }}" placeholder="Cari nama alat, kode unit, atau spesifikasi (contoh: Las, Oscilloscope, Sony, Basket)..." 
                        class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all">
                </div>

                <!-- Submit / Clear Buttons -->
                <div class="md:col-span-4 flex items-center gap-2">
                    <button type="submit" class="flex-1 py-3 px-4 bg-slate-900 hover:bg-blue-600 text-white rounded-2xl text-xs font-bold transition-colors">
                        Cari Alat
                    </button>
                    @if(request('q') || request('category'))
                        <a href="{{ route('catalog.index') }}" class="py-3 px-4 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-2xl text-xs font-bold transition-colors">
                            Reset
                        </a>
                    @endif
                </div>

                <!-- Hidden category if set via query -->
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
            </form>

            <!-- Category Filter Pills -->
            <div class="flex flex-wrap items-center gap-2 pt-3 border-t border-slate-100">
                <span class="text-xs font-bold text-slate-400 mr-2">Kategori:</span>
                <a href="{{ route('catalog.index', ['q' => request('q')]) }}" 
                   class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all {{ !request('category') || request('category') === 'all' ? 'bg-blue-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Semua ({{ \App\Models\Tool::count() }})
                </a>
                @foreach($categories as $cat)
                    <a href="{{ route('catalog.index', ['category' => $cat->slug, 'q' => request('q')]) }}" 
                       class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all {{ request('category') === $cat->slug ? 'bg-blue-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                        {{ $cat->name }}
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Tool Cards Grid -->
        @if($tools->isEmpty())
            <div class="text-center py-16 bg-white rounded-3xl border border-dashed border-slate-300 p-8">
                <div class="w-16 h-16 bg-slate-100 text-slate-400 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="text-base font-bold text-slate-800">Alat Tidak Ditemukan</h3>
                <p class="text-xs text-slate-500 max-w-sm mx-auto mt-1 mb-4">Tidak ada alat yang sesuai dengan kriteria pencarian Anda saat ini.</p>
                <a href="{{ route('catalog.index') }}" class="px-4 py-2 bg-blue-600 text-white rounded-xl text-xs font-bold hover:bg-blue-700 transition-colors inline-block">
                    Lihat Semua Alat
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($tools as $tool)
                    <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-xs hover:shadow-xl hover:border-blue-400 transition-all flex flex-col justify-between group">
                        
                        <!-- Tool Card Banner -->
                        <div class="h-32 bg-gradient-to-tr {{ $tool->image_gradient }} p-4 relative flex flex-col justify-between text-white">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-mono font-bold px-2 py-0.5 rounded-full bg-black/35 backdrop-blur-xs border border-white/20">
                                    {{ $tool->code }}
                                </span>
                                <span class="text-[10px] font-extrabold px-2 py-0.5 rounded-full {{ $tool->available_stock > 0 ? 'bg-emerald-500/90 text-white' : 'bg-rose-500 text-white' }}">
                                    {{ $tool->available_stock > 0 ? 'Tersedia' : 'Stok Habis' }}
                                </span>
                            </div>
                            <div>
                                <span class="text-[11px] font-semibold text-white/90 drop-shadow-xs">{{ $tool->category->name }}</span>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="p-5 flex-grow flex flex-col justify-between space-y-4">
                            <div class="space-y-1.5">
                                <h3 class="font-bold text-slate-900 group-hover:text-blue-600 transition-colors leading-snug line-clamp-2">
                                    {{ $tool->name }}
                                </h3>
                                <p class="text-xs text-slate-500 line-clamp-2">
                                    {{ $tool->spec }}
                                </p>
                            </div>

                            <!-- Stock Meter & Location -->
                            <div class="space-y-2 pt-2 border-t border-slate-100">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-slate-500 font-medium">Stok Ready:</span>
                                    <span class="font-bold font-mono text-slate-800">{{ $tool->available_stock }} / {{ $tool->total_stock }} Unit</span>
                                </div>
                                <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                                    @php
                                        $percent = $tool->total_stock > 0 ? round(($tool->available_stock / $tool->total_stock) * 100) : 0;
                                    @endphp
                                    <div class="h-full {{ $percent > 30 ? 'bg-blue-600' : 'bg-amber-500' }} rounded-full" style="width: {{ $percent }}%"></div>
                                </div>
                                <div class="flex items-center justify-between text-[11px] text-slate-400">
                                    <span class="truncate">PJ: {{ $tool->category->pj_name }}</span>
                                    <span class="font-semibold text-rose-600 whitespace-nowrap">Denda Rp {{ number_format($tool->fine_per_day, 0, ',', '.') }}/hari</span>
                                </div>
                            </div>

                            <!-- Action Button -->
                            <div class="pt-2">
                                @if($tool->available_stock > 0)
                                    <a href="{{ route('loans.create', $tool->id) }}" class="w-full py-2.5 px-4 bg-slate-900 hover:bg-blue-600 text-white rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-2 group-hover:shadow-md">
                                        <span>Pinjam Alat Ini</span>
                                        <svg class="w-3.5 h-3.5 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                    </a>
                                @else
                                    <button type="button" disabled class="w-full py-2.5 px-4 bg-slate-200 text-slate-400 rounded-xl text-xs font-bold cursor-not-allowed">
                                        Stok Sedang Dipinjam
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
