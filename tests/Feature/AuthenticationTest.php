<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCanLoginWithCorrectCredentials()
    {
        $user = User::factory()->create(['password' => bcrypt('valid-password')]);

        $loginData = [
            'email' => $user->email,
            'password' => 'valid-password',
        ];

        $response = $this->postJson('/api/login', $loginData);

        $response->assertStatus(200)
            ->assertJsonStructure(['access_token', 'token_type']);
    }

    public function testUserCannotLoginWithIncorrectPassword()
    {
        $user = User::factory()->create(['password' => bcrypt('correct-password')]);

        $loginData = [
            'email' => $user->email,
            'password' => 'wrong-password',
        ];

        $response = $this->postJson('/api/login', $loginData);

        $response->assertStatus(401)
            ->assertJson(['message' => 'Invalid login credentials']);
    }

    public function testUserCannotLoginWithNonExistingEmail()
    {
        $loginData = [
            'email' => 'nonexistent@example.com',
            'password' => 'any-password',
        ];

        $response = $this->postJson('/api/login', $loginData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function testUserCanLogoutSuccessfully()
    {
        $user = User::factory()->create();
        $tokenResult = $user->createToken('auth_token');
        $plainTextToken = $tokenResult->plainTextToken;
        $tokenId = $tokenResult->accessToken->id;

        $response = $this->actingAs($user)->postJson('/api/logout');

        $response->assertStatus(200)
            ->assertJson(['message' => 'Logged out successfully']);

        $this->assertDatabaseMissing('personal_access_tokens', [
            'id' => $tokenId,
            'tokenable_id' => $user->id,
            'tokenable_type' => User::class,
        ]);
    }
}
