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
            // Untuk merge task (Monday-style)
            if (!Schema::hasColumn('tasks', 'parent_id')) {
                $table->unsignedBigInteger('parent_id')
                    ->nullable()
                    ->after('order_id');

                $table->foreign('parent_id')
                    ->references('id')
                    ->on('tasks')
                    ->cascadeOnDelete();
            }

            // Group / bundle label (optional)
            if (!Schema::hasColumn('tasks', 'group_key')) {
                $table->string('group_key')->nullable()->after('parent_id');
            }

            // Priority (optional)
            if (!Schema::hasColumn('tasks', 'priority')) {
                $table->enum('priority', ['low', 'medium', 'high'])
                    ->default('medium')
                    ->after('status');
            }

            // Deadline task (pisah dari order)
            if (!Schema::hasColumn('tasks', 'deadline')) {
                $table->date('deadline')->nullable()->after('priority');
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
