<?php

use App\Traits\CanUploadFile;
use Illuminate\Http\UploadedFile;

test('can upload file', function () {
    $file = UploadedFile::fake()->image('avatar.jpg');

    $class = new class
    {
        use CanUploadFile;
    };

    $path = $class->uploadFile($file, 'avatars');

    $this->assertStringStartsWith(url('uploads/avatars'), $path);
});

test('can delete file', function () {
    $file = UploadedFile::fake()->image('avatar.jpg');

    $class = new class
    {
        use CanUploadFile;
    };

    $path = $class->uploadFile($file, 'avatars');
    $path = public_path(str_replace(url(''), '', $path));

    $class->deleteFile($path);

    $this->assertFileDoesNotExist($path);
});

test('can delete file from url', function () {
    $file = UploadedFile::fake()->image('avatar.jpg');

    $class = new class
    {
        use CanUploadFile;
    };

    $path = $class->uploadFile($file, 'avatars');

    $class->deleteFileFromUrl($path);

    $this->assertFileDoesNotExist(public_path(str_replace(url(''), '', $path)));
});
