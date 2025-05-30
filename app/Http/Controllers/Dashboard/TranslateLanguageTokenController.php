<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Language;
use App\Models\LanguageToken;
use Stichoza\GoogleTranslate\GoogleTranslate;

class TranslateLanguageTokenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __invoke(Language $language, LanguageToken $token)
    {
        $text = match (true) {
            $token->from_json && ! str_starts_with($token->key, 'sidebar.') => $token->key,
            default => __($token->key, [], config('app.fallback_locale')),
        };

        $translator = new GoogleTranslate($language->code);
        $translator->preserveParameters();

        return response()->json([
            'translation' => $translator->translate($text),
        ]);
    }
}
