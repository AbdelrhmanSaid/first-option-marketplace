<?php

use App\Models\Admin;
use App\Models\Language;

it('can translate a language token', function () {
    $language = Language::firstOrCreate(['code' => 'en'], ['name' => 'English']);
    $token = $language->tokens()->firstOrCreate(['key' => fake()->unique()->word], [
        'value' => fake()->sentence,
        'original_translation' => fake()->sentence,
    ]);

    $response = $this->actingAs(Admin::factory()->create(), 'admins')->get(route('dashboard.languages.tokens.translate', [$language, $token]));

    $response->assertOk();
    $response->assertJsonStructure(['translation']);
});
