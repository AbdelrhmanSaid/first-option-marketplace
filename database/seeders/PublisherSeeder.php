<?php

namespace Database\Seeders;

use App\Enums\PublisherMemberRole;
use App\Models\Publisher;
use App\Models\User;
use Illuminate\Database\Seeder;

class PublisherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::factory()->create([
            'first_name' => 'Kamal',
            'last_name' => 'Shawky',
            'email' => 'kamal@firstoption-es.com',
            'email_verified_at' => now(),
            'password' => 'password',
        ]);

        $publisher = Publisher::create([
            'name' => 'First Option',
            'headline' => 'The official first option publisher account',
            'email' => 'support@firstoption-es.com',
            'logo' => asset('assets/images/publisher-logo.png'),
            'website' => 'https://firstoption-es.com',
            'is_verified' => true,
        ]);

        $publisher->members()->create([
            'user_id' => $user->id,
            'role' => PublisherMemberRole::Owner->value,
        ]);
    }
}
