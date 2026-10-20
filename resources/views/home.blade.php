@extends('layouts.app')

@section('title', 'Beranda - Peminjaman Alat Praktik SMKN 5 Surakarta')

@section('content')
<!-- Hero Section -->
<section class="relative overflow-hidden pt-12 pb-20 lg:pt-20 lg:pb-28 bg-gradient-to-b from-blue-50/80 via-white to-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            
            <!-- Left Hero Info -->
            <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-blue-100/90 border border-blue-200 text-blue-800 text-xs font-bold tracking-wide">
                    <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                    SIPAS — Sistem Informasi Peminjaman Alat Sekolah SMK Negeri 5 Surakarta
                </div>

                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-slate-900 leading-[1.15]">
                    Peminjaman Alat Sekolah <br class="hidden sm:inline">
                    <span class="bg-gradient-to-r from-blue-700 via-indigo-600 to-teal-600 bg-clip-text text-transparent">Cepat, Tertib, & Terdata</span>
                </h1>

                <p class="text-base sm:text-lg text-slate-600 max-w-2xl mx-auto lg:mx-0 leading-relaxed">
                    Siswa dan Guru dapat meminjam alat praktik bengkel, lab RPL, multimedia, hingga perlengkapan olahraga. Lengkap dengan pilihan durasi peminjaman dan lampiran berkas proposal kegiatan.
                </p>

                <!-- Primary CTA Buttons -->
                <div class="flex flex-wrap items-center justify-center lg:justify-start gap-4 pt-2">
                    <a href="{{ route('catalog.index') }}" class="inline-flex items-center gap-2.5 px-7 py-4 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-sm shadow-xl shadow-blue-500/25 hover:shadow-2xl hover:-translate-y-0.5 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        Pinjam Alat Sekarang
                    </a>

                    <a href="{{ route('loans.index') }}" class="inline-flex items-center gap-2 px-6 py-4 rounded-2xl bg-white hover:bg-slate-50 text-slate-800 font-bold text-sm border border-slate-300 shadow-xs hover:border-slate-400 hover:-translate-y-0.5 transition-all">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        Lacak Status Tiket
                    </a>
                </div>

                <!-- Roles Overview Notice -->
                <div class="pt-2 text-xs text-slate-500 flex flex-wrap items-center justify-center lg:justify-start gap-4">
                    <span class="inline-flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        Siswa & Guru setara sebagai Peminjam
                    </span>
                    <span class="inline-flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        Dukungan Upload Proposal OSIS / Ekskul
                    </span>
                </div>
            </div>

            <!-- Right Hero Card -->
            <div class="lg:col-span-5">
                <div class="bg-slate-900 rounded-3xl p-6 sm:p-8 text-white border border-slate-800 shadow-2xl space-y-6">
                    <div class="flex items-center justify-between border-b border-slate-800 pb-4">
                        <div>
                            <span class="text-[10px] uppercase font-bold text-blue-400 tracking-wider">SIPAS • Ketersediaan Alat</span>
                            <h2 class="text-base font-bold text-white">Inventaris Terdata Real-Time</h2>
                        </div>
                        <span class="text-[11px] font-mono px-2 py-0.5 rounded bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">Aktif</span>
                    </div>

                    <!-- 4 Live Stats -->
                    <div class="grid grid-cols-2 gap-3.5">
                        <div class="bg-slate-800/80 p-4 rounded-2xl border border-slate-700/60">
                            <span class="text-2xl font-black text-blue-400">{{ $stats['total_units'] }}</span>
                            <p class="text-xs text-slate-400 mt-1">Total Unit Terdata</p>
                        </div>
                        <div class="bg-slate-800/80 p-4 rounded-2xl border border-slate-700/60">
                            <span class="text-2xl font-black text-emerald-400">{{ $stats['available_units'] }}</span>
                            <p class="text-xs text-slate-400 mt-1">Stok Ready di Ruang</p>
                        </div>
                        <div class="bg-slate-800/80 p-4 rounded-2xl border border-slate-700/60">
                            <span class="text-2xl font-black text-amber-400">{{ $stats['active_loans'] }}</span>
                            <p class="text-xs text-slate-400 mt-1">Peminjaman Aktif</p>
                        </div>
                        <div class="bg-slate-800/80 p-4 rounded-2xl border border-slate-700/60">
                            <span class="text-2xl font-black text-indigo-400">{{ $stats['completed_loans'] }}</span>
                            <p class="text-xs text-slate-400 mt-1">Pengembalian Tertib</p>
                        </div>
                    </div>

                    <!-- Quick Navigation Box -->
                    <div class="bg-slate-800/40 p-4 rounded-2xl border border-slate-700/50 flex items-center justify-between text-xs">
                        <div>
                            <h4 class="font-bold text-slate-200">Ingin Tahu Siapa Saja Petugas PJ Alat?</h4>
                            <p class="text-[11px] text-slate-400">PJ Bengkel, Lab RPL, Multimedia, Olahraga</p>
                        </div>
                        <a href="{{ route('about.pj') }}" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-[11px] transition-colors shrink-0">
                            Lihat Tim PJ Alat &rarr;
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- 4 Simple Steps Section -->
<section class="py-16 bg-white border-y border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-12 space-y-2">
            <span class="text-xs font-bold uppercase tracking-wider text-blue-600 block">Proses Peminjaman Sederhana</span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900">4 Tahap Peminjaman Alat Sekolah</h2>
            <p class="text-xs sm:text-sm text-slate-500">
                Pencatatan tertib dan transparan mulai dari pengajuan siswa/guru hingga verifikasi oleh Petugas Penanggung Jawab (PJ) Kategori Alat.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="p-6 rounded-3xl bg-slate-50 border border-slate-200 space-y-3 hover:border-blue-300 hover:shadow-md transition-all">
                <div class="w-10 h-10 rounded-2xl bg-blue-600 text-white flex items-center justify-center font-black text-sm">1</div>
                <h3 class="text-base font-bold text-slate-900">Pilih Alat di Katalog</h3>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Buka katalog alat, cari perkakas praktik yang dibutuhkan, dan pastikan stok unit mencukupi.
                </p>
            </div>

            <div class="p-6 rounded-3xl bg-slate-50 border border-slate-200 space-y-3 hover:border-indigo-300 hover:shadow-md transition-all">
                <div class="w-10 h-10 rounded-2xl bg-indigo-600 text-white flex items-center justify-center font-black text-sm">2</div>
                <h3 class="text-base font-bold text-slate-900">Isi Durasi & Lampiran</h3>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Tentukan berapa hari durasi pinjam dan lampirkan surat izin / proposal kegiatan bila dalam jumlah besar.
                </p>
            </div>

            <div class="p-6 rounded-3xl bg-slate-50 border border-slate-200 space-y-3 hover:border-emerald-300 hover:shadow-md transition-all">
                <div class="w-10 h-10 rounded-2xl bg-emerald-600 text-white flex items-center justify-center font-black text-sm">3</div>
                <h3 class="text-base font-bold text-slate-900">Verifikasi Admin PJ</h3>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Admin PJ kategori terkait menyetujui dan sistem secara otomatis meng-assign nomor unit fisik yang siap diambil.
                </p>
            </div>

            <div class="p-6 rounded-3xl bg-slate-50 border border-slate-200 space-y-3 hover:border-amber-300 hover:shadow-md transition-all">
                <div class="w-10 h-10 rounded-2xl bg-amber-600 text-white flex items-center justify-center font-black text-sm">4</div>
                <h3 class="text-base font-bold text-slate-900">Pengembalian & Cek</h3>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Kembalikan tepat waktu ke ruang alat praktik kejuruan. Petugas mencatat kondisi alat (Baik/Rusak/Hilang) dan menyelesaikan tiket.
                </p>
            </div>
        </div>

        <div class="mt-12 text-center">
            <a href="{{ route('catalog.index') }}" class="inline-flex items-center gap-2 px-6 py-3.5 bg-slate-900 hover:bg-blue-600 text-white rounded-2xl text-xs font-bold transition-all shadow-md">
                <span>Buka Katalog & Mulai Pinjam</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        </div>
    </div>
</section>
@endsection
