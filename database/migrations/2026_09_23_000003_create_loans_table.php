<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->unique(); // e.g. SIPAS-2026-001
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('tool_id')->constrained('tools')->cascadeOnDelete();
            $table->integer('quantity')->default(1);
            $table->date('start_date');
            $table->integer('duration_days')->default(1); // Durasi peminjaman (berapa hari)
            $table->date('end_date'); // Tanggal rencana pengembalian
            $table->text('purpose'); // Keperluan peminjaman / acara sekolah
            $table->string('proposal_path')->nullable(); // File proposal / surat izin yang dilampirkan
            $table->string('proposal_filename')->nullable(); // Nama asli file proposal
            $table->string('status')->default('menunggu'); // menunggu, disetujui, dipinjam, selesai, ditolak
            $table->json('assigned_units')->nullable(); // array kode unit fisik, misal ["BKL-LAS-01-U01"]
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->text('pj_notes')->nullable(); // Catatan dari admin PJ
            $table->date('actual_return_date')->nullable();
            $table->string('return_condition')->nullable(); // baik, rusak, hilang
            $table->integer('fine_amount')->default(0); // Denda otomatis
            $table->string('fine_status')->default('none'); // none, belum_lunas, lunas
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
