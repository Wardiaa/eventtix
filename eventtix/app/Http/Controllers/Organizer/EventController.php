<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEventRequest;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function index()
    {
        $events = Auth::user()->events()->withCount('bookings')->latest()->paginate(10);

        return view('organizer.events.index', compact('events'));
    }

    public function create()
    {
        return view('organizer.events.create');
    }

    public function store(StoreEventRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('events', 'public');
        }

        $data['organizer_id'] = Auth::id();

        $event = Event::create($data);

        return redirect()->route('organizer.events.show', $event)
            ->with('success', 'Événement créé. Ajoutez maintenant vos types de billets.');
    }

    public function show(Event $event)
    {
        $this->authorize('manage', $event);

        $event->load(['ticketTypes', 'bookings.user', 'bookings.ticketType']);

        return view('organizer.events.show', compact('event'));
    }

    public function edit(Event $event)
    {
        $this->authorize('update', $event);

        return view('organizer.events.edit', compact('event'));
    }

    public function update(StoreEventRequest $request, Event $event)
    {
        $this->authorize('update', $event);

        $data = $request->validated();

        if ($request->hasFile('cover_image')) {
            if ($event->cover_image) {
                Storage::disk('public')->delete($event->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image')->store('events', 'public');
        }

        $event->update($data);

        return redirect()->route('organizer.events.show', $event)->with('success', 'Événement mis à jour.');
    }

    public function destroy(Event $event)
    {
        $this->authorize('delete', $event);

        $event->delete();

        return redirect()->route('organizer.events.index')->with('success', 'Événement supprimé.');
    }
}
