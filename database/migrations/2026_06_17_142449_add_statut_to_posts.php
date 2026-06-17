<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE posts
            MODIFY COLUMN statut ENUM(
                'validation',
                'vente',
                'vendu',
                'livraison',
                'livré',
                'refusé',
                'préparation',
                'en voyage',
                'en cours de livraison',
                'ramassée',
                'retourné',
                'commande confirmée',
                'tentative de livraison',
                'retourné à l\'expéditeur',
                'annulé',
                'livraison retardée',
                'ramassage planifié',
                'reprogrammé'
            ) NOT NULL DEFAULT 'validation'
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE posts
            MODIFY COLUMN statut ENUM(
                'validation',
                'vente',
                'vendu',
                'livraison',
                'livré',
                'refusé',
                'préparation',
                'en voyage',
                'en cours de livraison',
                'ramassée',
                'retourné'
            ) NOT NULL DEFAULT 'validation'
        ");
    }
};
