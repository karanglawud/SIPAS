@extends('layouts.app')

@section('title', 'Formulir Pengajuan Peminjaman Alat')

@section('content')
<section class="py-10 bg-slate-50 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Header & Breadcrumbs -->
        <div>
            <nav class="flex items-center gap-2 text-xs text-slate-400 mb-2">
                <a href="{{ route('home') }}" class="hover:text-blue-900">Beranda</a>
                <span>/</span>
                <a href="{{ route('catalog.index') }}" class="hover:text-blue-900">Katalog</a>
                <span>/</span>
                <span class="text-slate-700 font-semibold">Formulir Peminjaman</span>
            </nav>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Formulir Pengajuan Peminjaman Alat</h1>
            <p class="text-slate-500 text-xs sm:text-sm mt-1">
                Lengkapi rincian durasi peminjaman dan lampirkan berkas proposal / surat izin kegiatan (khusus peminjaman skala besar OSIS / Ekskul).
            </p>
        </div>

        <!-- Form Card Container -->
        <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-xs">
            
            <form id="loanForm" action="{{ route('loans.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Section 1: Data Akun Peminjam (Otomatis dari Profil) -->
                <div class="bg-slate-50 rounded-2xl p-4 border border-slate-200 space-y-3">
                    <div class="flex items-center justify-between border-b border-slate-200/80 pb-2">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span>
                            <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Identitas Peminjam (Tervalidasi)</h3>
                        </div>
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase {{ Auth::user()->isGuru() ? 'bg-slate-200 text-slate-800' : 'bg-blue-50 text-blue-700 border border-blue-200' }}">
                            Role: {{ ucfirst(Auth::user()->role) }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
                        <div>
                            <span class="text-slate-400 block text-[10px]">Nama Lengkap:</span>
                            <span class="font-bold text-slate-800 text-sm">{{ Auth::user()->name }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block text-[10px]">{{ Auth::user()->isGuru() ? 'NIP Guru:' : 'NISN Siswa:' }}</span>
                            <span class="font-semibold text-slate-800 font-mono">{{ Auth::user()->identifier ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block text-[10px]">Kelas / Jabatan:</span>
                            <span class="font-semibold text-slate-800">{{ Auth::user()->class_or_dept ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Alat yang Dipilih & Jumlah Unit -->
                <div class="space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                        <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                            <span class="w-6 h-6 rounded-lg bg-blue-600 text-white flex items-center justify-center text-xs shadow-xs">1</span>
                            Alat yang Dipinjam &amp; Jumlah Unit
                        </h3>
                        <a href="{{ route('catalog.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-800 hover:underline flex items-center gap-1">
                            <span>Ganti Alat Lain</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>

                    <!-- Selected Tool Card Preview -->
                    <input type="hidden" name="tool_id" value="{{ $selectedTool->id }}">
                    @error('tool_id')
                        <p class="text-[11px] text-rose-600 font-semibold">{{ $message }}</p>
                    @enderror

                    <div class="bg-slate-50 rounded-2xl border border-slate-200 p-4 space-y-3">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div class="flex items-start gap-3">
                                <div class="w-12 h-12 rounded-xl bg-blue-600 flex items-center justify-center text-white font-mono font-bold text-xs shrink-0 shadow-xs">
                                    {{ substr($selectedTool->code, 0, 3) }}
                                </div>
                                <div>
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="text-[10px] font-mono font-bold px-2 py-0.5 rounded bg-slate-200 text-slate-700">
                                            {{ $selectedTool->code }}
                                        </span>
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-blue-50 text-blue-700 border border-blue-200">
                                            {{ $selectedTool->category->name }}
                                        </span>
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-emerald-50 text-emerald-800 border border-emerald-200">
                                            Stok: {{ $selectedTool->available_stock }} Unit Ready
                                        </span>
                                    </div>
                                    <h4 class="text-base font-bold text-slate-900 mt-1">{{ $selectedTool->name }}</h4>
                                    <p class="text-xs text-slate-500 mt-0.5">{{ $selectedTool->spec }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 pt-2 border-t border-slate-200/80 text-[11px]">
                            <div>
                                <span class="text-slate-400 block text-[10px]">Lokasi Ruang:</span>
                                <span class="font-bold text-slate-700">{{ $selectedTool->location }}</span>
                            </div>
                            <div>
                                <span class="text-slate-400 block text-[10px]">Penanggung Jawab:</span>
                                <span class="font-bold text-slate-700">{{ $selectedTool->category->pj_name }}</span>
                            </div>
                            <div>
                                <span class="text-slate-400 block text-[10px]">Maksimal Pinjam:</span>
                                <span class="font-bold text-slate-700">{{ $selectedTool->max_days }} Hari</span>
                            </div>
                            <div>
                                <span class="text-slate-400 block text-[10px]">Tarif Denda:</span>
                                <span class="font-bold text-rose-600">Rp {{ number_format($selectedTool->fine_per_day, 0, ',', '.') }}/hari</span>
                            </div>
                        </div>
                    </div>

                    <!-- Quantity Input -->
                    <div class="space-y-1 max-w-xs">
                        <label class="block text-xs font-bold text-slate-700">Jumlah Unit yang Diajukan <span class="text-rose-500">*</span></label>
                        <div class="flex items-center gap-3">
                            <input type="number" name="quantity" id="quantityInput" value="{{ old('quantity', 1) }}" min="1" max="{{ $selectedTool->available_stock }}" required
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none">
                            <span class="text-xs text-slate-500 whitespace-nowrap">/ {{ $selectedTool->available_stock }} unit ready</span>
                        </div>
                        <p class="text-[10px] text-slate-400">Unit fisik akan otomatis dialokasikan oleh Petugas PJ.</p>
                        @error('quantity')
                            <p class="text-[11px] text-rose-600 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Section 3: Durasi & Jadwal Peminjaman -->
                <div class="space-y-4">
                    <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-2 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-blue-600 text-white flex items-center justify-center text-xs shadow-xs">2</span>
                        Jadwal &amp; Durasi Peminjaman (Berapa Hari)
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Start Date -->
                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-slate-700">Mulai Tanggal Pinjam <span class="text-rose-500">*</span></label>
                            <input type="date" name="start_date" id="startDateInput" value="{{ old('start_date', date('Y-m-d')) }}" required
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none">
                            @error('start_date')
                                <p class="text-[11px] text-rose-600 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Duration in Days -->
                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-slate-700">Durasi Peminjaman (Berapa Hari) <span class="text-rose-500">*</span></label>
                            <div class="flex items-center gap-2">
                                <select name="duration_days" id="durationDaysSelect" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none">
                                    @for($i = 1; $i <= 14; $i++)
                                        <option value="{{ $i }}" {{ old('duration_days', 2) == $i ? 'selected' : '' }}>
                                            {{ $i }} Hari Kalender
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <p class="text-[10px] text-slate-400">Rekomendasi normal: 1 s/d 3 hari</p>
                            @error('duration_days')
                                <p class="text-[11px] text-rose-600 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Auto Calculated Return Date Box -->
                    <div class="bg-slate-100 border border-slate-200 rounded-2xl p-3.5 flex items-center justify-between text-xs text-slate-800">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-5 h-5 text-blue-900 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <div>
                                <span class="text-slate-500 block text-[10px]">Perkiraan Batas Tanggal Pengembalian:</span>
                                <strong id="calculatedReturnDate" class="text-sm font-bold text-blue-900">-</strong>
                            </div>
                        </div>
                        <span class="text-[11px] text-slate-500 font-medium">Harap kembali sebelum pukul 15.30 WIB</span>
                    </div>
                </div>

                <!-- Section 4: Upload File Proposal & Keperluan -->
                <div class="space-y-4">
                    <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-2 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-blue-600 text-white flex items-center justify-center text-xs shadow-xs">3</span>
                        Keperluan &amp; Lampiran Proposal (OSIS / Skala Besar)
                    </h3>

                    <!-- Purpose Textarea -->
                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-slate-700">Keperluan Peminjaman Praktik / Acara <span class="text-rose-500">*</span></label>
                        <textarea name="purpose" id="purposeTextarea" rows="3" required placeholder="Jelaskan untuk kegiatan praktikum apa atau acara sekolah mana alat ini digunakan..." 
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none">{{ old('purpose') }}</textarea>
                        @error('purpose')
                            <p class="text-[11px] text-rose-600 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- File Upload Input (Proposal / Surat Izin) -->
                    <div class="space-y-1.5 p-4 rounded-2xl border border-dashed border-slate-300 bg-slate-50">
                        <label class="block text-xs font-bold text-slate-800">
                            Lampirkan Berkas Proposal / Surat Izin Kegiatan
                            <span class="text-slate-400 font-normal">(Opsional untuk jumlah satuan, Wajib untuk jumlah banyak/OSIS)</span>
                        </label>
                        <p class="text-[11px] text-slate-500">
                            Format yang didukung: <strong>PDF, DOC, DOCX, JPG, PNG</strong> (Maksimal 10 MB). Berkas ini akan diverifikasi oleh Petugas PJ Kategori Alat.
                        </p>
                        <input type="file" name="proposal_file" id="proposalFileInput" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                            class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-600 file:text-white hover:file:bg-blue-700 cursor-pointer pt-2">
                        @error('proposal_file')
                            <p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Live Summary & Submission Button -->
                <div class="pt-4 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <a href="{{ route('catalog.index') }}" class="px-4 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs transition-colors w-full sm:w-auto text-center">
                        &larr; Kembali ke Katalog
                    </a>

                    <button type="button" id="btnOpenLoanConfirmModal" class="w-full sm:w-auto px-6 py-2.5 bg-blue-600 hover:bg-blue-800 text-white font-bold text-xs rounded-xl shadow-xs hover:shadow-md transition-all flex items-center justify-center gap-2 cursor-pointer">
                        <span>Kirim Permohonan Peminjaman</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </div>

            </form>

        </div>
    </div>

    <!-- Modal Popup Pengingat / Konfirmasi Sebelum Kirim Permohonan -->
    <div id="submitConfirmModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs hidden transition-opacity">
        <div class="bg-white rounded-3xl border border-slate-200 max-w-lg w-full p-6 sm:p-7 shadow-2xl space-y-5 animate-in fade-in zoom-in-95 duration-150">
            
            <!-- Modal Header -->
            <div class="flex items-start gap-3.5">
                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 border border-blue-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <div class="space-y-1">
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-blue-600 bg-blue-50 px-2 py-0.5 rounded border border-blue-200">
                        Konfirmasi Akhir Peminjaman
                    </span>
                    <h3 class="text-lg font-extrabold text-slate-900">
                        Yakin Ingin Mengirim Permohonan?
                    </h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Pastikan seluruh data kebutuhan dan jadwal peminjaman sudah sesuai sebelum dikirim ke Petugas PJ.
                    </p>
                </div>
            </div>

            <!-- Review Summary Table / Box -->
            <div class="bg-slate-50 rounded-2xl p-4 border border-slate-200 space-y-2.5 text-xs">
                <div class="flex items-start justify-between gap-2 border-b border-slate-200/80 pb-2">
                    <span class="text-slate-400 text-[11px]">Alat Dipinjam:</span>
                    <strong class="text-slate-900 text-right">{{ $selectedTool->name }} <span class="font-mono text-blue-600">({{ $selectedTool->code }})</span></strong>
                </div>

                <div class="flex items-center justify-between gap-2 border-b border-slate-200/80 pb-2">
                    <span class="text-slate-400 text-[11px]">Jumlah Unit:</span>
                    <strong id="confirmQuantity" class="text-slate-900 font-bold"></strong>
                </div>

                <div class="flex items-start justify-between gap-2 border-b border-slate-200/80 pb-2">
                    <span class="text-slate-400 text-[11px]">Jadwal Pengembalian:</span>
                    <strong id="confirmDates" class="text-blue-600 text-right font-bold"></strong>
                </div>

                <div class="flex items-start justify-between gap-2 border-b border-slate-200/80 pb-2">
                    <span class="text-slate-400 text-[11px]">Keperluan:</span>
                    <p id="confirmPurpose" class="text-slate-800 text-right italic font-medium max-w-[240px] truncate"></p>
                </div>

                <div class="flex items-center justify-between gap-2 border-b border-slate-200/80 pb-2">
                    <span class="text-slate-400 text-[11px]">Berkas Proposal:</span>
                    <span id="confirmProposal" class="text-slate-700 text-right font-medium truncate max-w-[200px]"></span>
                </div>

                <div class="flex items-center justify-between gap-2 pt-0.5 text-[11px]">
                    <span class="text-slate-400">Petugas PJ &amp; Lokasi:</span>
                    <span class="text-slate-700 font-medium text-right">{{ $selectedTool->category->pj_name }} • {{ $selectedTool->location }}</span>
                </div>
            </div>

            <div class="p-3 bg-amber-50 rounded-xl border border-amber-200 text-amber-900 text-xs flex items-center gap-2">
                <svg class="w-4 h-4 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Setelah dikirim, permohonan peminjaman akan ditinjau oleh Admin PJ kategori alat.</span>
            </div>

            <!-- Modal Action Buttons -->
            <div class="flex flex-col-reverse sm:flex-row items-center justify-end gap-2.5 pt-2 border-t border-slate-100">
                <button type="button" id="btnDismissSubmitModal" class="w-full sm:w-auto px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-700 hover:bg-slate-50 transition-colors">
                    Periksa Kembali
                </button>
                <button type="button" id="btnConfirmSubmitLoan" class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-800 text-white text-xs font-bold shadow-xs hover:shadow-md transition-all flex items-center justify-center gap-1.5 cursor-pointer">
                    <span>Ya, Kirim Sekarang</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>
            </div>

        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const loanForm = document.getElementById('loanForm');
        const startInput = document.getElementById('startDateInput');
        const durationSelect = document.getElementById('durationDaysSelect');
        const returnDateDisplay = document.getElementById('calculatedReturnDate');
        const quantityInput = document.getElementById('quantityInput');
        const purposeTextarea = document.getElementById('purposeTextarea');
        const proposalFileInput = document.getElementById('proposalFileInput');

        const btnOpenModal = document.getElementById('btnOpenLoanConfirmModal');
        const modal = document.getElementById('submitConfirmModal');
        const btnDismissModal = document.getElementById('btnDismissSubmitModal');
        const btnConfirmSubmit = document.getElementById('btnConfirmSubmitLoan');

        const confirmQuantity = document.getElementById('confirmQuantity');
        const confirmDates = document.getElementById('confirmDates');
        const confirmPurpose = document.getElementById('confirmPurpose');
        const confirmProposal = document.getElementById('confirmProposal');

        function updateReturnDate() {
            if (!startInput.value) return;
            const startDate = new Date(startInput.value);
            const days = parseInt(durationSelect.value, 10) || 1;

            startDate.setDate(startDate.getDate() + days);

            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            returnDateDisplay.textContent = startDate.toLocaleDateString('id-ID', options);
        }

        startInput.addEventListener('change', updateReturnDate);
        durationSelect.addEventListener('change', updateReturnDate);
        updateReturnDate();

        // Intercept submit button click to show popup reminder
        btnOpenModal.addEventListener('click', () => {
            if (!loanForm.reportValidity()) {
                return;
            }

            confirmQuantity.textContent = quantityInput.value + ' Unit';
            confirmDates.textContent = `${startInput.value} s/d ${returnDateDisplay.textContent} (${durationSelect.value} Hari)`;
            confirmPurpose.textContent = purposeTextarea.value.trim() || '-';
            confirmProposal.textContent = (proposalFileInput.files && proposalFileInput.files.length > 0) 
                ? proposalFileInput.files[0].name 
                : 'Tidak ada lampiran proposal';

            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        });

        function closeModal() {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }

        btnDismissModal.addEventListener('click', closeModal);

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

        btnConfirmSubmit.addEventListener('click', () => {
            btnConfirmSubmit.disabled = true;
            btnConfirmSubmit.innerHTML = '<span>Mengirim...</span>';
            loanForm.submit();
        });
    });
</script>
@endsection
