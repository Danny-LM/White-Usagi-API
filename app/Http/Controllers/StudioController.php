<?php

namespace App\Http\Controllers;

use App\Models\Studio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $studios = Studio::all();

        return response()->json($studios);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:studios',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $studio = Studio::create($request->all());

        return response()->json($studio, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Studio $studio)
    {
        //
        return response()->json($studio);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Studio $studio)
    {
        //
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255|unique:studios,name,' . $studio->id,
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $studio->update($request->all());

        return response()->json($studio);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Studio $studio)
    {
        //
        $studio->delete();
        
        return response()->json(null, 204);
    }
}
