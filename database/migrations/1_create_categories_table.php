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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string("titre");
            $table->text("description")->nullable()->default(null);
            $table->decimal("pourcentage_gain", 13, 3)->nullable()->default(null);
            $table->boolean("luxury")->default(false);
            $table->integer('order')->default(0);
            $table->string("icon");
            $table->string("small_icon")->nullable();
            $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
