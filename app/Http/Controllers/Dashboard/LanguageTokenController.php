<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Language;
use App\Models\LanguageToken;
use Illuminate\Http\Request;

class LanguageTokenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Language $language)
    {
        return view('dashboard.languages.tokens.index', [
            'language' => $language,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Language $language, LanguageToken $token)
    {
        return view('dashboard.languages.tokens.edit', [
            'language' => $language,
            'token' => $token,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Language $language, LanguageToken $token)
    {
        $request->validate([
            'value' => 'required|string',
        ]);

        $token->update([
            'value' => $request->input('value'),
        ]);

        return $this->updated(__('Language token'));
    }
}
