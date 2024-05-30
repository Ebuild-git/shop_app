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
            $table->integer("etoiles")->nullable();
            $table->unsignedBigInteger("id_user_sell");
            $table->unsignedBigInteger("id_user_buy");
            $table->unsignedBigInteger("id_post");
            $table->timestamp("date_buy")->default(now());
            $table->timestamps();


            //relations
            $table->foreign("id_user_sell")->references("id")->on("users")->onDelete("Cascade");
            $table->foreign("id_user_buy")->references("id")->on("users")->onDelete("Cascade");
            $table->foreign("id_post")->references("id")->on("posts")->onDelete("Cascade");

            
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
