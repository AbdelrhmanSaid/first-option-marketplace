<?php

namespace App\Http\Controllers\Website;

use App\Models\ShortenedUrl;

class ShortenedUrlController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(?ShortenedUrl $shortenedUrl = null)
    {
        if (is_null($shortenedUrl)) {
            return redirect()->route('website.index');
        }

        $shortenedUrl->increment('clicks');

        return redirect($shortenedUrl->url);
    }
}
