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
        Schema::create('history_change_prices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("id_post");
            $table->decimal("old_price", 13, 2);
            $table->decimal("new_price", 13, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history_change_prices');
    }
};
