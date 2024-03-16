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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('username');
            $table->enum('certifier', ['oui', 'non'])->default("non");
            $table->string('phone_number')->nullable()->default(null);
            $table->string("ip_adress")->nullable()->default(null);
            $table->string("avatar")->nullable()->default("avatar.png");
            $table->string("adress")->nullable()->default(null);
            $table->string("gouvernorat")->nullable()->default(null);
            $table->string("role");
            $table->string("ville")->nullable()->default(null);
            $table->enum('type', ['user', 'shop']);
            $table->timestamp('email_verified_at')->nullable(true)->default(null);
            $table->timestamp('validate_at')->nullable()->default(null);
            $table->timestamp('photo_verified_at')->nullable()->default(null);
            $table->string('matricule')->nullable()->default(null);
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
