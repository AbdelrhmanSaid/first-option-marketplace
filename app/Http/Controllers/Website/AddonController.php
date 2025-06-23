<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Addon;

class AddonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $addons = Addon::query()->orderBy('name')->paginate(10);

        return view('website.addons.index', [
            'addons' => $addons,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Addon $addon)
    {
        return view('website.addons.show', [
            'addon' => $addon,
        ]);
    }
}
