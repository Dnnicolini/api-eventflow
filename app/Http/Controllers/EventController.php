<?php

namespace App\Http\Controllers;

use App\Http\Filters\EventFilter;
use App\Http\Resources\EventResource;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, EventFilter $filters)
    {
        $query = Event::query()
            ->with(['category', 'location', 'organizer'])
            ->orderBy('start_date')
            ->orderBy('start_time');

        $filtered = $filters->apply($query, ['name', 'description']);

        $perPage = (int) $request->input('per_page', 15);

        $events = $perPage > 0
            ? $filtered->paginate($perPage)->appends($request->query())
            : $filtered->get();

        return response()->json(['data' => EventResource::collection($events)], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EventRequest $request)
    {
        $validated = $request->validated();

        $filePath = Storage::url($request->file('image')->store('public/events'));

        $validated['image'] = $filePath;

        $event = Event::create($validated);

        return response()->json(['data' => EventResource::make($event), 'message' => 'Event created successfully'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        return response()->json(['data' => EventResource::make($event)], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EventRequest $request, Event $event)
    {

        if(!$event) return response()->json(['message' => 'Event not found'], 404);

        $validated = $request->validated();

        if(isset($validated['image'])) {
            Storage::delete($event->image);
            $filePath = Storage::url($request->file('image')->store('public/events'));
            $validated['image'] = $filePath;
        }

        $event->update($validated);

        return response()->json(['data' => EventResource::make($event), 'message' => 'Event updated successfully'], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        $event->delete();

        return response()->json(['message' => 'Event deleted successfully'], 204);
    }
}
