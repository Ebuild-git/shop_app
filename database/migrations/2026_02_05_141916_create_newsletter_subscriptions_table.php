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
        Schema::create('newsletter_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('name')->nullable();

            // RGPD
            $table->boolean('consent_rgpd')->default(false);
            $table->timestamp('consent_rgpd_at')->nullable();
            $table->text('consent_text')->nullable();

            // Source
            $table->enum('source', ['contact_form', 'website_popup', 'checkout', 'manual'])->default('website_popup');

            // Tracking
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();

            // Status
            $table->boolean('active')->default(true);
            $table->timestamp('subscribed_at')->nullable();
            $table->timestamp('unsubscribed_at')->nullable();
            $table->string('unsubscribe_token')->unique()->nullable();

            // Soft deletes pour RGPD
            $table->softDeletes();
            $table->timestamps();

            // Indexes
            $table->index('active');
            $table->index('subscribed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('newsletter_subscriptions');
    }
};
