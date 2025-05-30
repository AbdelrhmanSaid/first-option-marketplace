<?php

use App\Models\ShortenedUrl;

test('redirect to the shortened URL', function () {
    $shortenedUrl = ShortenedUrl::factory()->create();

    $response = $this->get(route('website.shortened-urls.show', $shortenedUrl));

    $response->assertRedirect($shortenedUrl->url);
    $this->assertDatabaseHas('shortened_urls', [
        'id' => $shortenedUrl->id,
        'clicks' => $shortenedUrl->clicks + 1,
    ]);
});
