<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTicketTypeRequest;
use App\Models\Event;
use App\Models\TicketType;

class TicketTypeController extends Controller
{
    public function create(Event $event)
    {
        $this->authorize('update', $event);

        return view('organizer.ticket-types.create', compact('event'));
    }

    public function store(StoreTicketTypeRequest $request, Event $event)
    {
        $this->authorize('update', $event);

        $event->ticketTypes()->create($request->validated());

        return redirect()->route('organizer.events.show', $event)->with('success', 'Type de billet ajouté.');
    }

    public function edit(Event $event, TicketType $ticketType)
    {
        $this->authorize('update', $event);

        return view('organizer.ticket-types.edit', compact('event', 'ticketType'));
    }

    public function update(StoreTicketTypeRequest $request, Event $event, TicketType $ticketType)
    {
        $this->authorize('update', $event);

        $ticketType->update($request->validated());

        return redirect()->route('organizer.events.show', $event)->with('success', 'Type de billet mis à jour.');
    }

    public function destroy(Event $event, TicketType $ticketType)
    {
        $this->authorize('update', $event);

        abort_if($ticketType->quantity_sold > 0, 422, 'Impossible de supprimer un type de billet déjà vendu.');

        $ticketType->delete();

        return redirect()->route('organizer.events.show', $event)->with('success', 'Type de billet supprimé.');
    }
}
