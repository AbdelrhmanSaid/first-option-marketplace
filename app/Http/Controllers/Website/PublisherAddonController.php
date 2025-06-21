<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PublisherAddonController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('website.publishers.dashboard.addons.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }
}
