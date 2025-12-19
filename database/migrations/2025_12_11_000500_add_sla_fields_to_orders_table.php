<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'sla_status')) {
                $table->string('sla_status', 20)->nullable()->index();
            }
            if (!Schema::hasColumn('orders', 'lead_time_hours')) {
                $table->decimal('lead_time_hours', 8, 2)->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'sla_status')) {
                $table->dropColumn('sla_status');
            }
            if (Schema::hasColumn('orders', 'lead_time_hours')) {
                $table->dropColumn('lead_time_hours');
            }
        });
    }
};
