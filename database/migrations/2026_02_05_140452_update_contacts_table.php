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
        Schema::table('contacts', function (Blueprint $table) {
            $table->string('subject');
             $table->boolean('consent_rgpd')->default(false);
            $table->timestamp('consent_rgpd_at')->nullable();

            $table->boolean('consent_newsletter')->default(false);
            $table->timestamp('newsletter_subscribed_at')->nullable();

            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();

            $table->enum('status', ['pending', 'read', 'replied', 'spam'])->default('pending');
            $table->boolean('archived')->default(false);

            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contacts', function (Blueprint $table) {

            $table->dropSoftDeletes();

            $table->dropColumn([
                'subject',
                'consent_rgpd',
                'consent_rgpd_at',
                'consent_newsletter',
                'newsletter_subscribed_at',
                'ip_address',
                'user_agent',
                'status',
                'archived',
            ]);
        });
    }

};
