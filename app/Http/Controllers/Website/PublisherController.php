<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Publisher;

class PublisherController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(Publisher $publisher)
    {
        return view('website.publishers.show', [
            'publisher' => $publisher,
        ]);
    }
}
