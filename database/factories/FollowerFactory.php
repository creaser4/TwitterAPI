<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Follower;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Follower>
 */
class FollowerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Follower::class;

    public function definition(): array
    {
        // Generate random follower_id and following_id values based on user IDs
        $followerUser = \App\Models\User::inRandomOrder()->first();
        $followingUser = \App\Models\User::where('id', '!=', $followerUser->id)->inRandomOrder()->first();

        return [
            'follower_id' => $followerUser->id,
            'following_id' => $followingUser->id,
        ];
    }
}
