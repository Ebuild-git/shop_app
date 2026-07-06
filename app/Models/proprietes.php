<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class proprietes extends Model
{
    use HasFactory;

    protected $fillable = [
        'order'
    ];

    protected $casts = [
        'options' => 'json',
    ];

    // public function localizedOptions(): \Illuminate\Support\Collection
    // {
    //     $key = match (app()->getLocale()) {
    //         'en' => 'title_en',
    //         'ar' => 'title_ar',
    //         default => 'titre',
    //     };

    //     return collect($this->options ?? [])->map(fn ($option) => [
    //         'value' => $option['value'] ?? $option['titre'] ?? $option,
    //         'label' => $option[$key] ?? $option['titre'] ?? (is_string($option) ? $option : ''),
    //     ]);
    // }
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
}
