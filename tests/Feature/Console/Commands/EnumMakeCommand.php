<?php

use Illuminate\Support\Facades\File;

test('can create a new enum class', function () {
    $name = ucfirst(fake()->word);
    $type = 'string';

    $this->artisan('make:enum', [
        'name' => $name,
        'type' => $type,
    ])->assertExitCode(0);

    $this->assertFileExists(app_path("Enums/$name.php"));
    $this->assertStringContainsString("enum $name: $type", File::get(app_path("Enums/$name.php")));

    File::delete(app_path("Enums/$name.php"));
});
