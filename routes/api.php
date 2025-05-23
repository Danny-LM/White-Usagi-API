<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AnimeController;
use App\Http\Controllers\EpisodeController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\StudioController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\VerificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['middleware' => 'api'], function () {
    Route::post('/test-api', function (Request $request) {
        return response()->json(['message' => 'API test works!'], 200);
    });

    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.email');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

    Route::get('/login/google', [SocialAuthController::class, 'redirectToGoogle']);
    Route::get('/login/google/callback', [SocialAuthController::class, 'handleGoogleCallback']);

    Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify');

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', function (Request $request) {
            return $request->user();
        });

        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/logout/all', [AuthController::class, 'logoutAll']);

        Route::get('/tokens', [AuthController::class, 'listTokens']);
        Route::delete('/tokens/{token}', [AuthController::class, 'revokeToken']);//->middleware('can:revoke,token');

        Route::put('/user/profile', [UserProfileController::class, 'updateProfile']);
        Route::put('/user/profile/email', [UserProfileController::class, 'updateEmail']);
        Route::put('/user/profile/password', [UserProfileController::class, 'updatePassword']);

        Route::apiResource('animes', AnimeController::class);
        Route::apiResource('genres', GenreController::class);
        Route::apiResource('studios', StudioController::class);
        Route::apiResource('animes/{anime}/episodes', EpisodeController::class)->only(['index', 'show', 'store', 'update', 'destroy']);

        Route::post('/animes/{anime}/genres', [AnimeController::class, 'attachGenre']);
        Route::delete('/animes/{anime}/genres/{genre}', [AnimeController::class, 'detachGenre']);

        Route::post('/animes/{anime}/studios', [AnimeController::class, 'attachStudio']);
        Route::delete('/animes/{anime}/studios/{studio}', [AnimeController::class, 'detachStudio']);

        Route::apiResource('animes.episodes', EpisodeController::class, ['only' => ['index', 'store', 'show', 'update', 'destroy']]);

        Route::get('/animes-with-episode-count', [AnimeController::class, 'indexWithEpisodeCount']);
    });
});
