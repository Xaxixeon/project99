<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            if (!Schema::hasColumn('customers', 'member_type_id')) {
                $table->foreignId('member_type_id')
                    ->nullable()
                    ->after('instansi_id')
                    ->constrained('member_types');
            }
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            if (Schema::hasColumn('customers', 'member_type_id')) {
                $table->dropConstrainedForeignId('member_type_id');
            }
        });
    }
};
