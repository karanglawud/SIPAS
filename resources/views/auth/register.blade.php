@extends('layouts.app')

@section('title', 'Pendaftaran Akun Peminjam (Siswa & Guru)')

@section('content')
<section class="py-12 bg-slate-50 min-h-[85vh] flex items-center justify-center">
    <div class="max-w-lg w-full mx-auto px-4 space-y-6">
        
        <!-- Header -->
        <div class="text-center space-y-2">
            <div class="w-12 h-12 rounded-2xl bg-blue-600 text-white flex items-center justify-center font-black text-xl mx-auto shadow-md shadow-blue-500/20">
                S
            </div>
            <h1 class="text-2xl font-extrabold text-slate-900">Daftar Akun Peminjam</h1>
            <p class="text-xs text-slate-500">Pilih peran Anda (Siswa atau Guru). Keduanya memiliki hak akses meminjam alat sekolah.</p>
        </div>

        <!-- Registration Card -->
        <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-xs space-y-5">
            <form action="{{ route('register.post') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Role Selection -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700">Pilih Peran Akun: <span class="text-rose-500">*</span></label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="flex items-center gap-2.5 p-3 rounded-2xl border border-slate-200 cursor-pointer hover:bg-slate-50 has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50/70 transition-all">
                            <input type="radio" name="role" value="siswa" id="roleSiswa" {{ old('role', 'siswa') === 'siswa' ? 'checked' : '' }} class="text-blue-600">
                            <div>
                                <strong class="text-xs font-bold text-slate-900 block">Siswa SMKN 5</strong>
                                <span class="text-[10px] text-slate-500">Gunakan NISN & Kelas</span>
                            </div>
                        </label>

                        <label class="flex items-center gap-2.5 p-3 rounded-2xl border border-slate-200 cursor-pointer hover:bg-slate-50 has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50/70 transition-all">
                            <input type="radio" name="role" value="guru" id="roleGuru" {{ old('role') === 'guru' ? 'checked' : '' }} class="text-blue-600">
                            <div>
                                <strong class="text-xs font-bold text-slate-900 block">Guru / Pembina</strong>
                                <span class="text-[10px] text-slate-500">Gunakan NIP / Mapel</span>
                            </div>
                        </label>
                    </div>
                    @error('role')
                        <p class="text-[11px] text-rose-600 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Full Name -->
                <div class="space-y-1">
                    <label class="block text-xs font-bold text-slate-700">Nama Lengkap: <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Budi Santoso"
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    @error('name')
                        <p class="text-[11px] text-rose-600 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div class="space-y-1">
                    <label class="block text-xs font-bold text-slate-700">Alamat Email: <span class="text-rose-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="nama@smkn5solo.sch.id"
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    @error('email')
                        <p class="text-[11px] text-rose-600 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Identifier & Class/Dept -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label id="labelIdentifier" class="block text-xs font-bold text-slate-700">NISN Siswa: <span class="text-rose-500">*</span></label>
                        <input type="text" name="identifier" value="{{ old('identifier') }}" required placeholder="Contoh: 0081234567"
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono focus:bg-white focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        @error('identifier')
                            <p class="text-[11px] text-rose-600 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-1">
                        <label id="labelClassOrDept" class="block text-xs font-bold text-slate-700">Kelas / Presensi: <span class="text-rose-500">*</span></label>
                        <input type="text" name="class_or_dept" value="{{ old('class_or_dept') }}" required placeholder="Contoh: XI RPL 1 / XII TITL 2"
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        @error('class_or_dept')
                            <p class="text-[11px] text-rose-600 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Phone -->
                <div class="space-y-1">
                    <label class="block text-xs font-bold text-slate-700">Nomor WhatsApp / HP: <span class="text-slate-400 font-normal">(Untuk notifikasi pinjam)</span></label>
                    <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Contoh: 081234567890"
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>

                <!-- Password & Confirmation -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-slate-700">Kata Sandi: <span class="text-rose-500">*</span></label>
                        <input type="password" name="password" required placeholder="Minimal 6 karakter"
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        @error('password')
                            <p class="text-[11px] text-rose-600 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-slate-700">Ulangi Kata Sandi: <span class="text-rose-500">*</span></label>
                        <input type="password" name="password_confirmation" required placeholder="Ketik ulang sandi"
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-3.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-xs shadow-md shadow-blue-500/25 transition-all">
                        Daftar Sebagai Peminjam
                    </button>
                </div>
            </form>

            <div class="text-center pt-2 border-t border-slate-100 text-xs text-slate-500">
                Sudah memiliki akun? 
                <a href="{{ route('login') }}" class="font-bold text-blue-600 hover:underline">Masuk Sekarang</a>
            </div>
        </div>

    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const roleSiswa = document.getElementById('roleSiswa');
        const roleGuru = document.getElementById('roleGuru');
        const labelIdentifier = document.getElementById('labelIdentifier');
        const labelClassOrDept = document.getElementById('labelClassOrDept');

        function updateLabels() {
            if (roleGuru.checked) {
                labelIdentifier.innerHTML = 'NIP Guru: <span class="text-rose-500">*</span>';
                labelClassOrDept.innerHTML = 'Mata Pelajaran / Unit Kerja: <span class="text-rose-500">*</span>';
            } else {
                labelIdentifier.innerHTML = 'NISN Siswa: <span class="text-rose-500">*</span>';
                labelClassOrDept.innerHTML = 'Kelas / Presensi: <span class="text-rose-500">*</span>';
            }
        }

        roleSiswa.addEventListener('change', updateLabels);
        roleGuru.addEventListener('change', updateLabels);
        updateLabels();
    });
</script>
@endsection
