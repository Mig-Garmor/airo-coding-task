<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegisterApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register(): void
    {
        $response = $this->postJson('/register', [
            'name' => 'Reviewer',
            'email' => 'reviewer@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response
            ->assertCreated()
            ->assertJsonStructure([
                'token',
                'token_type',
                'user' => [
                    'id',
                    'name',
                    'email',
                ],
            ])
            ->assertJson([
                'token_type' => 'bearer',
                'user' => [
                    'name' => 'Reviewer',
                    'email' => 'reviewer@example.com',
                ],
            ]);

        $this->assertDatabaseHas('users', [
            'name' => 'Reviewer',
            'email' => 'reviewer@example.com',
        ]);
    }

    public function test_registered_user_password_is_hashed(): void
    {
        $this->postJson('/register', [
            'name' => 'Reviewer',
            'email' => 'reviewer@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $user = User::where('email', 'reviewer@example.com')->first();

        $this->assertNotNull($user);
        $this->assertTrue(Hash::check('password123', $user->password));
        $this->assertNotSame('password123', $user->password);
    }

    public function test_user_cannot_register_with_duplicate_email(): void
    {
        User::factory()->create([
            'email' => 'reviewer@example.com',
        ]);

        $response = $this->postJson('/register', [
            'name' => 'Reviewer',
            'email' => 'reviewer@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['email']);
    }

    public function test_user_cannot_register_without_required_fields(): void
    {
        $response = $this->postJson('/register', []);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'name',
                'email',
                'password',
            ]);
    }

    public function test_user_cannot_register_with_invalid_email(): void
    {
        $response = $this->postJson('/register', [
            'name' => 'Reviewer',
            'email' => 'not-an-email',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['email']);
    }

    public function test_user_cannot_register_with_short_password(): void
    {
        $response = $this->postJson('/register', [
            'name' => 'Reviewer',
            'email' => 'reviewer@example.com',
            'password' => 'short',
            'password_confirmation' => 'short',
        ]);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['password']);
    }

    public function test_user_cannot_register_when_password_confirmation_does_not_match(): void
    {
        $response = $this->postJson('/register', [
            'name' => 'Reviewer',
            'email' => 'reviewer@example.com',
            'password' => 'password123',
            'password_confirmation' => 'different-password',
        ]);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['password']);
    }
}
