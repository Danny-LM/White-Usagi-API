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
        $episodes = $anime->episodes;

        return response()->json($episodes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Anime $anime)
    {
        //
        $validator = Validator::make($request->all(), [
            'title' => 'nullable|string|max:255',
            'episode_number' => 'required|integer|min:1',
            'duration_minutes' => 'nullable|integer|min:0',
            'duration_seconds' => 'nullable|integer|min:0|max:59',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $episode = $anime->episodes()->create($request->all());
        return response()->json($episode, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Anime $anime, Episode $episode)
    {
        //
        if ($episode->anime_id !== $anime->id) {
            return response()->json(['message' => 'Episode not found for this anime.'], 404);
        }
        
        return response()->json($episode);
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
