@extends('layouts.app')

@section('title', 'Riwayat & Status Peminjaman')

@section('content')
<section class="py-4 sm:py-10 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 space-y-4 sm:space-y-6">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-3 sm:gap-4">
            <div>
                <nav class="flex items-center gap-1.5 sm:gap-2 text-[11px] sm:text-xs text-slate-400 mb-1.5 sm:mb-2">
                    <a href="{{ route('home') }}" class="hover:text-blue-900">Beranda</a>
                    <span>/</span>
                    <span class="text-slate-700 font-semibold">Riwayat Peminjaman</span>
                </nav>
                <h1 class="text-xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Riwayat Peminjaman Saya</h1>
                <p class="text-slate-500 text-[11px] sm:text-sm mt-0.5 sm:mt-1">
                    Pantau status verifikasi Admin PJ, unit fisik yang di-assign, dan cetak bukti peminjaman digital.
                </p>
            </div>

            <div class="flex items-center gap-2 shrink-0">
                @if(!$loans->isEmpty())
                    <a href="{{ route('loans.history.print') }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-2 sm:px-4 sm:py-2.5 rounded-xl sm:rounded-2xl bg-white hover:bg-slate-50 text-slate-700 font-bold text-[11px] sm:text-xs border border-slate-300 shadow-2xs hover:border-slate-400 transition-all shrink-0 whitespace-nowrap active:scale-95">
                        <svg class="w-3.5 h-3.5 text-slate-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        <span>Cetak Rekap</span>
                    </a>
                @endif

                <a href="{{ route('catalog.index') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 sm:px-4 sm:py-2.5 rounded-xl sm:rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-[11px] sm:text-xs shadow-2xs hover:shadow-md transition-all shrink-0 whitespace-nowrap active:scale-95">
                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Ajukan Pinjam</span>
                </a>
            </div>
        </div>

        @if($loans->isEmpty())
            <div class="bg-white rounded-2xl sm:rounded-3xl border border-slate-200 p-8 sm:p-12 text-center space-y-3 sm:space-y-4">
                <div class="w-12 h-12 sm:w-16 sm:h-16 bg-slate-100 text-blue-600 rounded-full flex items-center justify-center mx-auto border border-slate-200">
                    <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <h3 class="text-base sm:text-lg font-bold text-slate-800">Belum Ada Riwayat Peminjaman</h3>
                <p class="text-[11px] sm:text-xs text-slate-500 max-w-sm mx-auto">
                    Anda belum pernah mengajukan peminjaman alat kejuruan. Buka katalog untuk memilih alat praktik yang dibutuhkan.
                </p>
                <div class="pt-1">
                    <a href="{{ route('catalog.index') }}" class="px-4 py-2 sm:px-5 sm:py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition-colors inline-block shadow-xs">
                        Eksplor Katalog Alat
                    </a>
                </div>
            </div>
        @else
            <!-- Filter Status Quick Pills -->
            <div class="flex items-center gap-1.5 overflow-x-auto pb-1 text-xs">
                <button type="button" onclick="filterLoans('all')" class="loan-filter-btn px-3 py-1.5 rounded-full font-bold transition-all bg-blue-600 text-white shadow-2xs whitespace-nowrap active:scale-95" data-filter="all">
                    Semua ({{ $loans->count() }})
                </button>
                <button type="button" onclick="filterLoans('menunggu')" class="loan-filter-btn px-3 py-1.5 rounded-full font-bold transition-all bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 whitespace-nowrap active:scale-95" data-filter="menunggu">
                    Menunggu ({{ $loans->where('status', 'menunggu')->count() }})
                </button>
                <button type="button" onclick="filterLoans('aktif')" class="loan-filter-btn px-3 py-1.5 rounded-full font-bold transition-all bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 whitespace-nowrap active:scale-95" data-filter="aktif">
                    Aktif / Dipinjam ({{ $loans->whereIn('status', ['disetujui', 'dipinjam'])->count() }})
                </button>
                <button type="button" onclick="filterLoans('selesai')" class="loan-filter-btn px-3 py-1.5 rounded-full font-bold transition-all bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 whitespace-nowrap active:scale-95" data-filter="selesai">
                    Selesai ({{ $loans->where('status', 'selesai')->count() }})
                </button>
            </div>

            <!-- List of Loans (Compact Ticket Cards) -->
            <div class="space-y-2.5 sm:space-y-3.5" id="loansContainer">
                @foreach($loans as $loan)
                    <div class="loan-card bg-white rounded-2xl sm:rounded-3xl border border-slate-200 p-3.5 sm:p-5 shadow-2xs hover:border-slate-300 transition-all space-y-2 sm:space-y-3" data-status="{{ $loan->status }}">
                        
                        <!-- Row 1: Ticket + Date + Status -->
                        <div class="flex items-center justify-between gap-2 border-b border-slate-100 pb-2">
                            <div class="flex items-center gap-2 min-w-0">
                                <span class="text-xs sm:text-sm font-mono font-bold text-blue-950 bg-slate-100 px-2 sm:px-2.5 py-0.5 rounded-lg border border-slate-200 shrink-0">
                                    {{ $loan->ticket_number }}
                                </span>
                                <span class="text-[10px] sm:text-xs text-slate-400 truncate">
                                    {{ $loan->created_at->translatedFormat('d M Y, H:i') }}
                                </span>
                            </div>

                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 sm:px-3 sm:py-1 rounded-full text-[10px] sm:text-xs font-bold border {{ $loan->status_badge }} shrink-0">
                                <span class="w-1.5 h-1.5 rounded-full {{ $loan->status === 'disetujui' || $loan->status === 'dipinjam' ? 'bg-emerald-600' : ($loan->status === 'menunggu' ? 'bg-amber-600' : ($loan->status === 'dibatalkan' || $loan->status === 'ditolak' ? 'bg-rose-600' : 'bg-slate-600')) }}"></span>
                                <span class="sm:hidden">{{ ucfirst($loan->status) }}</span>
                                <span class="hidden sm:inline">{{ $loan->status_label }}</span>
                            </span>
                        </div>

                        <!-- Row 2: Tool Name & Inline Specs -->
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                            <div class="min-w-0">
                                <span class="text-[9.5px] sm:text-[10px] font-bold uppercase tracking-wider text-slate-400 block truncate">
                                    {{ $loan->tool->category->name }} • PJ: {{ $loan->tool->category->pj_name }}
                                </span>
                                <h3 class="text-sm sm:text-base font-bold text-slate-900 leading-tight truncate">
                                    {{ $loan->tool->name }}
                                </h3>
                                @if($loan->purpose)
                                    <p class="text-[10.5px] sm:text-xs text-slate-500 italic truncate mt-0.5">"{{ $loan->purpose }}"</p>
                                @endif
                            </div>

                            <!-- Inline Specs Strip -->
                            <div class="flex items-center gap-2 text-[10px] sm:text-xs py-1 px-2.5 rounded-xl bg-slate-50 border border-slate-200/70 text-slate-700 shrink-0 self-start sm:self-auto">
                                <span class="font-bold flex items-center gap-1 text-slate-800">
                                    <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                    {{ $loan->quantity }} Unit
                                </span>
                                <span class="text-slate-300">•</span>
                                <span class="font-semibold text-blue-900 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ $loan->duration_days }} Hari
                                </span>
                                <span class="text-slate-300">•</span>
                                <span class="text-slate-500 font-mono text-[10px]">
                                    {{ $loan->start_date->format('d/m/y') }} - {{ $loan->end_date->format('d/m/y') }}
                                </span>
                            </div>
                        </div>

                        <!-- Row 3: Assigned Units & Notes (if available) -->
                        @if(!empty($loan->assigned_units) || $loan->pj_notes || $loan->proposal_path)
                            <div class="pt-1.5 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-1.5 text-xs">
                                <div class="flex items-center gap-1.5 flex-wrap min-w-0">
                                    @if(!empty($loan->assigned_units))
                                        <span class="text-[9.5px] font-bold text-slate-400 uppercase shrink-0">Unit:</span>
                                        @foreach($loan->assigned_units as $unitCode)
                                            <span class="px-1.5 py-0.5 rounded-md bg-emerald-50 text-emerald-800 border border-emerald-200 font-mono text-[9.5px] sm:text-[10px] font-bold">
                                                {{ $unitCode }}
                                            </span>
                                        @endforeach
                                    @elseif($loan->status === 'menunggu')
                                        <span class="text-[10px] text-amber-700 bg-amber-50 px-2 py-0.5 rounded-md border border-amber-200">
                                            Menunggu assignment unit oleh PJ
                                        </span>
                                    @endif

                                    @if($loan->proposal_path)
                                        <a href="{{ asset('storage/' . $loan->proposal_path) }}" target="_blank" class="inline-flex items-center gap-1 text-[10px] sm:text-[11px] text-blue-900 hover:text-blue-950 font-semibold underline ml-1">
                                            <svg class="w-3 h-3 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                            Proposal
                                        </a>
                                    @endif
                                </div>

                                @if($loan->pj_notes)
                                    <p class="text-[10px] sm:text-[11px] text-slate-600 line-clamp-1 sm:line-clamp-none">
                                        <span class="font-bold text-slate-700">Catatan:</span> {{ $loan->pj_notes }}
                                    </p>
                                @endif
                            </div>
                        @endif

                        <!-- Overdue Warning -->
                        @if($loan->is_overdue)
                            <div class="p-2.5 sm:p-3 bg-rose-50 border border-rose-200 rounded-xl text-rose-900 text-xs flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                <div class="flex items-center gap-2">
                                    <span class="relative flex h-2.5 w-2.5 shrink-0">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-rose-600"></span>
                                    </span>
                                    <div>
                                        <span class="font-extrabold text-rose-800 text-[11px] sm:text-xs">Terlambat: {{ $loan->computed_overdue_days }} Hari</span>
                                        <span class="text-rose-600 block text-[10px]">Harap segera kembalikan alat ke ruang sarana.</span>
                                    </div>
                                </div>
                                <div class="bg-white px-2.5 py-1 rounded-lg border border-rose-200 shrink-0 text-left sm:text-right">
                                    <span class="text-[9.5px] text-slate-500 block uppercase font-bold">Denda:</span>
                                    <span class="text-rose-700 font-mono font-black text-xs sm:text-sm">Rp {{ number_format($loan->computed_fine, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        @elseif($loan->status === 'selesai' && $loan->fine_amount > 0)
                            <div class="p-2 sm:p-2.5 bg-amber-50/80 border border-amber-200 rounded-xl text-amber-900 text-[11px] flex items-center justify-between">
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span>Denda ({{ $loan->overdue_days }} hari): <strong class="font-mono text-amber-950">Rp {{ number_format($loan->fine_amount, 0, ',', '.') }}</strong></span>
                                </div>
                                <span class="px-2 py-0.5 rounded-full text-[9.5px] font-bold uppercase {{ $loan->fine_status === 'lunas' ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : 'bg-rose-100 text-rose-800 border border-rose-300' }}">
                                    {{ $loan->fine_status === 'lunas' ? 'Lunas' : 'Belum Lunas' }}
                                </span>
                            </div>
                        @endif

                        <!-- Row 4: Action Buttons -->
                        <div class="pt-2 border-t border-slate-100 flex items-center justify-end gap-2 text-xs">
                            @if(in_array($loan->status, ['menunggu', 'disetujui']))
                                <button type="button" 
                                        class="btn-open-cancel-modal px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-xl text-[11px] font-bold transition-colors inline-flex items-center gap-1 cursor-pointer active:scale-95 shadow-2xs"
                                        data-ticket="{{ $loan->ticket_number }}"
                                        data-tool="{{ $loan->tool->name }}"
                                        data-action="{{ route('loans.cancel', $loan->id) }}">
                                    <svg class="w-3 h-3 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    <span>Batalkan</span>
                                </button>
                            @endif

                            @if(in_array($loan->status, ['disetujui', 'dipinjam', 'selesai']))
                                <a href="{{ route('loans.print', $loan->id) }}" target="_blank" class="px-3.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-[11px] font-bold transition-all inline-flex items-center gap-1.5 shadow-2xs hover:shadow-xs active:scale-95">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                    <span>Cetak Bukti Pinjam</span>
                                </a>
                            @endif

                            @if($loan->status === 'dibatalkan')
                                <span class="px-3 py-1 bg-slate-100 text-slate-500 rounded-xl text-[10.5px] font-semibold border border-slate-200 inline-flex items-center">
                                    Permohonan Dibatalkan
                                </span>
                            @endif
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
    function filterLoans(status) {
        document.querySelectorAll('.loan-filter-btn').forEach(btn => {
            if (btn.getAttribute('data-filter') === status) {
                btn.className = 'loan-filter-btn px-3 py-1.5 rounded-full font-bold transition-all bg-blue-600 text-white shadow-2xs whitespace-nowrap active:scale-95';
            } else {
                btn.className = 'loan-filter-btn px-3 py-1.5 rounded-full font-bold transition-all bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 whitespace-nowrap active:scale-95';
            }
        });

        document.querySelectorAll('.loan-card').forEach(card => {
            const cardStatus = card.getAttribute('data-status');
            if (status === 'all') {
                card.style.display = '';
            } else if (status === 'aktif') {
                card.style.display = (cardStatus === 'disetujui' || cardStatus === 'dipinjam') ? '' : 'none';
            } else {
                card.style.display = (cardStatus === status) ? '' : 'none';
            }
        });
    }

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
