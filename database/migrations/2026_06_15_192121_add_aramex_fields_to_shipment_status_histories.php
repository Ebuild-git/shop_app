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
        Schema::table('shipment_status_histories', function (Blueprint $table) {
            $table->string('update_code')->nullable();
            $table->string('update_description')->nullable();
            $table->string('update_location')->nullable();
            $table->timestamp('update_datetime')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipment_status_histories', function (Blueprint $table) {
            $table->dropColumn('update_code');
            $table->dropColumn('update_description');
            $table->dropColumn('update_location');
            $table->dropColumn('update_datetime');
        });
    }
};
