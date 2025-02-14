<?php

use App\Models\configurations;
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
            $table->string("email")->nullable()->default(null);
            $table->string("adresse")->nullable()->default(null);
            $table->string("logo")->nullable()->default(null);
            $table->string("facebook")->nullable()->default(null);
            $table->string("linkedin")->nullable()->default(null);
            $table->string("tiktok")->nullable()->default(null);
            $table->string("instagram")->nullable()->default(null);
            $table->boolean('valider_photo')->default(false);
            $table->boolean('valider_publication')->default(false);
            $table->json('partenaires')->nullable()->default(null);
            $table->string("email_send_message")->nullable(true);
            $table->timestamps();
        });

        //create first save
        $configuration = configurations::firstorCreate();
        $configuration->email = "<EMAIL>";
        $configuration->save();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configurations');
    }
};
