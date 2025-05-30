<?php

use Illuminate\Support\Facades\File;

test('it clears all uploads from the uploads directory', function () {
    $filename = fake()->word . '.txt';

    File::put(public_path('uploads/' . $filename), 'test');

    $this->artisan('uploads:clear')
        ->expectsConfirmation('Are you sure you want to delete all files in the uploads directory?', 'yes')
        ->assertExitCode(0);

    $this->assertFileDoesNotExist(public_path('uploads/' . $filename));
});

test('it does not clear uploads when the user declines', function () {
    $filename = fake()->word . '.txt';

    File::put(public_path('uploads/' . $filename), 'test');

    $this->artisan('uploads:clear')
        ->expectsConfirmation('Are you sure you want to delete all files in the uploads directory?', 'no')
        ->assertExitCode(0);

    $this->assertFileExists(public_path('uploads/' . $filename));
});

test('it fails when the uploads directory is empty', function () {
    $files = File::allFiles(public_path('uploads'));

    foreach ($files as $file) {
        File::delete($file->getPathname());
    }

    $this->artisan('uploads:clear')->assertExitCode(1);
});
