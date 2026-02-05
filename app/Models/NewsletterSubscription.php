<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class NewsletterSubscription extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'email',
        'name',
        'consent_rgpd',
        'consent_rgpd_at',
        'consent_text',
        'source',
        'ip_address',
        'user_agent',
        'active',
        'subscribed_at',
        'unsubscribed_at',
        'unsubscribe_token'
    ];

    protected $casts = [
        'consent_rgpd' => 'boolean',
        'consent_rgpd_at' => 'datetime',
        'active' => 'boolean',
        'subscribed_at' => 'datetime',
        'unsubscribed_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->unsubscribe_token)) {
                $model->unsubscribe_token = Str::random(32);
            }
            if ($model->active && empty($model->subscribed_at)) {
                $model->subscribed_at = now();
            }
        });
    }

    /**
     * Désabonner l'utilisateur
     */
    public function unsubscribe()
    {
        $this->update([
            'active' => false,
            'unsubscribed_at' => now()
        ]);
    }

    /**
     * Réabonner l'utilisateur
     */
    public function resubscribe()
    {
        $this->update([
            'active' => true,
            'subscribed_at' => now(),
            'unsubscribed_at' => null
        ]);
    }

    /**
     * Scope pour les abonnés actifs
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Vérifier si l'abonnement est valide RGPD
     */
    public function isRgpdCompliant()
    {
        return $this->consent_rgpd &&
               $this->consent_rgpd_at &&
               $this->consent_rgpd_at->diffInYears(now()) < 2;
    }
}
