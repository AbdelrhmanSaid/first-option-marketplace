<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

trait CanUploadFile
{
    /**
     * Upload file to path.
     */
    public function uploadFile(UploadedFile|array $file, string $path = ''): string|array
    {
        if (is_array($file)) {
            return collect($file)->map(fn ($file) => $this->uploadFile($file, $path))->toArray();
        }

        $path = 'uploads/' . $path;
        $directory = dirname(public_path($path));
        File::ensureDirectoryExists($directory, 0755, true);

        $extension = $file->getClientOriginalExtension();
        $originalName = basename($file->getClientOriginalName(), '.' . $extension);
        $filename = Str::slug($originalName) . '-' . Str::random(8) . '.' . $extension;

        $file->move(public_path($path), $filename);

        return URL::to($path . '/' . $filename);
    }

    /**
     * Delete file from path.
     */
    public function deleteFile(string|array $path): bool|array
    {
        if (is_array($path)) {
            return collect($path)->map(fn ($path) => $this->deleteFile($path))->toArray();
        }

        $path = Str::startsWith($path, public_path()) ? $path : public_path($path);

        return File::delete($path);
    }

    /**
     * Delete file from path.
     */
    public function deleteFileFromUrl(string|array $url): bool|array
    {
        if (is_array($url)) {
            return collect($url)->map(fn ($url) => $this->deleteFileFromUrl($url))->toArray();
        }

        $path = str_replace(URL::to('/'), '', $url);

        return $this->deleteFile($path);
    }
}
