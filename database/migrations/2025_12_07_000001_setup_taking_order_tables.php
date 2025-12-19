<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Extend orders table with taking-order fields (idempotent)
        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                if (!Schema::hasColumn('orders', 'product_type')) {
                    $table->string('product_type')->nullable()->after('customer_id');
                }
                if (!Schema::hasColumn('orders', 'size')) {
                    $table->string('size')->nullable()->after('product_type');
                }
                if (!Schema::hasColumn('orders', 'material')) {
                    $table->string('material')->nullable()->after('size');
                }
                if (!Schema::hasColumn('orders', 'quantity')) {
                    $table->integer('quantity')->nullable()->after('material');
                }
                if (!Schema::hasColumn('orders', 'finishing')) {
                    $table->string('finishing')->nullable()->after('quantity');
                }
                if (!Schema::hasColumn('orders', 'need_design')) {
                    $table->boolean('need_design')->default(false)->after('finishing');
                }
                if (!Schema::hasColumn('orders', 'deadline')) {
                    $table->date('deadline')->nullable()->after('need_design');
                }
                if (!Schema::hasColumn('orders', 'notes')) {
                    $table->text('notes')->nullable()->after('deadline');
                }
                if (!Schema::hasColumn('orders', 'order_status')) {
                    $table->string('order_status')->default('received')->after('notes');
                }
                if (!Schema::hasColumn('orders', 'created_by')) {
                    $table->unsignedBigInteger('created_by')->nullable()->after('order_status');
                }
            });
        }

        // order_files table
        if (!Schema::hasTable('order_files')) {
            Schema::create('order_files', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('order_id');
                $table->string('file_path');
                $table->enum('file_type', ['draft', 'final', 'customer_file'])->default('customer_file');
                $table->unsignedBigInteger('uploaded_by')->nullable();
                $table->timestamps();

                $table->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete();
            });
        }

        // tasks table
        if (!Schema::hasTable('tasks')) {
            Schema::create('tasks', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('order_id');
                $table->unsignedBigInteger('assigned_to')->nullable();
                $table->enum('role', ['designer', 'operator'])->nullable();
                $table->enum('task_type', ['create_design', 'revise_design', 'print_job', 'finishing'])->nullable();
                $table->enum('status', ['pending', 'in_progress', 'waiting_approval', 'done'])->default('pending');
                $table->text('description')->nullable();
                $table->timestamps();

                $table->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete();
            });
        }

        // order_status_logs table
        if (!Schema::hasTable('order_status_logs')) {
            Schema::create('order_status_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('order_id');
                $table->string('previous_status')->nullable();
                $table->string('new_status');
                $table->unsignedBigInteger('changed_by')->nullable();
                $table->timestamp('changed_at')->useCurrent();
                $table->timestamps();

                $table->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete();
            });
        }

        // payments table (optional)
        if (!Schema::hasTable('payments')) {
            Schema::create('payments', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('order_id');
                $table->decimal('amount', 14, 2)->default(0);
                $table->string('payment_method')->nullable();
                $table->enum('payment_status', ['pending', 'paid'])->default('pending');
                $table->timestamps();

                $table->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete();
            });
        }
    }

    public function down(): void
    {
        // Drop optional tables
        Schema::dropIfExists('payments');
        Schema::dropIfExists('order_status_logs');
        Schema::dropIfExists('tasks');
        Schema::dropIfExists('order_files');

        // Remove added columns from orders (if present)
        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                foreach ([
                    'product_type', 'size', 'material', 'quantity',
                    'finishing', 'need_design', 'deadline', 'notes',
                    'order_status', 'created_by'
                ] as $col) {
                    if (Schema::hasColumn('orders', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }
    }
};
