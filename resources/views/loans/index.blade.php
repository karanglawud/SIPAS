@extends('layouts.app')

@section('title', 'Riwayat & Status Tiket Peminjaman')

@section('content')
<section class="py-10 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
            <div>
                <nav class="flex items-center gap-2 text-xs text-slate-400 mb-2">
                    <a href="{{ route('home') }}" class="hover:text-blue-600">Beranda</a>
                    <span>/</span>
                    <span class="text-slate-700 font-semibold">Riwayat Peminjaman</span>
                </nav>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Riwayat Peminjaman Saya</h1>
                <p class="text-slate-500 text-xs sm:text-sm mt-1">
                    Pantau status verifikasi Admin PJ, kode unit fisik yang di-assign, dan cetak bukti peminjaman digital.
                </p>
            </div>

            <a href="{{ route('catalog.index') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-500/20 transition-all shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Ajukan Pinjam Baru
            </a>
        </div>

        @if($loans->isEmpty())
            <div class="bg-white rounded-3xl border border-slate-200 p-12 text-center space-y-4">
                <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mx-auto">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800">Belum Ada Riwayat Peminjaman</h3>
                <p class="text-xs text-slate-500 max-w-sm mx-auto">
                    Anda belum pernah mengajukan peminjaman alat kejuruan. Buka katalog untuk memilih alat praktik yang dibutuhkan.
                </p>
                <div class="pt-2">
                    <a href="{{ route('catalog.index') }}" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition-colors inline-block shadow-sm">
                        Eksplor Katalog Alat
                    </a>
                </div>
            </div>
        @else
            <!-- List of Loans -->
            <div class="space-y-4">
                @foreach($loans as $loan)
                    <div class="bg-white rounded-3xl border border-slate-200 p-5 sm:p-6 shadow-xs hover:border-blue-300 transition-all space-y-4">
                        
                        <!-- Top Bar: Ticket Number & Status Badge -->
                        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 pb-3">
                            <div class="flex items-center gap-3">
                                <span class="text-sm font-mono font-black text-blue-700 bg-blue-50 px-3 py-1 rounded-xl border border-blue-200">
                                    {{ $loan->ticket_number }}
                                </span>
                                <span class="text-xs text-slate-400">Diajukan: {{ $loan->created_at->translatedFormat('d M Y, H:i') }}</span>
                            </div>

                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold border {{ $loan->status_badge }}">
                                <span class="w-2 h-2 rounded-full {{ $loan->status === 'disetujui' ? 'bg-emerald-500 animate-pulse' : ($loan->status === 'menunggu' ? 'bg-amber-500' : 'bg-slate-500') }}"></span>
                                {{ $loan->status_label }}
                            </span>
                        </div>

                        <!-- Loan Details Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
                            
                            <!-- Left: Tool & Purpose -->
                            <div class="md:col-span-6 space-y-1.5">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                    {{ $loan->tool->category->name }} • PJ: {{ $loan->tool->category->pj_name }}
                                </span>
                                <h3 class="text-base font-bold text-slate-900">{{ $loan->tool->name }}</h3>
                                <p class="text-xs text-slate-600 italic">"{{ $loan->purpose }}"</p>
                            </div>

                            <!-- Middle: Quantity & Dates -->
                            <div class="md:col-span-3 text-xs space-y-1 bg-slate-50 p-3 rounded-2xl border border-slate-200">
                                <div>
                                    <span class="text-slate-400 text-[10px] block">Jumlah Unit:</span>
                                    <span class="font-bold text-slate-800">{{ $loan->quantity }} Unit</span>
                                </div>
                                <div>
                                    <span class="text-slate-400 text-[10px] block">Durasi (Hari):</span>
                                    <span class="font-bold text-blue-600">{{ $loan->duration_days }} Hari</span>
                                    <span class="text-slate-500 text-[11px]">({{ $loan->start_date->format('d/m/Y') }} s/d {{ $loan->end_date->format('d/m/Y') }})</span>
                                </div>
                            </div>

                            <!-- Right: Assigned Units or Action -->
                            <div class="md:col-span-3 space-y-2 text-right md:text-left">
                                @if(!empty($loan->assigned_units))
                                    <div>
                                        <span class="text-[10px] font-bold text-slate-400 block uppercase">Unit Fisik Ter-assign:</span>
                                        <div class="flex flex-wrap gap-1 mt-1">
                                            @foreach($loan->assigned_units as $unitCode)
                                                <span class="px-2 py-0.5 rounded-lg bg-emerald-50 text-emerald-800 border border-emerald-300 font-mono text-[11px] font-bold">
                                                    {{ $unitCode }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                @else
                                    <span class="text-xs text-amber-700 bg-amber-50 px-2.5 py-1 rounded-xl border border-amber-200 block text-center">
                                        Menunggu assignment unit oleh PJ
                                    </span>
                                @endif

                                <!-- Proposal File Indicator -->
                                @if($loan->proposal_path)
                                    <div class="pt-1">
                                        <a href="{{ asset('storage/' . $loan->proposal_path) }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs text-blue-600 hover:text-blue-800 font-semibold underline">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                            Berkas Proposal Terlampir
                                        </a>
                                    </div>
                                @endif
                            </div>

                        </div>

                        <!-- PJ Notes & Print Action Bar -->
                        <div class="pt-3 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                            <div class="text-slate-500">
                                @if($loan->pj_notes)
                                    <span class="font-bold text-slate-700">Catatan PJ:</span> {{ $loan->pj_notes }}
                                @else
                                    <span class="text-slate-400">Pengajuan menunggu konfirmasi dari Petugas Penanggung Jawab (PJ) Alat.</span>
                                @endif
                            </div>

                            <div class="flex items-center gap-2 shrink-0">
                                @if(in_array($loan->status, ['disetujui', 'dipinjam', 'selesai']))
                                    <a href="{{ route('loans.print', $loan->id) }}" target="_blank" class="px-4 py-2 bg-slate-900 hover:bg-blue-600 text-white rounded-xl text-xs font-bold transition-colors inline-flex items-center gap-1.5 shadow-xs">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                        Cetak Bukti Pinjam
                                    </a>
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
