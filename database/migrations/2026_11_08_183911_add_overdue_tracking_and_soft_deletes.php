<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->timestamp('last_reminder_sent_at')->nullable()->after('fine_status');
            $table->integer('overdue_days')->default(0)->after('last_reminder_sent_at');
        });

        Schema::table('tools', function (Blueprint $table) {
            $table->softDeletes()->after('image');
        });

        Schema::table('tool_units', function (Blueprint $table) {
            $table->softDeletes()->after('notes');
        });
    }

    public function down(): void
    {
        Schema::table('tool_units', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('tools', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('loans', function (Blueprint $table) {
            $table->dropColumn(['last_reminder_sent_at', 'overdue_days']);
        });
    }
};
