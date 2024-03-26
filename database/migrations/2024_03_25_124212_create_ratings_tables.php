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
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->integer("etoiles");
            $table->unsignedBigInteger("id_user_rating");
            $table->unsignedBigInteger("id_user_rated");
            $table->timestamps();


            //relation
            $table->foreign("id_user_rating")
                ->references("id")
                ->on("users")
                ->cascadeOnDelete();

            $table->foreign("id_user_rated")
                ->references("id")
                ->on("users")
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};
