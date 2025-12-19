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

            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('staff_id')->nullable();

            $table->string('from_status', 50)->nullable();
            $table->string('to_status', 50)->nullable();

            $table->text('note')->nullable();
            $table->timestamp('client_timestamp')->nullable();

            $table->json('before_payload')->nullable();
            $table->json('after_payload')->nullable();

            $table->timestamps();

            $table->index('order_id');
            $table->index('staff_id');
            $table->index('from_status');
            $table->index('to_status');

            $table->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete();
            // staff_id dibiarkan nullable, sesuaikan nama tabel staff jika berbeda
            if (Schema::hasTable('staff_users')) {
                $table->foreign('staff_id')->references('id')->on('staff_users')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_activity_logs');
    }
};
