<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\URL;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    /**
     * Determine if database seeder should run when refreshing the database.
     */
    protected $seed = true;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Disable "RoutePermission" middleware for all tests
        $this->withoutMiddleware(\App\Http\Middleware\Dashboard\RoutePermission::class);

        // Set the default locale for all tests
        URL::defaults(['locale' => app()->getLocale()]);
    }
}
