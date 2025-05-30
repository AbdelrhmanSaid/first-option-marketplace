<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ShortenedUrl>
 */
class ShortenedUrlFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'url' => $this->faker->url,
            'slug' => \Illuminate\Support\Str::random(8),
            'title' => $this->faker->sentence,
            'clicks' => $this->faker->numberBetween(0, 100),
            'tags' => $this->faker->words(5),
        ];
    }
}
