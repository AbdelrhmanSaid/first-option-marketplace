<?php

namespace App\Http\Controllers\Dashboard;

use App\Jobs\PublishLanguageTokens;
use App\Models\Language;

class PublishLanguageTokensController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Language $language)
    {
        PublishLanguageTokens::dispatchSync($language);

        return $this->success(__('Language tokens published successfully.'), 'dashboard.languages.tokens.index', $language);
    }
}
