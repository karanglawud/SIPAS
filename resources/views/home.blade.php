@extends('layouts.app')

@section('title', 'Beranda - Peminjaman Alat Praktik SMKN 5 Surakarta')

@section('content')
<!-- Hero Section -->
<section class="relative overflow-hidden pt-6 pb-12 sm:pt-12 sm:pb-20 lg:pt-20 lg:pb-28 bg-white border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
            
            <!-- Left Hero Info -->
            <div class="lg:col-span-7 space-y-4 sm:space-y-6 text-center lg:text-left">
                <div class="inline-flex items-center gap-1.5 sm:gap-2 px-3 sm:px-3.5 py-1 sm:py-1.5 rounded-full bg-slate-100 border border-slate-200 text-slate-700 text-[11px] sm:text-xs font-bold tracking-wide max-w-full">
                    <span class="w-2 h-2 rounded-full bg-blue-600 shrink-0"></span>
                    <span class="truncate">SIPAS — Peminjaman Alat SMKN 5 Surakarta</span>
                </div>

                <h1 class="text-3xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-slate-900 leading-[1.18]">
                    Peminjaman Alat Sekolah <br class="hidden sm:inline">
                    <span class="text-blue-600">Cepat, Tertib, &amp; Terdata</span>
                </h1>

                <p class="text-xs sm:text-base lg:text-lg text-slate-600 max-w-2xl mx-auto lg:mx-0 leading-relaxed">
                    Siswa dan Guru dapat meminjam alat praktik bengkel, lab RPL, multimedia, hingga perlengkapan olahraga. Lengkap dengan pilihan durasi peminjaman dan lampiran berkas proposal kegiatan.
                </p>

                <!-- Primary CTA Buttons (Berjejer 2 kolom di mobile) -->
                <div class="grid grid-cols-2 gap-2 sm:flex sm:flex-wrap sm:items-center sm:justify-start sm:gap-4 pt-1 sm:pt-2">
                    <a href="{{ route('catalog.index') }}" class="inline-flex items-center justify-center gap-1.5 sm:gap-2.5 px-3 sm:px-7 py-3 sm:py-4 rounded-xl sm:rounded-2xl bg-blue-600 hover:bg-blue-800 text-white font-extrabold text-xs sm:text-sm shadow-xs hover:shadow-md hover:-translate-y-0.5 active:scale-95 transition-all text-center">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        <span>Pinjam Alat</span>
                    </a>

                    <a href="{{ route('loans.index') }}" class="inline-flex items-center justify-center gap-1.5 sm:gap-2 px-3 sm:px-6 py-3 sm:py-4 rounded-xl sm:rounded-2xl bg-white hover:bg-slate-50 text-slate-800 font-bold text-xs sm:text-sm border border-slate-300 shadow-xs hover:border-slate-400 hover:-translate-y-0.5 active:scale-95 transition-all text-center">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        <span>Lacak Status</span>
                    </a>
                </div>

                <!-- Roles Overview Notice -->
                <div class="pt-1 text-[11px] sm:text-xs text-slate-500 flex flex-wrap items-center justify-center lg:justify-start gap-2.5 sm:gap-4">
                    <span class="inline-flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        <span class="text-slate-600 font-medium">Siswa &amp; Guru setara</span>
                    </span>
                    <span class="inline-flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        <span class="text-slate-600 font-medium">Dukungan Proposal OSIS / Ekskul</span>
                    </span>
                </div>
            </div>

            <!-- Right Hero Card -->
            <div class="lg:col-span-5">
                <div class="bg-slate-900 rounded-2xl sm:rounded-3xl p-4 sm:p-7 text-white border border-slate-800 shadow-md space-y-3.5 sm:space-y-6">
                    <div class="flex items-center justify-between border-b border-slate-800 pb-3 sm:pb-4">
                        <div>
                            <span class="text-[9.5px] sm:text-[10px] uppercase font-bold text-slate-400 tracking-wider">SIPAS • Ketersediaan Alat</span>
                            <h2 class="text-sm sm:text-base font-bold text-white">Inventaris Terdata Real-Time</h2>
                        </div>
                        <span class="inline-flex items-center gap-1.5 text-[10px] sm:text-[11px] font-semibold px-2 sm:px-2.5 py-0.5 rounded-full bg-emerald-950/70 text-emerald-300 border border-emerald-700/60">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                            Aktif
                        </span>
                    </div>

                    <!-- 4 Live Stats (Sejajar 4 Kolom Compact) -->
                    <div class="grid grid-cols-4 gap-1.5 sm:gap-3">
                        <div class="bg-slate-800/80 p-2 sm:p-3.5 rounded-xl sm:rounded-2xl border border-slate-700/80 text-center flex flex-col justify-between">
                            <div>
                                <span class="text-base sm:text-2xl font-black text-white block leading-tight">{{ (int) $stats['total_units'] }}</span>
                                <span class="text-[9.5px] sm:text-xs font-bold text-slate-200 block leading-tight mt-0.5">Total Unit</span>
                            </div>
                            <p class="text-[8px] sm:text-[11px] text-slate-400 mt-1 leading-tight">Terdata</p>
                        </div>
                        <div class="bg-slate-800/80 p-2 sm:p-3.5 rounded-xl sm:rounded-2xl border border-slate-700/80 text-center flex flex-col justify-between">
                            <div>
                                <span class="text-base sm:text-2xl font-black text-emerald-400 block leading-tight">{{ (int) $stats['available_units'] }}</span>
                                <span class="text-[9.5px] sm:text-xs font-bold text-emerald-400 block leading-tight mt-0.5">Siap Pakai</span>
                            </div>
                            <p class="text-[8px] sm:text-[11px] text-slate-400 mt-1 leading-tight">Ready</p>
                        </div>
                        <div class="bg-slate-800/80 p-2 sm:p-3.5 rounded-xl sm:rounded-2xl border border-slate-700/80 text-center flex flex-col justify-between">
                            <div>
                                <span class="text-base sm:text-2xl font-black text-amber-400 block leading-tight">{{ (int) $stats['active_loans'] }}</span>
                                <span class="text-[9.5px] sm:text-xs font-bold text-amber-400 block leading-tight mt-0.5">Dipinjam</span>
                            </div>
                            <p class="text-[8px] sm:text-[11px] text-slate-400 mt-1 leading-tight">Aktif</p>
                        </div>
                        <div class="bg-slate-800/80 p-2 sm:p-3.5 rounded-xl sm:rounded-2xl border border-slate-700/80 text-center flex flex-col justify-between">
                            <div>
                                <span class="text-base sm:text-2xl font-black text-blue-300 block leading-tight">{{ (int) $stats['completed_loans'] }}</span>
                                <span class="text-[9.5px] sm:text-xs font-bold text-blue-300 block leading-tight mt-0.5">Kembali</span>
                            </div>
                            <p class="text-[8px] sm:text-[11px] text-slate-400 mt-1 leading-tight">Tertib</p>
                        </div>
                    </div>

                    <!-- Quick Navigation Box -->
                    <div class="bg-slate-800/50 p-2.5 sm:p-4 rounded-xl sm:rounded-2xl border border-slate-700/80 flex items-center justify-between gap-2 text-xs">
                        <div class="min-w-0">
                            <h4 class="font-bold text-slate-200 text-[11px] sm:text-xs leading-tight truncate sm:whitespace-normal">Ingin Tahu Petugas PJ Alat?</h4>
                            <p class="text-[9.5px] sm:text-[11px] text-slate-400 truncate">PJ Bengkel, Lab RPL, Multimedia, Olahraga</p>
                        </div>
                        <a href="{{ route('about.pj') }}" class="px-2.5 sm:px-3.5 py-1.5 sm:py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-[10px] sm:text-[11px] transition-colors shrink-0 shadow-xs whitespace-nowrap">
                            Lihat Tim PJ &rarr;
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- 4 Simple Steps Section (2x2 di Mobile) -->
<section class="py-10 sm:py-16 bg-white border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-8 sm:mb-12 space-y-1.5 sm:space-y-2">
            <span class="text-[11px] sm:text-xs font-bold uppercase tracking-wider text-blue-600 block">Proses Peminjaman Sederhana</span>
            <h2 class="text-xl sm:text-3xl font-extrabold text-slate-900">4 Tahap Peminjaman Alat Sekolah</h2>
            <p class="text-xs sm:text-sm text-slate-500">
                Pencatatan tertib dan transparan mulai dari pengajuan siswa/guru hingga verifikasi oleh Petugas Penanggung Jawab (PJ) Kategori Alat.
            </p>
        </div>

        <!-- 2x2 Grid on Mobile, 4 Columns on Desktop -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-2.5 sm:gap-6">
            <div class="p-3.5 sm:p-6 rounded-2xl sm:rounded-3xl bg-slate-50 border border-slate-200 flex flex-col justify-between space-y-2 sm:space-y-3 hover:border-slate-400 transition-all">
                <div>
                    <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl sm:rounded-2xl bg-blue-600 text-white flex items-center justify-center font-black text-xs sm:text-sm shadow-2xs mb-2">1</div>
                    <h3 class="text-xs sm:text-base font-bold text-slate-900 leading-tight">Pilih Alat di Katalog</h3>
                </div>
                <p class="text-[11px] sm:text-xs text-slate-600 leading-relaxed">
                    Buka katalog alat, cari perkakas praktik yang dibutuhkan, dan pastikan stok mencukupi.
                </p>
            </div>

            <div class="p-3.5 sm:p-6 rounded-2xl sm:rounded-3xl bg-slate-50 border border-slate-200 flex flex-col justify-between space-y-2 sm:space-y-3 hover:border-slate-400 transition-all">
                <div>
                    <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl sm:rounded-2xl bg-blue-600 text-white flex items-center justify-center font-black text-xs sm:text-sm shadow-2xs mb-2">2</div>
                    <h3 class="text-xs sm:text-base font-bold text-slate-900 leading-tight">Isi Durasi &amp; Berkas</h3>
                </div>
                <p class="text-[11px] sm:text-xs text-slate-600 leading-relaxed">
                    Tentukan berapa hari durasi pinjam dan lampirkan surat izin kegiatan bila diperlukan.
                </p>
            </div>

            <div class="p-3.5 sm:p-6 rounded-2xl sm:rounded-3xl bg-slate-50 border border-slate-200 flex flex-col justify-between space-y-2 sm:space-y-3 hover:border-slate-400 transition-all">
                <div>
                    <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl sm:rounded-2xl bg-blue-600 text-white flex items-center justify-center font-black text-xs sm:text-sm shadow-2xs mb-2">3</div>
                    <h3 class="text-xs sm:text-base font-bold text-slate-900 leading-tight">Verifikasi Admin PJ</h3>
                </div>
                <p class="text-[11px] sm:text-xs text-slate-600 leading-relaxed">
                    Admin PJ menyetujui dan sistem menetapkan nomor unit fisik spesifik yang siap diambil.
                </p>
            </div>

            <div class="p-3.5 sm:p-6 rounded-2xl sm:rounded-3xl bg-slate-50 border border-slate-200 flex flex-col justify-between space-y-2 sm:space-y-3 hover:border-slate-400 transition-all">
                <div>
                    <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl sm:rounded-2xl bg-blue-600 text-white flex items-center justify-center font-black text-xs sm:text-sm shadow-2xs mb-2">4</div>
                    <h3 class="text-xs sm:text-base font-bold text-slate-900 leading-tight">Pengembalian &amp; Cek</h3>
                </div>
                <p class="text-[11px] sm:text-xs text-slate-600 leading-relaxed">
                    Kembalikan tepat waktu ke ruang alat. Petugas mencatat kondisi fisik alat yang dikembalikan.
                </p>
            </div>
        </div>

        <div class="mt-8 sm:mt-12 text-center">
            <a href="{{ route('catalog.index') }}" class="inline-flex items-center gap-2 px-5 sm:px-6 py-2.5 sm:py-3.5 bg-blue-600 hover:bg-blue-800 text-white rounded-xl sm:rounded-2xl text-xs font-bold transition-all shadow-xs hover:shadow-md active:scale-95">
                <span>Buka Katalog &amp; Mulai Pinjam</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        </div>
    </div>
</section>
@endsection
