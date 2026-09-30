<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $organizerId = Auth::id();

        $events = Auth::user()->events()->withCount('bookings')->with('ticketTypes')->latest()->get();

        $stats = [
            'total_events' => $events->count(),
            'published_events' => $events->where('status', 'published')->count(),
            'total_tickets_sold' => $events->flatMap->ticketTypes->sum('quantity_sold'),
            'total_revenue' => Booking::whereIn('event_id', $events->pluck('id'))
                ->where('status', 'confirmed')->sum('total_price'),
        ];

        $recentBookings = Booking::whereIn('event_id', $events->pluck('id'))
            ->with(['user', 'event', 'ticketType'])
            ->latest()
            ->limit(8)
            ->get();

        return view('organizer.dashboard', compact('events', 'stats', 'recentBookings'));
    }
}
