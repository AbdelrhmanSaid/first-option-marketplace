<?php

use App\Models\Setting;
use Illuminate\Support\Facades\File;

test('setting function return all settings as key-value pairs if no key is provided', function () {
    $this->assertEquals(
        Setting::all()->mapWithKeys(fn ($setting) => [$setting->key => $setting->value])->toArray(),
        setting()
    );
});

test('setting function return the specified setting value', function () {
    $setting = Setting::firstOrCreate(['key' => 'foo'], ['value' => 'bar']);

    $this->assertEquals('bar', setting('foo'));
});

test('trigger_dependecies_build function delete the dist directories and lock file', function () {
    File::shouldReceive('deleteDirectories')->with(public_path('assets/dist'))->once();
    File::shouldReceive('delete')->with(public_path('assets/dist/lock'))->once();

    trigger_dependencies_build();
});

test('format_phone function return the formatted phone number', function () {
    $this->assertEquals('+201234567890', format_phone('01234567890', 'EG'));
    $this->assertEquals('+14155552671', format_phone('4155552671', 'US'));
    $this->assertEquals('+447400123456', format_phone('07400123456', 'GB'));
});
