<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique(); // bengkel, lab-komputer, multimedia, olahraga
            $table->string('name');
            $table->string('badge');
            $table->string('pj_name');
            $table->string('pj_role_title');
            $table->text('description')->nullable();
            $table->string('icon')->default('tool');
            $table->string('color')->default('blue');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
