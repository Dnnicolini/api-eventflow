<?php

namespace App\Http\Controllers;

use App\Http\Filters\LocationFilter;
use App\Http\Requests\LocationRequest;
use App\Http\Resources\LocationResource;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, LocationFilter $filters)
    {
        $query = Location::query()->orderBy('name');

        $filtered = $filters->apply($query, ['name', 'address']);

        $perPage = (int) $request->input('per_page', 15);

        $locations = $perPage > 0
            ? $filtered->paginate($perPage)->appends($request->query())
            : $filtered->get();

        return response()->json(['data' => LocationResource::collection($locations)], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LocationRequest $request)
    {
        $location = Location::create($request->validated());

        return response()->json([
            'data' => LocationResource::make($location),
            'message' => 'Location created successfully',
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Location $location)
    {
        return response()->json(['data' => LocationResource::make($location)], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LocationRequest $request, Location $location)
    {
        $location->update($request->validated());

        return response()->json([
            'data' => LocationResource::make($location),
            'message' => 'Location updated successfully',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Location $location)
    {
        $location->delete();

        return response()->json(['message' => 'Location deleted successfully'], 204);
    }
}
