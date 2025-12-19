<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('order_operations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('staff_id')->nullable()->constrained('staff_users');

            $table->enum('type', [
                'printing',
                'laminating',
                'cutting',
                'finishing_manual',
                'qc',
                'packaging',
                'delivery',
            ]);

            $table->enum('status', ['pending', 'in_progress', 'done'])
                ->default('pending');

            $table->dateTime('started_at')->nullable();
            $table->dateTime('finished_at')->nullable();

            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_operations');
    }
};
