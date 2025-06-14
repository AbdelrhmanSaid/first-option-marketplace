<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\StaticPage;

class StaticPageController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(StaticPage $staticPage)
    {
        return view('website.static-pages.show', [
            'staticPage' => $staticPage,
        ]);
    }
}
