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
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->string('shipment_id')->unique();
            $table->json('client_info');
            $table->string('reference1')->nullable();
            $table->string('reference2')->nullable();
            $table->string('reference3')->nullable();
            $table->string('reference4')->nullable();
            $table->string('reference5')->nullable();
            $table->json('shipment_details');
            $table->string('origin')->nullable();
            $table->string('destination')->nullable();
            $table->string('status');
            $table->json('notifications')->nullable();
            $table->string('tracking_number')->nullable();
            $table->json('request_data');
            $table->json('response_data');
            $table->json('additional_details')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
