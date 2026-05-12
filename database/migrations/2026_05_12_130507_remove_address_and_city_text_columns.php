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
        $table->dropColumn('address');
        });

        Schema::table('user_addresses', function (Blueprint $table) {
            $table->dropColumn('city');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('address')->nullable();
        });

        Schema::table('user_addresses', function (Blueprint $table) {
            $table->string('city')->nullable();
        });
    }
};
