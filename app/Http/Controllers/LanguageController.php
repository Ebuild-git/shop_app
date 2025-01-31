<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\TranslateTrait;

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
}
