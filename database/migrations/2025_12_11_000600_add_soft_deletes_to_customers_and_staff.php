<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            if (!Schema::hasColumn('customers', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        Schema::table('staff_users', function (Blueprint $table) {
            if (!Schema::hasColumn('staff_users', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            if (Schema::hasColumn('customers', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });

        Schema::table('staff_users', function (Blueprint $table) {
            if (Schema::hasColumn('staff_users', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });
    }
};
