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
        Schema::create('sous_categories', function (Blueprint $table) {
            $table->id();
            $table->string("titre");
            $table->unsignedBigInteger("id_categorie");
            $table->text("description")->nullable()->default(null);
            $table->json("proprietes")->nullable(true);
            $table->json("required")->nullable(true);
            $table->integer('order')->default(0);
            $table->timestamps();

            $table->foreign('id_categorie')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sous_categories');
    }
};
