<?php

namespace Tests\Feature\Feature;

use App\Models\User;
use App\Mail\EmailChangedMail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class UserProfileTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 
     */
    public function test_user_can_update_their_name(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $new_name = 'testUserName';

        $response = $this->putJson('/api/user/profile', [
            'name' => $new_name,
        ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Profile updated successfully'])
            ->assertJsonStructure(['user' => ['id', 'name', 'email', 'email_verified_at', 'created_at']]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => $new_name,
        ]);
    }

    /**
     * 
     */
    public function test_user_can_update_their_email(): void
    {
        Mail::fake();
        $user = User::factory()->create(['email' => 'old@example.com', 'email_verified_at' => now()]);
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
                         ->putJson('/api/user/profile/email', [
                             'email' => 'new@example.com',
                         ]);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Email updated successfully. Please verify it.'])
                 ->assertJsonStructure(['user' => ['id', 'name', 'email', 'email_verified_at', 'created_at']]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => 'new@example.com',
            'email_verified_at' => null,
        ]);

        Mail::assertSent(EmailChangedMail::class, function ($mail) use ($user) {
            return $mail->hasTo('new@example.com') &&
                $mail->user->id === $user->id &&
                $mail->oldEmail === 'old@example.com';
        });
    }

    public function test_user_can_update_their_password(): void
    {
        $user = User::factory()->create(['password' => bcrypt('old-password')]);
        $token = $user->createToken('test-token')->plainTextToken;

        $newPassword = 'NewSecurePassword123!';

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
                        ->putJson('/api/user/profile/password', [
                            'current_password' => 'old-password',
                            'password' => $newPassword,
                            'password_confirmation' => $newPassword,
                        ]);

        // dd($response->json());

        $response->assertStatus(200)
                ->assertJson(['message' => 'Password updated successfully.']);

        $this->assertTrue(Hash::check($newPassword, $user->fresh()->password), 'The password should be updated and correctly hashed.');

        $this->assertCount(0, $user->tokens);
    }
}
