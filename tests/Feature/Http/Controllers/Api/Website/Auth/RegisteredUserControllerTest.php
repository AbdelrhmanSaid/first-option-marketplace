<?php

test('user can register', function () {
    $response = $this->post(route('api.website.register'), [
        'first_name' => fake()->firstName,
        'last_name' => fake()->lastName,
        'email' => fake()->unique()->safeEmail,
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertStatus(201);
    $response->assertJsonStructure(['payload' => ['token', 'token_type']]);
});
