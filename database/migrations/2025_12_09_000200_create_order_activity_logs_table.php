<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_activity_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                ->constrained('orders')->cascadeOnDelete();
            $table->foreignId('staff_id')
                ->nullable()->constrained('staff_users')->nullOnDelete();

            $table->string('from_status', 50)->nullable();
            $table->string('to_status', 50)->nullable();

            $table->text('note')->nullable();
            $table->timestamp('client_timestamp')->nullable();

            $table->json('before_payload')->nullable();
            $table->json('after_payload')->nullable();

            $table->timestamps();

            $table->index(['order_id', 'staff_id']);
            $table->index(['from_status', 'to_status']);

            $this->assertDatabaseHas('order_activity_logs', [
                'from_status' => 'pending',
                'to_status'   => 'in_progress',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_activity_logs');
    }
};
