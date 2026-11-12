@extends('layouts.app')

@section('title', 'Pusat Pemberitahuan & Pesan Admin - SIPAS')

@section('content')
<section class="py-4 sm:py-10 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 space-y-4 sm:space-y-6">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-3">
            <div class="space-y-1">
                <nav class="flex items-center gap-1.5 text-[11px] sm:text-xs text-slate-400">
                    <a href="{{ route('home') }}" class="hover:text-blue-600">Beranda</a>
                    <span>/</span>
                    <a href="{{ route('admin.loans.index') }}" class="hover:text-blue-600">Admin</a>
                    <span>/</span>
                    <span class="text-slate-700 font-semibold">Kirim Pesan &amp; Peringatan</span>
                </nav>
                <div class="flex items-center gap-2 sm:gap-3 flex-wrap">
                    <h1 class="text-xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Kanal Pesan &amp; Peringatan ke Peminjam</h1>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] sm:text-xs font-bold uppercase tracking-wider bg-slate-100 text-slate-800 border border-slate-200 shrink-0">
                        {{ $admin->class_or_dept ?? 'Petugas PJ' }}
                    </span>
                </div>
                <p class="text-slate-500 text-xs sm:text-sm line-clamp-2 sm:line-clamp-none">
                    Kirim pesan satu arah kepada siswa atau guru (peringatan keterlambatan, permintaan berkas proposal, maupun instruksi pengambilan alat).
                </p>
            </div>
        </div>

        <!-- Mobile Segmented Tab Switcher -->
        <div class="lg:hidden grid grid-cols-2 gap-1 p-1 bg-slate-200/80 rounded-2xl">
            <button type="button" id="btnTabCompose" class="py-2 px-3 rounded-xl text-xs font-bold transition-all text-center bg-white text-slate-900 shadow-xs">
                Tulis Pesan Baru
            </button>
            <button type="button" id="btnTabHistory" class="py-2 px-3 rounded-xl text-xs font-bold transition-all text-center text-slate-600 hover:text-slate-900 flex items-center justify-center gap-1.5">
                <span>Riwayat Terkirim</span>
                <span class="px-1.5 py-0.2 rounded-full text-[10px] font-extrabold bg-slate-300 text-slate-700">
                    {{ $sentMessages->total() }}
                </span>
            </button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 sm:gap-8 items-start">
            
            <!-- Left: Message Composer Form with Quick Templates -->
            <div id="sectionCompose" class="lg:col-span-5 bg-white rounded-2xl sm:rounded-3xl border border-slate-200 p-4 sm:p-7 shadow-xs space-y-4 sm:space-y-5">
                <div class="flex items-center justify-between border-b border-slate-100 pb-2.5 sm:pb-3">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-blue-600 shrink-0"></span>
                        <h2 class="text-xs sm:text-sm font-bold text-slate-900 uppercase tracking-wider">Tulis Pesan Baru</h2>
                    </div>
                    <span class="text-[10px] sm:text-[11px] text-slate-400">Pesan Masuk ke Kotak User</span>
                </div>

                <!-- Quick Templates Buttons -->
                <div class="space-y-1.5">
                    <label class="block text-[10px] sm:text-[11px] font-bold text-slate-500 uppercase">Pilih Template Cepat:</label>
                    <div class="grid grid-cols-2 gap-1.5 sm:gap-2">
                        <button type="button" id="tplLate" class="btn-template p-2 sm:p-2.5 rounded-xl border border-rose-200 bg-rose-50/70 hover:bg-rose-100 text-rose-800 text-[10px] sm:text-[11px] font-bold text-left transition-colors flex items-center gap-1.5 cursor-pointer">
                            <span>⏰ Peringatan Terlambat</span>
                        </button>
                        <button type="button" id="tplProposal" class="btn-template p-2 sm:p-2.5 rounded-xl border border-amber-200 bg-amber-50/70 hover:bg-amber-100 text-amber-800 text-[10px] sm:text-[11px] font-bold text-left transition-colors flex items-center gap-1.5 cursor-pointer">
                            <span>📄 Minta Proposal</span>
                        </button>
                        <button type="button" id="tplPickup" class="btn-template p-2 sm:p-2.5 rounded-xl border border-slate-200 bg-slate-100 hover:bg-slate-200 text-slate-800 text-[10px] sm:text-[11px] font-bold text-left transition-colors flex items-center gap-1.5 cursor-pointer">
                            <span>📦 Info Ambil Alat</span>
                        </button>
                        <button type="button" id="tplGeneral" class="btn-template p-2 sm:p-2.5 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 text-slate-800 text-[10px] sm:text-[11px] font-bold text-left transition-colors flex items-center gap-1.5 cursor-pointer">
                            <span>💬 Pengumuman PJ</span>
                        </button>
                    </div>
                </div>

                <!-- Form -->
                <form action="{{ route('admin.messages.store') }}" method="POST" class="space-y-3 sm:space-y-4 text-xs">
                    @csrf

                    @if($category)
                        <input type="hidden" name="category_id" value="{{ $category->id }}">
                    @endif

                    <!-- Target / Recipient Picker -->
                    <div class="space-y-1">
                        <label class="block font-bold text-slate-700">Pilih Peminjam (Siswa / Guru): <span class="text-rose-500">*</span></label>
                        <select name="recipient_id" id="recipientSelect" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:bg-white focus:ring-2 focus:ring-blue-900 focus:outline-none">
                            <option value="">-- Pilih Nama Siswa atau Guru --</option>
                            @foreach($borrowers as $b)
                                <option value="{{ $b->id }}">
                                    {{ $b->name }} ({{ strtoupper($b->role) }} • {{ $b->class_or_dept ?? $b->identifier }})
                                </option>
                            @endforeach
                        </select>
                        @error('recipient_id')
                            <p class="text-[11px] text-rose-600 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Optional: Link with active loan -->
                    @if($activeLoans->isNotEmpty())
                        <div class="space-y-1">
                            <label class="block font-bold text-slate-700">Hubungkan dengan Data Peminjaman (Opsional):</label>
                            <select name="loan_id" id="loanSelect" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono focus:bg-white focus:ring-2 focus:ring-blue-900 focus:outline-none">
                                <option value="">-- Tanpa Tautan Peminjaman --</option>
                                @foreach($activeLoans as $al)
                                    <option value="{{ $al->id }}" data-recipient="{{ $al->user_id }}" data-tool="{{ $al->tool->name }}" data-ticket="{{ $al->ticket_number }}" data-end="{{ $al->end_date->format('d/m/Y') }}">
                                        [{{ $al->ticket_number }}] {{ $al->user->name }} - {{ $al->tool->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <!-- Message Type -->
                    <div class="space-y-1">
                        <label class="block font-bold text-slate-700">Kategori / Tingkat Urgensi Pesan:</label>
                        <div class="grid grid-cols-3 gap-1.5 sm:gap-2">
                            <label class="flex items-center justify-center gap-1 p-2 rounded-xl border border-slate-200 cursor-pointer hover:bg-slate-50 has-[:checked]:border-blue-900 has-[:checked]:bg-slate-100 transition-colors text-center">
                                <input type="radio" name="type" value="info" id="typeInfo" checked class="text-blue-900">
                                <span class="font-bold text-[10px] sm:text-[11px]">Info Biasa</span>
                            </label>
                            <label class="flex items-center justify-center gap-1 p-2 rounded-xl border border-slate-200 cursor-pointer hover:bg-slate-50 has-[:checked]:border-amber-600 has-[:checked]:bg-amber-50 transition-colors text-center">
                                <input type="radio" name="type" value="warning" id="typeWarning" class="text-amber-600">
                                <span class="font-bold text-[10px] sm:text-[11px]">Peringatan</span>
                            </label>
                            <label class="flex items-center justify-center gap-1 p-2 rounded-xl border border-slate-200 cursor-pointer hover:bg-slate-50 has-[:checked]:border-emerald-600 has-[:checked]:bg-emerald-50 transition-colors text-center">
                                <input type="radio" name="type" value="approval" id="typeApproval" class="text-emerald-600">
                                <span class="font-bold text-[10px] sm:text-[11px]">Instruksi</span>
                            </label>
                        </div>
                    </div>

                    <!-- Message Body -->
                    <div class="space-y-1">
                        <label class="block font-bold text-slate-700">Isi Pesan Pemberitahuan: <span class="text-rose-500">*</span></label>
                        <textarea name="message" id="messageBody" rows="4" required placeholder="Ketik pesan resmi yang ingin disampaikan ke peminjam..." 
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none">{{ old('message') }}</textarea>
                        @error('message')
                            <p class="text-[11px] text-rose-600 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="w-full py-2.5 px-4 bg-blue-600 hover:bg-blue-800 text-white rounded-xl font-bold text-xs shadow-xs hover:shadow-md transition-all flex items-center justify-center gap-2 cursor-pointer">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        <span>Kirim Pesan Resmi</span>
                    </button>
                </form>
            </div>

            <!-- Right: Sent Messages History -->
            <div id="sectionHistory" class="lg:col-span-7 bg-white rounded-2xl sm:rounded-3xl border border-slate-200 p-4 sm:p-7 shadow-xs space-y-3.5 sm:space-y-4 hidden lg:block">
                <div class="flex items-center justify-between border-b border-slate-100 pb-2.5 sm:pb-3">
                    <div>
                        <h2 class="text-xs sm:text-sm font-bold text-slate-900 uppercase tracking-wider">Riwayat Pesan yang Telah Dikirim</h2>
                        <p class="text-[10px] sm:text-[11px] text-slate-500">Pesan resmi dari akun Petugas PJ Anda</p>
                    </div>
                    <span class="px-2 sm:px-2.5 py-0.5 sm:py-1 rounded-full text-[11px] sm:text-xs font-bold bg-slate-100 text-slate-700 shrink-0">
                        {{ $sentMessages->total() }} Pesan
                    </span>
                </div>

                @if($sentMessages->isEmpty())
                    <div class="py-12 text-center text-slate-400 space-y-2">
                        <svg class="w-10 h-10 mx-auto text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        <p class="text-xs">Belum ada pesan resmi yang dikirimkan oleh akun Anda.</p>
                    </div>
                @else
                    <div class="space-y-2.5 sm:space-y-3">
                        @foreach($sentMessages as $msg)
                            <div class="p-3 sm:p-4 rounded-xl sm:rounded-2xl border border-slate-200 bg-slate-50/70 space-y-2 text-xs hover:border-slate-400 transition-all">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1 sm:gap-2 border-b border-slate-200/60 pb-1.5">
                                    <div class="flex items-center gap-1.5 flex-wrap min-w-0">
                                        <strong class="text-slate-900 font-bold text-xs sm:text-sm truncate">{{ $msg->recipient->name }}</strong>
                                        <span class="text-[9px] sm:text-[10px] px-1.5 py-0.2 rounded-md bg-white border border-slate-200 text-slate-600 font-bold uppercase shrink-0">
                                            {{ $msg->recipient->class_or_dept ?? strtoupper($msg->recipient->role) }}
                                        </span>
                                        @if($msg->loan)
                                            <span class="font-mono text-[9px] sm:text-[10px] text-blue-900 bg-blue-50 px-1.5 py-0.2 rounded border border-blue-200 font-bold shrink-0">
                                                {{ $msg->loan->ticket_number }}
                                            </span>
                                        @endif
                                    </div>

                                    <div class="flex items-center justify-between sm:justify-end gap-2 text-[10px] font-mono text-slate-400 shrink-0">
                                        <span>{{ $msg->created_at->format('d/m/Y, H:i') }}</span>
                                        <span class="px-2 py-0.5 rounded text-[9px] sm:text-[10px] font-bold uppercase {{ $msg->type === 'warning' ? 'bg-rose-100 text-rose-800 border border-rose-200' : ($msg->type === 'approval' ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : 'bg-slate-100 text-slate-800 border border-slate-200') }}">
                                            {{ $msg->type }}
                                        </span>
                                    </div>
                                </div>

                                <p class="text-slate-700 leading-relaxed bg-white p-2.5 sm:p-3 rounded-xl border border-slate-100 text-xs">
                                    {{ $msg->message }}
                                </p>

                                <div class="flex items-center justify-between text-[10px] sm:text-[11px] text-slate-400 pt-0.5">
                                    <span class="truncate">Penerima: {{ $msg->recipient->phone ?? 'Tanpa nomor HP' }}</span>
                                    <div class="flex items-center gap-2 shrink-0">
                                        @if($msg->recipient->phone)
                                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $msg->recipient->phone) }}" target="_blank" class="text-emerald-600 font-bold hover:underline">Chat WA &rarr;</a>
                                        @endif
                                        <span class="font-semibold {{ $msg->is_read ? 'text-emerald-600' : 'text-slate-400' }}">
                                            {{ $msg->is_read ? '✓ Sudah Dibaca' : 'Belum Dibaca' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="pt-2">
                        {{ $sentMessages->links() }}
                    </div>
                @endif
            </div>

        </div>

    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const messageBody = document.getElementById('messageBody');
        const loanSelect = document.getElementById('loanSelect');
        const recipientSelect = document.getElementById('recipientSelect');
        const typeInfo = document.getElementById('typeInfo');
        const typeWarning = document.getElementById('typeWarning');
        const typeApproval = document.getElementById('typeApproval');

        // Mobile Tab Switcher
        const btnTabCompose = document.getElementById('btnTabCompose');
        const btnTabHistory = document.getElementById('btnTabHistory');
        const sectionCompose = document.getElementById('sectionCompose');
        const sectionHistory = document.getElementById('sectionHistory');

        function showTab(tab) {
            if (!btnTabCompose || !btnTabHistory || !sectionCompose || !sectionHistory) return;
            if (tab === 'compose') {
                sectionCompose.classList.remove('hidden');
                sectionHistory.classList.add('hidden');
                btnTabCompose.className = 'py-2 px-3 rounded-xl text-xs font-bold transition-all text-center bg-white text-slate-900 shadow-xs';
                btnTabHistory.className = 'py-2 px-3 rounded-xl text-xs font-bold transition-all text-center text-slate-600 hover:text-slate-900 flex items-center justify-center gap-1.5';
            } else {
                sectionCompose.classList.add('hidden');
                sectionHistory.classList.remove('hidden');
                btnTabHistory.className = 'py-2 px-3 rounded-xl text-xs font-bold transition-all text-center bg-white text-slate-900 shadow-xs flex items-center justify-center gap-1.5';
                btnTabCompose.className = 'py-2 px-3 rounded-xl text-xs font-bold transition-all text-center text-slate-600 hover:text-slate-900';
            }
        }

        btnTabCompose?.addEventListener('click', () => showTab('compose'));
        btnTabHistory?.addEventListener('click', () => showTab('history'));

        // If URL has pagination ?page= or filter, automatically switch to history tab on mobile
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('page') || urlParams.has('filter_type')) {
            showTab('history');
        }

        // Auto select recipient when a loan is selected
        loanSelect?.addEventListener('change', () => {
            const selectedOpt = loanSelect.options[loanSelect.selectedIndex];
            const recipientId = selectedOpt.getAttribute('data-recipient');
            if (recipientId) {
                recipientSelect.value = recipientId;
            }
        });

        // Template Late Warning
        document.getElementById('tplLate')?.addEventListener('click', () => {
            const selectedOpt = loanSelect ? loanSelect.options[loanSelect.selectedIndex] : null;
            const tool = selectedOpt?.getAttribute('data-tool') || '[Nama Alat]';
            const ticket = selectedOpt?.getAttribute('data-ticket') || '[No. Peminjaman]';
            const end = selectedOpt?.getAttribute('data-end') || '[Batas Tanggal]';

            messageBody.value = `Pemberitahuan Keterlambatan: Masa peminjaman alat "${tool}" (No. Peminjaman: ${ticket}) telah melewati batas tenggat pengembalian (${end}). Mohon segera mengembalikan alat fisik ke ruang sarana kejuruan untuk menghindari akumulasi denda keterlambatan.`;
            typeWarning.checked = true;
        });

        // Template Proposal Request
        document.getElementById('tplProposal')?.addEventListener('click', () => {
            const selectedOpt = loanSelect ? loanSelect.options[loanSelect.selectedIndex] : null;
            const tool = selectedOpt?.getAttribute('data-tool') || '[Nama Alat]';
            const ticket = selectedOpt?.getAttribute('data-ticket') || '[No. Peminjaman]';

            messageBody.value = `Permintaan Kelengkapan Berkas: Permohonan peminjaman alat "${tool}" (No. Peminjaman: ${ticket}) memerlukan lampiran proposal kegiatan atau surat pengantar resmi yang ditandatangani guru pembina. Silakan bawa berkas fisik ke ruang sarana untuk verifikasi persetujuan.`;
            typeWarning.checked = true;
        });

        // Template Pickup Instruction
        document.getElementById('tplPickup')?.addEventListener('click', () => {
            const selectedOpt = loanSelect ? loanSelect.options[loanSelect.selectedIndex] : null;
            const tool = selectedOpt?.getAttribute('data-tool') || '[Nama Alat]';
            const ticket = selectedOpt?.getAttribute('data-ticket') || '[No. Peminjaman]';

            messageBody.value = `Instruksi Pengambilan Alat: Permohonan alat "${tool}" (No. Peminjaman: ${ticket}) telah disiapkan. Silakan mengambil alat fisik di ruang sarana kejuruan pada jam istirahat atau jam layanan sekolah dengan menunjukkan kartu identitas siswa/guru.`;
            typeApproval.checked = true;
        });

        // Template General Announcement
        document.getElementById('tplGeneral')?.addEventListener('click', () => {
            messageBody.value = `Pemberitahuan Petugas PJ Sarana: Harap memperhatikan kondisi kebersihan serta kelengkapan alat saat menggunakan fasilitas praktik kejuruan di SMK Negeri 5 Surakarta.`;
            typeInfo.checked = true;
        });
    });
</script>
@endsection
