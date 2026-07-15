<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('orders_items', function (Blueprint $table) {
            $table->string('cancelled_pickup_id')->nullable()->after('cancelled_pickup_guid');
        });
    }

    public function down()
    {
        Schema::table('orders_items', function (Blueprint $table) {
            $table->dropColumn('cancelled_pickup_id');
        });
    }
};
