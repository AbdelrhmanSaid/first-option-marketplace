<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PublisherRegisterController extends Controller
{
    /**
     * Show the publisher register form.
     */
    public function create()
    {
        return view('website.publisher.register');
    }

    /**
     * Store the publisher registration.
     */
    public function store(Request $request)
    {
        dd($request->all());
    }
}
