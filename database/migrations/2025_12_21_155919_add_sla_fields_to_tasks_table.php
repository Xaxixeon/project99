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
        Schema::table('tasks', function (Blueprint $table) {

            if (!Schema::hasColumn('tasks', 'sla_status')) {
                $table->string('sla_status')->nullable()->after('deadline');
            }

            if (!Schema::hasColumn('tasks', 'started_at')) {
                $table->timestamp('started_at')->nullable()->after('created_at');
            }

            if (!Schema::hasColumn('tasks', 'completed_at')) {
                $table->timestamp('completed_at')->nullable()->after('started_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            //
        });
    }
};
