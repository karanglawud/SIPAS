@extends('layouts.app')

@section('title', 'Edit Alat - ' . $tool->name)

@section('content')
<section class="py-10 bg-slate-50 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
            <div>
                <nav class="flex items-center gap-2 text-xs text-slate-400 mb-2">
                    <a href="{{ route('home') }}" class="hover:text-blue-900">Beranda</a>
                    <span>/</span>
                    <a href="{{ route('admin.tools.index') }}" class="hover:text-blue-900">Kelola Alat</a>
                    <span>/</span>
                    <span class="text-slate-700 font-semibold">Edit Alat</span>
                </nav>
                <div class="flex items-center gap-3">
                    <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Edit Rincian Alat &amp; Unit Fisik</h1>
                    <span class="text-xs font-mono font-bold px-2.5 py-0.5 rounded-lg bg-slate-200 text-slate-700">
                        {{ $tool->code }}
                    </span>
                </div>
            </div>

            <a href="{{ route('admin.tools.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold text-xs transition-colors shrink-0">
                &larr; Kembali ke Daftar Alat
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            
            <!-- Left: Edit Tool Form -->
            <div class="lg:col-span-7 bg-white rounded-3xl border border-slate-200 p-6 shadow-xs space-y-4">
                <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-2">Informasi Master Alat</h3>

                <form action="{{ route('admin.tools.update', $tool->id) }}" method="POST" class="space-y-4 text-xs">
                    @csrf
                    @method('PUT')

                    <div class="space-y-1">
                        <label class="block font-bold text-slate-700">Kategori Alat: (Terkunci)</label>
                        <input type="text" disabled value="{{ $tool->category->name }}" class="w-full px-3.5 py-2 bg-slate-100 border border-slate-200 rounded-xl text-slate-500 font-bold">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div class="space-y-1">
                            <label class="block font-bold text-slate-700">Kode Alat: <span class="text-rose-500">*</span></label>
                            <input type="text" name="code" value="{{ old('code', $tool->code) }}" required 
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl font-mono font-bold focus:bg-white focus:ring-2 focus:ring-blue-900 focus:outline-none">
                            @error('code')
                                <p class="text-[11px] text-rose-600 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-2 space-y-1">
                            <label class="block font-bold text-slate-700">Nama Alat: <span class="text-rose-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $tool->name) }}" required 
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl font-bold focus:bg-white focus:ring-2 focus:ring-blue-900 focus:outline-none">
                            @error('name')
                                <p class="text-[11px] text-rose-600 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="block font-bold text-slate-700">Spesifikasi Alat:</label>
                        <textarea name="spec" rows="3" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-900 focus:outline-none">{{ old('spec', $tool->spec) }}</textarea>
                    </div>

                    <div class="space-y-1">
                        <label class="block font-bold text-slate-700">Lokasi Ruang Penyimpanan: <span class="text-rose-500">*</span></label>
                        <input type="text" name="location" value="{{ old('location', $tool->location) }}" required 
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl font-semibold focus:bg-white focus:ring-2 focus:ring-blue-900 focus:outline-none">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div class="space-y-1">
                            <label class="block font-bold text-slate-700">Tarif Denda (Rp/Hari): <span class="text-rose-500">*</span></label>
                            <input type="number" name="fine_per_day" value="{{ old('fine_per_day', $tool->fine_per_day) }}" min="0" step="500" required 
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl font-bold focus:bg-white focus:ring-2 focus:ring-blue-900 focus:outline-none">
                        </div>

                        <div class="space-y-1">
                            <label class="block font-bold text-slate-700">Maks Pinjam (Hari): <span class="text-rose-500">*</span></label>
                            <input type="number" name="max_days" value="{{ old('max_days', $tool->max_days) }}" min="1" max="30" required 
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl font-bold focus:bg-white focus:ring-2 focus:ring-blue-900 focus:outline-none">
                        </div>
                    </div>

                    <div class="pt-3 border-t border-slate-100 flex items-center justify-end">
                        <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-800 text-white font-bold rounded-xl shadow-xs hover:shadow-md transition-all">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

            <!-- Right: Manage Physical Units -->
            <div class="lg:col-span-5 space-y-4">
                <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-xs space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <div>
                            <h3 class="text-sm font-bold text-slate-900">Kelola Unit Fisik</h3>
                            <p class="text-[11px] text-slate-500">Stok: {{ $tool->available_stock }} Ready / {{ $tool->total_stock }} Total</p>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-800 border border-slate-200">
                            {{ $tool->units->count() }} Unit
                        </span>
                    </div>

                    <!-- Quick Add Unit -->
                    <form action="{{ route('admin.tools.units.store', $tool->id) }}" method="POST" class="p-3 bg-slate-50 rounded-2xl border border-slate-200 space-y-2 text-xs">
                        @csrf
                        <span class="font-bold text-slate-700 block text-[11px]">+ Tambah Unit Fisik Baru:</span>
                        <div class="flex gap-2">
                            <input type="text" name="unit_code" placeholder="Kode (Auto jika kosong)" class="flex-1 px-2.5 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-mono">
                            <select name="condition" class="px-2 py-1.5 bg-white border border-slate-200 rounded-lg text-[11px]">
                                <option value="baik">Kondisi Baik</option>
                                <option value="rusak ringan">Rusak Ringan</option>
                            </select>
                            <button type="submit" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-800 text-white font-bold rounded-lg text-xs shrink-0 shadow-xs">
                                Tambah
                            </button>
                        </div>
                    </form>

                    <!-- Units List -->
                    <div class="space-y-2 max-h-[420px] overflow-y-auto pr-1">
                        @foreach($tool->units as $unit)
                            <div class="p-3 rounded-2xl border border-slate-200 bg-slate-50/60 space-y-2 text-xs">
                                <div class="flex items-center justify-between">
                                    <span class="font-mono font-bold text-slate-800">{{ $unit->unit_code }}</span>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $unit->status === 'tersedia' ? 'bg-slate-100 text-slate-800 border border-slate-300' : ($unit->status === 'dipinjam' ? 'bg-blue-50 text-blue-900 border border-blue-200' : 'bg-rose-100 text-rose-800') }}">
                                        {{ $unit->status }}
                                    </span>
                                </div>

                                <form action="{{ route('admin.tools.units.update', $unit->id) }}" method="POST" class="grid grid-cols-2 gap-2 pt-1">
                                    @csrf
                                    @method('PATCH')
                                    <div>
                                        <label class="block text-[10px] text-slate-400">Status:</label>
                                        <select name="status" class="w-full px-2 py-1 bg-white border border-slate-200 rounded-lg text-[11px]">
                                            <option value="tersedia" {{ $unit->status === 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                                            <option value="rusak" {{ $unit->status === 'rusak' ? 'selected' : '' }}>Perbaikan / Rusak</option>
                                            <option value="hilang" {{ $unit->status === 'hilang' ? 'selected' : '' }}>Hilang</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] text-slate-400">Kondisi:</label>
                                        <select name="condition" class="w-full px-2 py-1 bg-white border border-slate-200 rounded-lg text-[11px]">
                                            <option value="baik" {{ $unit->condition === 'baik' ? 'selected' : '' }}>Baik</option>
                                            <option value="rusak ringan" {{ $unit->condition === 'rusak ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                                            <option value="rusak berat" {{ $unit->condition === 'rusak berat' ? 'selected' : '' }}>Rusak Berat</option>
                                        </select>
                                    </div>
                                    <div class="col-span-2 flex items-center justify-between pt-1">
                                        <span class="text-[10px] text-slate-400 truncate">{{ $unit->notes ?? '-' }}</span>
                                        <button type="submit" class="px-2.5 py-1 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-md font-bold text-[10px]">
                                            Update
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>

        </div>

    </div>
</section>
@endsection
