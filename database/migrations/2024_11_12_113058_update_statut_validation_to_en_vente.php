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
        Schema::table('en_vente', function (Blueprint $table) {
            DB::table('posts')
            ->where('statut', 'validation')
            ->update(['statut' => 'vente']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('en_vente', function (Blueprint $table) {
            DB::table('posts')
            ->where('statut', 'vente')
            ->update(['statut' => 'validation']);
        });
    }
};
