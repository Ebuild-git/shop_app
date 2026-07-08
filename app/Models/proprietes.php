<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class proprietes extends Model
{
    use HasFactory;

    protected $fillable = [
        'order', 'nom', 'nom_ar', 'nom_en'
    ];

    protected $casts = [
        'options' => 'json',
        'options_order' => 'json',
    ];

    public function localizedNom(?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();

        return match ($locale) {
            'en' => $this->nom_en ?: $this->nom,
            'ar' => $this->nom_ar ?: $this->nom,
            default => $this->nom,
        };
    }

    public function localizedOptions(?string $locale = null): array
    {
        $locale = $locale ?? app()->getLocale();

        return collect($this->options ?? [])->map(function ($option) use ($locale) {
            if (is_array($option)) {
                $value = $option['value'] ?? ($option['titre'] ?? '');
                $label = match ($locale) {
                    'en' => $option['title_en'] ?: ($option['titre'] ?? $value),
                    'ar' => $option['title_ar'] ?: ($option['titre'] ?? $value),
                    default => $option['titre'] ?? $value,
                };

                return ['value' => $value, 'label' => $label];
            }

            // legacy plain-string option
            return ['value' => $option, 'label' => $option];
        })->values()->toArray();
    }

    /**
     * Given a property name and a raw value stored on a post,
     * return its localized label. Falls back to the raw value
     * if no property/option match is found (e.g. free-text or color props).
     */
    public static function localizeValueForName(string $nom, $value, ?string $locale = null)
    {
        if ($value === null || $value === '') {
            return $value;
        }

        $propriete = static::where('nom', $nom)->first();

        if (! $propriete || $propriete->type !== 'option') {
            return $value;
        }

        foreach ($propriete->localizedOptions($locale) as $option) {
            if ($option['value'] === $value) {
                return $option['label'];
            }
        }

        return $value;
    }
    /**
     * Localize the *field label itself* (e.g. "Taille" -> "Size" -> "الحجم")
     * given the property `nom` used as a key on posts.proprietes.
     */
    public static function localizeNomForName(string $nom, ?string $locale = null): string
    {
        $propriete = static::where('nom', $nom)->first();

        return $propriete ? $propriete->localizedNom($locale) : $nom;
    }

    public function toDisplayArray(): array
    {
        $data = [
            'nom'    => $this->nom,
            'nom_en' => $this->nom_en,
            'nom_ar' => $this->nom_ar,
            'type'   => $this->type,
        ];

        if ($this->type === 'option') {
            $data['options'] = collect($this->options ?? [])->map(function ($option) {
                if (is_array($option)) {
                    return [
                        'value'    => $option['value'] ?? ($option['titre'] ?? ''),
                        'titre'    => $option['titre'] ?? '',
                        'title_en' => $option['title_en'] ?? '',
                        'title_ar' => $option['title_ar'] ?? '',
                    ];
                }

                // legacy plain-string option
                return [
                    'value'    => $option,
                    'titre'    => $option,
                    'title_en' => '',
                    'title_ar' => '',
                ];
            })->values()->toArray();
        }

        return $data;
    }

    public function orderedOptions(?string $locale = null): array
    {
        $locale = $locale ?: app()->getLocale();
        $options = collect($this->options ?? []);
        $order = data_get($this->options_order, $locale);

        $keyOf = fn($o) => is_array($o) ? ($o['value'] ?? $o['titre'] ?? '') : $o;

        if (!$order) {
            return $options->values()->toArray(); // fallback: default array order
        }

        $byValue = $options->keyBy($keyOf);

        $ordered = collect($order)->map(fn($v) => $byValue->get($v))->filter()->values();

        // keep any newly-added option that isn't in the saved order yet
        $missing = $options->reject(fn($o) => in_array($keyOf($o), $order));

        return $ordered->concat($missing)->values()->toArray();
    }
}
