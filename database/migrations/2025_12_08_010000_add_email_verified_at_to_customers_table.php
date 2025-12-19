<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasColumn('customers', 'email_verified_at')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->timestamp('email_verified_at')->nullable()->after('email');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('customers', 'email_verified_at')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->dropColumn('email_verified_at');
            });
        }
    }
};
