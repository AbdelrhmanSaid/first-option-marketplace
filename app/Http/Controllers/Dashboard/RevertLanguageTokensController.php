<?php

namespace App\Http\Controllers\Dashboard;

use App\Jobs\RevertLanguageTokens;
use App\Models\Language;

class RevertLanguageTokensController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Language $language)
    {
        RevertLanguageTokens::dispatchSync($language);

        return $this->success(__('Language tokens reverted successfully.'), 'dashboard.languages.tokens.index', $language);
    }
}
