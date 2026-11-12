@extends('layouts.app')

@section('title', 'Tambah Alat Praktik Baru - Panel Admin SIPAS')

@section('content')
<section class="py-10 bg-slate-50 min-h-screen">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        
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

            {{-- Validation error summary --}}
            @if($errors->any())
                <div class="mb-5 p-4 bg-rose-50 border border-rose-200 rounded-2xl">
                    <p class="text-xs font-bold text-rose-700 mb-1">⚠️ Mohon perbaiki kesalahan berikut:</p>
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach($errors->all() as $error)
                            <li class="text-[11px] text-rose-600">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.tools.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5 text-xs">
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

                <!-- Tool Image Upload -->
                <div class="space-y-2">
                    <label class="block font-bold text-slate-700">Foto / Gambar Alat Praktik: <span class="text-slate-400 font-normal">(Opsional)</span></label>
                    <div class="border-2 border-dashed border-slate-200 rounded-2xl p-4 bg-slate-50/50 hover:bg-slate-50 transition-colors">
                        <div class="flex flex-col sm:flex-row items-center gap-4">
                            <!-- Preview Box -->
                            <div id="imagePreviewContainer" class="w-24 h-24 rounded-2xl bg-slate-200 flex items-center justify-center overflow-hidden shrink-0 border border-slate-300 relative group">
                                <img id="imagePreview" src="" alt="Preview" class="w-full h-full object-cover hidden">
                                <div id="imagePlaceholder" class="flex flex-col items-center justify-center text-slate-400">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span class="text-[9px] font-semibold mt-1">Belum ada foto</span>
                                </div>
                            </div>

                            <!-- Upload Inputs -->
                            <div class="space-y-1.5 flex-1 w-full text-center sm:text-left">
                                <div class="flex items-center gap-2 justify-center sm:justify-start">
                                    <label for="toolImageInput" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-300 hover:border-slate-400 text-slate-700 rounded-xl font-bold cursor-pointer transition-all shadow-xs text-xs">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                        <span>Pilih File Gambar</span>
                                    </label>
                                    <button type="button" id="removeImageBtn" class="hidden text-xs text-rose-600 hover:text-rose-800 font-bold px-2 py-1">Batal</button>
                                </div>
                                <input type="file" id="toolImageInput" name="image" accept="image/jpeg,image/png,image/jpg,image/webp" class="hidden">
                                <p class="text-[11px] text-slate-500">
                                    Format: <strong>JPG, PNG, atau WebP</strong> (Maks. 5 MB). Gambar akan ditampilkan dengan rasio persegi (1:1) di katalog.
                                </p>
                            </div>
                        </div>
                    </div>
                    @error('image')
                        <p class="text-[11px] text-rose-600 font-semibold">{{ $message }}</p>
                    @enderror
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

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const input = document.getElementById('toolImageInput');
        const preview = document.getElementById('imagePreview');
        const placeholder = document.getElementById('imagePlaceholder');
        const removeBtn = document.getElementById('removeImageBtn');

        if (input) {
            input.addEventListener('change', (e) => {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (event) => {
                        preview.src = event.target.result;
                        preview.classList.remove('hidden');
                        placeholder.classList.add('hidden');
                        removeBtn.classList.remove('hidden');
                    };
                    reader.readAsDataURL(file);
                }
            });

            removeBtn.addEventListener('click', () => {
                input.value = '';
                preview.src = '';
                preview.classList.add('hidden');
                placeholder.classList.remove('hidden');
                removeBtn.classList.add('hidden');
            });
        }
    });
</script>
@endsection
