<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'deadline_reminder_sent_at')) {
                $table->timestamp('deadline_reminder_sent_at')->nullable()->index();
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'deadline_reminder_sent_at')) {
                $table->dropColumn('deadline_reminder_sent_at');
            }
        });
    }
};
