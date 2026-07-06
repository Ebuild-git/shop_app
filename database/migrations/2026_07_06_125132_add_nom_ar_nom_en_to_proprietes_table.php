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
        Schema::table('proprietes', function (Blueprint $table) {
            $table->string('nom_ar')->nullable()->after('nom');
            $table->string('nom_en')->nullable()->after('nom_ar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('proprietes', function (Blueprint $table) {
            $table->dropColumn(['nom_ar', 'nom_en']);
        });
    }
};
