<?php

namespace Database\Seeders;

use App\Jobs\SyncLanguageTokens;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $languages = config('redot.locales');

        foreach ($languages as $language) {
            $this->seedLanguage($language);
        }
    }

    /**
     * Seed a language.
     */
    protected function seedLanguage(array $language): void
    {
        $language = \App\Models\Language::create([
            'code' => $language['code'],
            'name' => $language['name'] ?? strtoupper($language['code']),
            'is_rtl' => $language['is_rtl'] ?? false,
        ]);

        SyncLanguageTokens::dispatchSync($language);
    }
}
