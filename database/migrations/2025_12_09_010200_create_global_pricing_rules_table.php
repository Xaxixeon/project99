<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('global_pricing_rules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('applies_to', ['all', 'product', 'variant'])->default('all');
            $table->foreignId('product_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('product_variant_id')->nullable()->constrained()->cascadeOnDelete();
            $table->enum('value_type', ['percent', 'flat'])->default('percent');
            $table->decimal('value', 8, 2)->default(0);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('global_pricing_rules');
    }
};
