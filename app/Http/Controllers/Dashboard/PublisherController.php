<?php

namespace App\Http\Controllers\Dashboard;

class PublisherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.publishers.index');
    }
}
