<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_successfully_sign_up_user()
    {
        $this->assertDatabaseCount('users', 0);

        $user = [
            'name' => 'Cedric Romasoc',
            'password' => 'secret',
            'email' => 'cedric@example.com',
        ];

        $response = $this->post('/api/register', $user);

        $actualToken = $response->json('plainTextToken');

    $response->assertStatus(201)
             ->assertJson([
                 'user' => [
                     'name' => 'Cedric Romasoc',
                     'email' => 'cedric@example.com',
                 ],
                 'plainTextToken' => $actualToken, // Use the actual token value
             ]);
    }

    public function test_login_user()
    {
        $user = User::factory()->create([
            'email' => 'jane@example.com',
            'password' => bcrypt('password'), // Use the hashed password
        ]);

        $loginData = [
            'email' => 'jane@example.com',
            'password' => 'password',
        ];

        $response = $this->post('/api/login', $loginData);

        $response->assertStatus(200)
                 ->assertJsonStructure(['token']);
    }
}
