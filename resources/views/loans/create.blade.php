@extends('layouts.app')

@section('title', 'Formulir Pengajuan Peminjaman Alat')

@section('content')
<section class="py-10 bg-slate-50 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Header & Breadcrumbs -->
        <div>
            <nav class="flex items-center gap-2 text-xs text-slate-400 mb-2">
                <a href="{{ route('home') }}" class="hover:text-blue-600">Beranda</a>
                <span>/</span>
                <a href="{{ route('catalog.index') }}" class="hover:text-blue-600">Katalog</a>
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
            
            <form action="{{ route('loans.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Section 1: Data Akun Peminjam (Otomatis dari Profil) -->
                <div class="bg-slate-50 rounded-2xl p-4 border border-slate-200 space-y-3">
                    <div class="flex items-center justify-between border-b border-slate-200/80 pb-2">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span>
                            <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Identitas Peminjam (Tervalidasi)</h3>
                        </div>
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase {{ Auth::user()->isGuru() ? 'bg-indigo-100 text-indigo-800' : 'bg-blue-100 text-blue-800' }}">
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

                <!-- Section 2: Pemilihan Alat & Stok -->
                <div class="space-y-4">
                    <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-2 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-blue-600 text-white flex items-center justify-center text-xs">1</span>
                        Pilih Alat & Jumlah Unit
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <!-- Select Tool -->
                        <div class="sm:col-span-2 space-y-1">
                            <label class="block text-xs font-bold text-slate-700">Pilih Alat Praktik <span class="text-rose-500">*</span></label>
                            <select name="tool_id" id="toolSelect" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:bg-white focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                <option value="">-- Pilih Alat Praktik Tersedia --</option>
                                @foreach($tools as $t)
                                    <option value="{{ $t->id }}" 
                                            data-code="{{ $t->code }}"
                                            data-pj="{{ $t->category->pj_name }}"
                                            data-category="{{ $t->category->name }}"
                                            data-maxdays="{{ $t->max_days }}"
                                            data-fine="{{ $t->fine_per_day }}"
                                            data-stock="{{ $t->available_stock }}"
                                            {{ (isset($selectedTool) && $selectedTool->id === $t->id) || old('tool_id') == $t->id ? 'selected' : '' }}>
                                        [{{ $t->code }}] {{ $t->name }} (Sisa: {{ $t->available_stock }} unit - PJ: {{ $t->category->pj_name }})
                                    </option>
                                @endforeach
                            </select>
                            @error('tool_id')
                                <p class="text-[11px] text-rose-600 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Quantity -->
                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-slate-700">Jumlah Unit <span class="text-rose-500">*</span></label>
                            <input type="number" name="quantity" id="quantityInput" value="{{ old('quantity', 1) }}" min="1" max="100" required
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold focus:bg-white focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            <p class="text-[10px] text-slate-400">Unit fisik akan otomatis di-assign PJ</p>
                            @error('quantity')
                                <p class="text-[11px] text-rose-600 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Section 3: Durasi & Jadwal Peminjaman -->
                <div class="space-y-4">
                    <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-2 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-blue-600 text-white flex items-center justify-center text-xs">2</span>
                        Jadwal & Durasi Peminjaman (Berapa Hari)
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Start Date -->
                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-slate-700">Mulai Tanggal Pinjam <span class="text-rose-500">*</span></label>
                            <input type="date" name="start_date" id="startDateInput" value="{{ old('start_date', date('Y-m-d')) }}" required
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            @error('start_date')
                                <p class="text-[11px] text-rose-600 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Duration in Days -->
                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-slate-700">Durasi Peminjaman (Berapa Hari) <span class="text-rose-500">*</span></label>
                            <div class="flex items-center gap-2">
                                <select name="duration_days" id="durationDaysSelect" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-blue-700 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:outline-none">
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
                    <div class="bg-blue-50/80 border border-blue-200 rounded-2xl p-3.5 flex items-center justify-between text-xs text-blue-900">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-5 h-5 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <div>
                                <span class="text-slate-500 block text-[10px]">Perkiraan Batas Tanggal Pengembalian:</span>
                                <strong id="calculatedReturnDate" class="text-sm font-bold text-blue-700">-</strong>
                            </div>
                        </div>
                        <span class="text-[11px] text-slate-500 font-medium">Harap kembali sebelum pukul 15.30 WIB</span>
                    </div>
                </div>

                <!-- Section 4: Upload File Proposal & Keperluan -->
                <div class="space-y-4">
                    <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-2 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-blue-600 text-white flex items-center justify-center text-xs">3</span>
                        Keperluan & Lampiran Proposal (OSIS / Skala Besar)
                    </h3>

                    <!-- Purpose Textarea -->
                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-slate-700">Keperluan Peminjaman Praktik / Acara <span class="text-rose-500">*</span></label>
                        <textarea name="purpose" rows="3" required placeholder="Jelaskan untuk kegiatan praktikum apa atau acara sekolah mana alat ini digunakan..." 
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:ring-2 focus:ring-blue-500 focus:outline-none">{{ old('purpose') }}</textarea>
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
                    <a href="{{ route('catalog.index') }}" class="px-5 py-3 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-colors w-full sm:w-auto text-center">
                        &larr; Kembali ke Katalog
                    </a>

                    <button type="submit" class="w-full sm:w-auto px-8 py-3.5 bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-xs rounded-2xl shadow-lg shadow-blue-500/25 hover:shadow-xl transition-all flex items-center justify-center gap-2">
                        <span>Kirim Permohonan Peminjaman</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </div>

            </form>

        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const startInput = document.getElementById('startDateInput');
        const durationSelect = document.getElementById('durationDaysSelect');
        const returnDateDisplay = document.getElementById('calculatedReturnDate');

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
    });
</script>
@endsection
