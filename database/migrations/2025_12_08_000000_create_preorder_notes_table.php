<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('preorder_notes', function (Blueprint $table) {
            $table->id();

            $table->string('note_id')->unique();
            $table->string('customer_name');
            $table->string('title')->nullable();
            $table->string('product')->nullable();

            $table->text('notes')->nullable();
            $table->date('deadline')->nullable();
            $table->string('priority')->default('Low');

            $table->foreignId('user_id')
                ->nullable()->constrained('staff_users')->nullOnDelete();

            $table->timestamp('updated_at_client')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('preorder_notes');
    }
};
