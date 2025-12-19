<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('printing_materials', function (Blueprint $table) {
            if (!Schema::hasColumn('printing_materials', 'cost_per_m2')) {
                $table->integer('cost_per_m2')->default(0)->after('price_per_m2');
            }
        });

        Schema::table('printing_finishings', function (Blueprint $table) {
            if (!Schema::hasColumn('printing_finishings', 'cost_per_m2')) {
                $table->integer('cost_per_m2')->default(0)->after('price_per_m2');
            }
            if (!Schema::hasColumn('printing_finishings', 'cost_flat')) {
                $table->integer('cost_flat')->default(0)->after('flat_fee');
            }
        });
    }

    public function down(): void
    {
        Schema::table('printing_materials', function (Blueprint $table) {
            if (Schema::hasColumn('printing_materials', 'cost_per_m2')) {
                $table->dropColumn('cost_per_m2');
            }
        });

        Schema::table('printing_finishings', function (Blueprint $table) {
            $cols = [];
            if (Schema::hasColumn('printing_finishings', 'cost_per_m2')) {
                $cols[] = 'cost_per_m2';
            }
            if (Schema::hasColumn('printing_finishings', 'cost_flat')) {
                $cols[] = 'cost_flat';
            }
            if ($cols) {
                $table->dropColumn($cols);
            }
        });
    }
};
