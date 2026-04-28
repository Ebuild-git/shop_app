<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders_items', function (Blueprint $table) {
            $table->string('pickup_id')->nullable()->after('shipment_id');
            $table->string('pickup_guid')->nullable()->after('pickup_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders_items', function (Blueprint $table) {
            $table->dropColumn(['pickup_id', 'pickup_guid']);
        });
    }
};
