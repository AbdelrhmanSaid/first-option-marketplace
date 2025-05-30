<?php

if (! function_exists('setting')) {
    /**
     * Get the specified setting value.
     */
    function setting(?string $key = null, mixed $default = null, bool $fresh = false): mixed
    {
        if (is_null($key)) {
            return \App\Models\Setting::all()->pluck('value', 'key')->toArray();
        }

        return \App\Models\Setting::get($key, $default, $fresh);
    }
}

if (! function_exists('app_name')) {
    /**
     * Get the application name.
     */
    function app_name(): string
    {
        return \Illuminate\Support\Arr::get(setting('app_name'), app()->getLocale()) ?: config('app.name');
    }
}

if (! function_exists('app_url')) {
    /**
     * Get the application url.
     */
    function app_url(): string
    {
        return \Illuminate\Support\Facades\URL::to('/');
    }
}

if (! function_exists('route_from_url')) {
    /**
     * Get the route from the url.
     */
    function route_from_url(string $url): ?string
    {
        try {
            $request = \Illuminate\Http\Request::create($url);
            $matchedRoute = \Illuminate\Support\Facades\Route::getRoutes()->match($request);

            return $matchedRoute->getName() ?: null;
        } catch (Throwable) {
            return null;
        }
    }
}

if (! function_exists('route_allowed')) {
    /**
     * Check if the gate allows the given permission.
     */
    function route_allowed(string $route): bool
    {
        if (! auth('admins')->check()) {
            return false;
        }

        $user = auth('admins')->user();
        $key = 'permission.' . $user->id . '.' . $route;

        return cache()->rememberForever($key, function () use ($route) {
            return \Spatie\Permission\Models\Permission::whereName($route)->doesntExist() ||
                \Illuminate\Support\Facades\Gate::allows($route);
        });
    }
}

if (! function_exists('url_allowed')) {
    /**
     * Check if the url is allowed.
     */
    function url_allowed(string $url): bool
    {
        // Early return if the url is external
        if (! str_contains($url, str_replace(['http://', 'https://'], '', app_url()))) {
            return true;
        }

        return route_allowed(route_from_url($url) ?: '');
    }
}

if (! function_exists('trigger_dependencies_build')) {
    /**
     * Trigger the build of the dependencies.
     */
    function trigger_dependencies_build(): void
    {
        \Illuminate\Support\Facades\File::deleteDirectories(public_path('assets/dist'));
        \Illuminate\Support\Facades\File::delete(public_path('assets/dist/lock'));
    }
}

if (! function_exists('hashed_asset')) {
    /**
     * Generate a hashed asset path.
     */
    function hashed_asset(string $path, ?bool $secure = null): string
    {
        $hash = null;
        $realPath = public_path($path);

        if (file_exists($realPath)) {
            $hash = md5(filemtime($realPath));
        }

        return asset($path, $secure) . ($hash ? '?v=' . $hash : '');
    }
}

if (! function_exists('collect_ellipsis')) {
    /**
     * Collect the given array with ellipsis.
     */
    function collect_ellipsis($value = [], int $limit = 3, ?string $ellipsis = '...'): \Illuminate\Support\Collection
    {
        $collection = collect($value);
        $count = $collection->count();

        return $collection
            ->take($limit)
            ->when($count > $limit, function ($collection) use ($count, $limit, $ellipsis) {
                return $collection->push(__($ellipsis, ['count' => $count - $limit]));
            });
    }
}

if (! function_exists('format_phone')) {
    /**
     * Format the given phone number.
     */
    function format_phone(string $phone, string $country = 'EG'): string
    {
        $instance = \libphonenumber\PhoneNumberUtil::getInstance();

        return $instance->format($instance->parse($phone, $country), \libphonenumber\PhoneNumberFormat::E164);
    }
}

if (! function_exists('back_or_route')) {
    /**
     * Create a redirect response or redirect to a named route.
     */
    function back_or_route(string $route, mixed $parameters = [], bool $absolute = true): string
    {
        $url = url()->previous();
        $route = route($route, $parameters, $absolute);

        if (! $url || $url === url()->current()) {
            return $route;
        }

        return $url;
    }
}

if (! function_exists('switch_badge')) {
    /**
     * Switch the block.
     */
    function switch_badge(mixed $value, ?string $true = null, ?string $false = null): string
    {
        $true = $true ?: __('Yes');
        $false = $false ?: __('No');

        return $value ? '<span class="badge bg-success-lt">' . $true . '</span>' : '<span class="badge bg-danger-lt">' . $false . '</span>';
    }
}

if (! function_exists('current_admin')) {
    /**
     * Get the current logged in admin.
     */
    function current_admin(): ?\App\Models\Admin
    {
        return auth('admins')->user();
    }
}

if (! function_exists('current_user')) {
    /**
     * Get the current logged in user.
     */
    function current_user(): ?\App\Models\User
    {
        return auth('users')->user();
    }
}

if (! function_exists('component')) {
    /**
     * Render the given component.
     */
    function component(string $name, array $data = []): string|\Illuminate\View\View
    {
        // Define the base namespace for components
        $baseNamespace = app()->getNamespace() . 'View\\Components\\';

        // Convert the name to a fully qualified class name
        $className = class_exists($name) ? $name : $baseNamespace . str_replace(' ', '\\', ucwords(str_replace('.', ' ', $name)));

        if (! class_exists($className)) {
            // If the class does not exist, render the view as an inline component
            return view("components.$name", $data);
        }

        // Create a new component instance and render it
        return \Illuminate\Support\Facades\Blade::renderComponent(app()->make($className, $data));
    }
}

if (! function_exists('search_model')) {
    /**
     * Search the given model.
     */
    function search_model(\Illuminate\Database\Eloquent\Builder $query, array $columns = [], ?string $term = null): \Illuminate\Database\Eloquent\Builder
    {
        $term = trim($term);

        if (! $term) {
            return $query;
        }

        $handleRelation = function (&$query, string $column, string $term) {
            $relation = \Illuminate\Support\Str::beforeLast($column, '.');
            $column = \Illuminate\Support\Str::afterLast($column, '.');

            $query->orWhereHas($relation, function ($query) use ($column, $term) {
                $query->where($column, 'like', "%{$term}%");
            });
        };

        return $query->where(function ($query) use ($columns, $term, $handleRelation) {
            foreach ($columns as $column) {
                if (str_contains($column, '.')) {
                    $handleRelation($query, $column, $term);

                    continue;
                }

                $query->orWhere($column, 'like', "%{$term}%");
            }
        });
    }
}
