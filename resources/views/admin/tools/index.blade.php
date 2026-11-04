@extends('layouts.app')

@section('title', 'Kelola Alat Praktik Kejuruan - Panel Admin SIPAS')

@section('content')
<section class="py-10 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Header & Breadcrumbs -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <nav class="flex items-center gap-2 text-xs text-slate-400 mb-2">
                    <a href="{{ route('home') }}" class="hover:text-blue-900">Beranda</a>
                    <span>/</span>
                    <a href="{{ route('admin.loans.index') }}" class="hover:text-blue-900">Admin</a>
                    <span>/</span>
                    <span class="text-slate-700 font-semibold">Kelola Alat Praktik</span>
                </nav>
                <div class="flex items-center gap-3">
                    <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Inventaris &amp; Alat Praktik</h1>
                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-slate-100 text-slate-800 border border-slate-200">
                        {{ $admin->class_or_dept ?? 'Petugas PJ' }}
                    </span>
                </div>
                <p class="text-slate-500 text-xs sm:text-sm mt-1">
                    Kelola data master alat kejuruan, nomor seri unit fisik, kuantitas stok ready, dan penetapan denda harian.
                </p>
            </div>

            <div class="flex items-center gap-2.5">
                <a href="{{ route('admin.tools.create') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-blue-600 hover:bg-blue-800 text-white font-bold text-xs shadow-xs transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Tambah Alat Baru</span>
                </a>
            </div>
        </div>

        <!-- 4 Stats Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-xs space-y-1">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Jenis Alat</span>
                <div class="flex items-baseline gap-2">
                    <span class="text-2xl font-black text-slate-900">{{ $totalTools }}</span>
                    <span class="text-xs text-slate-500">Master Item</span>
                </div>
            </div>

            <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-xs space-y-1">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Unit Fisik</span>
                <div class="flex items-baseline gap-2">
                    <span class="text-2xl font-black text-blue-600">{{ $totalUnits }}</span>
                    <span class="text-xs text-slate-500">Unit Terdaftar</span>
                </div>
            </div>

            <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-xs space-y-1">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Unit Siap Pakai</span>
                <div class="flex items-baseline gap-2">
                    <span class="text-2xl font-black text-slate-800">{{ $readyUnits }}</span>
                    <span class="text-xs text-slate-600 font-semibold">Tersedia</span>
                </div>
            </div>

            <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-xs space-y-1">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Unit Keluar / Dipinjam</span>
                <div class="flex items-baseline gap-2">
                    <span class="text-2xl font-black text-slate-800">{{ $borrowedUnits }}</span>
                    <span class="text-xs text-slate-500">Dipinjam</span>
                </div>
            </div>
        </div>

        <!-- Filter & Search Controls Bar -->
        <div class="bg-white rounded-3xl border border-slate-200 p-4 sm:p-5 shadow-xs space-y-3">
            <form action="{{ route('admin.tools.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center">
                <div class="md:col-span-9 relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama alat, kode inventaris, atau lokasi ruang..." 
                        class="w-full pl-11 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-blue-900 focus:bg-white transition-all">
                </div>

                <div class="md:col-span-3 flex items-center gap-2">
                    <button type="submit" class="flex-1 py-2.5 px-4 bg-blue-600 hover:bg-blue-800 text-white rounded-2xl text-xs font-bold transition-all shadow-xs hover:shadow-md">
                        Filter Alat
                    </button>
                    @if(request('q') || request('category'))
                        <a href="{{ route('admin.tools.index') }}" class="py-2.5 px-4 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-2xl text-xs font-bold transition-colors">
                            Reset
                        </a>
                    @endif
                </div>

                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
            </form>

            @if($categories->count() > 1)
                <div class="flex flex-wrap items-center gap-2 pt-2 border-t border-slate-100 text-xs">
                    <span class="text-slate-400 font-bold mr-1">Kategori:</span>
                    <a href="{{ route('admin.tools.index', ['q' => request('q')]) }}" 
                       class="px-3 py-1 rounded-xl font-bold transition-colors {{ !request('category') || request('category') === 'all' ? 'bg-blue-900 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                        Semua Kategori
                    </a>
                    @foreach($categories as $cat)
                        <a href="{{ route('admin.tools.index', ['category' => $cat->slug, 'q' => request('q')]) }}" 
                           class="px-3 py-1 rounded-xl font-bold transition-colors {{ request('category') === $cat->slug ? 'bg-blue-900 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                            {{ $cat->name }}
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Tools List / Grid -->
        @if($tools->isEmpty())
            <div class="bg-white rounded-3xl border border-dashed border-slate-300 p-12 text-center space-y-3">
                <div class="w-14 h-14 bg-slate-100 text-slate-400 rounded-full flex items-center justify-center mx-auto">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <h3 class="text-base font-bold text-slate-800">Belum Ada Alat Praktik Terdata</h3>
                <p class="text-xs text-slate-500 max-w-sm mx-auto">Mulai tambahkan alat praktik kejuruan baru untuk didata dan dapat dipinjam oleh siswa atau guru.</p>
                <div class="pt-2">
                    <a href="{{ route('admin.tools.create') }}" class="px-5 py-2.5 bg-blue-900 hover:bg-blue-950 text-white rounded-xl text-xs font-bold transition-colors inline-block">
                        + Tambah Alat Pertama
                    </a>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
                @foreach($tools as $tool)
                    <div class="bg-white rounded-3xl border border-slate-200 p-5 sm:p-6 shadow-xs hover:border-slate-400 transition-all space-y-4 flex flex-col justify-between">
                        
                        <!-- Top Info -->
                        <div class="space-y-3">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-center gap-3">
                                    @if($tool->image)
                                        <img src="{{ asset('storage/' . $tool->image) }}" alt="{{ $tool->name }}" class="w-12 h-12 rounded-2xl object-cover shrink-0 border border-slate-200 shadow-2xs">
                                    @else
                                        <div class="w-12 h-12 rounded-2xl bg-blue-900 flex items-center justify-center text-white font-mono font-bold text-xs shadow-2xs shrink-0">
                                            {{ substr($tool->code, 0, 3) }}
                                        </div>
                                    @endif
                                    <div>
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <span class="text-xs font-mono font-bold px-2 py-0.5 rounded-lg bg-slate-100 text-slate-800 border border-slate-200">
                                                {{ $tool->code }}
                                            </span>
                                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-slate-100 text-slate-700 border border-slate-200">
                                                {{ $tool->category->name }}
                                            </span>
                                        </div>
                                        <h3 class="text-base font-bold text-slate-900 mt-1 leading-snug">{{ $tool->name }}</h3>
                                    </div>
                                </div>

                                <div class="text-right shrink-0">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold {{ $tool->available_stock > 0 ? 'bg-slate-100 text-slate-800 border border-slate-300' : 'bg-rose-100 text-rose-800' }}">
                                        {{ $tool->available_stock }} / {{ $tool->total_stock }} Ready
                                    </span>
                                </div>
                            </div>

                            <p class="text-xs text-slate-500 line-clamp-2">{{ $tool->spec }}</p>

                            <!-- Meta Specifications -->
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 pt-2 border-t border-slate-100 text-[11px]">
                                <div>
                                    <span class="text-slate-400 block text-[10px]">Lokasi Ruang:</span>
                                    <strong class="text-slate-700">{{ $tool->location }}</strong>
                                </div>
                                <div>
                                    <span class="text-slate-400 block text-[10px]">Denda / Hari:</span>
                                    <strong class="text-slate-800">Rp {{ number_format($tool->fine_per_day, 0, ',', '.') }}</strong>
                                </div>
                                <div>
                                    <span class="text-slate-400 block text-[10px]">Maks Pinjam:</span>
                                    <strong class="text-slate-700">{{ $tool->max_days }} Hari</strong>
                                </div>
                                <div>
                                    <span class="text-slate-400 block text-[10px]">PJ Kategori:</span>
                                    <strong class="text-slate-700 truncate block">{{ $tool->category->pj_name }}</strong>
                                </div>
                            </div>

                            <!-- Physical Units Preview -->
                            <div class="bg-slate-50 p-3 rounded-2xl border border-slate-200 space-y-2">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="font-bold text-slate-700">Daftar Unit Fisik ({{ $tool->units->count() }} Unit):</span>
                                    <button type="button" 
                                            class="btn-open-unit-modal text-[11px] font-bold text-blue-900 hover:text-blue-950 hover:underline flex items-center gap-1 cursor-pointer"
                                            data-tool-id="{{ $tool->id }}"
                                            data-tool-name="{{ $tool->name }}"
                                            data-tool-code="{{ $tool->code }}">
                                        <span>+ Kelola Unit</span>
                                    </button>
                                </div>
                                <div class="flex flex-wrap gap-1.5 max-h-24 overflow-y-auto">
                                    @foreach($tool->units as $u)
                                        <span class="px-2 py-0.5 rounded-lg text-[10px] font-mono font-bold border {{ $u->status === 'tersedia' ? 'bg-slate-100 text-slate-800 border-slate-300' : ($u->status === 'dipinjam' ? 'bg-blue-50 text-blue-900 border-blue-200' : 'bg-rose-50 text-rose-800 border-rose-300') }}" title="Kondisi: {{ ucfirst($u->condition) }}">
                                            {{ $u->unit_code }} ({{ substr($u->status, 0, 4) }})
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Card Action Buttons -->
                        <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2 text-xs">
                            <a href="{{ route('admin.tools.edit', $tool->id) }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition-colors inline-flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                <span>Edit Rincian</span>
                            </a>

                            <form action="{{ route('admin.tools.destroy', $tool->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus alat {{ $tool->name }}? Data unit fisik juga akan terhapus.');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3.5 py-2 bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold rounded-xl transition-colors inline-flex items-center gap-1 border border-rose-200">
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
                    <button type="button" id="btnCancelAddUnit" class="px-4 py-2 rounded-xl border border-slate-200 text-slate-700 font-bold hover:bg-slate-50">
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
