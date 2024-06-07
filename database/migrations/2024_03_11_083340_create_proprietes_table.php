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
        Schema::create('proprietes', function (Blueprint $table) {
            $table->id();
            $table->string("nom");
            $table->enum("type",["text","number","color","option"]);
            $table->enum("affichage",["case","input"])->default("case");
            $table->json("options")->nullable(true);
            $table->boolean("required")->default(false);
            $table->integer('order')->default(0);
            $table->boolean("show_in_filter")->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proprietes');
    }
};
