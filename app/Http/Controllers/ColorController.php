<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Stichoza\GoogleTranslate\GoogleTranslate;
class ColorController extends Controller
{
    private $googleTranslateApiKey = 'YOUR_GOOGLE_TRANSLATE_API_KEY';

    public function getColorName(Request $request)
    {
        $colorCode = ltrim($request->input('color'), '#');
        $response = Http::get("https://www.thecolorapi.com/id?hex={$colorCode}");

        if ($response->successful()) {
            $colorData = $response->json();
            $colorNameEn = $colorData['name']['value'];

            // Traduire le nom de la couleur en français
            $translatedName = $this->translateColorName($colorNameEn, 'fr');


            return response()->json([
                'name' => $translatedName,
                'hex' => $colorData['hex']['value']
            ]);
        } else {
            return response()->json(['error' => 'Unable to retrieve color name'], 500);
        }
    }

    private function translateColorName($text, $targetLanguage)
    {
        $tr = new GoogleTranslate();
        $tr->setSource('en'); // Translate from English
        $tr->setTarget($targetLanguage);

        // Output the translated text
        return $tr->translate($text);
    }
}
