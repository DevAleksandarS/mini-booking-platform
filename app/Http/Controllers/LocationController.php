<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Location;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Location::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'city' => 'required|string',
            'country' => 'required|string',
        ]);

        $location = Location::create($data);
        return response()->json($location, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Location $location)
    {
        return $location;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Location $location)
    {
        $data = $request->validate([
            'city' => 'sometimes|required|string',
            'country' => 'sometimes|required|string',
        ]);

        $location->update($data);
        return $location;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Location $location)
    {
        $location->delete();
        return response()->noContent();
    }
}
