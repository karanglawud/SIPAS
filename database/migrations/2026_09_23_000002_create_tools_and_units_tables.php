<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tools', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->string('code')->unique(); // e.g. BKL-LAS-01
            $table->string('name');
            $table->text('spec')->nullable();
            $table->string('location')->nullable();
            $table->integer('total_stock')->default(1);
            $table->integer('available_stock')->default(1);
            $table->integer('fine_per_day')->default(2000); // dalam rupiah
            $table->integer('max_days')->default(3);
            $table->string('image_gradient')->default('from-blue-600 to-indigo-700');
            $table->timestamps();
        });

        Schema::create('tool_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tool_id')->constrained('tools')->cascadeOnDelete();
            $table->string('unit_code')->unique(); // e.g. BKL-LAS-01-U01
            $table->string('status')->default('tersedia'); // tersedia, dipinjam, rusak, hilang
            $table->string('condition')->default('baik'); // baik, rusak ringan, rusak berat
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tool_units');
        Schema::dropIfExists('tools');
    }
};
