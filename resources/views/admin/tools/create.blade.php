@extends('layouts.app')

@section('title', 'Tambah Alat Praktik Baru - Panel Admin SIPAS')

@section('content')
<section class="py-10 bg-slate-50 min-h-screen">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        
        <div>
            <nav class="flex items-center gap-2 text-xs text-slate-400 mb-2">
                <a href="{{ route('home') }}" class="hover:text-blue-900">Beranda</a>
                <span>/</span>
                <a href="{{ route('admin.tools.index') }}" class="hover:text-blue-900">Kelola Alat</a>
                <span>/</span>
                <span class="text-slate-700 font-semibold">Tambah Alat Baru</span>
            </nav>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Tambah Alat Praktik Kejuruan</h1>
            <p class="text-xs text-slate-500 mt-1">
                Data alat dan kode unit fisik awal akan otomatis terdaftar dan dapat dipinjam setelah disimpan.
            </p>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-xs">
            <form action="{{ route('admin.tools.store') }}" method="POST" class="space-y-5 text-xs">
                @csrf

                <!-- Category -->
                <div class="space-y-1">
                    <label class="block font-bold text-slate-700">Kategori Alat Kejuruan: <span class="text-rose-500">*</span></label>
                    <select name="category_id" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold focus:bg-white focus:ring-2 focus:ring-blue-900 focus:outline-none">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }} (PJ: {{ $cat->pj_name }})
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="text-[11px] text-rose-600 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Code & Name -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div class="space-y-1">
                        <label class="block font-bold text-slate-700">Kode Alat: <span class="text-rose-500">*</span></label>
                        <input type="text" name="code" value="{{ old('code') }}" required placeholder="Contoh: BKL-LAS-02" 
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono font-bold uppercase focus:bg-white focus:ring-2 focus:ring-blue-900 focus:outline-none">
                        @error('code')
                            <p class="text-[11px] text-rose-600 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="sm:col-span-2 space-y-1">
                        <label class="block font-bold text-slate-700">Nama Lengkap Alat: <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Mesin Gerinda Tangan 4 Inch Bosch" 
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold focus:bg-white focus:ring-2 focus:ring-blue-900 focus:outline-none">
                        @error('name')
                            <p class="text-[11px] text-rose-600 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Spec -->
                <div class="space-y-1">
                    <label class="block font-bold text-slate-700">Spesifikasi &amp; Kelengkapan Alat:</label>
                    <textarea name="spec" rows="3" placeholder="Contoh: Daya 670 Watt, Kecepatan tanpa beban 11000 rpm, include handle tambahan & kunci mata..." 
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:ring-2 focus:ring-blue-900 focus:outline-none">{{ old('spec') }}</textarea>
                    @error('spec')
                        <p class="text-[11px] text-rose-600 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Location -->
                <div class="space-y-1">
                    <label class="block font-bold text-slate-700">Lokasi Ruang Penyimpanan: <span class="text-rose-500">*</span></label>
                    <input type="text" name="location" value="{{ old('location') }}" required placeholder="Contoh: Ruang Bengkel Mesin 1 Lemari B2" 
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:ring-2 focus:ring-blue-900 focus:outline-none">
                    @error('location')
                        <p class="text-[11px] text-rose-600 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Stock, Fine, Max Days -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div class="space-y-1">
                        <label class="block font-bold text-slate-700">Jumlah Unit Awal: <span class="text-rose-500">*</span></label>
                        <input type="number" name="total_stock" value="{{ old('total_stock', 1) }}" min="1" max="100" required 
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold focus:bg-white focus:ring-2 focus:ring-blue-900 focus:outline-none">
                        <p class="text-[10px] text-slate-400">Unit fisik otomatis tergenerate</p>
                        @error('total_stock')
                            <p class="text-[11px] text-rose-600 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-1">
                        <label class="block font-bold text-slate-700">Tarif Denda (Rp/Hari): <span class="text-rose-500">*</span></label>
                        <input type="number" name="fine_per_day" value="{{ old('fine_per_day', 5000) }}" min="0" step="500" required 
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold focus:bg-white focus:ring-2 focus:ring-blue-900 focus:outline-none">
                        @error('fine_per_day')
                            <p class="text-[11px] text-rose-600 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-1">
                        <label class="block font-bold text-slate-700">Maks Pinjam (Hari): <span class="text-rose-500">*</span></label>
                        <input type="number" name="max_days" value="{{ old('max_days', 3) }}" min="1" max="30" required 
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold focus:bg-white focus:ring-2 focus:ring-blue-900 focus:outline-none">
                        @error('max_days')
                            <p class="text-[11px] text-rose-600 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Theme / Banner Style -->
                <div class="space-y-1.5">
                    <label class="block font-bold text-slate-700">Tema Warna Solid:</label>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                        <label class="flex items-center gap-2 p-2 rounded-xl border border-slate-200 cursor-pointer hover:bg-slate-50 has-[:checked]:border-blue-900 has-[:checked]:bg-slate-100">
                            <input type="radio" name="image_gradient" value="from-blue-900 to-slate-900" checked class="text-blue-900">
                            <span class="w-4 h-4 rounded-full bg-blue-900 shrink-0"></span>
                            <span class="text-[11px] font-bold">Biru Tua</span>
                        </label>
                        <label class="flex items-center gap-2 p-2 rounded-xl border border-slate-200 cursor-pointer hover:bg-slate-50 has-[:checked]:border-blue-900 has-[:checked]:bg-slate-100">
                            <input type="radio" name="image_gradient" value="from-slate-800 to-slate-950" {{ old('image_gradient') === 'from-slate-800 to-slate-950' ? 'checked' : '' }} class="text-blue-900">
                            <span class="w-4 h-4 rounded-full bg-slate-800 shrink-0"></span>
                            <span class="text-[11px] font-bold">Hitam Slate</span>
                        </label>
                        <label class="flex items-center gap-2 p-2 rounded-xl border border-slate-200 cursor-pointer hover:bg-slate-50 has-[:checked]:border-blue-900 has-[:checked]:bg-slate-100">
                            <input type="radio" name="image_gradient" value="from-emerald-900 to-slate-900" {{ old('image_gradient') === 'from-emerald-900 to-slate-900' ? 'checked' : '' }} class="text-blue-900">
                            <span class="w-4 h-4 rounded-full bg-emerald-900 shrink-0"></span>
                            <span class="text-[11px] font-bold">Hijau Tua</span>
                        </label>
                        <label class="flex items-center gap-2 p-2 rounded-xl border border-slate-200 cursor-pointer hover:bg-slate-50 has-[:checked]:border-blue-900 has-[:checked]:bg-slate-100">
                            <input type="radio" name="image_gradient" value="from-rose-900 to-slate-900" {{ old('image_gradient') === 'from-rose-900 to-slate-900' ? 'checked' : '' }} class="text-blue-900">
                            <span class="w-4 h-4 rounded-full bg-rose-900 shrink-0"></span>
                            <span class="text-[11px] font-bold">Merah Tua</span>
                        </label>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                    <a href="{{ route('admin.tools.index') }}" class="px-4 py-2.5 rounded-xl border border-slate-200 font-bold text-slate-600 hover:bg-slate-50">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-800 text-white font-bold shadow-xs hover:shadow-md transition-all">
                        Simpan Alat Praktik
                    </button>
                </div>
            </form>
        </div>

    </div>
</section>
@endsection
