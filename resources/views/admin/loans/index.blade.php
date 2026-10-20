@extends('layouts.app')

@section('title', 'Panel Verifikasi Admin PJ Kategori Alat - SIPAS')

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
                    Kelola verifikasi pengajuan peminjaman alat, periksa proposal terlampir, alokasikan kode unit fisik, dan catat kondisi pengembalian.
                </p>
            </div>

            <!-- Admin Scope Badge -->
            <div class="bg-white px-4 py-2.5 rounded-2xl border border-slate-200 shadow-xs flex items-center gap-3 text-xs">
                <div class="w-8 h-8 rounded-xl bg-amber-500/10 text-amber-600 flex items-center justify-center font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <div>
                    <span class="text-slate-400 text-[10px] font-bold block uppercase tracking-wider">Lingkup Verifikasi:</span>
                    <span class="font-bold text-slate-800">{{ $admin->class_or_dept ?? 'Petugas PJ Kategori Alat' }}</span>
                </div>
            </div>
        </div>

        <!-- Filter Status Tabs -->
        <div class="flex items-center gap-2 border-b border-slate-200 pb-3">
            <a href="{{ route('admin.loans.index', ['status' => 'menunggu']) }}" 
               class="px-4 py-2.5 rounded-2xl text-xs font-bold transition-all flex items-center gap-2 {{ $statusTab === 'menunggu' ? 'bg-amber-500 text-white shadow-sm' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-100' }}">
                <span>Menunggu Verifikasi</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold {{ $statusTab === 'menunggu' ? 'bg-white/20 text-white' : 'bg-amber-100 text-amber-800' }}">
                    {{ $pendingCount }}
                </span>
            </a>

            <a href="{{ route('admin.loans.index', ['status' => 'berjalan']) }}" 
               class="px-4 py-2.5 rounded-2xl text-xs font-bold transition-all flex items-center gap-2 {{ $statusTab === 'berjalan' ? 'bg-blue-600 text-white shadow-sm' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-100' }}">
                <span>Sedang Dipinjam / Approved</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold {{ $statusTab === 'berjalan' ? 'bg-white/20 text-white' : 'bg-blue-100 text-blue-800' }}">
                    {{ $activeCount }}
                </span>
            </a>

            <a href="{{ route('admin.loans.index', ['status' => 'selesai']) }}" 
               class="px-4 py-2.5 rounded-2xl text-xs font-bold transition-all flex items-center gap-2 {{ $statusTab === 'selesai' ? 'bg-slate-800 text-white shadow-sm' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-100' }}">
                <span>Selesai & Ditolak</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold {{ $statusTab === 'selesai' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-700' }}">
                    {{ $completedCount }}
                </span>
            </a>
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
                    <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-xs hover:border-blue-300 transition-all space-y-4">
                        
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
                                <div class="bg-slate-50 p-3 rounded-2xl border border-slate-200 space-y-1">
                                    <h4 class="font-bold text-slate-900 text-sm">{{ $loan->user->name }}</h4>
                                    <p class="text-slate-600">
                                        Peran: <strong class="text-blue-700 uppercase">{{ $loan->user->role }}</strong> 
                                        ({{ $loan->user->identifier ?? '-' }})
                                    </p>
                                    <p class="text-slate-500">Kelas/Unit: {{ $loan->user->class_or_dept ?? '-' }}</p>
                                    <p class="text-slate-500">Kontak: {{ $loan->user->phone ?? '-' }}</p>
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
                                        <span class="bg-slate-100 text-slate-700 px-2 py-0.5 rounded-lg">
                                            Tenggat: {{ $loan->end_date->format('d/m/Y') }}
                                        </span>
                                    </div>
                                    <p class="text-slate-600 italic pt-1">Keperluan: "{{ $loan->purpose }}"</p>
                                </div>

                                <!-- Assigned Units Display -->
                                @if(!empty($loan->assigned_units))
                                    <div class="pt-2">
                                        <span class="text-[10px] text-slate-400 block font-bold uppercase">Unit Fisik yang Diberikan:</span>
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
                                            Buka Proposal (PDF)
                                        </a>
                                    </div>
                                @else
                                    <span class="text-slate-400 text-xs italic">Tidak ada berkas lampiran.</span>
                                @endif
                            </div>

                        </div>

                        <!-- Action Bar for Admin -->
                        <div class="pt-3 border-t border-slate-100 flex flex-wrap items-center justify-between gap-3 text-xs">
                            <div class="text-slate-500">
                                @if($loan->pj_notes)
                                    <span class="font-bold text-slate-700">Catatan:</span> {{ $loan->pj_notes }}
                                @endif
                            </div>

                            <!-- Action Buttons depending on status -->
                            <div class="flex items-center gap-2">
                                
                                @if($loan->status === 'menunggu')
                                    <!-- Reject Form -->
                                    <form action="{{ route('admin.loans.reject', $loan->id) }}" method="POST" onsubmit="return confirm('Tolak permohonan peminjaman ini?');" class="inline">
                                        @csrf
                                        <input type="hidden" name="reject_reason" value="Stok alat sedang dialokasikan untuk pemeliharaan rutin atau dokumen proposal belum lengkap.">
                                        <button type="submit" class="px-3.5 py-2 bg-rose-50 text-rose-700 hover:bg-rose-100 border border-rose-200 rounded-xl font-bold text-xs transition-colors">
                                            Tolak Pengajuan
                                        </button>
                                    </form>

                                    <!-- Approve Form -->
                                    <form action="{{ route('admin.loans.approve', $loan->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs shadow-xs transition-colors flex items-center gap-1.5">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            Setujui & Assign Unit Fisik
                                        </button>
                                    </form>
                                @endif

                                @if(in_array($loan->status, ['disetujui', 'dipinjam']))
                                    <!-- Print Button -->
                                    <a href="{{ route('loans.print', $loan->id) }}" target="_blank" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold text-xs transition-colors inline-flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                        Cetak Surat
                                    </a>

                                    <!-- Complete Form -->
                                    <form action="{{ route('admin.loans.complete', $loan->id) }}" method="POST" onsubmit="return confirm('Catat pengembalian alat ini? Unit fisik akan dikembalikan ke stok tersedia.');" class="inline">
                                        @csrf
                                        <input type="hidden" name="return_condition" value="baik">
                                        <input type="hidden" name="return_notes" value="Alat dikembalikan tepat waktu dalam kondisi BAIK dan bersih.">
                                        <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-xs shadow-xs transition-colors flex items-center gap-1.5">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            Catat Pengembalian (Kondisi Baik)
                                        </button>
                                    </form>
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
</section>
@endsection
