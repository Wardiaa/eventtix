<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $featured = Event::published()->upcoming()->with(['ticketTypes', 'organizer'])
            ->orderBy('start_date')
            ->limit(6)
            ->get();

        $categories = Event::published()->select('category')->distinct()->pluck('category');

        $nextBooking = null;
        if ($user = $request->user()) {
            $nextBooking = $user->bookings()
                ->with(['event', 'ticketType'])
                ->where('status', 'confirmed')
                ->whereHas('event', fn ($q) => $q->where('end_date', '>=', now()))
                ->get()
                ->sortBy(fn ($booking) => $booking->event->start_date)
                ->first();
        }

        return view('home', compact('featured', 'categories', 'nextBooking'));
    }
}
