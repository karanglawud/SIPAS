@extends('layouts.app')

@section('title', 'Penanggung Jawab (PJ) Kategori Alat - SIPAS SMKN 5')

@section('content')
<section class="py-10 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Header -->
        <div>
            <nav class="flex items-center gap-2 text-xs text-slate-400 mb-2">
                <a href="{{ route('home') }}" class="hover:text-blue-600">Beranda</a>
                <span>/</span>
                <span class="text-slate-700 font-semibold">Tim PJ Alat</span>
            </nav>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Struktur Penanggung Jawab (PJ) Kategori Alat</h1>
            <p class="text-slate-500 text-xs sm:text-sm mt-1">
                SMK Negeri 5 Surakarta membagi tanggung jawab pengelolaan alat kejuruan per kategori untuk memastikan kondisi, ketersediaan unit, dan akurasi verifikasi peminjaman alat praktik.
            </p>
        </div>

        <!-- 4 Categories Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($categories as $cat)
                <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-xs hover:border-blue-300 hover:shadow-md transition-all space-y-5">
                    
                    <div class="flex items-center justify-between">
                        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-blue-100 text-blue-800">
                            {{ $cat->badge }}
                        </span>
                        <span class="text-xs font-mono font-bold text-slate-500">
                            {{ $cat->tools_count }} Jenis Alat Terdaftar
                        </span>
                    </div>

                    <div>
                        <h3 class="text-xl font-bold text-slate-900">{{ $cat->name }}</h3>
                        <p class="text-xs text-slate-600 mt-2 leading-relaxed">{{ $cat->description }}</p>
                    </div>

                    <!-- PJ Profile Box -->
                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-blue-600 text-white flex items-center justify-center font-bold text-base shadow-xs">
                            PJ
                        </div>
                        <div>
                            <span class="text-[10px] text-slate-400 uppercase font-bold block">{{ $cat->pj_role_title }}</span>
                            <h4 class="text-sm font-bold text-slate-900">{{ $cat->pj_name }}</h4>
                            <p class="text-[11px] text-slate-500">Penanggung Jawab Kategori Alat SMKN 5 Surakarta</p>
                        </div>
                    </div>

                    <!-- Quick Action -->
                    <div class="pt-2 flex items-center justify-between text-xs">
                        <span class="text-slate-400">Peminjaman harus disetujui PJ ini.</span>
                        <a href="{{ route('catalog.index', ['category' => $cat->slug]) }}" class="text-blue-600 hover:text-blue-700 font-bold flex items-center gap-1">
                            <span>Lihat Alat di Kategori Ini</span>
                            &rarr;
                        </a>
                    </div>

                </div>
            @endforeach
        </div>

        <!-- Operating Hours & Office Location Banner -->
        <div class="bg-slate-900 rounded-3xl p-6 sm:p-8 text-white space-y-4 shadow-xl">
            <h3 class="text-lg font-bold">Jam Layanan & Pengambilan Alat Sekolah</h3>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs text-slate-300">
                <div class="bg-slate-800 p-4 rounded-2xl border border-slate-700">
                    <span class="text-slate-400 block text-[11px]">Hari Operasional:</span>
                    <strong class="text-white text-sm">Senin s/d Jumat</strong>
                </div>
                <div class="bg-slate-800 p-4 rounded-2xl border border-slate-700">
                    <span class="text-slate-400 block text-[11px]">Waktu Layanan:</span>
                    <strong class="text-white text-sm">07.00 - 15.30 WIB</strong>
                </div>
                <div class="bg-slate-800 p-4 rounded-2xl border border-slate-700">
                    <span class="text-slate-400 block text-[11px]">Lokasi Ruang / Lab Praktik:</span>
                    <strong class="text-white text-sm">Ruang Alat & Lab Praktik Terkait</strong>
                </div>
            </div>
        </div>

    </div>
</section>
@endsection
