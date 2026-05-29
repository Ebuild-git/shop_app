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
        Schema::table('configurations', function (Blueprint $table) {
            $table->decimal('prix_min_luxury', 10, 2)->nullable()->after('email_send_message');
            $table->decimal('prix_min_non_luxury', 10, 2)->nullable()->after('prix_min_luxury');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('configurations', function (Blueprint $table) {
            $table->dropColumn(['prix_min_luxury', 'prix_min_non_luxury']);
        });
    }
};
