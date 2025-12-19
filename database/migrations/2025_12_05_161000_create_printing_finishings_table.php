<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('printing_finishings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->nullable(); // misal: LAM_DOFF
            $table->integer('price_per_m2')->default(0); // tambahan per m2
            $table->integer('flat_fee')->default(0);     // biaya sekali order
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('printing_finishings');
    }
};
