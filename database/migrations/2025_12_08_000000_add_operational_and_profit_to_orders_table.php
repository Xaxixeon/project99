<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('priority', ['low', 'normal', 'high', 'urgent'])
                ->default('normal')
                ->after('status');

            $table->dateTime('due_at')->nullable()->after('priority');
            $table->dateTime('started_at')->nullable()->after('due_at');
            $table->dateTime('completed_at')->nullable()->after('started_at');

            $table->integer('material_cost')->default(0)->after('total_price');
            $table->integer('finishing_cost')->default(0)->after('material_cost');
            $table->integer('other_cost')->default(0)->after('finishing_cost');
            $table->integer('total_cost')->default(0)->after('other_cost');
            $table->integer('profit')->default(0)->after('total_cost');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'priority',
                'due_at',
                'started_at',
                'completed_at',
                'material_cost',
                'finishing_cost',
                'other_cost',
                'total_cost',
                'profit',
            ]);
        });
    }
};
