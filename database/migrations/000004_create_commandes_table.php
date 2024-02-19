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
        Schema::create('commandes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("id_vendor");
            $table->unsignedBigInteger("id_buyer");
            $table->unsignedBigInteger("id_post");
            $table->decimal("frais_livraison", 13, 3)->nullable()->default(null);
            $table->string("etat");
            $table->string("statut");
            $table->timestamps();

            $table->foreign('id_buyer')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_vendor')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_post')->references('id')->on('posts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commandes');
    }
};
