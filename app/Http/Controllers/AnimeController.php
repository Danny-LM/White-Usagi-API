<?php

namespace App\Http\Controllers;

use App\Models\Anime;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AnimeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $animes = Anime::all();

        return response()->json($animes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'release_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:release_date',
            'season' => 'nullable|string|max:50',
            'synopsis' => 'nullable|string',
            'poster_url' => 'nullable|url|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $anime = Anime::create($request->all());

        return response()->json($anime, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Anime $anime)
    {
        //
        return response()->json($anime);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Anime $anime)
    {
        //
        $validator = Validator::make($request->all(), [
            'title' => 'nullable|string|max:255',
            'release_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:release_date',
            'season' => 'nullable|string|max:50',
            'synopsis' => 'nullable|string',
            'poster_url' => 'nullable|url|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $anime->update($request->all());
        return response()->json($anime);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Anime $anime)
    {
        //
        $anime->delete();
        return response()->json(null, 204);
    }

    public function attachGenre(Request $request, Anime $anime)
    {
        $validator = Validator::make($request->all(), [
            'genres' => 'required|array',
            'genres.*' => 'integer|exists:genres,id', // Asegura que cada ID sea un entero y exista en la tabla 'genres'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $anime->genres()->attach($request->input('genres'));
        return response()->json(['message' => 'Genres attached successfully to the anime'], 200);
    }

    public function detachGenre(Anime $anime, Genre $genre)
    {
        $anime->genres()->detach($genre->id);
        return response()->json(['message' => 'Genre detached successfully from the anime'], 200);
    }
}
