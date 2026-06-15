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
        Schema::create('shipment_status_histories', function (Blueprint $table) {
            $table->id();
            $table->string('shipment_id');
            $table->unsignedBigInteger('post_id')->nullable();
            $table->unsignedBigInteger('order_item_id')->nullable();
            $table->string('old_etat')->nullable();
            $table->string('new_etat');
            $table->timestamps();
            $table->index('shipment_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipment_status_histories');
    }
};
