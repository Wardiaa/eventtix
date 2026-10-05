<?php

namespace App\Http\Controllers;

use App\Exceptions\BookingException;
use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\TicketType;
use App\Notifications\BookingConfirmed;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = auth()->user()->bookings()
            ->with(['event', 'ticketType', 'tickets'])
            ->latest()
            ->paginate(10);

        return view('bookings.index', compact('bookings'));
    }

    public function store(StoreBookingRequest $request, Event $event)
    {
        // Guard against booking an event reached through a stale/old URL
        // (cancelled, unpublished, completed...). This is a normal,
        // expected situation, not a bug, so it's a BookingException
        // rather than abort(): see bootstrap/app.php for how it's
        // rendered as a friendly redirect instead of an exception page.
        if ($event->status !== 'published') {
            throw new BookingException("Cet événement n'est plus disponible à la réservation.");
        }

        $data = $request->validated();

        $booking = DB::transaction(function () use ($data, $event, $request) {
            // Lock the ticket type row to prevent overselling under
            // concurrent bookings. firstOrFail() here is a last-resort
            // safety net (StoreBookingRequest already validates that the
            // ticket type belongs to this event) — if it somehow still
            // fails, it becomes a normal 404 via the custom error page.
            $ticketType = TicketType::where('id', $data['ticket_type_id'])
                ->where('event_id', $event->id)
                ->lockForUpdate()
                ->firstOrFail();

            // Each case below throws inside the transaction, which rolls
            // back automatically — nothing has been written yet at this
            // point, so this is safe. The three checks are evaluated
            // separately (rather than via the combined isOnSale()) so the
            // user is told exactly why, instead of a generic message.
            if ($ticketType->saleNotStarted()) {
                throw new BookingException("La vente de ce billet n'a pas encore commencé.");
            }

            if ($ticketType->saleEnded()) {
                throw new BookingException('La vente de ce billet est terminée.');
            }

            if ($ticketType->isSoldOut()) {
                throw new BookingException('Ce type de billet est complet.');
            }

            $quantity = (int) $data['quantity'];
            $available = $ticketType->available();

            if ($available < $quantity) {
                throw new BookingException(
                    "Il ne reste que {$available} billet(s) disponible(s) pour ce type de billet."
                );
            }

            $ticketType->increment('quantity_sold', $quantity);

            $booking = Booking::create([
                'user_id' => $request->user()->id,
                'event_id' => $event->id,
                'ticket_type_id' => $ticketType->id,
                'quantity' => $quantity,
                'unit_price' => $ticketType->price,
                'total_price' => $ticketType->price * $quantity,
                'status' => 'confirmed',
            ]);

            for ($i = 0; $i < $quantity; $i++) {
                Ticket::create(['booking_id' => $booking->id]);
            }

            return $booking;
        });

        // The booking has already been committed to the database at this
        // point. A failure to send the confirmation email is an
        // infrastructure problem, not a reason to tell the user their
        // booking failed (which could make them submit the form again and
        // create a duplicate booking). So we log it and continue.
        try {
            $booking->user->notify(new BookingConfirmed($booking));
        } catch (\Throwable $e) {
            Log::error('Échec de l\'envoi de la notification de confirmation de réservation.', [
                'booking_id' => $booking->id,
                'user_id' => $booking->user_id,
                'exception' => $e->getMessage(),
            ]);
        }

        return redirect()->route('bookings.show', $booking)
            ->with('success', 'Réservation confirmée ! Vos billets et QR codes sont prêts ci-dessous.');
    }

    public function show(Booking $booking)
    {
        $this->authorize('view', $booking);

        $booking->load(['event', 'ticketType', 'tickets']);

        return view('bookings.show', compact('booking'));
    }

    public function cancel(Booking $booking)
    {
        $this->authorize('cancel', $booking);

        // Checked before the transaction starts, so nothing is written to
        // the database when cancellation is rejected.
        if (! $booking->isCancellable()) {
            throw new BookingException('Cette réservation ne peut plus être annulée.');
        }

        DB::transaction(function () use ($booking) {
            $booking->ticketType()->decrement('quantity_sold', $booking->quantity);
            $booking->tickets()->update(['status' => 'cancelled']);
            $booking->update(['status' => 'cancelled']);
        });

        return back()->with('success', 'Réservation annulée.');
    }
}
