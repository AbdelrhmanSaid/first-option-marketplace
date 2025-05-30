<?php

use Illuminate\Support\Facades\Event;

test('register screen can be rendered', function () {
    $response = $this->get(route('website.register'));

    $response->assertOk();
});

test('user can register', function () {
    Event::fake();

    $response = $this->post(route('website.register.store'), [
        'first_name' => fake()->firstName,
        'last_name' => fake()->lastName,
        'email' => fake()->unique()->safeEmail,
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    Event::assertDispatched(\Illuminate\Auth\Events\Registered::class);

    $this->assertAuthenticated('users');
    $response->assertRedirect(route('website.index'));
});
