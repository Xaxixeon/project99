<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pos_sales', function (Blueprint $table) {
            $table->id();
            $table->string('sale_code')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('total', 15, 2)->default(0);
            $table->json('items');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('pos_sales');
    }
};
