<?php

namespace App\Traits;

use Stichoza\GoogleTranslate\GoogleTranslate;
use Illuminate\Support\Facades\Log;

trait TranslateTrait
{
    public static function TranslateText($text)
    {
        $locale = app()->getLocale();

        if ($locale == "fr" || $text == "") {
            return $text;
        }

        $cacheKey = 'translation_' . md5($text . '_' . $locale);

        // Check cache first, outside the closure
        if (cache()->has($cacheKey)) {
            return cache()->get($cacheKey);
        }

        try {
            $translated = retry(2, function () use ($text, $locale) {
                $tr = new GoogleTranslate($locale);
                return $tr->translate($text);
            }, 1500); // wait 1.5s between retries

            // Cache successful translations for a long time
            cache()->put($cacheKey, $translated, now()->addDays(30));

            return $translated;
        } catch (\Throwable $e) {
            Log::warning('Translation failed, falling back to original text', [
                'text' => $text,
                'locale' => $locale,
                'error' => $e->getMessage(),
            ]);

            // Cache the fallback briefly so we don't retry Google on every
            // request for this string while it's rate-limiting us
            cache()->put($cacheKey, $text, now()->addMinutes(10));

            return $text;
        }
    }
}
