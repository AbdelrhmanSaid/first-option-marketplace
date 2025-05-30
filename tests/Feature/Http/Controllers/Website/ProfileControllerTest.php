<?php

use App\Models\User;

test('profile page is displayed', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user, 'users')->get(route('website.profile.edit'));

    $response->assertOk();
});

test('profile information can be updated', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user, 'users')->put(route('website.profile.update'), [
        'first_name' => fake()->firstName,
        'last_name' => fake()->lastName,
        'email' => fake()->unique()->safeEmail,
    ]);

    $response->assertSessionHasNoErrors();

    $user->refresh();
});

test('user can update password', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user, 'users')->put(route('website.profile.update'), [
        'password' => 'new-password',
        'password_confirmation' => 'new-password',
    ]);

    $response->assertSessionHasNoErrors();
});
