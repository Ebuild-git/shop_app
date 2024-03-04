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
        Schema::create('propositions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("id_post");
            $table->unsignedBigInteger("id_acheteur");
            $table->unsignedBigInteger("id_vendeur");
            $table->enum( "etat", ['traitement','refusé','accepté'] )->default("traitement");
            $table->timestamps();

            $table->foreign('id_post')->references('id')->on('posts')->onDelete('cascade');
            $table->foreign('id_acheteur')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_vendeur')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('propositions');
    }
};
