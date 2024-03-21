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
        Schema::create('regions_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("id_categorie");
            $table->unsignedBigInteger("id_region");
            $table->decimal("prix", 13, 3);
            $table->timestamps();


            // Relation
            $table->foreign("id_categorie")
                ->references("id")
                ->on("categories")
                ->cascadeOnDelete("cascade");

            $table->foreign("id_region")
                ->references("id")
                ->on("regions")
                ->cascadeOnDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('regions_categories');
    }
};
