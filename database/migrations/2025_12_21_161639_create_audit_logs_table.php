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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();

            // ACTOR
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('user_role')->nullable();

            // TARGET
            $table->string('action');              // merge_task, update_status, qc_approve
            $table->string('subject_type');         // Task, Order, PreorderNote
            $table->unsignedBigInteger('subject_id')->nullable();

            // DETAIL
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();

            // META
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();

            $table->timestamps();

            $table->index(['subject_type', 'subject_id']);
            $table->index('action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
