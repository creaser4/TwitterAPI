<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Tweet;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tweet>
 */
class TweetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Tweet::class;

    public function definition(): array
    {
        return [
            'text' => $this->faker->text(140), // Generate a random text with up to 140 characters
            'user_id' => function () {
                return \App\Models\User::factory()->create()->id; // Assuming you have a User model
            },
            'media' => null, // Example: Set media to null by default
        ];
    }
}
