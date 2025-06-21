<?php

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

/**
 * Get the application name.
 */
function app_name(): string
{
    return \Illuminate\Support\Arr::get(setting('app_name'), app()->getLocale()) ?: config('app.name');
}

/**
 * Get the application url.
 */
function app_url(): string
{
    return \Illuminate\Support\Facades\URL::to('/');
}

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

/**
 * Trigger the build of the dependencies.
 */
function trigger_dependencies_build(): void
{
    \Illuminate\Support\Facades\File::deleteDirectories(public_path('assets/dist'));
    \Illuminate\Support\Facades\File::delete(public_path('assets/dist/lock'));
}

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

/**
 * Format the given phone number.
 */
function format_phone(string $phone, string $country = 'EG'): string
{
    $instance = \libphonenumber\PhoneNumberUtil::getInstance();

    return $instance->format($instance->parse($phone, $country), \libphonenumber\PhoneNumberFormat::E164);
}

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

/**
 * Switch the block.
 */
function switch_badge(mixed $value, ?string $true = null, ?string $false = null): string
{
    $true = $true ?: __('Yes');
    $false = $false ?: __('No');

    return $value ? '<span class="badge bg-success-lt">' . $true . '</span>' : '<span class="badge bg-danger-lt">' . $false . '</span>';
}

/**
 * Get the current logged in admin.
 */
function current_admin(): ?\App\Models\Admin
{
    return auth('admins')->user();
}

/**
 * Get the current logged in user.
 */
function current_user(): ?\App\Models\User
{
    return auth('users')->user();
}

/**
 * Get the current logged in publisher.
 */
function current_publisher(): ?\App\Models\Publisher
{
    return current_user()->publisher;
}

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

/**
 * Render the no content message.
 */
function no_content(): string
{
    return '<p class="text-muted">' . __('No content') . '</p>';
}

/**
 * Check if the given path is an image.
 */
function is_image(string $path): bool
{
    return str_starts_with(mime_content_type($path), 'image/');
}

/**
 * Create a thumbnail for the given path inside the same directory under "thumbnails" directory.
 */
function create_thumbnail(string $path, int $width = 100, int $height = 100, int $quality = 85): ?string
{
    // Check if file exists
    if (! file_exists($path)) {
        throw new InvalidArgumentException("File does not exist: $path");
    }

    // Check if file is an image
    if (! is_image($path)) {
        throw new InvalidArgumentException("File is not an image: $path");
    }

    // Get file info
    $pathInfo = pathinfo($path);
    $directory = $pathInfo['dirname'];
    $filename = $pathInfo['filename'];
    $extension = strtolower($pathInfo['extension']);

    // Create thumbnails directory if it doesn't exist
    $thumbnailsDir = $directory . DIRECTORY_SEPARATOR . 'thumbnails';
    if (! is_dir($thumbnailsDir)) {
        mkdir($thumbnailsDir, 0755, true);
    }

    // Generate thumbnail filename
    $thumbnailPath = $thumbnailsDir . DIRECTORY_SEPARATOR . $filename . '-thumb.' . $extension;

    // Return existing thumbnail if it exists and is newer than the original
    if (file_exists($thumbnailPath) && filemtime($thumbnailPath) >= filemtime($path)) {
        return $thumbnailPath;
    }

    // Get image dimensions and type
    $imageInfo = getimagesize($path);
    if ($imageInfo === false) {
        throw new InvalidArgumentException("Unable to get image information: $path");
    }

    [$originalWidth, $originalHeight, $imageType] = $imageInfo;

    // Create image resource from file
    $sourceImage = match ($imageType) {
        IMAGETYPE_JPEG => imagecreatefromjpeg($path),
        IMAGETYPE_PNG => imagecreatefrompng($path),
        IMAGETYPE_GIF => imagecreatefromgif($path),
        IMAGETYPE_WEBP => imagecreatefromwebp($path),
        default => throw new InvalidArgumentException("Unsupported image type: $extension")
    };

    if ($sourceImage === false) {
        throw new RuntimeException("Failed to create image resource from: $path");
    }

    // Calculate thumbnail dimensions maintaining aspect ratio
    $aspectRatio = $originalWidth / $originalHeight;

    if ($width / $height > $aspectRatio) {
        $thumbnailWidth = (int) ($height * $aspectRatio);
        $thumbnailHeight = $height;
    } else {
        $thumbnailWidth = $width;
        $thumbnailHeight = (int) ($width / $aspectRatio);
    }

    // Create thumbnail image
    $thumbnailImage = imagecreatetruecolor($thumbnailWidth, $thumbnailHeight);

    if ($thumbnailImage === false) {
        imagedestroy($sourceImage);
        throw new RuntimeException('Failed to create thumbnail image resource');
    }

    // Preserve transparency for PNG and GIF
    if ($imageType === IMAGETYPE_PNG || $imageType === IMAGETYPE_GIF) {
        imagealphablending($thumbnailImage, false);
        imagesavealpha($thumbnailImage, true);
        $transparent = imagecolorallocatealpha($thumbnailImage, 255, 255, 255, 127);
        imagefill($thumbnailImage, 0, 0, $transparent);
    }

    // Resize image
    if (! imagecopyresampled($thumbnailImage, $sourceImage, 0, 0, 0, 0, $thumbnailWidth, $thumbnailHeight, $originalWidth, $originalHeight)) {
        imagedestroy($sourceImage);
        imagedestroy($thumbnailImage);
        throw new RuntimeException('Failed to resize image');
    }

    // Save thumbnail
    $success = match ($imageType) {
        IMAGETYPE_JPEG => imagejpeg($thumbnailImage, $thumbnailPath, $quality),
        IMAGETYPE_PNG => imagepng($thumbnailImage, $thumbnailPath, 6),
        IMAGETYPE_GIF => imagegif($thumbnailImage, $thumbnailPath),
        IMAGETYPE_WEBP => imagewebp($thumbnailImage, $thumbnailPath, $quality),
        default => false
    };

    // Clean up memory
    imagedestroy($sourceImage);
    imagedestroy($thumbnailImage);

    if (! $success) {
        throw new RuntimeException("Failed to save thumbnail: $thumbnailPath");
    }

    return str_replace(public_path(), '', $thumbnailPath);
}
