<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_user()
    {
        $userData = [
            'name' => 'Cedric Romasoc',
            'email' => 'cedric@example.com',
            'password' => 'secret',
        ];

        $response = $this->postJson('/api/register', $userData);

        $response->assertStatus(201)
                 ->assertJson([
                     'user' => [
                         'name' => 'Cedric Romasoc',
                         'email' => 'cedric@example.com',
                     ],
                 ]);
    }

    public function test_login_user()
    {
        $user = \App\Models\User::factory()->create([
            'email' => 'jane@example.com',
            'password' => bcrypt('password'), // Use the hashed password
        ]);

        $loginData = [
            'email' => 'jane@example.com',
            'password' => 'password',
        ];

        $response = $this->postJson('/api/login', $loginData);

        $response->assertStatus(200)
                 ->assertJsonStructure(['token']);
    }
}
