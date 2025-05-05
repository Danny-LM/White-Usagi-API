<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\ResetPasswordMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    /**
     * Validation rules for registration.
     *
     * @var array
     */
    protected $registerRules = [
        'name' => 'required|string|min:3|max:50|unique:users',
        'email' => 'required|string|email|max:320|unique:users',
        'password' => ['required', 'string', 'min:8', 'confirmed', 'regex:/^(?=.*[A-Z])(?=.*\d).+$/'],
    ];

    /**
     * Custom error messages for registration validation.
     *
     * @var array
     */
    protected $registerMessages = [
        'name.required' => 'The username is required.',
        'name.min' => 'The username must be at least :min characters long.',
        'name.max' => 'The username must not exceed :max characters.',
        'name.unique' => 'This username is already registered.',

        'email.required' => 'The email address is required.',
        'email.email' => 'The email address must be a valid email.',
        'email.max' => 'The email address must not exceed :max characters.',
        'email.unique' => 'This email address is already registered.',

        'password.required' => 'The password is required.',
        'password.min' => 'The password must be at least :min characters long.',
        'password.confirmed' => 'The password confirmation does not match.',
        'password.regex' => 'The password must contain at least one uppercase letter and one number.',
    ];
    
    /**
     * Validation rules for login.
     *
     * @var array
     */
    protected $loginRules = [
        'password' => 'required|string|min:8',
    ];

    /**
     * Custom error messages for login validation.
     *
     * @var array
     */
    protected $loginMessages = [
        'password.required' => 'The password is required.',
        'password.min' => 'The password must be at least :min characters long.',
    ];

    /**
     * Handle user registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), $this->registerRules, $this->registerMessages);

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

    /**
     * Handle user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $loginField = filter_var($request->input('email'), FILTER_VALIDATE_EMAIL) ? 'email' : 'name';
        $rules = array_merge([$loginField => 'required|string|max:320|exists:users,' . $loginField], $this->loginRules);
        $messages = array_merge([
            'email.required' => 'The email address or username is required.',
            'email.email' => 'The email address must be a valid email.',
            'email.max' => 'The email address or username must not exceed :max characters.',
            'email.exists' => 'This email address or username is not registered.',
            'name.required' => 'The email address or username is required.',
            'name.max' => 'The email address or username must not exceed :max characters.',
            'name.exists' => 'This email address or username is not registered.',
        ], $this->loginMessages);

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $credentials = [
            $loginField => $request->input($loginField),
            'password' => $request->input('password'),
        ];

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid login credentials'], 401);
        }

        $user = User::where($loginField, $request->input($loginField))->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    /**
     * 
     */
    public function logout(Request $request)
    {
        // $request->user()->currentAccessToken()->delete();
        if ($request->user()->currentAccessToken() instanceof \Laravel\Sanctum\PersonalAccessToken) {
            $request->user()->currentAccessToken()->delete();
        } else {
            $request->user()->tokens()->delete();
        }

        return response()->json(['message' => 'Logged out successfully']);
    }

    /**
     * 
     */
    public function logoutAll(Request $request)
    {
        $request->user()->deleteTokens();

        return response()->json(['message' => 'Logged out from all devices successfully']);
    }

    /**
     * 
     */
    public function listTokens(Request $request)
    {
        $tokens = $request->user()->tokens()->get(['id', 'name', 'created_at', 'last_used_at']);

        return response()->json(['tokens' => $tokens]);
    }

    /**
     * 
     */
    public function revokeToken(Request $request, PersonalAccessToken $token)
    {
        if ($token->tokenable_id !== $request->user()->id) {
            return response()->json(['message' => 'This token does not belong to you'], 403);
        }

        $token->delete();

        return response()->json(['message' => 'Token revoked successfully']);
    }

    /**
     *
     */
    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users']);

        $token = Str::random(60);

        DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->delete();

        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => Hash::make($token),
            'created_at' => now(),
        ]);

        $user = User::where('email', $request->email)->first();

        Mail::to($user->email)->send(new ResetPasswordMail($token, $user));

        return response()->json(['message' => 'A password reset link has been sent to your email.']);
    }

    /**
     *
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'email' => 'required|email|exists:users',
            'password' => ['required', 'string', 'min:8', 'confirmed', 'regex:/^(?=.*[A-Z])(?=.*\d).+$/'],
        ]);

        $passwordReset = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$passwordReset || !Hash::check($request->token, $passwordReset->token)) {
            return response()->json(['message' => 'Invalid password reset token.'], 422);
        }

        $user = User::where('email', $request->email)->firstOrFail();

        $user->password = Hash::make($request->password);
        $user->save();

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return response()->json(['message' => 'Your password has been successfully reset.']);
    }
}
