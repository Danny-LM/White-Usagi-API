<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;

class SocialAuthController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirectToGoogle()
    {
        /** @var \Laravel\Socialite\Two\GoogleProvider $googleDriver */
        $googleDriver = Socialite::driver('google');
        return $googleDriver->stateless()->redirect();
    }

    /**
     * Handle the callback from Google.
     */
    public function handleGoogleCallback(): JsonResponse
    {
        try {
            /** @var \Laravel\Socialite\Two\AbstractProvider $googleDriver */
            $googleDriver = Socialite::driver('google');
            $googleUser = $googleDriver->stateless()->user();
        } catch (\Exception $e) {
            Log::error('Google login failed: ' . $e->getMessage());
            return response()->json(['message' => 'Unable to login with Google. ' . $e->getMessage()], 400);
        }

        if (!filter_var($googleUser->email, FILTER_VALIDATE_EMAIL)) {
            return response()->json(['message' => 'Invalid email provided by Google.'], 400);
        }

        $user = User::where('provider_id', $googleUser->id)
                    ->where('provider_type', 'google')
                    ->first();

        if (!$user) {
            $existingUser = User::where('email', $googleUser->email)->first();

            if ($existingUser) {
                return response()->json([
                    'message' => 'Email already in use. Please log in with your existing account.',
                ], 409);
            }

            Log::info('Timestamp for email_verified_at:', [now()]);
            $user = User::create([
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'provider_id' => $googleUser->id,
                'provider_type' => 'google',
                'email_verified_at' => now(),
                'password' => bcrypt(Str::random(32)),
            ]);
            Log::info('User created with email_verified_at:', [$user->email_verified_at]);
        }

        $token = $user->createToken('social_login')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ]
        ]);
    }
}
