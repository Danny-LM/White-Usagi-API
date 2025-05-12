<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use App\Http\Requests\UpdateEmailRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Mail\PasswordChangedMail;
use App\Mail\EmailChangedMail;
use Exception;

class UserProfileController extends Controller
{
    /**
     *
     */
    public function updateProfile(UpdateProfileRequest $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        Gate::authorize('updateProfile', $user);

        if ($request->name === $user->name) {
            return response()->json(['message' => 'The name is already up to date.'], 200);
        }

        $user->name = $request->name;
        $user->save();

        return response()->json(['message' => 'Profile updated successfully', 'user' => new UserResource($user)], 200);
    }

    /**
     *
     */
    public function updateEmail(UpdateEmailRequest $request)
    {
        $user = Auth::user();
        /** @var \App\Models\User $user */
        Gate::authorize('updateProfile', $user);

        if ($request->email === $user->email) {
            return response()->json(['message' => 'The email is already up to date.'], 200);
        }

        $oldEmail = $user->email;
        $user->email = $request->email;
        $user->email_verified_at = null;
        $user->save();

        try {
            Mail::to($user->email)->send(new EmailChangedMail($user, $oldEmail));
        } catch (Exception $e) {
            Log::error("Email not sent for user ID {$user->id}: {$e->getMessage()}");
        }

        return response()->json(['message' => 'Email updated successfully. Please verify it.', 'user' => new UserResource($user)], 200);
    }

    /**
     *
     */
    public function updatePassword(UpdatePasswordRequest $request)
    {
        $user = Auth::user();
        /** @var \App\Models\User $user */
        Gate::authorize('updateProfile', $user);

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'Invalid current password.'], 401);
        }

        if (Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'New password must be different from current.'], 422);
        }

        try {
            $user->password = Hash::make($request->password);
            $user->save();
    
            $user->tokens()->delete();
    
            try {
                Mail::to($user->email)->send(new PasswordChangedMail($user));
            } catch (Exception $e) {
                Log::error("Password change email failed: {$e->getMessage()}");
            }
    
            return response()->json(['message' => 'Password updated successfully.'], 200);
    
        } catch (\Exception $e) {
            Log::error("Error updating password for user {$user->id}: {$e->getMessage()}");
            return response()->json(['message' => 'Failed to update password. Please try again later.'], 500);
        }
    }
}
