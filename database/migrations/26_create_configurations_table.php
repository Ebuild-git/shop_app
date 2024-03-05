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
        Schema::create('configurations', function (Blueprint $table) {
            $table->id();
            $table->string("phone_number")->nullable()->default(null);
            $table->decimal("frais_livraison", 13, 3)->nullable()->default(null);
            $table->decimal("pourcentage_gain", 13, 3)->nullable()->default(null);
            $table->string("email")->nullable()->default(null);
            $table->string("adresse")->nullable()->default(null);
            $table->string("logo")->nullable()->default(null);
            $table->string("facebook")->nullable()->default(null);
            $table->string("linkedin")->nullable()->default(null);
            $table->string("tiktok")->nullable()->default(null);
            $table->string("instagram")->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configurations');
    }
};
