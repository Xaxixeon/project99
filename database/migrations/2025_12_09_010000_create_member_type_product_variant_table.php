<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('member_type_product_variant', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_type_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_variant_id')->constrained()->cascadeOnDelete();
            $table->decimal('price_override', 12, 2)->nullable();
            $table->decimal('discount_percent', 5, 2)->nullable();
            $table->decimal('markup_percent', 5, 2)->nullable();
            $table->timestamps();
            $table->unique(['member_type_id', 'product_variant_id'], 'mtpv_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('member_type_product_variant');
    }
};
