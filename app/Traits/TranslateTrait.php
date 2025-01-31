<?php

namespace App\Traits;
use Stichoza\GoogleTranslate\GoogleTranslate;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redis;

trait TranslateTrait
{

    public static function TranslateText($text)
    {
        $locale = app()->getLocale();

        // Si la langue est déjà "fr", pas besoin de traduire
        if ($locale == "fr" || $text == "") {
            return $text;
        }

        // Créer une clé unique pour chaque texte et langue
        $cacheKey = 'translation_' . md5($text . '_' . $locale);

        // Vérifier si la traduction est déjà en cache
        return cache()->remember($cacheKey, now()->addDays(7), function () use ($text, $locale) {
            $tr = new GoogleTranslate($locale);
            return $tr->translate($text);
        });
    }

}
