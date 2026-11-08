@extends('layouts.app')

@section('title', 'Riwayat & Status Peminjaman')

@section('content')
<section class="py-10 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
            <div>
                <nav class="flex items-center gap-2 text-xs text-slate-400 mb-2">
                    <a href="{{ route('home') }}" class="hover:text-blue-900">Beranda</a>
                    <span>/</span>
                    <span class="text-slate-700 font-semibold">Riwayat Peminjaman</span>
                </nav>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Riwayat Peminjaman Saya</h1>
                <p class="text-slate-500 text-xs sm:text-sm mt-1">
                    Pantau status verifikasi Admin PJ, kode unit fisik yang di-assign, dan cetak bukti peminjaman digital.
                </p>
            </div>

            <div class="flex items-center gap-2.5 shrink-0">
                @if(!$loans->isEmpty())
                    <a href="{{ route('loans.history.print') }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-3 rounded-2xl bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs border border-slate-300 shadow-xs transition-all">
                        <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        <span>Cetak Rekap Riwayat</span>
                    </a>
                @endif

                <a href="{{ route('catalog.index') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-blue-600 hover:bg-blue-800 text-white font-bold text-xs shadow-xs transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Ajukan Pinjam Baru</span>
                </a>
            </div>
        </div>

        @if($loans->isEmpty())
            <div class="bg-white rounded-3xl border border-slate-200 p-12 text-center space-y-4">
                <div class="w-16 h-16 bg-slate-100 text-blue-600 rounded-full flex items-center justify-center mx-auto border border-slate-200">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800">Belum Ada Riwayat Peminjaman</h3>
                <p class="text-xs text-slate-500 max-w-sm mx-auto">
                    Anda belum pernah mengajukan peminjaman alat kejuruan. Buka katalog untuk memilih alat praktik yang dibutuhkan.
                </p>
                <div class="pt-2">
                    <a href="{{ route('catalog.index') }}" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-800 text-white rounded-xl text-xs font-bold transition-colors inline-block shadow-xs">
                        Eksplor Katalog Alat
                    </a>
                </div>
            </div>
        @else
            <!-- List of Loans -->
            <div class="space-y-4">
                @foreach($loans as $loan)
                    <div class="bg-white rounded-3xl border border-slate-200 p-5 sm:p-6 shadow-xs hover:border-slate-400 transition-all space-y-4">
                        
                        <!-- Top Bar: Ticket Number & Status Badge -->
                        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 pb-3">
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-semibold text-slate-500">No. Peminjaman:</span>
                                <span class="text-sm font-mono font-bold text-blue-950 bg-slate-100 px-3 py-1 rounded-xl border border-slate-200">
                                    {{ $loan->ticket_number }}
                                </span>
                                <span class="text-xs text-slate-400">Diajukan: {{ $loan->created_at->translatedFormat('d M Y, H:i') }}</span>
                            </div>

                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold border {{ $loan->status_badge }}">
                                <span class="w-2 h-2 rounded-full {{ $loan->status === 'disetujui' ? 'bg-emerald-600' : ($loan->status === 'menunggu' ? 'bg-amber-600' : ($loan->status === 'dibatalkan' ? 'bg-rose-600' : 'bg-slate-600')) }}"></span>
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
                                    <span class="font-bold text-blue-900">{{ $loan->duration_days }} Hari</span>
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
                                    <span class="text-xs text-amber-800 bg-amber-50 px-2.5 py-1 rounded-xl border border-amber-200 block text-center">
                                        Menunggu assignment unit oleh PJ
                                    </span>
                                @endif

                                <!-- Proposal File Indicator -->
                                @if($loan->proposal_path)
                                    <div class="pt-1">
                                        <a href="{{ asset('storage/' . $loan->proposal_path) }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs text-blue-900 hover:text-blue-950 font-semibold underline">
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
                                @if(in_array($loan->status, ['menunggu', 'disetujui']))
                                    <button type="button" 
                                            class="btn-open-cancel-modal px-3.5 py-2 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-xl text-xs font-bold transition-colors inline-flex items-center gap-1.5 cursor-pointer shadow-2xs"
                                            data-ticket="{{ $loan->ticket_number }}"
                                            data-tool="{{ $loan->tool->name }}"
                                            data-action="{{ route('loans.cancel', $loan->id) }}">
                                        <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        <span>Batalkan Peminjaman</span>
                                    </button>
                                @endif

                                @if(in_array($loan->status, ['disetujui', 'dipinjam', 'selesai']))
                                    <a href="{{ route('loans.print', $loan->id) }}" target="_blank" class="px-4 py-2 bg-blue-600 hover:bg-blue-800 text-white rounded-xl text-xs font-bold transition-all inline-flex items-center gap-1.5 shadow-xs hover:shadow-md">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                        Cetak Bukti Pinjam
                                    </a>
                                @endif

                                @if($loan->status === 'dibatalkan')
                                    <span class="px-3 py-1.5 bg-slate-100 text-slate-500 rounded-xl text-xs font-semibold border border-slate-200">
                                        Permohonan Dibatalkan
                                    </span>
                                @endif
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
        @endif

    </div>

    <!-- Modal Konfirmasi Batalkan Peminjaman -->
    <div id="cancelLoanModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs hidden transition-opacity">
        <div class="bg-white rounded-3xl border border-slate-200 max-w-md w-full p-6 sm:p-7 shadow-2xl space-y-5 animate-in fade-in zoom-in-95 duration-150">
            <div class="flex items-start gap-3.5">
                <div class="w-12 h-12 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center shrink-0 border border-rose-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div class="space-y-1">
                    <h3 class="text-base font-extrabold text-slate-900">
                        Batalkan Permohonan Pinjam?
                    </h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Anda akan membatalkan permohonan untuk alat <strong id="cancelToolName" class="text-slate-800"></strong> dengan No. Peminjaman <strong id="cancelTicketNumber" class="text-slate-800 font-mono"></strong>.
                    </p>
                </div>
            </div>

            <div class="p-3 bg-slate-100 rounded-xl border border-slate-200 text-slate-800 text-xs flex items-center gap-2">
                <svg class="w-4 h-4 text-slate-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Tindakan ini akan menghentikan proses verifikasi dan mengembalikan alokasi alat.</span>
            </div>

            <form id="cancelLoanForm" method="POST" action="" class="flex items-center justify-end gap-2.5 pt-2 border-t border-slate-100">
                @csrf
                <button type="button" id="btnDismissCancelModal" class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-700 hover:bg-slate-100 transition-colors">
                    Tidak, Kembali
                </button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold shadow-xs transition-all flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    <span>Ya, Batalkan Sekarang</span>
                </button>
            </form>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById('cancelLoanModal');
        const form = document.getElementById('cancelLoanForm');
        const toolNameSpan = document.getElementById('cancelToolName');
        const ticketSpan = document.getElementById('cancelTicketNumber');
        const btnDismiss = document.getElementById('btnDismissCancelModal');

        document.querySelectorAll('.btn-open-cancel-modal').forEach(button => {
            button.addEventListener('click', () => {
                const ticket = button.getAttribute('data-ticket');
                const tool = button.getAttribute('data-tool');
                const action = button.getAttribute('data-action');

                ticketSpan.textContent = ticket;
                toolNameSpan.textContent = tool;
                form.setAttribute('action', action);

                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            });
        });

        function closeModal() {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }

        btnDismiss.addEventListener('click', closeModal);

        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                closeModal();
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                closeModal();
            }
        });
    });
</script>
@endsection
