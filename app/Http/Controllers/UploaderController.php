<?php

namespace App\Http\Controllers;

use App\Traits\CanUploadFile;
use App\Traits\RespondAsApi;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;

class UploaderController extends Controller
{
    use CanUploadFile;
    use RespondAsApi;

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file',
            'config' => 'required|string',
        ]);

        $config = json_decode(decrypt($request->config));
        $file = $request->file('file');

        // Set the locale
        if ($config->locale) {
            app()->setLocale($config->locale);
        }

        // Validate the file type
        if ($config->accept) {
            $this->validateFileType($file, $config->accept);
        }

        // Run the server validation
        if ($config->serverValidation) {
            $request->validate(['file' => $config->serverValidation]);
        }

        // Get the path to store the file
        $url = $this->uploadFile($file, $config->directory, $config->optimize);
        $path = str_replace(URL::to('/'), '', $url);

        $payload = [
            'url' => $url,
            'path' => $path,
            'size' => filesize(public_path($path)),
        ];

        if ($config->thumbnail && is_image(public_path($path))) {
            $payload['thumbnail'] = @create_thumbnail(public_path($path)) or null;
        }

        return $this->respond($payload);
    }

    /**
     * Validate file type based on accept parameter.
     */
    protected function validateFileType(UploadedFile $file, string $accept)
    {
        $acceptedTypes = array_map('trim', explode(',', $accept));
        $fileMimeType = $file->getMimeType();
        $fileExtension = '.' . strtolower($file->getClientOriginalExtension());

        $isValid = false;

        foreach ($acceptedTypes as $type) {
            // Check wildcard accept all
            if ($type === '*' || $type === '*/*') {
                $isValid = true;
                break;
            }

            // Check file extension
            if (str_starts_with($type, '.')) {
                if (strtolower($type) === $fileExtension) {
                    $isValid = true;
                    break;
                }
            } elseif (str_ends_with($type, '/*')) {
                $category = str_replace('/*', '', $type);
                if (str_starts_with($fileMimeType, $category . '/')) {
                    $isValid = true;
                    break;
                }
            } else {
                if ($fileMimeType === $type) {
                    $isValid = true;
                    break;
                }
            }
        }

        if (! $isValid) {
            throw ValidationException::withMessages([
                'file' => [__('The file type is not allowed. Accepted types: :types', ['types' => $accept])],
            ]);
        }
    }
}
