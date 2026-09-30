<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketValidationController extends Controller
{
    public function show()
    {
        return view('organizer.validate');
    }

    public function validateTicket(Request $request)
    {
        $request->validate(['ticket_code' => ['required', 'string']]);

        $ticket = Ticket::with(['booking.event', 'booking.user'])
            ->where('ticket_code', strtoupper(trim($request->ticket_code)))
            ->first();

        if (! $ticket) {
            return back()->with('scan_result', ['status' => 'error', 'message' => 'Billet introuvable.']);
        }

        $event = $ticket->booking->event;

        if (! $request->user()->isAdmin() && $event->organizer_id !== $request->user()->id) {
            return back()->with('scan_result', ['status' => 'error', 'message' => 'Ce billet n\'appartient pas à un de vos événements.']);
        }

        if ($ticket->status === 'cancelled') {
            return back()->with('scan_result', ['status' => 'error', 'message' => 'Billet annulé, entrée refusée.']);
        }

        if ($ticket->status === 'used') {
            return back()->with('scan_result', [
                'status' => 'warning',
                'message' => 'Ce billet a déjà été validé le '.$ticket->checked_in_at->format('d/m/Y à H:i').'.',
                'ticket' => $ticket,
            ]);
        }

        $ticket->update(['status' => 'used', 'checked_in_at' => now()]);

        return back()->with('scan_result', [
            'status' => 'success',
            'message' => 'Entrée validée pour '.$ticket->booking->user->name.'.',
            'ticket' => $ticket,
        ]);
    }
}
