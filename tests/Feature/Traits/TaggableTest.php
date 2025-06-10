<?php

use App\Models\ShortenedUrl;

test('attachTag adds tags to the model', function () {
    $url = ShortenedUrl::factory()->create(['tags' => ['foo']]);

    $url->attachTag('bar');
    $url->refresh();

    expect($url->tags)->toBe(['foo', 'bar']);
});

test('detachTag removes tags from the model', function () {
    $url = ShortenedUrl::factory()->create(['tags' => ['foo', 'bar']]);

    $url->detachTag('bar');
    $url->refresh();

    expect($url->tags)->toBe(['foo']);
});
