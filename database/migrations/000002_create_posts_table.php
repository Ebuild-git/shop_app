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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string("titre");
            $table->string("description");
            $table->json("photos");
            $table->unsignedBigInteger("id_user");
            $table->unsignedBigInteger("id_user_buy")->nullable()->default(null);
            $table->unsignedBigInteger("id_categorie");
            $table->unsignedBigInteger("id_sous_categorie");
            $table->string("ville");
            $table->string("gouvernorat");
            $table->decimal("prix", 13, 3);
            $table->timestamp('verified_at')->nullable()->default(null);
            $table->timestamp('sell_at')->nullable()->default(null);
            $table->timestamps();

            $table->unsignedBigInteger("id_user_buy")->nullable()->default(null);
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_user_user')->references('id')->on('users');
            $table->foreign('id_categorie')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('id_sous_categorie')->references('id')->on('sous_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
