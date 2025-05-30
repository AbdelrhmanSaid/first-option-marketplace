<?php

use App\Models\User;
use Illuminate\Support\Facades\Notification;

beforeEach(function () {
    $this->withoutMiddleware(\Illuminate\Routing\Middleware\ThrottleRequests::class);
});

test('user can request email verification notification', function () {
    Notification::fake();

    $user = User::factory()->unverified()->create();

    $this->actingAs($user, 'users');

    $response = $this->from(route('website.index'))->post(route('website.verification.send'));

    Notification::assertSentTo($user, \Illuminate\Auth\Notifications\VerifyEmail::class);

    $response->assertRedirect(route('website.index'));
});
