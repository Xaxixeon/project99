<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'size')) {
                $table->string('size')->nullable()->after('total');
            }

            if (!Schema::hasColumn('orders', 'width_cm')) {
                $table->unsignedInteger('width_cm')->nullable()->after('size');
            }

            if (!Schema::hasColumn('orders', 'height_cm')) {
                $table->unsignedInteger('height_cm')->nullable()->after('width_cm');
            }

            if (!Schema::hasColumn('orders', 'area_m2')) {
                $table->integer('area_m2')->nullable()->comment('area x100 (1.25m2 = 125)')->after('height_cm');
            }

            if (!Schema::hasColumn('orders', 'printing_material_id')) {
                $table->foreignId('printing_material_id')
                    ->nullable()
                    ->constrained('printing_materials');
            }

            if (!Schema::hasColumn('orders', 'printing_finishing_id')) {
                $table->foreignId('printing_finishing_id')
                    ->nullable()
                    ->constrained('printing_finishings');
            }

            if (Schema::hasColumn('orders', 'total_price')) {
                $table->integer('total_price')->default(0)->change(); // kalau belum integer
            } else {
                $table->integer('total_price')->default(0)->after('total');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'printing_material_id')) {
                $table->dropConstrainedForeignId('printing_material_id');
            }

            if (Schema::hasColumn('orders', 'printing_finishing_id')) {
                $table->dropConstrainedForeignId('printing_finishing_id');
            }

            $columns = ['size', 'width_cm', 'height_cm', 'area_m2', 'total_price'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('orders', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
