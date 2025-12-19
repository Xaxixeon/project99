<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('product_member_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('member_type_id')->constrained('member_types')->cascadeOnDelete();

            // harga override
            $table->integer('price_per_m2')->nullable();
            $table->integer('flat_price')->nullable(); // opsional

            $table->timestamps();

            $table->unique(['product_id', 'member_type_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_member_prices');
    }
};
