@extends('layouts.app')

@section('title', 'Lupa Kata Sandi - SIPAS')

@section('content')
<section class="py-16 bg-slate-50 min-h-[75vh] flex items-center justify-center">
    <div class="max-w-md w-full mx-auto px-4 space-y-6">
        
        <div class="text-center space-y-2">
            <div class="w-12 h-12 rounded-2xl bg-blue-600 text-white flex items-center justify-center font-black text-xl mx-auto shadow-xs border border-blue-500">
               <img src="{{ asset('storage/images/1.png') }}" alt="Logo SIPAS" class="w-full h-full rounded-xl object-contain">
            </div>
            <h1 class="text-2xl font-extrabold text-slate-900">Reset Kata Sandi</h1>
            <p class="text-xs text-slate-500">Verifikasi identitas akun Anda dengan NISN (Siswa) atau NIP (Guru)</p>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-xs space-y-5">
            
            {{-- Global verification error --}}
            @error('identifier')
                <div class="p-3.5 bg-rose-50 border border-rose-200 rounded-2xl">
                    <p class="text-xs font-bold text-rose-700 mb-0.5">⚠️ Verifikasi Gagal</p>
                    <p class="text-[11px] text-rose-600 leading-relaxed">{{ $message }}</p>
                </div>
            @enderror

            <form action="{{ route('password.reset.identifier') }}" method="POST" class="space-y-4">
                @csrf

                <div class="space-y-1">
                    <label class="block text-xs font-bold text-slate-700">Alamat Email Terdaftar: <span class="text-rose-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="nama@smkn5solo.sch.id"
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none">
                    @error('email')
                        <p class="text-[11px] text-rose-600 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-1">
                    <label class="block text-xs font-bold text-slate-700">NISN Siswa / NIP Guru: <span class="text-rose-500">*</span></label>
                    <input type="text" name="identifier" value="{{ old('identifier') }}" required placeholder="Contoh: 0081234567 atau 19850110..."
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none">
                    <p class="text-[10px] text-slate-400">Nomor induk resmi yang Anda gunakan saat pertama kali mendaftar.</p>
                </div>

                <div class="pt-2 border-t border-slate-100 space-y-3">
                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-slate-700">Kata Sandi Baru: <span class="text-rose-500">*</span></label>
                        <input type="password" name="password" required placeholder="Minimal 6 karakter"
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none">
                        @error('password')
                            <p class="text-[11px] text-rose-600 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-slate-700">Ulangi Kata Sandi Baru: <span class="text-rose-500">*</span></label>
                        <input type="password" name="password_confirmation" required placeholder="Ketik ulang kata sandi baru"
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none">
                    </div>
                </div>

                <button type="submit" class="w-full py-2.5 bg-blue-600 hover:bg-blue-800 text-white rounded-xl font-bold text-xs shadow-xs hover:shadow-md transition-all">
                    Simpan &amp; Perbarui Kata Sandi
                </button>
            </form>

            <div class="text-center pt-2 border-t border-slate-100 text-xs text-slate-500">
                Sudah ingat kata sandi Anda? 
                <a href="{{ route('login') }}" class="font-bold text-blue-600 hover:text-blue-800 hover:underline">Kembali ke Masuk</a>
            </div>
        </div>

    </div>
</section>
@endsection
