<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\TicketType;
use App\Notifications\BookingConfirmed;
use Illuminate\Support\Facades\DB;

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
        $data = $request->validated();

        $booking = DB::transaction(function () use ($data, $event, $request) {
            // lock the ticket type row to prevent overselling under concurrent bookings
            $ticketType = TicketType::where('id', $data['ticket_type_id'])
                ->where('event_id', $event->id)
                ->lockForUpdate()
                ->firstOrFail();

            abort_unless($ticketType->isOnSale(), 422, 'Ce type de billet n\'est plus disponible.');

            $quantity = (int) $data['quantity'];

            abort_if($ticketType->available() < $quantity, 422, 'Il ne reste pas assez de places disponibles.');

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

        $booking->user->notify(new BookingConfirmed($booking));

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

        abort_unless($booking->isCancellable(), 422, 'Cette réservation ne peut plus être annulée.');

        DB::transaction(function () use ($booking) {
            $booking->ticketType()->decrement('quantity_sold', $booking->quantity);
            $booking->tickets()->update(['status' => 'cancelled']);
            $booking->update(['status' => 'cancelled']);
        });

        return back()->with('success', 'Réservation annulée.');
    }
}
