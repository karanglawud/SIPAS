@extends('layouts.app')

@section('title', 'Kanal Pemberitahuan Petugas PJ Alat - SIPAS')

@section('content')
<section class="py-8 bg-slate-100 min-h-[calc(100vh-140px)]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden flex flex-col md:flex-row h-[750px]">
            
            <!-- Left Panel: Channel List -->
            <div class="w-full md:w-80 lg:w-96 border-b md:border-b-0 md:border-r border-slate-200 flex flex-col bg-slate-50/70">
                
                <!-- Left Header -->
                <div class="p-4 border-b border-slate-200 bg-white flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-bold text-slate-900">Pemberitahuan Peminjaman</h2>
                        <p class="text-[11px] text-slate-500">Kanal Resmi Petugas PJ Kategori Alat</p>
                    </div>
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                </div>

                <!-- Channels List -->
                <div class="overflow-y-auto flex-grow divide-y divide-slate-100">
                    @foreach($categories as $cat)
                        @php
                            $latestMsg = $cat->messages->first();
                            $unreadCount = $cat->messages->where('is_read', false)->count();
                            $isActive = $activeCategory && $activeCategory->id === $cat->id;
                        @endphp
                        <a href="{{ route('messages.index', ['kategori' => $cat->slug]) }}" 
                           class="p-4 flex items-start gap-3 transition-colors {{ $isActive ? 'bg-white border-l-4 border-blue-600 shadow-xs' : 'hover:bg-slate-100/80' }}">
                            
                            <div class="w-11 h-11 rounded-2xl bg-blue-600 text-white flex items-center justify-center font-bold text-sm shrink-0 shadow-xs">
                                {{ strtoupper(substr($cat->name, 0, 2)) }}
                            </div>

                            <div class="flex-grow min-w-0">
                                <div class="flex items-center justify-between gap-1 mb-0.5">
                                    <h3 class="text-xs font-bold text-slate-900 truncate">{{ $cat->name }}</h3>
                                    @if($latestMsg)
                                        <span class="text-[10px] text-slate-400 font-mono shrink-0">
                                            {{ $latestMsg->created_at->format('H:i') }}
                                        </span>
                                    @endif
                                </div>

                                <p class="text-[11px] text-slate-500 truncate mb-1">
                                    PJ: {{ $cat->pj_name }}
                                </p>

                                <div class="flex items-center justify-between gap-2">
                                    <p class="text-[11px] text-slate-600 truncate">
                                        {{ $latestMsg ? $latestMsg->message : 'Belum ada pemberitahuan baru' }}
                                    </p>
                                    @if($unreadCount > 0)
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-600 text-white shrink-0">
                                            {{ $unreadCount }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                        </a>
                    @endforeach
                </div>

            </div>

            <!-- Right Panel: Message Thread (WhatsApp Inspired) -->
            <div class="flex-grow flex flex-col bg-slate-50">
                
                @if($activeCategory)
                    <!-- Active Channel Header -->
                    <div class="p-4 border-b border-slate-200 bg-white flex items-center justify-between shrink-0">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-blue-700 text-white flex items-center justify-center font-bold text-sm shadow-xs">
                                {{ strtoupper(substr($activeCategory->name, 0, 2)) }}
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h3 class="text-sm font-bold text-slate-900">{{ $activeCategory->name }}</h3>
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-blue-100 text-blue-700">
                                        {{ $activeCategory->badge }}
                                    </span>
                                </div>
                                <p class="text-xs text-slate-500">Penanggung Jawab: <strong class="text-slate-700">{{ $activeCategory->pj_name }}</strong></p>
                            </div>
                        </div>

                        <span class="text-[11px] font-medium text-slate-400 hidden sm:inline">
                            Kanal Terverifikasi Sekolah
                        </span>
                    </div>

                    <!-- Chat Messages Flow Container -->
                    <div class="flex-grow overflow-y-auto p-4 sm:p-6 space-y-4 bg-slate-100/60">
                        
                        @if($messages->isEmpty())
                            <div class="h-full flex flex-col items-center justify-center text-center p-8 space-y-2">
                                <div class="w-12 h-12 rounded-2xl bg-white border border-slate-200 flex items-center justify-center text-slate-400">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                </div>
                                <h4 class="text-sm font-bold text-slate-700">Belum Ada Pesan Masuk</h4>
                                <p class="text-xs text-slate-400 max-w-sm">Pemberitahuan verifikasi pengajuan, persetujuan, atau instruksi pengambilan alat dari PJ ini akan tampil di sini.</p>
                            </div>
                        @else
                            <div class="text-center my-2">
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-white border border-slate-200 text-slate-500 shadow-xs">
                                    Riwayat Pemberitahuan Resmi
                                </span>
                            </div>

                            @foreach($messages as $msg)
                                <div class="flex flex-col items-start max-w-xl space-y-1">
                                    
                                    <!-- Sender Name Tag -->
                                    <span class="text-[11px] font-bold text-slate-600 pl-1">
                                        {{ $msg->sender->name }} ({{ $activeCategory->name }})
                                    </span>

                                    <!-- Message Bubble -->
                                    <div class="bg-white rounded-2xl rounded-tl-xs p-4 border border-slate-200 shadow-xs space-y-3 text-xs text-slate-800 leading-relaxed">
                                        
                                        <p>{{ $msg->message }}</p>

                                        <!-- Loan Ticket Embed Card if attached -->
                                        @if($msg->loan)
                                            <div class="bg-slate-50 p-3 rounded-xl border border-slate-200 space-y-2 text-[11px]">
                                                <div class="flex items-center justify-between border-b border-slate-200/80 pb-1.5">
                                                    <span class="font-mono font-bold text-blue-700">{{ $msg->loan->ticket_number }}</span>
                                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold border {{ $msg->loan->status_badge }}">
                                                        {{ $msg->loan->status_label }}
                                                    </span>
                                                </div>
                                                <div class="grid grid-cols-2 gap-2 text-slate-600">
                                                    <div>
                                                        <span class="text-slate-400 block text-[10px]">Alat:</span>
                                                        <span class="font-semibold text-slate-800">{{ $msg->loan->tool->name }}</span>
                                                    </div>
                                                    <div>
                                                        <span class="text-slate-400 block text-[10px]">Tenggat Kembali:</span>
                                                        <span class="font-semibold text-slate-800">{{ $msg->loan->end_date->format('d/m/Y') }}</span>
                                                    </div>
                                                </div>
                                                <div class="pt-1">
                                                    <a href="{{ route('loans.index') }}" class="text-blue-600 hover:text-blue-800 font-bold hover:underline flex items-center gap-1">
                                                        <span>Buka Riwayat Peminjaman Ini</span>
                                                        &rarr;
                                                    </a>
                                                </div>
                                            </div>
                                        @endif

                                        <!-- Timestamp Only (No Fake Checkmarks) -->
                                        <div class="text-right pt-1">
                                            <span class="text-[10px] text-slate-400 font-mono">
                                                {{ $msg->created_at->format('H:i') }} • {{ $msg->created_at->format('d/m/Y') }}
                                            </span>
                                        </div>

                                    </div>

                                </div>
                            @endforeach
                        @endif

                    </div>

                    <!-- Bottom Bar (One-Way Info Note) -->
                    <div class="p-3 bg-white border-t border-slate-200 text-center text-[11px] text-slate-500 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Kanal Pemberitahuan Resmi SIPAS — SMK Negeri 5 Surakarta (Satu Arah dari Petugas PJ Kategori Alat)</span>
                    </div>

                @else
                    <div class="h-full flex items-center justify-center text-slate-400 text-xs">
                        Pilih salah satu kategori di sebelah kiri untuk melihat pesan.
                    </div>
                @endif

            </div>

        </div>

    </div>
</section>
@endsection
