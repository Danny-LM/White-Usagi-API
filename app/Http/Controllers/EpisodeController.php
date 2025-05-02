<?php

namespace App\Http\Controllers;

use App\Models\Anime;
use App\Models\Episode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EpisodeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Anime $anime)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Anime $anime)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Anime $anime, Episode $episode)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Anime $anime, Episode $episode)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Anime $anime, Episode $episode)
    {
        //
    }
}
