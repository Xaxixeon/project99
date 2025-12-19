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
        if (Schema::hasTable('member_prices')) {
            return;
        }

        Schema::create('member_prices', function (Blueprint $table) {
            $table->id();
            $table->string('member_type');
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->decimal('price', 15, 2);
            $table->string('label')->nullable();
            $table->timestamps();

            $table->unique(['member_type', 'product_id']);
            $table->foreign('member_type')->references('code')->on('member_types')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_prices');
    }
};
