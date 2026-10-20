<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Bukti Peminjaman - {{ $loan->ticket_number }}</title>
    @vite(['resources/css/app.css'])
    <style>
        @media print {
            body { background: white !important; padding: 0 !important; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body class="bg-slate-100 p-4 sm:p-8 font-sans text-slate-900">
    
    <!-- Action Bar (Not Printed) -->
    <div class="max-w-3xl mx-auto mb-6 flex items-center justify-between no-print">
        <a href="{{ route('loans.index') }}" class="px-4 py-2 bg-white border border-slate-300 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-50 transition-colors">
            &larr; Kembali ke Riwayat
        </a>
        <button type="button" onclick="window.print()" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-md transition-colors flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            Cetak / Simpan PDF
        </button>
    </div>

    <!-- Official Paper Sheet -->
    <div class="max-w-3xl mx-auto bg-white border border-slate-300 p-8 sm:p-12 shadow-sm rounded-2xl text-slate-800 space-y-6">
        
        <!-- Kop Surat Sekolah -->
        <div class="flex items-center gap-4 border-b-2 border-slate-900 pb-4 text-center">
            <div class="w-16 h-16 rounded-2xl bg-blue-700 flex items-center justify-center text-white font-black text-2xl shrink-0">
                5
            </div>
            <div class="flex-grow">
                <span class="text-xs uppercase tracking-widest font-bold text-slate-500">Pemerintah Provinsi Jawa Tengah • Dinas Pendidikan & Kebudayaan</span>
                <h1 class="text-xl sm:text-2xl font-black tracking-tight text-slate-900 uppercase">SMK NEGERI 5 SURAKARTA</h1>
                <p class="text-xs text-slate-600">Jl. Adi Sucipto No. 42, Kerten, Kec. Laweyan, Kota Surakarta, Jawa Tengah 57143</p>
                <p class="text-[10px] text-slate-500 font-mono mt-0.5">SISTEM INFORMASI PEMINJAMAN ALAT SEKOLAH (SIPAS) — BUKTI RESMI</p>
            </div>
        </div>

        <!-- Title of Document -->
        <div class="text-center space-y-1">
            <h2 class="text-base font-extrabold uppercase tracking-wider underline">SURAT BUKTI PEMINJAMAN ALAT SEKOLAH</h2>
            <p class="text-xs font-mono font-bold text-blue-700">NOMOR TIKET: {{ $loan->ticket_number }}</p>
        </div>

        <!-- Info Table -->
        <div class="border border-slate-300 rounded-xl overflow-hidden text-xs">
            <table class="w-full text-left">
                <tbody class="divide-y divide-slate-200">
                    <tr class="bg-slate-50">
                        <th class="py-2.5 px-4 font-bold text-slate-700 w-1/3">Nama Peminjam</th>
                        <td class="py-2.5 px-4 font-semibold text-slate-900">{{ $loan->user->name }} ({{ ucfirst($loan->user->role) }})</td>
                    </tr>
                    <tr>
                        <th class="py-2.5 px-4 font-bold text-slate-700">{{ $loan->user->isGuru() ? 'NIP Guru' : 'NISN / Kelas' }}</th>
                        <td class="py-2.5 px-4 font-mono">{{ $loan->user->identifier ?? '-' }} • {{ $loan->user->class_or_dept ?? '-' }}</td>
                    </tr>
                    <tr class="bg-slate-50">
                        <th class="py-2.5 px-4 font-bold text-slate-700">Nama Alat Praktik</th>
                        <td class="py-2.5 px-4 font-bold text-slate-900">{{ $loan->tool->name }} ({{ $loan->tool->code }})</td>
                    </tr>
                    <tr>
                        <th class="py-2.5 px-4 font-bold text-slate-700">Kategori & PJ Alat</th>
                        <td class="py-2.5 px-4">{{ $loan->tool->category->name }} — PJ: <strong>{{ $loan->tool->category->pj_name }}</strong></td>
                    </tr>
                    <tr class="bg-slate-50">
                        <th class="py-2.5 px-4 font-bold text-slate-700">Jumlah & Kode Unit Fisik</th>
                        <td class="py-2.5 px-4">
                            <strong>{{ $loan->quantity }} Unit</strong>
                            @if(!empty($loan->assigned_units))
                                <span class="font-mono text-blue-700 font-bold ml-1">({{ implode(', ', $loan->assigned_units) }})</span>
                            @else
                                <span class="text-amber-700 font-italic">(Menunggu assignment)</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="py-2.5 px-4 font-bold text-slate-700">Jadwal & Durasi Pinjam</th>
                        <td class="py-2.5 px-4">
                            <strong>{{ $loan->duration_days }} Hari Kalender</strong> 
                            ({{ $loan->start_date->translatedFormat('d F Y') }} s/d {{ $loan->end_date->translatedFormat('d F Y') }})
                        </td>
                    </tr>
                    <tr class="bg-slate-50">
                        <th class="py-2.5 px-4 font-bold text-slate-700">Keperluan Penggunaan</th>
                        <td class="py-2.5 px-4">{{ $loan->purpose }}</td>
                    </tr>
                    @if($loan->proposal_filename)
                        <tr>
                            <th class="py-2.5 px-4 font-bold text-slate-700">Lampiran Proposal / Berkas</th>
                            <td class="py-2.5 px-4 font-semibold text-emerald-700">{{ $loan->proposal_filename }} (Terverifikasi di Sistem)</td>
                        </tr>
                    @endif
                    <tr class="bg-slate-50">
                        <th class="py-2.5 px-4 font-bold text-slate-700">Catatan Petugas PJ Alat</th>
                        <td class="py-2.5 px-4 text-slate-600">{{ $loan->pj_notes ?? 'Disetujui untuk kegiatan praktik sekolah.' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Rules Notice on Receipt -->
        <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-200 text-[11px] text-slate-600 space-y-1">
            <span class="font-bold text-slate-800 block">Ketentuan Pemegang Surat Izin:</span>
            <p>1. Alat hanya dipergunakan untuk keperluan dinas/praktik SMK Negeri 5 Surakarta.</p>
            <p>2. Wajib dikembalikan sebelum batas waktu. Keterlambatan dikenai denda otomatis Rp {{ number_format($loan->tool->fine_per_day, 0, ',', '.') }} / hari / unit.</p>
            <p>3. Segala bentuk kerusakan atau kehilangan menjadi tanggung jawab peminjam untuk mengganti spesifikasi serupa.</p>
        </div>

        <!-- Signature Boxes -->
        <div class="pt-6 grid grid-cols-2 text-center text-xs">
            <div class="space-y-12">
                <div>
                    <span class="text-slate-400 block text-[10px]">Peminjam Alat:</span>
                    <p class="font-semibold text-slate-700">Surakarta, {{ date('d F Y') }}</p>
                </div>
                <div>
                    <p class="font-bold text-slate-900 underline">{{ $loan->user->name }}</p>
                    <p class="text-[10px] text-slate-500 font-mono">{{ $loan->user->identifier ?? '-' }}</p>
                </div>
            </div>

            <div class="space-y-12">
                <div>
                    <span class="text-slate-400 block text-[10px]">Menyetujui & Menyerahkan:</span>
                    <p class="font-semibold text-slate-700">PJ Kategori {{ $loan->tool->category->name }}</p>
                </div>
                <div>
                    <p class="font-bold text-slate-900 underline">{{ $loan->approver ? $loan->approver->name : $loan->tool->category->pj_name }}</p>
                    <p class="text-[10px] text-slate-500 font-mono">{{ $loan->approver ? $loan->approver->identifier : 'NIP. SMKN 5' }}</p>
                </div>
            </div>
        </div>

        <div class="text-center pt-4 border-t border-slate-100 text-[10px] text-slate-400 font-mono">
            Dicetak secara elektronik melalui SIPAS SMKN 5 Surakarta pada {{ date('d/m/Y H:i:s') }}
        </div>

    </div>
</body>
</html>
