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
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('statut');
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->enum("statut", [
                "validation",
                "vente",
                "vendu",
                "livraison",
                "livré",
                "refusé",
                "préparation",
                "en voyage",
                "en cours de livraison",
                "ramassée",
                "retourné"
            ])->default("validation");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('statut');
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->enum("statut", [
                "validation",
                "vente",
                "vendu",
                "livraison",
                "livré",
                "refusé"
            ])->default("validation");
        });
    }
};
