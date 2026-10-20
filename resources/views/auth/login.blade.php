@extends('layouts.app')

@section('title', 'Masuk ke Sistem')

@section('content')
<section class="py-16 bg-slate-50 min-h-[75vh] flex items-center justify-center">
    <div class="max-w-md w-full mx-auto px-4 space-y-6">
        
        <div class="text-center space-y-2">
            <div class="w-12 h-12 rounded-2xl bg-blue-600 text-white flex items-center justify-center font-black text-xl mx-auto shadow-md shadow-blue-500/20">
                S
            </div>
            <h1 class="text-2xl font-extrabold text-slate-900">Masuk ke SIPAS</h1>
            <p class="text-xs text-slate-500">Sistem Informasi Peminjaman Alat Sekolah</p>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-xs space-y-5">
            <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
                @csrf

                <div class="space-y-1">
                    <label class="block text-xs font-bold text-slate-700">Alamat Email:</label>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="nama@smkn5solo.sch.id"
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    @error('email')
                        <p class="text-[11px] text-rose-600 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-1">
                    <label class="block text-xs font-bold text-slate-700">Kata Sandi:</label>
                    <input type="password" name="password" required placeholder="••••••••"
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    @error('password')
                        <p class="text-[11px] text-rose-600 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between text-xs text-slate-500">
                    <label class="flex items-center gap-1.5 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded text-blue-600">
                        <span>Ingat saya</span>
                    </label>
                </div>

                <button type="submit" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-xs shadow-md shadow-blue-500/20 transition-all">
                    Masuk
                </button>
            </form>

            <div class="text-center pt-2 border-t border-slate-100 text-xs text-slate-500">
                Belum memiliki akun peminjam? 
                <a href="{{ route('register') }}" class="font-bold text-blue-600 hover:underline">Daftar Akun Baru</a>
            </div>
        </div>

    </div>
</section>
@endsection
