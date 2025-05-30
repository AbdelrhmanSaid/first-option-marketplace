<?php

use App\Jobs\PublishLanguageTokens;
use App\Models\Admin;
use App\Models\Language;
use Illuminate\Support\Facades\Bus;

test('publish language tokens run the job synchronously', function () {
    Bus::fake();

    $admin = Admin::factory()->create();
    $language = Language::firstOrCreate(['code' => 'en'], ['name' => 'English']);

    $response = $this->actingAs($admin, 'admins')->get(route('dashboard.languages.tokens.publish', $language));

    $response->assertRedirect();

    Bus::assertDispatchedSync(PublishLanguageTokens::class);
});
