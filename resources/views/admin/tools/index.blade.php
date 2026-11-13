@extends('layouts.app')

@section('title', 'Kelola Alat Praktik Kejuruan - Panel Admin SIPAS')

@section('content')
<section class="py-4 sm:py-10 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 space-y-3.5 sm:space-y-6">
        
        <!-- Header & Breadcrumbs -->
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-3 sm:gap-4">
            <div>
                <nav class="flex items-center gap-1.5 sm:gap-2 text-[11px] sm:text-xs text-slate-400 mb-1 sm:mb-2">
                    <a href="{{ route('home') }}" class="hover:text-blue-900">Beranda</a>
                    <span>/</span>
                    <a href="{{ route('admin.loans.index') }}" class="hover:text-blue-900">Admin</a>
                    <span>/</span>
                    <span class="text-slate-700 font-semibold">Kelola Alat</span>
                </nav>
                <div class="flex items-center gap-2 sm:gap-3 flex-wrap">
                    <h1 class="text-xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Inventaris &amp; Alat Praktik</h1>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] sm:text-xs font-bold uppercase tracking-wider bg-slate-100 text-slate-800 border border-slate-200">
                        {{ $admin->class_or_dept ?? 'Petugas PJ' }}
                    </span>
                </div>
                <p class="text-slate-500 text-[11px] sm:text-sm mt-0.5 sm:mt-1 line-clamp-1 sm:line-clamp-none">
                    Kelola data master alat kejuruan, nomor seri unit fisik, kuantitas stok ready, dan penetapan denda harian.
                </p>
            </div>

            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('admin.tools.create') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 sm:px-4 sm:py-2.5 rounded-xl sm:rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-2xs hover:shadow-md transition-all shrink-0 whitespace-nowrap active:scale-95">
                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Tambah Alat Baru</span>
                </a>
            </div>
        </div>

        <!-- 4 Stats Cards (Sejajar 4 Kolom di Mobile) -->
        <div class="grid grid-cols-4 gap-1.5 sm:gap-4">
            <div class="bg-white p-2 sm:p-5 rounded-xl sm:rounded-3xl border border-slate-200 shadow-2xs text-center flex flex-col justify-between">
                <div>
                    <span class="text-base sm:text-2xl font-black text-slate-900 block leading-tight">{{ $totalTools }}</span>
                    <span class="text-[9.5px] sm:text-xs font-bold text-slate-700 block leading-tight mt-0.5">Jenis Alat</span>
                </div>
                <p class="text-[8px] sm:text-xs text-slate-400 mt-0.5 leading-tight">Master</p>
            </div>

            <div class="bg-white p-2 sm:p-5 rounded-xl sm:rounded-3xl border border-slate-200 shadow-2xs text-center flex flex-col justify-between">
                <div>
                    <span class="text-base sm:text-2xl font-black text-blue-600 block leading-tight">{{ $totalUnits }}</span>
                    <span class="text-[9.5px] sm:text-xs font-bold text-blue-900 block leading-tight mt-0.5">Total Unit</span>
                </div>
                <p class="text-[8px] sm:text-xs text-slate-400 mt-0.5 leading-tight">Terdaftar</p>
            </div>

            <div class="bg-white p-2 sm:p-5 rounded-xl sm:rounded-3xl border border-slate-200 shadow-2xs text-center flex flex-col justify-between">
                <div>
                    <span class="text-base sm:text-2xl font-black text-emerald-600 block leading-tight">{{ $readyUnits }}</span>
                    <span class="text-[9.5px] sm:text-xs font-bold text-emerald-800 block leading-tight mt-0.5">Siap Pakai</span>
                </div>
                <p class="text-[8px] sm:text-xs text-slate-400 mt-0.5 leading-tight">Ready</p>
            </div>

            <div class="bg-white p-2 sm:p-5 rounded-xl sm:rounded-3xl border border-slate-200 shadow-2xs text-center flex flex-col justify-between">
                <div>
                    <span class="text-base sm:text-2xl font-black text-amber-600 block leading-tight">{{ $borrowedUnits }}</span>
                    <span class="text-[9.5px] sm:text-xs font-bold text-amber-800 block leading-tight mt-0.5">Dipinjam</span>
                </div>
                <p class="text-[8px] sm:text-xs text-slate-400 mt-0.5 leading-tight">Aktif</p>
            </div>
        </div>

        <!-- Filter & Search Controls Bar (Ringkas Single-Line di Mobile) -->
        <div class="bg-white rounded-2xl sm:rounded-3xl border border-slate-200 p-2.5 sm:p-4 shadow-2xs space-y-2">
            <form action="{{ route('admin.tools.index') }}" method="GET" class="flex items-center gap-2">
                <div class="relative flex-grow">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama, kode, lokasi ruang..." 
                        class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition-all">
                </div>

                <button type="submit" class="py-2 px-3.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition-all shadow-2xs shrink-0 active:scale-95">
                    Cari
                </button>
                @if(request('q') || request('category'))
                    <a href="{{ route('admin.tools.index') }}" class="py-2 px-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-colors shrink-0 active:scale-95">
                        Reset
                    </a>
                @endif

                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
            </form>

            @if($categories->count() > 1)
                <div class="flex items-center gap-1.5 overflow-x-auto pb-0.5 text-xs">
                    <span class="text-slate-400 font-bold text-[11px] mr-0.5 shrink-0">Kategori:</span>
                    <a href="{{ route('admin.tools.index', ['q' => request('q')]) }}" 
                       class="px-2.5 py-1 rounded-lg text-[11px] font-bold transition-colors whitespace-nowrap {{ !request('category') || request('category') === 'all' ? 'bg-blue-600 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                        Semua
                    </a>
                    @foreach($categories as $cat)
                        <a href="{{ route('admin.tools.index', ['category' => $cat->slug, 'q' => request('q')]) }}" 
                           class="px-2.5 py-1 rounded-lg text-[11px] font-bold transition-colors whitespace-nowrap {{ request('category') === $cat->slug ? 'bg-blue-600 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                            {{ $cat->name }}
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Tools List / Grid -->
        @if($tools->isEmpty())
            <div class="bg-white rounded-2xl sm:rounded-3xl border border-dashed border-slate-300 p-8 sm:p-12 text-center space-y-3">
                <div class="w-12 h-12 sm:w-14 sm:h-14 bg-slate-100 text-slate-400 rounded-full flex items-center justify-center mx-auto">
                    <svg class="w-6 h-6 sm:w-7 sm:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <h3 class="text-sm sm:text-base font-bold text-slate-800">Belum Ada Alat Praktik Terdata</h3>
                <p class="text-xs text-slate-500 max-w-sm mx-auto">Mulai tambahkan alat praktik kejuruan baru untuk didata dan dapat dipinjam oleh siswa atau guru.</p>
                <div class="pt-2">
                    <a href="{{ route('admin.tools.create') }}" class="px-4 py-2 sm:px-5 sm:py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition-colors inline-block shadow-xs">
                        + Tambah Alat Pertama
                    </a>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 sm:gap-5">
                @foreach($tools as $tool)
                    <div class="bg-white rounded-2xl sm:rounded-3xl border border-slate-200 p-3.5 sm:p-6 shadow-2xs hover:border-slate-400 transition-all space-y-3 sm:space-y-4 flex flex-col justify-between">
                        
                        <!-- Top Info -->
                        <div class="space-y-2 sm:space-y-3">
                            <div class="flex items-start justify-between gap-2.5">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    @php
                                        $hasValidImage = !empty($tool->image) && \Illuminate\Support\Facades\Storage::disk('public')->exists($tool->image);
                                    @endphp
                                    @if($hasValidImage)
                                        <img src="{{ asset('storage/' . $tool->image) }}" alt="{{ $tool->name }}" class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl sm:rounded-2xl object-cover shrink-0 border border-slate-200 shadow-2xs" onerror="this.classList.add('hidden'); this.nextElementSibling?.classList.remove('hidden');">
                                    @endif
                                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl sm:rounded-2xl bg-blue-600 flex items-center justify-center text-white font-mono font-bold text-xs shadow-2xs shrink-0 {{ $hasValidImage ? 'hidden' : '' }}">
                                        {{ substr($tool->code, 0, 3) }}
                                    </div>
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-1.5 flex-wrap">
                                            <span class="text-[10px] sm:text-xs font-mono font-bold px-1.5 py-0.5 rounded bg-slate-100 text-slate-800 border border-slate-200">
                                                {{ $tool->code }}
                                            </span>
                                            <span class="text-[9.5px] sm:text-[10px] font-bold px-1.5 py-0.5 rounded-full bg-slate-100 text-slate-700 border border-slate-200">
                                                {{ $tool->category->name }}
                                            </span>
                                        </div>
                                        <h3 class="text-sm sm:text-base font-bold text-slate-900 mt-0.5 leading-snug truncate">{{ $tool->name }}</h3>
                                    </div>
                                </div>

                                <div class="text-right shrink-0">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 sm:px-3 sm:py-1 rounded-full text-[10px] sm:text-xs font-bold {{ $tool->available_stock > 0 ? 'bg-slate-100 text-slate-800 border border-slate-300' : 'bg-rose-100 text-rose-800' }}">
                                        {{ $tool->available_stock }} / {{ $tool->total_stock }} Ready
                                    </span>
                                </div>
                            </div>

                            <p class="text-[11px] sm:text-xs text-slate-500 line-clamp-1 sm:line-clamp-2">{{ $tool->spec }}</p>

                            <!-- Meta Specifications -->
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-1.5 pt-1.5 border-t border-slate-100 text-[10px] sm:text-[11px]">
                                <div>
                                    <span class="text-slate-400 block text-[9.5px]">Ruang:</span>
                                    <strong class="text-slate-700 truncate block">{{ $tool->location }}</strong>
                                </div>
                                <div>
                                    <span class="text-slate-400 block text-[9.5px]">Denda:</span>
                                    <strong class="text-slate-800">Rp {{ number_format($tool->fine_per_day, 0, ',', '.') }}/hr</strong>
                                </div>
                                <div>
                                    <span class="text-slate-400 block text-[9.5px]">Maks Pinjam:</span>
                                    <strong class="text-slate-700">{{ $tool->max_days }} Hari</strong>
                                </div>
                                <div>
                                    <span class="text-slate-400 block text-[9.5px]">PJ Kategori:</span>
                                    <strong class="text-slate-700 truncate block">{{ $tool->category->pj_name }}</strong>
                                </div>
                            </div>

                            <!-- Physical Units Preview -->
                            <div class="bg-slate-50 p-2.5 sm:p-3 rounded-xl sm:rounded-2xl border border-slate-200 space-y-1.5">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="font-bold text-slate-700 text-[11px] sm:text-xs">Daftar Unit Fisik ({{ $tool->units->count() }}):</span>
                                    <button type="button" 
                                            class="btn-open-unit-modal text-[10.5px] sm:text-[11px] font-bold text-blue-600 hover:text-blue-800 hover:underline flex items-center gap-1 cursor-pointer"
                                            data-tool-id="{{ $tool->id }}"
                                            data-tool-name="{{ $tool->name }}"
                                            data-tool-code="{{ $tool->code }}">
                                        <span>+ Kelola Unit</span>
                                    </button>
                                </div>
                                <div class="flex flex-wrap gap-1 max-h-20 overflow-y-auto">
                                    @foreach($tool->units as $u)
                                        <span class="px-1.5 py-0.5 rounded-md text-[9.5px] font-mono font-bold border {{ $u->status === 'tersedia' ? 'bg-white text-slate-800 border-slate-300' : ($u->status === 'dipinjam' ? 'bg-blue-50 text-blue-900 border-blue-200' : 'bg-rose-50 text-rose-800 border-rose-300') }}" title="Kondisi: {{ ucfirst($u->condition) }}">
                                            {{ $u->unit_code }} ({{ substr($u->status, 0, 4) }})
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Card Action Buttons -->
                        <div class="pt-2 sm:pt-3 border-t border-slate-100 flex items-center justify-end gap-2 text-xs">
                            <a href="{{ route('admin.tools.edit', $tool->id) }}" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition-colors inline-flex items-center gap-1 text-[11px] sm:text-xs active:scale-95">
                                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                <span>Edit</span>
                            </a>

                            <form action="{{ route('admin.tools.destroy', $tool->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus alat {{ $tool->name }}? Data unit fisik juga akan terhapus.');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold rounded-xl transition-colors inline-flex items-center gap-1 border border-rose-200 text-[11px] sm:text-xs cursor-pointer active:scale-95">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    <span>Hapus</span>
                                </button>
                            </form>
                        </div>

                    </div>
                @endforeach
            </div>

            <div class="pt-4">
                {{ $tools->links() }}
            </div>
        @endif

    </div>

    <!-- Modal Tambah Unit Fisik -->
    <div id="addUnitModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs hidden transition-opacity">
        <div class="bg-white rounded-3xl border border-slate-200 max-w-md w-full p-6 shadow-2xl space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div>
                    <h3 class="text-base font-extrabold text-slate-900">Tambah Unit Fisik Baru</h3>
                    <p id="modalToolTitle" class="text-xs text-slate-500"></p>
                </div>
                <button type="button" id="btnDismissUnitModal" class="text-slate-400 hover:text-slate-700 font-bold">&times;</button>
            </div>

            <form id="addUnitForm" method="POST" action="" class="space-y-3.5 text-xs">
                @csrf
                <div class="space-y-1">
                    <label class="block font-bold text-slate-700">Kode Unit Fisik (Opsional - otomatis jika kosong):</label>
                    <input type="text" name="unit_code" placeholder="Contoh: BKL-LAS-01-U03" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl font-mono focus:bg-white focus:ring-2 focus:ring-blue-900 focus:outline-none">
                </div>

                <div class="space-y-1">
                    <label class="block font-bold text-slate-700">Kondisi Awal Fisik:</label>
                    <select name="condition" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-900 focus:outline-none">
                        <option value="baik">Kondisi Baik &amp; Prima</option>
                        <option value="rusak ringan">Rusak Ringan (Perlu Pengecekan)</option>
                        <option value="rusak berat">Rusak Berat</option>
                    </select>
                </div>

                <div class="space-y-1">
                    <label class="block font-bold text-slate-700">Catatan / Serial Number Fisik (Opsional):</label>
                    <input type="text" name="notes" placeholder="Contoh: SN: 88921-X / Hibah 2026" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-900 focus:outline-none">
                </div>

                <div class="pt-3 flex items-center justify-end gap-2 border-t border-slate-100">
                    <button type="button" id="btnCancelAddUnit" class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-700 font-bold hover:bg-slate-50">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-800 text-white font-bold shadow-xs hover:shadow-md transition-all">
                        Simpan Unit Baru
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById('addUnitModal');
        const form = document.getElementById('addUnitForm');
        const toolTitle = document.getElementById('modalToolTitle');
        const btnDismiss = document.getElementById('btnDismissUnitModal');
        const btnCancel = document.getElementById('btnCancelAddUnit');

        document.querySelectorAll('.btn-open-unit-modal').forEach(button => {
            button.addEventListener('click', () => {
                const toolId = button.getAttribute('data-tool-id');
                const toolName = button.getAttribute('data-tool-name');
                const toolCode = button.getAttribute('data-tool-code');

                toolTitle.textContent = `${toolName} (${toolCode})`;
                form.setAttribute('action', `/admin/alat/${toolId}/unit`);

                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            });
        });

        function closeModal() {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }

        btnDismiss.addEventListener('click', closeModal);
        btnCancel.addEventListener('click', closeModal);

        modal.addEventListener('click', (e) => {
            if (e.target === modal) closeModal();
        });
    });
</script>
@endsection
