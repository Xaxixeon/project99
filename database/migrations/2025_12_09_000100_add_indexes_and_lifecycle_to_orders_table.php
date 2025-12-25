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

            // lifecycle column
            if (!Schema::hasColumn('orders', 'lifecycle')) {
                $table->string('lifecycle', 50)
                    ->nullable()
                    ->after('status');
            }

            // index status
            if ($this->shouldCreateIndex('orders_status_index')) {
                $table->index('status', 'orders_status_index');
            }

            // index customer_id
            if ($this->shouldCreateIndex('orders_customer_id_index')) {
                $table->index('customer_id', 'orders_customer_id_index');
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

            if ($this->shouldDropIndex('orders_status_index')) {
                $table->dropIndex('orders_status_index');
            }

            if ($this->shouldDropIndex('orders_customer_id_index')) {
                $table->dropIndex('orders_customer_id_index');
            }
        });
    }

    /**
     * Check if index should be created
     */
    private function shouldCreateIndex(string $index): bool
    {
        // SQLite: always safe to create index
        if ($this->isSqlite()) {
            return true;
        }

        return !$this->indexExists('orders', $index);
    }

    /**
     * Check if index should be dropped
     */
    private function shouldDropIndex(string $index): bool
    {
        if ($this->isSqlite()) {
            return true;
        }

        return $this->indexExists('orders', $index);
    }

    /**
     * MySQL-only index existence check
     */
    private function indexExists(string $table, string $index): bool
    {
        $connection = config('database.default');

        if (config("database.connections.$connection.driver") !== 'mysql') {
            return false;
        }

        $database = config("database.connections.$connection.database");

        if (!$database) {
            return false;
        }

        $result = DB::select(
            "
            SELECT COUNT(1) AS exists_count
            FROM information_schema.statistics
            WHERE table_schema = ?
              AND table_name = ?
              AND index_name = ?
            LIMIT 1
            ",
            [$database, $table, $index]
        );

        return !empty($result) && ($result[0]->exists_count ?? 0) > 0;
    }

    /**
     * Detect sqlite connection
     */
    private function isSqlite(): bool
    {
        $connection = config('database.default');
        return config("database.connections.$connection.driver") === 'sqlite';
    }
};
