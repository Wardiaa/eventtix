<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $events = Event::published()
            ->with(['ticketTypes', 'organizer'])
            ->search($request->string('q'))
            ->when($request->filled('category'), fn ($q) => $q->where('category', $request->category))
            ->when($request->filled('location'), fn ($q) => $q->where('location', 'like', '%'.$request->location.'%'))
            ->when($request->filled('date'), fn ($q) => $q->whereDate('start_date', $request->date))
            ->when(! $request->filled('date'), fn ($q) => $q->upcoming())
            ->orderBy('start_date')
            ->paginate(9)
            ->withQueryString();

        $categories = Event::published()->select('category')->distinct()->pluck('category');

        return view('events.index', compact('events', 'categories'));
    }

    public function show(Event $event)
    {
        abort_unless($event->status === 'published' || ($event->status !== 'published' && auth()->check() && (auth()->user()->isAdmin() || auth()->id() === $event->organizer_id)), 404);

        $event->load(['ticketTypes' => fn ($q) => $q->orderBy('price')]);

        return view('events.show', compact('event'));
    }
}
