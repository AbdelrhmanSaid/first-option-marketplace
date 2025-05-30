<?php

namespace App\Providers;

use App\Models\Language;
use Exception;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Js;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureBlade();
        $this->configurePaginatorView();

        $this->configureApiRateLimiter();
        $this->configureConvertEmptyStringToNull();

        $this->configureApplicationLocales();
        $this->configureDestructiveCommands();

        $this->configureValidationRules();
    }

    /**
     * Configure Blade directives and components.
     */
    protected function configureBlade(): void
    {
        Blade::anonymousComponentPath(resource_path('layouts'), 'layouts');
        Blade::componentNamespace('App\\View\\Layouts', 'layouts');

        Blade::directive('themer', function ($expression = 'theme') {
            $path = hashed_asset('assets/js/themer.js');
            $expression = str_replace(['"', "'", '`'], '', $expression);
            $config = Js::encode(setting('theme'));

            return Blade::compileString(
                <<<EOT
                    @push('pre-content')
                        <script>window.themerKey = '$expression';</script>
                        <script>window.themeConfig = $config;</script>
                        <script src="$path"></script>
                    @endpush
                EOT
            );
        });
    }

    /**
     * Configure the default pagination view.
     */
    protected function configurePaginatorView(): void
    {
        Paginator::defaultView('components.pagination');
    }

    /**
     * Configure the rate limiter for the API.
     */
    protected function configureApiRateLimiter(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }

    /**
     * Configure the conversion of empty strings to null.
     */
    protected function configureConvertEmptyStringToNull(): void
    {
        ConvertEmptyStringsToNull::skipWhen(function (Request $request) {
            return $request->is('*settings*') && $request->isMethod('put');
        });
    }

    /**
     * Configure the application locales.
     */
    protected function configureApplicationLocales(): void
    {
        try {
            config(['app.locales' => Language::pluck('name', 'code')->toArray()]);
        } catch (Exception) {
            config(['app.locales' => array_column(config('redot.locales'), 'name', 'code')]);
        }

        // Set the default locale to the first locale in the locales array
        URL::defaults(['locale' => Arr::first(array_keys(config('app.locales')))]);
    }

    /**
     * Configure the destructive commands.
     */
    protected function configureDestructiveCommands(): void
    {
        DB::prohibitDestructiveCommands(app()->environment('production'));
    }

    /**
     * Configure the custom validation rules.
     */
    protected function configureValidationRules(): void
    {
        Validator::extend('phone', function ($attribute, $value, $parameters) {
            return (new \App\Rules\Phone(...$parameters))->passes($attribute, $value);
        });

        Validator::extend('captcha', function ($attribute, $value, $parameters) {
            return (new \App\Rules\Captcha(...$parameters))->passes($attribute, $value);
        });
    }
}
