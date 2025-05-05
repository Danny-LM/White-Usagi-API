<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;
use App\Mail\EmailChangedMail;
use App\Mail\PasswordChangedMail;
use Exception;

class UserProfileController extends Controller
{
    /**
     * Update the authenticated user's profile name.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3|max:50|unique:users,name,' . $user->id,
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if (!$user->can('updateProfile', $user)) {
            return response()->json(['message' => 'Unauthorized to update this profile.'], 403);
        }

        if ($request->name === $user->name) {
            return response()->json(['message' => 'The name is already up to date.'], 200);
        }

        $user->name = $request->name;
        $user->save();

        return response()->json(['message' => 'Profile updated successfully', 'user' => $user], 200);
    }

    /**
     * Update the authenticated user's email.
     */
    public function updateEmail(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:320|unique:users,email,' . $user->id,
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if (!$user->can('updateProfile', $user)) {
            return response()->json(['message' => 'Unauthorized to update this profile.'], 403);
        }

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
            \Log::error('Failed to send email change notification: ' . $e->getMessage());
        }

        return response()->json([
            'message' => 'Email updated successfully. Please check your new email address for verification.',
            'user' => $user
        ], 200);
    }

    /**
     * Update the authenticated user's password.
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'password' => [
                'required',
                'confirmed',
                Password::min(8)->mixedCase()->numbers()->symbols()
            ],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if (!$user->can('updateProfile', $user)) {
            return response()->json(['message' => 'Unauthorized to update this profile.'], 403);
        }

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'Invalid current password.'], 401);
        }

        if (Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'The new password must be different from the current one.'], 422);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        try {
            Mail::to($user->email)->send(new PasswordChangedMail($user));
        } catch (Exception $e) {
            \Log::error('Failed to send password change notification: ' . $e->getMessage());
        }

        return response()->json(['message' => 'Password updated successfully.'], 200);
    }
}
