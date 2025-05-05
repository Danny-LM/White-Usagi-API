<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/images/animes/{filename}', function ($filename) {
    $path = public_path('images/animes/' . $filename);

    if (file_exists($path)) {
        return response()->file($path);
    }

    return abort(404);
});

