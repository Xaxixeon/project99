<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('customer_code')->nullable()->unique()->after('id');
            $table->string('email')->nullable()->unique()->change();
            $table->string('password')->nullable()->after('email');
            $table->enum('member_type', ['silver', 'gold', 'platinum', 'vip'])->default('silver')->after('password');
            $table->unsignedBigInteger('instansi_id')->nullable()->after('member_type');
            $table->enum('status', ['active', 'inactive'])->default('active')->after('instansi_id');
        });
    }

    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn(['customer_code', 'password', 'member_type', 'instansi_id', 'status']);
        });
    }
};
