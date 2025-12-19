<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('member_types', function (Blueprint $table) {
            if (!Schema::hasColumn('member_types', 'discount_percent')) {
                $table->integer('discount_percent')->default(0)->after('label');
            }
            if (!Schema::hasColumn('member_types', 'description')) {
                $table->text('description')->nullable()->after('discount_percent');
            }
            if (!Schema::hasColumn('member_types', 'name')) {
                $table->string('name')->nullable()->after('code');
            }
        });
    }

    public function down(): void
    {
        Schema::table('member_types', function (Blueprint $table) {
            if (Schema::hasColumn('member_types', 'discount_percent')) {
                $table->dropColumn('discount_percent');
            }
            if (Schema::hasColumn('member_types', 'description')) {
                $table->dropColumn('description');
            }
            if (Schema::hasColumn('member_types', 'name')) {
                $table->dropColumn('name');
            }
        });
    }
};
