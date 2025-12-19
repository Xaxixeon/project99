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
        Schema::table('users', function (Blueprint $table) {
            $table->string('staff_code')->nullable()->unique()->after('id'); // 0001021
            $table->string('username')->nullable()->unique()->after('name');
            $table->string('role')->nullable()->after('email'); // admin, cs, dsb (boleh redundant dari roles())
            $table->enum('status', ['active', 'inactive'])->default('active')->after('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['staff_code', 'username', 'role', 'status']);
        });
    }
};
