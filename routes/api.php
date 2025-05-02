<?php

use App\Http\Controllers\AnimeController;
use App\Http\Controllers\EpisodeController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\StudioController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('animes', AnimeController::class);
Route::apiResource('genres', GenreController::class);
Route::apiResource('studios', StudioController::class);
Route::apiResource('animes/{anime}/episodes', EpisodeController::class)->only(['index', 'show', 'store', 'update', 'destroy']);

Route::post('/animes/{anime}/genres', [AnimeController::class, 'attachGenre']);
Route::delete('/animes/{anime}/genres/{genre}', [AnimeController::class, 'detachGenre']);

Route::post('/animes/{anime}/studios', [AnimeController::class, 'attachStudio']);
Route::delete('/animes/{anime}/studios/{studio}', [AnimeController::class, 'detachStudio']);

