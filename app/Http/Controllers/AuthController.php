<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * 
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3|max:50',
            'email' => 'required|string|email|max:320|unique:users',
            'password' => 'required|string|min:8|confirmed','regex:/^(?=.*[A-Z])(?=.*\d).+$/',
        ], [
            'name.required' => 'The name is required.',
            'name.min' => 'The name must be at least :min characters long.',
            'name.max' => 'The name must not exceed :max characters.',

            'email.required' => 'The email address is required.',
            'email.email' => 'The email address must be a valid email.',
            'email.max' => 'The email address must not exceed :max characters.',
            'email.unique' => 'This email address is already registered.',
            
            'password.required' => 'The password is required.',
            'password.min' => 'The password must be at least :min characters long.',
            'password.confirmed' => 'The password confirmation does not match.',
            'password.regex' => 'The password must contain at least one uppercase letter and one number.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:320|exists:users',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Invalid login credentials'], 401);
        }

        $user = User::where('email', $request->email)->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }
}
