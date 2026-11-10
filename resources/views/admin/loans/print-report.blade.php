<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Rekap Peminjaman Alat - Panel Admin SIPAS</title>
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
    <div class="max-w-6xl mx-auto mb-6 flex items-center justify-between no-print">
        <a href="{{ $backRoute ?? route('admin.loans.index') }}" class="px-4 py-2 bg-white border border-slate-300 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-50 transition-colors">
            &larr; {{ $backLabel ?? 'Kembali ke Panel Admin' }}
        </a>
        <button type="button" onclick="window.print()" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-md transition-colors flex items-center gap-2 cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            <span>Cetak / Simpan PDF</span>
        </button>
    </div>

    <!-- Official Paper Sheet -->
    <div class="max-w-6xl mx-auto bg-white border border-slate-300 p-8 sm:p-12 shadow-sm rounded-2xl text-slate-800 space-y-6">
        
        <!-- Kop Surat Sekolah -->
        <div class="flex items-center gap-4 border-b-2 border-slate-900 pb-4 text-center">
            <div class="w-16 h-16 rounded-2xl bg-blue-700 flex items-center justify-center text-white font-black text-2xl shrink-0">
                <img src="{{ asset('storage/images/1.png') }}" alt="Logo SIPAS" class="w-full h-full rounded-xl object-contain">
            </div>
            <div class="flex-grow">
                <span class="text-xs uppercase tracking-widest font-bold text-slate-500">Pemerintah Provinsi Jawa Tengah • Dinas Pendidikan &amp; Kebudayaan</span>
                <h1 class="text-xl sm:text-2xl font-black tracking-tight text-slate-900 uppercase">SMK NEGERI 5 SURAKARTA</h1>
                <p class="text-xs text-slate-600">Jl. Adi Sucipto No. 42, Kerten, Kec. Laweyan, Kota Surakarta, Jawa Tengah 57143</p>
                <p class="text-[10px] text-slate-500 font-mono mt-0.5">SISTEM INFORMASI PEMINJAMAN ALAT SEKOLAH (SIPAS) — LAPORAN RESMI</p>
            </div>
        </div>

        <!-- Document Title -->
        <div class="text-center space-y-1">
            <h2 class="text-base font-extrabold uppercase tracking-wider underline">LAPORAN REKAPITULASI TRANSAKSI PEMINJAMAN ALAT</h2>
            <p class="text-xs text-slate-500">
                Kategori Scope: <strong>{{ $admin->category_scope ? ucfirst($admin->category_scope) : 'Semua Kategori' }}</strong> 
                • Filter Status: <strong>{{ ucfirst($statusTab) }}</strong>
            </p>
        </div>

        <!-- Metadata Box -->
        <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 text-xs">
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div>
                    <span class="text-slate-500 block text-[10px] uppercase font-bold">Petugas Admin / PJ:</span>
                    <span class="font-bold text-slate-900 text-sm">{{ $admin->name }}</span>
                    <span class="text-[10px] text-slate-500 block">{{ $admin->class_or_dept ?? 'PJ Sarana' }}</span>
                </div>
                <div>
                    <span class="text-slate-500 block text-[10px] uppercase font-bold">NIP Petugas:</span>
                    <span class="font-mono font-bold text-slate-800">{{ $admin->identifier ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-slate-500 block text-[10px] uppercase font-bold">Jumlah Transaksi:</span>
                    <span class="font-bold text-blue-900">{{ $stats['total'] }} Peminjaman</span>
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
                <span class="text-[10px] font-bold uppercase text-slate-500 block">Menunggu Konfirmasi</span>
                <span class="text-lg font-black text-amber-600">{{ $stats['menunggu'] }}</span>
            </div>
            <div class="bg-slate-50 p-3 rounded-xl border border-slate-200 text-center">
                <span class="text-[10px] font-bold uppercase text-slate-500 block">Sedang Dipinjam / Aktif</span>
                <span class="text-lg font-black text-blue-700">{{ $stats['berjalan'] }}</span>
            </div>
            <div class="bg-slate-50 p-3 rounded-xl border border-slate-200 text-center">
                <span class="text-[10px] font-bold uppercase text-slate-500 block">Selesai / Dikembalikan</span>
                <span class="text-lg font-black text-emerald-700">{{ $stats['selesai'] }}</span>
            </div>
            <div class="bg-slate-50 p-3 rounded-xl border border-slate-200 text-center">
                <span class="text-[10px] font-bold uppercase text-slate-500 block">Total Denda Terdata</span>
                <span class="text-lg font-black {{ $stats['total_denda'] > 0 ? 'text-rose-600' : 'text-slate-700' }}">
                    Rp {{ number_format($stats['total_denda'], 0, ',', '.') }}
                </span>
            </div>
        </div>

        <!-- Table of Loans -->
        <div class="border border-slate-300 rounded-xl overflow-hidden text-xs">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-100 border-b border-slate-300 text-[11px] font-bold text-slate-700">
                        <th class="py-2.5 px-3 text-center w-8 border-r border-slate-300">No</th>
                        <th class="py-2.5 px-3 border-r border-slate-300">No. Peminjaman</th>
                        <th class="py-2.5 px-3 border-r border-slate-300">Peminjam</th>
                        <th class="py-2.5 px-3 border-r border-slate-300">Alat Praktik</th>
                        <th class="py-2.5 px-3 text-center border-r border-slate-300">Qty</th>
                        <th class="py-2.5 px-3 border-r border-slate-300">Unit Fisik</th>
                        <th class="py-2.5 px-3 border-r border-slate-300">Durasi Pinjam</th>
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
                                <span class="font-bold text-slate-900 block leading-tight">{{ $item->user->name }}</span>
                                <span class="text-[10px] text-slate-500">{{ $item->user->identifier ?? '-' }} • {{ $item->user->class_or_dept ?? '-' }}</span>
                            </td>
                            <td class="py-2.5 px-3 border-r border-slate-200">
                                <span class="font-bold text-slate-900 block leading-tight">{{ $item->tool->name }}</span>
                                <span class="text-[10px] text-slate-500">{{ $item->tool->category->name }}</span>
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
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $item->status === 'selesai' ? 'bg-emerald-100 text-emerald-800' : ($item->status === 'dipinjam' ? 'bg-blue-100 text-blue-800' : ($item->status === 'disetujui' ? 'bg-amber-100 text-amber-800' : ($item->status === 'ditolak' ? 'bg-rose-100 text-rose-800' : 'bg-slate-100 text-slate-700'))) }}">
                                    {{ $item->status }}
                                </span>
                            </td>
                            <td class="py-2.5 px-3 text-right font-mono font-bold {{ $item->fine_amount > 0 ? 'text-rose-600' : 'text-slate-600' }}">
                                {{ $item->fine_amount > 0 ? 'Rp ' . number_format($item->fine_amount, 0, ',', '.') : '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-8 text-center text-slate-400 italic">
                                Tidak ada data peminjaman yang sesuai dengan kriteria filter saat ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="bg-slate-100 border-t-2 border-slate-300 font-bold text-slate-800 text-[11px]">
                        <td colspan="4" class="py-2.5 px-3 text-right uppercase">Total Rekapitulasi:</td>
                        <td class="py-2.5 px-3 text-center">{{ $loans->sum('quantity') }} Unit</td>
                        <td colspan="3"></td>
                        <td class="py-2.5 px-3 text-right font-mono text-rose-600">
                            Rp {{ number_format($stats['total_denda'], 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Signature Boxes -->
        <div class="pt-6 grid grid-cols-2 text-center text-xs">
            <div class="space-y-12">
                <div>
                    <span class="text-slate-400 block text-[10px]">Dibuat &amp; Dilaporkan Oleh:</span>
                    <p class="font-semibold text-slate-700">Surakarta, {{ now()->translatedFormat('d F Y') }}</p>
                </div>
                <div>
                    <p class="font-bold text-slate-900 underline">{{ $admin->name }}</p>
                    <p class="text-[10px] text-slate-500 font-mono">NIP. {{ $admin->identifier ?? '-' }}</p>
                </div>
            </div>

            <div class="space-y-12">
                <div>
                    <span class="text-slate-400 block text-[10px]">Mengetahui:</span>
                    <p class="font-semibold text-slate-700">Kepala Bagian Sarana &amp; Prasarana</p>
                </div>
                <div>
                    <p class="font-bold text-slate-900 underline">Koordinator Sarpras SMKN 5 Surakarta</p>
                    <p class="text-[10px] text-slate-500 font-mono">NIP. 197509142002121003</p>
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
