<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Stichoza\GoogleTranslate\GoogleTranslate;

class ColorController extends Controller
{
    public function getColorName(Request $request)
    {
        $colorCode = ltrim($request->input('color'), '#');

        $cacheKey = 'colorapi_hex_' . strtolower($colorCode);
        $colorData = cache()->remember($cacheKey, now()->addDays(30), function () use ($colorCode) {
            $response = Http::get("https://www.thecolorapi.com/id?hex={$colorCode}");
            return $response->successful() ? $response->json() : null;
        });

        if (!$colorData) {
            return response()->json(['error' => 'Unable to retrieve color name'], 500);
        }

        $colorNameEn = $colorData['name']['value'] ?? 'Unknown';
        $translatedName = $this->translateColorName($colorNameEn, 'fr');

        return response()->json([
            'name' => $translatedName,
            'hex' => $colorData['hex']['value'],
        ]);
    }

    private function translateColorName($text, $targetLanguage)
    {
        $cacheKey = 'color_translation_' . md5($text . '_' . $targetLanguage);

        if (cache()->has($cacheKey)) {
            return cache()->get($cacheKey);
        }

        try {
            $translated = retry(2, function () use ($text, $targetLanguage) {
                $tr = new GoogleTranslate();
                $tr->setSource('en');
                $tr->setTarget($targetLanguage);
                return $tr->translate($text);
            }, 1500);

            cache()->put($cacheKey, $translated, now()->addDays(30));
            return $translated;
        } catch (\Throwable $e) {
            Log::warning('Color name translation failed, falling back to English', [
                'text' => $text,
                'target' => $targetLanguage,
                'error' => $e->getMessage(),
            ]);

            cache()->put($cacheKey, $text, now()->addMinutes(10));
            return $text;
        }
    }
}
