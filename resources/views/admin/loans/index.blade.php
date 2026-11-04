@extends('layouts.app')

@section('title', 'Panel Verifikasi Peminjaman Alat - SIPAS')

@section('content')
<section class="py-10 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Header with Admin Scope -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <nav class="flex items-center gap-2 text-xs text-slate-400 mb-2">
                    <a href="{{ route('home') }}" class="hover:text-blue-600">Beranda</a>
                    <span>/</span>
                    <span class="text-slate-700 font-semibold">Panel Admin PJ Alat</span>
                </nav>
                <div class="flex items-center gap-3">
                    <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Panel Verifikasi Peminjaman Alat</h1>
                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-amber-100 text-amber-800 border border-amber-300">
                        Admin: {{ $admin->class_or_dept ?? $admin->name }}
                    </span>
                </div>
                <p class="text-slate-500 text-xs sm:text-sm mt-1">
                    Verifikasi pengajuan siswa/guru, alokasikan unit fisik spesifik, kirim pesan peringatan, dan catat kondisi pengembalian alat.
                </p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('admin.tools.index') }}" class="px-4 py-2.5 bg-white hover:bg-slate-100 text-slate-700 border border-slate-200 rounded-2xl text-xs font-bold shadow-xs transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    <span>Kelola Inventaris Alat</span>
                </a>
                <a href="{{ route('admin.messages.index') }}" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-800 text-white rounded-2xl text-xs font-bold shadow-xs hover:shadow-md transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                    <span>Pusat Pesan</span>
                </a>
            </div>
        </div>

        <!-- Filter Status Tabs & Search -->
        <div class="bg-white rounded-3xl border border-slate-200 p-4 sm:p-5 shadow-xs space-y-4">
            
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
                <!-- Status Tabs -->
                <div class="flex items-center gap-2 w-full sm:w-auto overflow-x-auto pb-1 sm:pb-0">
                    <a href="{{ route('admin.loans.index', ['status' => 'menunggu']) }}" 
                       class="px-4 py-2 rounded-2xl text-xs font-bold transition-all flex items-center gap-2 shrink-0 {{ $statusTab === 'menunggu' ? 'bg-amber-500 text-white shadow-xs' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                        <span>Menunggu Verifikasi</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold {{ $statusTab === 'menunggu' ? 'bg-white/20 text-white' : 'bg-amber-100 text-amber-800' }}">
                            {{ $pendingCount }}
                        </span>
                    </a>

                    <a href="{{ route('admin.loans.index', ['status' => 'berjalan']) }}" 
                       class="px-4 py-2 rounded-2xl text-xs font-bold transition-all flex items-center gap-2 shrink-0 {{ $statusTab === 'berjalan' ? 'bg-blue-600 text-white shadow-xs' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                        <span>Aktif / Dipinjam</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold {{ $statusTab === 'berjalan' ? 'bg-white/20 text-white' : 'bg-blue-100 text-blue-800' }}">
                            {{ $activeCount }}
                        </span>
                    </a>

                    <a href="{{ route('admin.loans.index', ['status' => 'selesai']) }}" 
                       class="px-4 py-2 rounded-2xl text-xs font-bold transition-all flex items-center gap-2 shrink-0 {{ $statusTab === 'selesai' ? 'bg-slate-800 text-white shadow-xs' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                        <span>Selesai, Ditolak & Batal</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold {{ $statusTab === 'selesai' ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700' }}">
                            {{ $completedCount }}
                        </span>
                    </a>
                </div>

                <!-- Search -->
                <form action="{{ route('admin.loans.index') }}" method="GET" class="w-full sm:w-72 flex items-center gap-2">
                    <input type="hidden" name="status" value="{{ $statusTab }}">
                    <div class="relative w-full">
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama, NISN, tiket..." 
                            class="w-full pl-3 pr-8 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white">
                        @if(request('q'))
                            <a href="{{ route('admin.loans.index', ['status' => $statusTab]) }}" class="absolute right-2.5 top-2 text-slate-400 hover:text-slate-600 text-xs font-bold">&times;</a>
                        @endif
                    </div>
                    <button type="submit" class="px-3 py-2 bg-blue-600 hover:bg-blue-800 text-white rounded-xl text-xs font-bold shrink-0 shadow-xs transition-colors">
                        Cari
                    </button>
                </form>
            </div>

        </div>

        <!-- Loans Cards -->
        @if($loans->isEmpty())
            <div class="bg-white rounded-3xl border border-slate-200 p-12 text-center space-y-3">
                <div class="w-14 h-14 bg-slate-100 text-slate-400 rounded-full flex items-center justify-center mx-auto">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
                <h3 class="text-base font-bold text-slate-800">Tidak Ada Permohonan pada Kategori Ini</h3>
                <p class="text-xs text-slate-500 max-w-sm mx-auto">Semua pengajuan dalam status ini sudah selesai diproses.</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($loans as $loan)
                    @php
                        $isOverdue = in_array($loan->status, ['disetujui', 'dipinjam']) && now()->gt($loan->end_date);
                        $availableUnits = $loan->tool->availableUnits;
                    @endphp
                    <div class="bg-white rounded-3xl border {{ $isOverdue ? 'border-rose-400 shadow-sm shadow-rose-100' : 'border-slate-200' }} p-6 shadow-xs hover:border-blue-300 transition-all space-y-4">
                        
                        <!-- Top Info Bar -->
                        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 pb-3">
                            <div class="flex items-center gap-3">
                                <span class="text-sm font-mono font-black text-slate-900 bg-slate-100 px-3 py-1 rounded-xl">
                                    {{ $loan->ticket_number }}
                                </span>
                                <span class="text-xs text-slate-400">Diajukan: {{ $loan->created_at->format('d/m/Y H:i') }}</span>
                                <span class="text-xs font-semibold px-2.5 py-0.5 rounded-lg bg-blue-50 text-blue-700 border border-blue-200">
                                    {{ $loan->tool->category->name }}
                                </span>
                                @if($isOverdue)
                                    <span class="text-xs font-bold px-2.5 py-0.5 rounded-full bg-rose-600 text-white animate-pulse">
                                        Terlambat Kembali
                                    </span>
                                @endif
                            </div>

                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold border {{ $loan->status_badge }}">
                                {{ $loan->status_label }}
                            </span>
                        </div>

                        <!-- Main Content Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-5 items-start">
                            
                            <!-- Col 1: Peminjam Info -->
                            <div class="md:col-span-4 space-y-2 text-xs">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Identitas Peminjam:</span>
                                <div class="bg-slate-50 p-3.5 rounded-2xl border border-slate-200 space-y-1.5">
                                    <div class="flex items-center justify-between">
                                        <h4 class="font-bold text-slate-900 text-sm">{{ $loan->user->name }}</h4>
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $loan->user->role === 'guru' ? 'bg-slate-200 text-slate-800' : 'bg-blue-100 text-blue-900' }}">
                                            {{ $loan->user->role }}
                                        </span>
                                    </div>
                                    <p class="text-slate-600">
                                        {{ $loan->user->role === 'guru' ? 'NIP:' : 'NISN:' }} <strong class="font-mono text-slate-800">{{ $loan->user->identifier ?? '-' }}</strong>
                                    </p>
                                    <p class="text-slate-600">
                                        Kelas / Unit: <strong class="text-slate-800">{{ $loan->user->class_or_dept ?? '-' }}</strong>
                                    </p>
                                    <p class="text-slate-600 flex items-center justify-between pt-1 border-t border-slate-200/60">
                                        <span>HP / WA: <strong class="text-slate-800">{{ $loan->user->phone ?? '-' }}</strong></span>
                                        @if($loan->user->phone)
                                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $loan->user->phone) }}" target="_blank" class="text-[10px] text-emerald-600 hover:underline font-bold flex items-center gap-0.5">
                                                <span>Chat WA</span> &rarr;
                                            </a>
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <!-- Col 2: Tool, Quantity, & Schedule -->
                            <div class="md:col-span-5 space-y-2 text-xs">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Rincian Alat & Waktu:</span>
                                <div class="space-y-1.5">
                                    <h4 class="text-sm font-bold text-slate-900">{{ $loan->tool->name }}</h4>
                                    <div class="flex flex-wrap gap-2 text-[11px]">
                                        <span class="bg-slate-100 text-slate-700 px-2 py-0.5 rounded-lg font-bold">
                                            Jumlah: {{ $loan->quantity }} Unit
                                        </span>
                                        <span class="bg-blue-50 text-blue-700 px-2 py-0.5 rounded-lg font-bold">
                                            Durasi: {{ $loan->duration_days }} Hari
                                        </span>
                                        <span class="{{ $isOverdue ? 'bg-rose-100 text-rose-800 font-bold' : 'bg-slate-100 text-slate-700' }} px-2 py-0.5 rounded-lg">
                                            Tenggat: {{ $loan->end_date->format('d/m/Y') }}
                                        </span>
                                    </div>
                                    <p class="text-slate-600 italic pt-1">Keperluan: "{{ $loan->purpose }}"</p>
                                </div>

                                <!-- Assigned Units Display -->
                                @if(!empty($loan->assigned_units))
                                    <div class="pt-2">
                                        <span class="text-[10px] text-slate-400 block font-bold uppercase">Unit Fisik yang Dialokasikan:</span>
                                        <div class="flex flex-wrap gap-1 mt-1">
                                            @foreach($loan->assigned_units as $unit)
                                                <span class="px-2 py-0.5 bg-emerald-50 text-emerald-800 border border-emerald-300 rounded-lg font-mono text-[11px] font-bold">
                                                    {{ $unit }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Col 3: Proposal File & Notes -->
                            <div class="md:col-span-3 space-y-3 text-xs">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Dokumen Lampiran:</span>
                                @if($loan->proposal_path)
                                    <div class="bg-blue-50/70 p-3 rounded-2xl border border-blue-200 space-y-1.5">
                                        <span class="text-[11px] font-bold text-blue-900 block truncate">{{ $loan->proposal_filename ?? 'Berkas Proposal' }}</span>
                                        <a href="{{ asset('storage/' . $loan->proposal_path) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition-colors w-full justify-center shadow-xs">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                            <span>Buka Berkas Proposal</span>
                                        </a>
                                    </div>
                                @else
                                    <div class="p-3 bg-slate-50 rounded-2xl border border-slate-200 text-center">
                                        <span class="text-slate-400 text-xs italic block">Tidak ada lampiran berkas.</span>
                                        @if($loan->status === 'menunggu')
                                            <button type="button" 
                                                    class="btn-open-request-proposal mt-1.5 text-[10px] font-bold text-amber-700 bg-amber-50 hover:bg-amber-100 px-2.5 py-1 rounded-lg border border-amber-200 transition-colors inline-block cursor-pointer"
                                                    data-loan-id="{{ $loan->id }}"
                                                    data-tool="{{ $loan->tool->name }}"
                                                    data-ticket="{{ $loan->ticket_number }}"
                                                    data-recipient-name="{{ $loan->user->name }}">
                                                + Minta Berkas Proposal
                                            </button>
                                        @endif
                                    </div>
                                @endif
                            </div>

                        </div>

                        <!-- Action Bar for Admin -->
                        <div class="pt-3 border-t border-slate-100 flex flex-wrap items-center justify-between gap-3 text-xs">
                            <div class="text-slate-500 max-w-md">
                                @if($loan->pj_notes)
                                    <span class="font-bold text-slate-700">Catatan PJ:</span> {{ $loan->pj_notes }}
                                @endif
                            </div>

                            <!-- Action Buttons depending on status -->
                            <div class="flex items-center gap-2 flex-wrap">
                                
                                <!-- Kirim Pesan Shortcut to borrower -->
                                <button type="button" 
                                        class="btn-open-msg-modal px-3.5 py-2 bg-slate-100 hover:bg-blue-50 text-blue-950 border border-slate-300 rounded-xl font-bold text-xs transition-colors inline-flex items-center gap-1.5 cursor-pointer"
                                        data-loan-id="{{ $loan->id }}"
                                        data-recipient-name="{{ $loan->user->name }}"
                                        data-ticket="{{ $loan->ticket_number }}"
                                        data-tool="{{ $loan->tool->name }}"
                                        data-end="{{ $loan->end_date->format('d/m/Y') }}"
                                        data-is-overdue="{{ $isOverdue ? '1' : '0' }}">
                                    <svg class="w-3.5 h-3.5 text-blue-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                                    <span>Kirim Pesan</span>
                                </button>

                                @if($loan->status === 'menunggu')
                                    <!-- Reject Action Button -->
                                    <button type="button" 
                                            class="btn-open-reject-modal px-3.5 py-2 bg-rose-50 text-rose-700 hover:bg-rose-100 border border-rose-200 rounded-xl font-bold text-xs transition-colors cursor-pointer"
                                            data-loan-id="{{ $loan->id }}"
                                            data-ticket="{{ $loan->ticket_number }}"
                                            data-tool="{{ $loan->tool->name }}">
                                        Tolak Pengajuan
                                    </button>

                                    <!-- Advanced Approve Action Button -->
                                    <button type="button" 
                                            class="btn-open-approve-modal px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs shadow-xs transition-colors flex items-center gap-1.5 cursor-pointer"
                                            data-loan-id="{{ $loan->id }}"
                                            data-ticket="{{ $loan->ticket_number }}"
                                            data-tool="{{ $loan->tool->name }}"
                                            data-quantity="{{ $loan->quantity }}"
                                            data-location="{{ $loan->tool->location }}"
                                            data-units="{{ json_encode($availableUnits->pluck('unit_code')) }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        <span>Setujui & Alokasikan Unit</span>
                                    </button>
                                @endif

                                @if($loan->status === 'disetujui')
                                    <!-- Print Ticket -->
                                    <a href="{{ route('loans.print', $loan->id) }}" target="_blank" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold text-xs transition-colors inline-flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                        <span>Cetak Surat</span>
                                    </a>

                                    <!-- Handover Button (Serah Terima Fisik) -->
                                    <form action="{{ route('admin.loans.handover', $loan->id) }}" method="POST" onsubmit="return confirm('Konfirmasi bahwa alat fisik telah diserahkan langsung kepada {{ $loan->user->name }}?');" class="inline">
                                        @csrf
                                        <button type="submit" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-xl font-bold text-xs shadow-xs transition-colors flex items-center gap-1.5">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                                            <span>Serah Terima Fisik</span>
                                        </button>
                                    </form>

                                    <!-- Complete (Return) Button -->
                                    <button type="button" 
                                            class="btn-open-return-modal px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-xs shadow-xs transition-colors flex items-center gap-1.5 cursor-pointer"
                                            data-loan-id="{{ $loan->id }}"
                                            data-ticket="{{ $loan->ticket_number }}"
                                            data-tool="{{ $loan->tool->name }}"
                                            data-quantity="{{ $loan->quantity }}"
                                            data-fine-per-day="{{ $loan->tool->fine_per_day }}"
                                            data-is-overdue="{{ $isOverdue ? '1' : '0' }}"
                                            data-end-date="{{ $loan->end_date->format('Y-m-d') }}">
                                        <span>Catat Pengembalian</span>
                                    </button>
                                @endif

                                @if($loan->status === 'dipinjam')
                                    <a href="{{ route('loans.print', $loan->id) }}" target="_blank" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold text-xs transition-colors inline-flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                        <span>Cetak Surat</span>
                                    </a>

                                    <!-- Complete (Return) Button -->
                                    <button type="button" 
                                            class="btn-open-return-modal px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-xs shadow-xs transition-colors flex items-center gap-1.5 cursor-pointer"
                                            data-loan-id="{{ $loan->id }}"
                                            data-ticket="{{ $loan->ticket_number }}"
                                            data-tool="{{ $loan->tool->name }}"
                                            data-quantity="{{ $loan->quantity }}"
                                            data-fine-per-day="{{ $loan->tool->fine_per_day }}"
                                            data-is-overdue="{{ $isOverdue ? '1' : '0' }}"
                                            data-end-date="{{ $loan->end_date->format('Y-m-d') }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span>Catat Pengembalian</span>
                                    </button>
                                @endif

                                @if($loan->status === 'selesai')
                                    <span class="text-xs text-slate-400 font-semibold">Terselesaikan pada {{ $loan->updated_at->format('d/m/Y') }}</span>
                                @endif

                            </div>
                        </div>

                    </div>
                @endforeach
            </div>

            <div class="pt-4">
                {{ $loans->links() }}
            </div>
        @endif

    </div>

    <!-- 1. MODAL ADVANCED APPROVE & UNIT ALLOCATION -->
    <div id="approveModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs hidden transition-opacity">
        <div class="bg-white rounded-3xl border border-slate-200 max-w-lg w-full p-6 sm:p-7 shadow-2xl space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div>
                    <h3 class="text-base font-extrabold text-slate-900">Persetujuan & Alokasi Unit Fisik</h3>
                    <p id="approveModalSubtitle" class="text-xs text-slate-500"></p>
                </div>
                <button type="button" class="btn-dismiss-modal text-slate-400 hover:text-slate-700 font-bold">&times;</button>
            </div>

            <form id="approveForm" method="POST" action="" class="space-y-4 text-xs">
                @csrf
                <div class="p-3 bg-blue-50/80 rounded-2xl border border-blue-100 space-y-1 text-[11px] text-blue-900">
                    <div class="flex items-center justify-between">
                        <span>Jumlah Unit yang Harus Dialokasikan:</span>
                        <strong id="approveRequiredQty" class="font-bold text-sm text-blue-700"></strong>
                    </div>
                    <p class="text-slate-500 text-[10px]">Pilih kode unit fisik tersedia di bawah ini sesuai jumlah unit yang diajukan.</p>
                </div>

                <!-- Unit Selection Checkboxes Container -->
                <div class="space-y-1.5">
                    <label class="block font-bold text-slate-700">Pilih Kode Unit Fisik Ready: <span class="text-rose-500">*</span></label>
                    <div id="approveUnitsContainer" class="grid grid-cols-2 gap-2 max-h-40 overflow-y-auto p-2 bg-slate-50 rounded-2xl border border-slate-200">
                        <!-- Populated by JS -->
                    </div>
                </div>

                <!-- Pickup Instruction / Notes -->
                <div class="space-y-1">
                    <label class="block font-bold text-slate-700">Instruksi / Catatan Pengambilan untuk Peminjam:</label>
                    <textarea name="pj_notes" id="approvePjNotes" rows="2" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 focus:outline-none"></textarea>
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" name="send_notification" id="chkNotifyApprove" value="1" checked class="rounded text-blue-600">
                    <label for="chkNotifyApprove" class="text-slate-700 font-medium">Kirim notifikasi otomatis persetujuan ke akun peminjam</label>
                </div>

                <div class="pt-3 flex items-center justify-end gap-2 border-t border-slate-100">
                    <button type="button" class="btn-dismiss-modal px-4 py-2 rounded-xl border border-slate-200 text-slate-700 font-bold hover:bg-slate-50">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold shadow-xs">
                        Setujui & Alokasikan Unit
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- 2. MODAL REJECT WITH REASONS -->
    <div id="rejectModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs hidden transition-opacity">
        <div class="bg-white rounded-3xl border border-slate-200 max-w-md w-full p-6 shadow-2xl space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div>
                    <h3 class="text-base font-extrabold text-slate-900">Tolak Permohonan Peminjaman</h3>
                    <p id="rejectModalSubtitle" class="text-xs text-slate-500"></p>
                </div>
                <button type="button" class="btn-dismiss-modal text-slate-400 hover:text-slate-700 font-bold">&times;</button>
            </div>

            <form id="rejectForm" method="POST" action="" class="space-y-3.5 text-xs">
                @csrf
                <div class="space-y-1">
                    <label class="block font-bold text-slate-700">Pilih Preset Alasan Cepat:</label>
                    <div class="space-y-1">
                        <button type="button" class="btn-preset-reason text-left w-full p-2 rounded-xl border border-slate-200 hover:bg-slate-50 text-[11px] text-slate-700">
                            Stok alat sedang dialokasikan untuk pemeliharaan rutin atau kalibrasi alat kejuruan.
                        </button>
                        <button type="button" class="btn-preset-reason text-left w-full p-2 rounded-xl border border-slate-200 hover:bg-slate-50 text-[11px] text-slate-700">
                            Dokumen proposal kegiatan belum lengkap atau belum disetujui guru pembina resmi.
                        </button>
                        <button type="button" class="btn-preset-reason text-left w-full p-2 rounded-xl border border-slate-200 hover:bg-slate-50 text-[11px] text-slate-700">
                            Jadwal peminjaman bertabrakan dengan agenda praktikum utama mata pelajaran sekolah.
                        </button>
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="block font-bold text-slate-700">Catatan Alasan Penolakan Resmi: <span class="text-rose-500">*</span></label>
                    <textarea name="reject_reason" id="rejectReasonInput" rows="3" required placeholder="Tuliskan alasan penolakan secara jelas..." 
                        class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-rose-500 focus:outline-none"></textarea>
                </div>

                <div class="pt-3 flex items-center justify-end gap-2 border-t border-slate-100">
                    <button type="button" class="btn-dismiss-modal px-4 py-2 rounded-xl border border-slate-200 text-slate-700 font-bold hover:bg-slate-50">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold shadow-xs">
                        Tolak & Kirim Alasan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- 3. MODAL RETURN / COMPLETE WITH FINE & CONDITION CHECK -->
    <div id="returnModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs hidden transition-opacity">
        <div class="bg-white rounded-3xl border border-slate-200 max-w-lg w-full p-6 shadow-2xl space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div>
                    <h3 class="text-base font-extrabold text-slate-900">Catat Pengembalian Alat Praktik</h3>
                    <p id="returnModalSubtitle" class="text-xs text-slate-500"></p>
                </div>
                <button type="button" class="btn-dismiss-modal text-slate-400 hover:text-slate-700 font-bold">&times;</button>
            </div>

            <form id="returnForm" method="POST" action="" class="space-y-3.5 text-xs">
                @csrf

                <div class="grid grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label class="block font-bold text-slate-700">Kondisi Fisik Saat Kembali: <span class="text-rose-500">*</span></label>
                        <select name="return_condition" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl font-bold focus:bg-white focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            <option value="baik">Kondisi Baik & Bersih</option>
                            <option value="rusak">Kondisi Rusak / Perlu Perbaikan</option>
                            <option value="hilang">Hilang / Tidak Kembali</option>
                        </select>
                    </div>

                    <div class="space-y-1">
                        <label class="block font-bold text-slate-700">Status Pembayaran Denda:</label>
                        <select name="fine_status" id="returnFineStatus" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl font-bold focus:bg-white focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            <option value="none">Tidak Ada Denda</option>
                            <option value="lunas">Denda Lunas di Tempat</option>
                            <option value="belum_lunas">Belum Lunas (Tercatat)</option>
                        </select>
                    </div>
                </div>

                <!-- Overdue Notice Banner if Late -->
                <div id="returnOverdueNotice" class="p-3 bg-rose-50 border border-rose-200 rounded-2xl text-rose-900 text-xs hidden space-y-1">
                    <span class="font-bold flex items-center gap-1.5 text-rose-700">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Peminjaman Ini Melewati Batas Waktu Pengembalian!</span>
                    </span>
                    <p class="text-[11px] text-rose-700" id="returnOverdueCalculation"></p>
                </div>

                <div class="space-y-1">
                    <label class="block font-bold text-slate-700">Catatan Kondisi / Berita Acara Pengembalian:</label>
                    <textarea name="return_notes" rows="2" placeholder="Contoh: Alat dikembalikan lengkap dengan kabel dan kotak penyimpan..." 
                        class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 focus:outline-none"></textarea>
                </div>

                <div class="pt-3 flex items-center justify-end gap-2 border-t border-slate-100">
                    <button type="button" class="btn-dismiss-modal px-4 py-2 rounded-xl border border-slate-200 text-slate-700 font-bold hover:bg-slate-50">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold shadow-xs">
                        Konfirmasi Selesai & Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- 4. MODAL KIRIM PESAN KE PEMINJAM -->
    <div id="quickMessageModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs hidden transition-opacity">
        <div class="bg-white rounded-3xl border border-slate-200 max-w-lg w-full p-6 shadow-2xl space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div>
                    <h3 class="text-base font-extrabold text-slate-900">Kirim Pesan Resmi ke Peminjam</h3>
                    <p id="msgModalRecipient" class="text-xs text-slate-500"></p>
                </div>
                <button type="button" class="btn-dismiss-modal text-slate-400 hover:text-slate-700 font-bold">&times;</button>
            </div>

            <!-- Templates Bar -->
            <div class="space-y-1">
                <span class="text-[10px] font-bold uppercase text-slate-400">Pilih Template Cepat:</span>
                <div class="grid grid-cols-3 gap-1.5 text-[10px]">
                    <button type="button" id="msgTplLate" class="p-2 rounded-xl border border-rose-200 bg-rose-50 text-rose-800 font-bold hover:bg-rose-100 text-left">
                        ⏰ Terlambat
                    </button>
                    <button type="button" id="msgTplProposal" class="p-2 rounded-xl border border-amber-200 bg-amber-50 text-amber-800 font-bold hover:bg-amber-100 text-left">
                        📄 Minta Proposal
                    </button>
                    <button type="button" id="msgTplPickup" class="p-2 rounded-xl border border-blue-200 bg-blue-50 text-blue-800 font-bold hover:bg-blue-100 text-left">
                        📦 Ambil Alat
                    </button>
                </div>
            </div>

            <form id="quickMessageForm" method="POST" action="" class="space-y-3.5 text-xs">
                @csrf
                <div class="space-y-1">
                    <label class="block font-bold text-slate-700">Tingkat Pesan:</label>
                    <div class="grid grid-cols-3 gap-2">
                        <label class="flex items-center gap-1.5 p-2 rounded-xl border border-slate-200 cursor-pointer has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50">
                            <input type="radio" name="type" value="info" id="msgTypeInfo" checked class="text-blue-600">
                            <span class="font-bold text-[11px]">Info</span>
                        </label>
                        <label class="flex items-center gap-1.5 p-2 rounded-xl border border-slate-200 cursor-pointer has-[:checked]:border-amber-600 has-[:checked]:bg-amber-50">
                            <input type="radio" name="type" value="warning" id="msgTypeWarning" class="text-amber-600">
                            <span class="font-bold text-[11px]">Peringatan</span>
                        </label>
                        <label class="flex items-center gap-1.5 p-2 rounded-xl border border-slate-200 cursor-pointer has-[:checked]:border-emerald-600 has-[:checked]:bg-emerald-50">
                            <input type="radio" name="type" value="approval" id="msgTypeApproval" class="text-emerald-600">
                            <span class="font-bold text-[11px]">Instruksi</span>
                        </label>
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="block font-bold text-slate-700">Isi Pesan: <span class="text-rose-500">*</span></label>
                    <textarea name="message" id="quickMsgBody" rows="4" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-900 focus:outline-none"></textarea>
                </div>

                <div class="pt-3 flex items-center justify-end gap-2 border-t border-slate-100">
                    <button type="button" class="btn-dismiss-modal px-4 py-2 rounded-xl border border-slate-200 text-slate-700 font-bold hover:bg-slate-50">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-blue-900 hover:bg-blue-950 text-white font-bold shadow-xs">
                        Kirim Pesan
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Universal modal dismiss
        document.querySelectorAll('.btn-dismiss-modal').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('#approveModal, #rejectModal, #returnModal, #quickMessageModal').forEach(m => {
                    m.classList.add('hidden');
                });
                document.body.style.overflow = '';
            });
        });

        // 1. APPROVE MODAL
        const approveModal = document.getElementById('approveModal');
        const approveForm = document.getElementById('approveForm');
        const approveSubtitle = document.getElementById('approveModalSubtitle');
        const approveRequiredQty = document.getElementById('approveRequiredQty');
        const approveUnitsContainer = document.getElementById('approveUnitsContainer');
        const approvePjNotes = document.getElementById('approvePjNotes');

        document.querySelectorAll('.btn-open-approve-modal').forEach(btn => {
            btn.addEventListener('click', () => {
                const loanId = btn.getAttribute('data-loan-id');
                const ticket = btn.getAttribute('data-ticket');
                const tool = btn.getAttribute('data-tool');
                const quantity = parseInt(btn.getAttribute('data-quantity'), 10) || 1;
                const location = btn.getAttribute('data-location');
                const units = JSON.parse(btn.getAttribute('data-units') || '[]');

                approveSubtitle.textContent = `Tiket ${ticket} • ${tool} (${quantity} Unit)`;
                approveRequiredQty.textContent = `${quantity} Unit`;
                approvePjNotes.value = `Disetujui. Silakan mengambil alat fisik di ruang ${location} pada jam layanan sarana.`;
                approveForm.setAttribute('action', `/admin/peminjaman/${loanId}/approve`);

                approveUnitsContainer.innerHTML = '';
                if (units.length === 0) {
                    approveUnitsContainer.innerHTML = '<span class="text-rose-600 col-span-2">Tidak ada unit berstatus tersedia!</span>';
                } else {
                    units.forEach((code, idx) => {
                        const isAutoCheck = idx < quantity;
                        const label = document.createElement('label');
                        label.className = 'flex items-center gap-2 p-2 bg-white rounded-xl border border-slate-200 cursor-pointer text-xs font-mono font-bold hover:bg-slate-50';
                        label.innerHTML = `
                            <input type="checkbox" name="unit_codes[]" value="${code}" ${isAutoCheck ? 'checked' : ''} class="rounded text-blue-600">
                            <span>${code}</span>
                        `;
                        approveUnitsContainer.appendChild(label);
                    });
                }

                approveModal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            });
        });

        // 2. REJECT MODAL
        const rejectModal = document.getElementById('rejectModal');
        const rejectForm = document.getElementById('rejectForm');
        const rejectSubtitle = document.getElementById('rejectModalSubtitle');
        const rejectReasonInput = document.getElementById('rejectReasonInput');

        document.querySelectorAll('.btn-open-reject-modal').forEach(btn => {
            btn.addEventListener('click', () => {
                const loanId = btn.getAttribute('data-loan-id');
                const ticket = btn.getAttribute('data-ticket');
                const tool = btn.getAttribute('data-tool');

                rejectSubtitle.textContent = `Tiket ${ticket} • ${tool}`;
                rejectForm.setAttribute('action', `/admin/peminjaman/${loanId}/reject`);
                rejectReasonInput.value = '';

                rejectModal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            });
        });

        document.querySelectorAll('.btn-preset-reason').forEach(btn => {
            btn.addEventListener('click', () => {
                rejectReasonInput.value = btn.textContent.trim();
            });
        });

        // 3. RETURN MODAL
        const returnModal = document.getElementById('returnModal');
        const returnForm = document.getElementById('returnForm');
        const returnSubtitle = document.getElementById('returnModalSubtitle');
        const returnOverdueNotice = document.getElementById('returnOverdueNotice');
        const returnOverdueCalculation = document.getElementById('returnOverdueCalculation');
        const returnFineStatus = document.getElementById('returnFineStatus');

        document.querySelectorAll('.btn-open-return-modal').forEach(btn => {
            btn.addEventListener('click', () => {
                const loanId = btn.getAttribute('data-loan-id');
                const ticket = btn.getAttribute('data-ticket');
                const tool = btn.getAttribute('data-tool');
                const isOverdue = btn.getAttribute('data-is-overdue') === '1';
                const finePerDay = parseInt(btn.getAttribute('data-fine-per-day'), 10) || 0;
                const quantity = parseInt(btn.getAttribute('data-quantity'), 10) || 1;

                returnSubtitle.textContent = `Tiket ${ticket} • ${tool} (${quantity} Unit)`;
                returnForm.setAttribute('action', `/admin/peminjaman/${loanId}/complete`);

                if (isOverdue) {
                    returnOverdueNotice.classList.remove('hidden');
                    returnOverdueCalculation.textContent = `Tarif denda: Rp ${finePerDay.toLocaleString('id-ID')} / unit / hari keterlambatan. Sistem akan menghitung akumulasi total secara otomatis.`;
                    returnFineStatus.value = 'belum_lunas';
                } else {
                    returnOverdueNotice.classList.add('hidden');
                    returnFineStatus.value = 'none';
                }

                returnModal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            });
        });

        // 4. QUICK MESSAGE MODAL
        const msgModal = document.getElementById('quickMessageModal');
        const msgForm = document.getElementById('quickMessageForm');
        const msgRecipient = document.getElementById('msgModalRecipient');
        const msgBody = document.getElementById('quickMsgBody');
        const msgTypeInfo = document.getElementById('msgTypeInfo');
        const msgTypeWarning = document.getElementById('msgTypeWarning');
        const msgTypeApproval = document.getElementById('msgTypeApproval');

        let currentLoanContext = null;

        function openMessageModal(loanId, recipientName, ticket, tool, endDate, isOverdue) {
            currentLoanContext = { loanId, recipientName, ticket, tool, endDate };

            msgRecipient.textContent = `Penerima: ${recipientName} (No. Tiket: ${ticket})`;
            msgForm.setAttribute('action', `/admin/peminjaman/${loanId}/pesan`);

            if (isOverdue) {
                msgBody.value = `Peringatan Keterlambatan: Masa peminjaman alat "${tool}" (No. Tiket ${ticket}) telah melewati batas tenggat (${endDate}). Harap segera mengembalikan alat fisik ke ruang sarana sekolah untuk menghindari akumulasi denda harian.`;
                msgTypeWarning.checked = true;
            } else {
                msgBody.value = `Pemberitahuan Petugas PJ: Mengenai pengajuan peminjaman alat "${tool}" (No. Tiket ${ticket})...`;
                msgTypeInfo.checked = true;
            }

            msgModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        document.querySelectorAll('.btn-open-msg-modal').forEach(btn => {
            btn.addEventListener('click', () => {
                const loanId = btn.getAttribute('data-loan-id');
                const recipientName = btn.getAttribute('data-recipient-name');
                const ticket = btn.getAttribute('data-ticket');
                const tool = btn.getAttribute('data-tool');
                const endDate = btn.getAttribute('data-end');
                const isOverdue = btn.getAttribute('data-is-overdue') === '1';

                openMessageModal(loanId, recipientName, ticket, tool, endDate, isOverdue);
            });
        });

        document.querySelectorAll('.btn-open-request-proposal').forEach(btn => {
            btn.addEventListener('click', () => {
                const loanId = btn.getAttribute('data-loan-id');
                const recipientName = btn.getAttribute('data-recipient-name');
                const ticket = btn.getAttribute('data-ticket');
                const tool = btn.getAttribute('data-tool');

                openMessageModal(loanId, recipientName, ticket, tool, '-', false);
                msgBody.value = `Permintaan Berkas Proposal: Pengajuan peminjaman alat "${tool}" (No. Tiket ${ticket}) memerlukan berkas proposal / surat pengantar izin resmi sebelum dapat disetujui. Harap segera membawa berkas fisik ke ruang sarana.`;
                msgTypeWarning.checked = true;
            });
        });

        document.getElementById('msgTplLate')?.addEventListener('click', () => {
            if (!currentLoanContext) return;
            msgBody.value = `Peringatan Keterlambatan: Masa peminjaman alat "${currentLoanContext.tool}" (No. Tiket ${currentLoanContext.ticket}) telah melewati batas tenggat. Mohon segera mengembalikan alat ke ruang sarana untuk menghindari akumulasi denda harian.`;
            msgTypeWarning.checked = true;
        });

        document.getElementById('msgTplProposal')?.addEventListener('click', () => {
            if (!currentLoanContext) return;
            msgBody.value = `Permintaan Kelengkapan Berkas: Permohonan peminjaman alat "${currentLoanContext.tool}" (No. Tiket ${currentLoanContext.ticket}) memerlukan lampiran proposal kegiatan atau surat pengantar resmi. Silakan serahkan berkas ke ruang sarana.`;
            msgTypeWarning.checked = true;
        });

        document.getElementById('msgTplPickup')?.addEventListener('click', () => {
            if (!currentLoanContext) return;
            msgBody.value = `Instruksi Pengambilan Alat: Permohonan alat "${currentLoanContext.tool}" (No. Tiket ${currentLoanContext.ticket}) telah siap diambil di ruang sarana kejuruan. Harap temui petugas PJ pada jam istirahat sekolah.`;
            msgTypeApproval.checked = true;
        });
    });
</script>
@endsection
