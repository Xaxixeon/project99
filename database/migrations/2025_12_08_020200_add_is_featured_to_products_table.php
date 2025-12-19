<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // produk unggulan untuk landing page
            $table->boolean('is_featured')
                ->default(false)
                ->after('is_active');

            // teks harga yang enak dibaca di landing (opsional)
            $table->string('display_price')
                ->nullable()
                ->after('is_featured');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['is_featured', 'display_price']);
        });
    }
};
