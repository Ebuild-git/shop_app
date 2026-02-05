<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use HasFactory, SoftDeletes;


    protected $fillable = [
        'name',
        'email',
        'subject',
        'message',
        'consent_rgpd',
        'consent_rgpd_at',
        'consent_newsletter',
        'newsletter_subscribed_at',
        'ip_address',
        'user_agent',
        'status',
        'archived'
    ];

    protected $casts = [
        'consent_rgpd' => 'boolean',
        'consent_rgpd_at' => 'datetime',
        'consent_newsletter' => 'boolean',
        'newsletter_subscribed_at' => 'datetime',
        'archived' => 'boolean',
    ];

    /**
     * Scope pour les messages non lus
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope pour les messages récents
     */
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Marquer comme lu
     */
    public function markAsRead()
    {
        $this->update(['status' => 'read']);
    }

    /**
     * Marquer comme répondu
     */
    public function markAsReplied()
    {
        $this->update(['status' => 'replied']);
    }

    /**
     * Archiver le message
     */
    public function archive()
    {
        $this->update(['archived' => true]);
    }

    public function isRgpdConsentValid()
    {
        if (!$this->consent_rgpd_at) {
            return false;
        }
        return $this->consent_rgpd_at->diffInYears(now()) < 2;
    }
}
