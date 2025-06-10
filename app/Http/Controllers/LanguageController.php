<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\TranslateTrait;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;

class LanguageController extends Controller
{
    use TranslateTrait;

    public function setLanguage(Request $request)
    {
        $request->validate([
            'lang' => 'required|string|in:en,fr,ar'
        ]);

        session(['app_locale' => $request->lang]);

        return response()->json(['status' => 'success']);
    }

    public function changeLanguage(Request $request, $lang)
    {
        $availableLocales = ['en', 'fr', 'ar'];

        if (in_array($lang, $availableLocales)) {
            Cookie::queue('locale', $lang, 60 * 24 * 365);
            session(['locale' => $lang]);
            if (auth()->check()) {
                auth()->user()->update(['locale' => $lang]);
            }
        }

        return redirect()->back();
    }
}
