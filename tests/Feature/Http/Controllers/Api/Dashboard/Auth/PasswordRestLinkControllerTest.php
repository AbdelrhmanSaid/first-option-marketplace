<?php

use App\Models\Admin;
use Illuminate\Support\Facades\Notification;

test('admin can request password reset', function () {
    Notification::fake();

    $admin = Admin::factory()->create();

    $response = $this->post(route('api.dashboard.password.email'), [
        'email' => $admin->email,
    ]);

    Notification::assertSentTo($admin, Illuminate\Auth\Notifications\ResetPassword::class);

    $response->assertOk();
});
