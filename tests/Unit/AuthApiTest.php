<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_with_valid_credentials(): void
    {
        User::factory()->create([
            'email' => 'reviewer@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/login', [
            'email' => 'reviewer@example.com',
            'password' => 'password123',
        ]);

        $response
            ->assertOk()
            ->assertJsonStructure([
                'token',
                'token_type',
            ])
            ->assertJson([
                'token_type' => 'bearer',
            ]);

        $this->assertIsString($response->json('token'));
        $this->assertNotEmpty($response->json('token'));
    }

    public function test_user_cannot_login_with_invalid_credentials(): void
    {
        User::factory()->create([
            'email' => 'reviewer@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/login', [
            'email' => 'reviewer@example.com',
            'password' => 'wrong-password',
        ]);

        $response
            ->assertUnauthorized()
            ->assertJson([
                'error' => 'Unauthorized',
            ]);
    }

    public function test_login_requires_email_and_password(): void
    {
        $response = $this->postJson('/login', []);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'email',
                'password',
            ]);
    }

    public function test_authenticated_user_can_access_me_endpoint(): void
    {
        $user = User::factory()->create();

        $token = auth('api')->login($user);

        $response = $this
            ->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/me');

        $response
            ->assertOk()
            ->assertJsonPath('user.id', $user->id)
            ->assertJsonPath('user.email', $user->email);
    }

    public function test_unauthenticated_user_cannot_access_me_endpoint(): void
    {
        $response = $this->getJson('/me');

        $response
            ->assertUnauthorized()
            ->assertJson([
                'message' => 'Unauthenticated.',
            ]);
    }
}
