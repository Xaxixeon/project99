<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('member_types', function (Blueprint $table) {
            if (!Schema::hasColumn('member_types', 'priority')) {
                $table->unsignedTinyInteger('priority')
                    ->default(10)
                    ->after('name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('member_types', function (Blueprint $table) {
            if (Schema::hasColumn('member_types', 'priority')) {
                $table->dropColumn('priority');
            }
        });
    }
};
