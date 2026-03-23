<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        return view('events.index');
    }

    public function allEvents()
    {
        $events = Event::all();

        return response()->json($events);
    }

    public function store(Request $request)
    {
        $event = Event::create($request->only('title', 'start', 'end', 'category', 'location', 'description'));

        return response()->json($event);
    }

    public function update(Request $request, Event $event)
    {
        $event->update($request->only('title', 'start', 'end', 'category', 'location', 'description'));

        return response()->json($event);
    }

    public function destroy(Event $event)
    {
        $event->delete();

        return response()->json(['success' => true]);
    }
}
