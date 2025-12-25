<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                ->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')
                ->nullable()->constrained()->nullOnDelete();

            $table->string('action');
            $table->string('description');
            $table->json('meta')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_logs');
    }
};
