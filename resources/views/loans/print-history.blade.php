<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Riwayat Peminjaman - {{ $user->name }}</title>
    @vite(['resources/css/app.css'])
    <style>
        @media print {
            body { background: white !important; padding: 0 !important; }
            .no-print { display: none !important; }
            .page-break { page-break-after: always; }
        }
    </style>
</head>
<body class="bg-slate-100 p-4 sm:p-8 font-sans text-slate-900">

    <!-- Action Bar (Hidden when printed) -->
    <div class="max-w-5xl mx-auto mb-6 flex items-center justify-between no-print">
        <a href="{{ route('loans.index') }}" class="px-4 py-2 bg-white border border-slate-300 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-50 transition-colors">
            &larr; Kembali ke Riwayat
        </a>
        <button type="button" onclick="window.print()" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-md transition-colors flex items-center gap-2 cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            <span>Cetak / Simpan PDF</span>
        </button>
    </div>

    <!-- Official Paper Sheet -->
    <div class="max-w-5xl mx-auto bg-white border border-slate-300 p-8 sm:p-12 shadow-sm rounded-2xl text-slate-800 space-y-6">
        
        <!-- Kop Surat Sekolah -->
        <div class="flex items-center gap-4 border-b-2 border-slate-900 pb-4 text-center">
            <div class="w-16 h-16 rounded-2xl bg-blue-700 flex items-center justify-center text-white font-black text-2xl shrink-0">
                5
            </div>
            <div class="flex-grow">
                <span class="text-xs uppercase tracking-widest font-bold text-slate-500">Pemerintah Provinsi Jawa Tengah • Dinas Pendidikan &amp; Kebudayaan</span>
                <h1 class="text-xl sm:text-2xl font-black tracking-tight text-slate-900 uppercase">SMK NEGERI 5 SURAKARTA</h1>
                <p class="text-xs text-slate-600">Jl. Adi Sucipto No. 42, Kerten, Kec. Laweyan, Kota Surakarta, Jawa Tengah 57143</p>
                <p class="text-[10px] text-slate-500 font-mono mt-0.5">SISTEM INFORMASI PEMINJAMAN ALAT SEKOLAH (SIPAS) — DOKUMEN RESMI</p>
            </div>
        </div>

        <!-- Document Title -->
        <div class="text-center space-y-1">
            <h2 class="text-base font-extrabold uppercase tracking-wider underline">REKAPITULASI RIWAYAT PEMINJAMAN ALAT</h2>
            <p class="text-xs text-slate-500">Rekap data transaksi peminjaman sarana dan alat praktik sekolah</p>
        </div>

        <!-- Identity Information -->
        <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 text-xs">
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div>
                    <span class="text-slate-500 block text-[10px] uppercase font-bold">Nama Peminjam:</span>
                    <span class="font-bold text-slate-900 text-sm">{{ $user->name }}</span>
                    <span class="text-[10px] text-slate-500 block capitalize">({{ $user->role }})</span>
                </div>
                <div>
                    <span class="text-slate-500 block text-[10px] uppercase font-bold">{{ $user->isGuru() ? 'NIP Guru:' : 'NISN Siswa:' }}</span>
                    <span class="font-mono font-bold text-slate-800">{{ $user->identifier ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-slate-500 block text-[10px] uppercase font-bold">Kelas / Bidang:</span>
                    <span class="font-semibold text-slate-800">{{ $user->class_or_dept ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-slate-500 block text-[10px] uppercase font-bold">Tanggal Cetak:</span>
                    <span class="font-semibold text-slate-800">{{ now()->translatedFormat('d F Y, H:i') }} WIB</span>
                </div>
            </div>
        </div>

        <!-- Summary KPIs -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
            <div class="bg-slate-50 p-3 rounded-xl border border-slate-200 text-center">
                <span class="text-[10px] font-bold uppercase text-slate-500 block">Total Transaksi</span>
                <span class="text-lg font-black text-slate-800">{{ $stats['total'] }}</span>
            </div>
            <div class="bg-slate-50 p-3 rounded-xl border border-slate-200 text-center">
                <span class="text-[10px] font-bold uppercase text-slate-500 block">Sedang Dipinjam</span>
                <span class="text-lg font-black text-blue-700">{{ $stats['berjalan'] }}</span>
            </div>
            <div class="bg-slate-50 p-3 rounded-xl border border-slate-200 text-center">
                <span class="text-[10px] font-bold uppercase text-slate-500 block">Selesai / Kembali</span>
                <span class="text-lg font-black text-emerald-700">{{ $stats['selesai'] }}</span>
            </div>
            <div class="bg-slate-50 p-3 rounded-xl border border-slate-200 text-center">
                <span class="text-[10px] font-bold uppercase text-slate-500 block">Total Denda</span>
                <span class="text-lg font-black {{ $stats['total_denda'] > 0 ? 'text-rose-600' : 'text-slate-700' }}">
                    Rp {{ number_format($stats['total_denda'], 0, ',', '.') }}
                </span>
            </div>
        </div>

        <!-- Loans Table -->
        <div class="border border-slate-300 rounded-xl overflow-hidden text-xs">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-100 border-b border-slate-300 text-[11px] font-bold text-slate-700">
                        <th class="py-2.5 px-3 text-center w-8 border-r border-slate-300">No</th>
                        <th class="py-2.5 px-3 border-r border-slate-300">No. Peminjaman</th>
                        <th class="py-2.5 px-3 border-r border-slate-300">Alat Praktik &amp; Kategori</th>
                        <th class="py-2.5 px-3 text-center border-r border-slate-300">Qty</th>
                        <th class="py-2.5 px-3 border-r border-slate-300">Unit Ter-assign</th>
                        <th class="py-2.5 px-3 border-r border-slate-300">Jadwal Pinjam</th>
                        <th class="py-2.5 px-3 text-center border-r border-slate-300">Status</th>
                        <th class="py-2.5 px-3 text-right">Denda</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($loans as $index => $item)
                        <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-slate-50/60' }}">
                            <td class="py-2.5 px-3 text-center font-bold text-slate-500 border-r border-slate-200">{{ $index + 1 }}</td>
                            <td class="py-2.5 px-3 font-mono font-bold text-blue-900 border-r border-slate-200 whitespace-nowrap">
                                {{ $item->ticket_number }}
                                <span class="block text-[9px] text-slate-400 font-sans">{{ $item->created_at->format('d/m/Y') }}</span>
                            </td>
                            <td class="py-2.5 px-3 border-r border-slate-200">
                                <span class="font-bold text-slate-900 block leading-tight">{{ $item->tool->name }}</span>
                                <span class="text-[10px] text-slate-500">{{ $item->tool->category->name }} ({{ $item->tool->code }})</span>
                            </td>
                            <td class="py-2.5 px-3 text-center font-bold border-r border-slate-200">{{ $item->quantity }}</td>
                            <td class="py-2.5 px-3 font-mono text-[11px] border-r border-slate-200">
                                @if(!empty($item->assigned_units))
                                    {{ implode(', ', $item->assigned_units) }}
                                @else
                                    <span class="text-slate-400 italic">-</span>
                                @endif
                            </td>
                            <td class="py-2.5 px-3 border-r border-slate-200 whitespace-nowrap text-[11px]">
                                {{ $item->start_date->format('d/m/Y') }} - {{ $item->end_date->format('d/m/Y') }}
                                <span class="text-[10px] text-slate-500 block">({{ $item->duration_days }} Hari)</span>
                            </td>
                            <td class="py-2.5 px-3 text-center border-r border-slate-200">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $item->status === 'selesai' ? 'bg-emerald-100 text-emerald-800' : ($item->status === 'dipinjam' ? 'bg-blue-100 text-blue-800' : ($item->status === 'disetujui' ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-700')) }}">
                                    {{ $item->status }}
                                </span>
                            </td>
                            <td class="py-2.5 px-3 text-right font-mono font-bold {{ $item->fine_amount > 0 ? 'text-rose-600' : 'text-slate-600' }}">
                                {{ $item->fine_amount > 0 ? 'Rp ' . number_format($item->fine_amount, 0, ',', '.') : '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-slate-400 italic">
                                Belum ada riwayat transaksi peminjaman alat yang tercatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="bg-slate-100 border-t-2 border-slate-300 font-bold text-slate-800 text-[11px]">
                        <td colspan="3" class="py-2.5 px-3 text-right uppercase">Total Rekapitulasi:</td>
                        <td class="py-2.5 px-3 text-center">{{ $loans->sum('quantity') }} Unit</td>
                        <td colspan="3"></td>
                        <td class="py-2.5 px-3 text-right font-mono text-rose-600">
                            Rp {{ number_format($stats['total_denda'], 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Notes / Terms -->
        <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-200 text-[11px] text-slate-600 space-y-1">
            <span class="font-bold text-slate-800 block">Catatan &amp; Ketentuan Sekolah:</span>
            <p>1. Rekapitulasi ini merupakan catatan resmi aktivitas peminjaman sarana dan alat praktik di lingkungan SMK Negeri 5 Surakarta.</p>
            <p>2. Segala status denda yang belum terselesaikan wajib dilunasi melalui Petugas Penanggung Jawab (PJ) alat masing-masing bengkel/lab.</p>
        </div>

        <!-- Signature Boxes -->
        <div class="pt-6 grid grid-cols-2 text-center text-xs">
            <div class="space-y-12">
                <div>
                    <span class="text-slate-400 block text-[10px]">Peminjam / Pemegang Dokumen:</span>
                    <p class="font-semibold text-slate-700">Surakarta, {{ now()->translatedFormat('d F Y') }}</p>
                </div>
                <div>
                    <p class="font-bold text-slate-900 underline">{{ $user->name }}</p>
                    <p class="text-[10px] text-slate-500 font-mono">{{ $user->identifier ?? '-' }}</p>
                </div>
            </div>

            <div class="space-y-12">
                <div>
                    <span class="text-slate-400 block text-[10px]">Mengetahui:</span>
                    <p class="font-semibold text-slate-700">Koordinator Sarana &amp; Prasarana</p>
                </div>
                <div>
                    <p class="font-bold text-slate-900 underline">Tim Pengelola Sarana SMKN 5</p>
                    <p class="text-[10px] text-slate-500 font-mono">NIP. 197805122006041002</p>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center pt-4 border-t border-slate-100 text-[10px] text-slate-400 font-mono">
            Dicetak secara elektronik melalui SIPAS SMKN 5 Surakarta pada {{ now()->format('d/m/Y H:i:s') }}
        </div>

    </div>
</body>
</html>
