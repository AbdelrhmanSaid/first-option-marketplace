<?php

namespace App\Http\Controllers\Website;

use App\Models\Addon;

class HomeController extends Controller
{
    /**
     * Show the website.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function __invoke()
    {
        return view('website.home.index', [
            'featuredAddons' => Addon::featured()->take(6)->get(),
        ]);
    }
}
