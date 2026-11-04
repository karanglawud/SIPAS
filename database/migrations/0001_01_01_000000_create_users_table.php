<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('role')->default('siswa'); // siswa, guru, admin
            $table->string('identifier')->nullable(); // NISN atau NIP
            $table->string('class_or_dept')->nullable(); // misal XII RPL B / 01
            $table->string('phone')->nullable();
            $table->string('category_scope')->nullable(); // null untuk peminjam; 'bengkel', 'lab-komputer', 'multimedia', 'olahraga', atau 'all' untuk admin
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
