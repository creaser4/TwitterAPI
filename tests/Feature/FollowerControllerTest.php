<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Follower;

class FollowerControllerTest extends TestCase
{
    use RefreshDatabase; // Refresh the database before each test

    /** @test */
    public function it_can_follow_a_user()
    {
        // Create a user to follow
        $userToFollow = User::factory()->create();

        // Create an authenticated user
        $authenticatedUser = User::factory()->create();

        // Authenticate the user
        $this->actingAs($authenticatedUser);

        // Make a request to follow the user
        $response = $this->post("/api/Follower/follow/{$userToFollow->id}");

        // Assert that the response is successful and contains the expected message
        $response->assertStatus(200)
            ->assertJson(['message' => 'You are now following ' . $userToFollow->name]);
    }

    // Add more test methods for other actions in your FollowerController here...

    /** @test */
    public function it_can_get_suggested_users()
    {
        // Create an authenticated user
        $authenticatedUser = User::factory()->create();

        // Authenticate the user
        $this->actingAs($authenticatedUser);

        // Make a request to get suggested users
        $response = $this->get('/api/Follower/suggested-users');

        // Assert that the response is successful and contains the expected data
        $response->assertStatus(200)
            ->assertJsonStructure(['*' => ['user', 'tweets']]);
    }
}
