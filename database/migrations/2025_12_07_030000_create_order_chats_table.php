<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_chats', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                ->constrained('orders')->cascadeOnDelete();
            $table->foreignId('staff_id')
                ->nullable()->constrained('staff_users')->nullOnDelete();
            $table->foreignId('customer_id')
                ->nullable()->constrained('customers')->nullOnDelete();

            $table->enum('sender_type', ['staff', 'customer']);
            $table->text('message')->nullable();
            $table->string('attachment')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_chats');
    }
};
