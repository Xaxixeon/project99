<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('orders')) {
            return;
        }

        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'lifecycle')) {
                $table->string('lifecycle', 50)->nullable()->after('status');
            }

            if (!$this->indexExists('orders', 'orders_status_index')) {
                $table->index('status');
            }

            // customer_id sudah terindeks lewat foreign key, tapi jaga-jaga
            if (!$this->indexExists('orders', 'orders_customer_id_index')) {
                $table->index('customer_id');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('orders')) {
            return;
        }

        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'lifecycle')) {
                $table->dropColumn('lifecycle');
            }

            if ($this->indexExists('orders', 'orders_status_index')) {
                $table->dropIndex('orders_status_index');
            }
            if ($this->indexExists('orders', 'orders_customer_id_index')) {
                $table->dropIndex('orders_customer_id_index');
            }
        });
    }

    private function indexExists(string $table, string $index): bool
    {
        $connection = config('database.default');
        $database = config("database.connections.{$connection}.database");
        if (!$database) {
            return false;
        }

        $result = DB::select("
            SELECT COUNT(1) AS exists_count
            FROM information_schema.statistics
            WHERE table_schema = ?
              AND table_name = ?
              AND index_name = ?
            LIMIT 1
        ", [$database, $table, $index]);

        return !empty($result) && ($result[0]->exists_count ?? 0) > 0;
    }
};
