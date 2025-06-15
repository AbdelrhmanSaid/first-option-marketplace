<?php

namespace Database\Seeders;

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
            'password' => 'password',
        ]);

        $user->publisher()->create([
            'name' => 'First Option',
            'headline' => 'The official First Option Publisher account',
            'email' => 'support@firstoption-es.com',
            'header_image' => asset('assets/images/default-header.jpg'),
            'logo' => asset('assets/images/publisher-logo.png'),
            'website' => 'https://firstoption-es.com',
            'is_verified' => true,
        ]);
    }
}
