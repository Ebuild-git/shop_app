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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string("type");
            $table->string("titre");
            $table->string("message");
            $table->string("url")->nullable()->default(null);
            $table->unsignedBigInteger("id_user_destination ")->nullable()->default(null);
            $table->unsignedBigInteger("id_user")->nullable()->default(null);
            $table->unsignedBigInteger("id_commande")->nullable()->default(null);
            $table->unsignedBigInteger("id_signalement")->nullable()->default(null);
            $table->enum('statut', ['read','unread'])->default('unread');
            $table->timestamps();


            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_user_destination ')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_commande')->references('id')->on('commandes')->onDelete('cascade');
            $table->foreign('id_signalement')->references('id')->on('signalements')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
