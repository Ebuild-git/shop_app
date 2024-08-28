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
        Schema::table('users', function (Blueprint $table) {
            $table->string('rue')->nullable()->default(null);
            $table->string('nom_batiment')->nullable()->default(null);
            $table->string('etage')->nullable()->default(null);
            $table->string('num_appartement')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['rue', 'nom_batiment', 'etage', 'num_appartement']);
        });
    }
};
