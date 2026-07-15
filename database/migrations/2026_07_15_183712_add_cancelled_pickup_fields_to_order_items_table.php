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
            $table->string('cancelled_pickup_guid')->nullable()->after('pickup_guid');
            $table->string('cancelled_shipment_id')->nullable()->after('shipment_id');
            $table->timestamp('pickup_cancelled_at')->nullable()->after('cancelled_shipment_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders_items', function (Blueprint $table) {
            $table->dropColumn(['cancelled_pickup_guid', 'cancelled_shipment_id', 'pickup_cancelled_at']);
        });
    }
};
