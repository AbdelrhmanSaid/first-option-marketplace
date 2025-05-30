<?php

use Illuminate\Support\Facades\File;

test('build dependencies command builds language files', function () {
    File::delete(public_path('assets/dist/lock.json'));

    $this->artisan('dependencies:build')->assertExitCode(0);

    $this->assertFileExists(public_path('assets/dist/lock.json'));
});
