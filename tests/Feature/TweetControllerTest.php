<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Tweet;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class TweetControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_tweet()
    {
        // Create a user (you may want to use the User factory)
        $user = User::factory()->create();

        // Simulate authentication
        $this->actingAs($user);

        // Prepare a file for media (if needed)
        $file = UploadedFile::fake()->image('tweet.jpg');

        // Make a POST request to the create tweet route with valid data
        $response = $this->post('/api/tweets', [
            'text' => 'This is a test tweet.',
            'media' => $file,
        ]);

        // Assert that the response has a 201 status code (created)
        $response->assertStatus(201);

        // Assert that the tweet was created in the database
        $this->assertDatabaseHas('tweets', [
            'text' => 'This is a test tweet.',
        ]);

        // Assert that the media file was stored (if applicable)
        Storage::disk('media')->assertExists($file->hashName());
    }

    public function test_edit_tweet()
    {
        // Create a user (you may want to use the User factory)
        $user = User::factory()->create();

        // Simulate authentication
        $this->actingAs($user);

        // Create a tweet by the user
        $tweet = Tweet::factory()->create([
            'user_id' => $user->id,
        ]);

        // Prepare a file for media (if needed)
        $file = UploadedFile::fake()->image('updated_tweet.jpg');

        // Make a PUT request to edit the tweet
        $response = $this->put("/api/tweets/{$tweet->id}", [
            'text' => 'Updated tweet text.',
            'media' => $file,
        ]);

        // Assert that the response has a 200 status code (OK)
        $response->assertStatus(200);

        // Assert that the tweet data was updated in the database
        $this->assertDatabaseHas('tweets', [
            'id' => $tweet->id,
            'text' => 'Updated tweet text.',
        ]);

        // Assert that the old media file was deleted (if applicable)
        Storage::disk('media')->assertMissing($tweet->media);

        // Assert that the new media file was stored (if applicable)
        Storage::disk('media')->assertExists($file->hashName());
    }

    public function test_delete_tweet()
    {
        // Create a user (you may want to use the User factory)
        $user = User::factory()->create();

        // Simulate authentication
        $this->actingAs($user);

        // Create a tweet by the user
        $tweet = Tweet::factory()->create([
            'user_id' => $user->id,
        ]);

        // Make a DELETE request to delete the tweet
        $response = $this->delete("/api/tweets/{$tweet->id}");

        // Assert that the response has a 200 status code (OK)
        $response->assertStatus(200);

        // Assert that the tweet was deleted from the database
        $this->assertDatabaseMissing('tweets', [
            'id' => $tweet->id,
        ]);

        // Assert that the media file was deleted (if applicable)
        Storage::disk('media')->assertMissing($tweet->media);
    }
}
