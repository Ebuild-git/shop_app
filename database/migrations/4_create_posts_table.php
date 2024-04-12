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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string("titre");
            $table->text("description")->nullable()->default(null);
            $table->json("photos");
            $table->unsignedBigInteger("id_region");
            $table->unsignedBigInteger("id_user");
            $table->unsignedBigInteger("id_user_buy")->nullable()->default(null);
            $table->unsignedBigInteger("id_sous_categorie");
            $table->json("proprietes")->nullable();
            $table->enum('etat',['Neuf avec étiquettes','Neuf sans étiquettes','Très bon état','Bon état','Usé']);
            $table->decimal("prix", 13, 3);
            $table->decimal("prix_achat", 13, 3)->nullable()->default(null);
            $table->decimal("old_achat", 13, 3)->nullable()->default(null);
            $table->timestamp('verified_at')->nullable()->default(null);
            $table->timestamp('sell_at')->nullable()->default(null);
            $table->timestamp("delivered_at")->nullable()->default(null);
            $table->enum("statut",["validation","vente","vendu","livraison","livré","refusé"])->default("validation");
            $table->timestamps();



            $table->foreign('id_user_buy')->references('id')->on('users');
            $table->foreign('id_region')->references('id')->on('regions')->onDelete('cascade');
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_sous_categorie')->references('id')->on('sous_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
