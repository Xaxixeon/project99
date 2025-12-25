<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('order_files');

        Schema::create('order_files', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                ->constrained('orders')->cascadeOnDelete();

            $table->foreignId('uploaded_by_staff_id')
                ->nullable()->constrained('staff_users')->nullOnDelete();

            $table->foreignId('uploaded_by_customer_id')
                ->nullable()->constrained('customers')->nullOnDelete();

            $table->string('file_path');
            $table->string('file_original_name');

            $table->enum('type', [
                'customer_upload',
                'designer_output',
                'revision',
            ]);

            $table->boolean('approved')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_files');
    }
};
