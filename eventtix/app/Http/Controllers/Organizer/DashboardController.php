<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $events = Auth::user()->events()->withCount('bookings')->with('ticketTypes')->latest()->get();

        $eventIds = $events->pluck('id');
        $confirmedBookings = Booking::whereIn('event_id', $eventIds)->where('status', 'confirmed');

        $soldTickets = $events->flatMap->ticketTypes->sum('quantity_sold');
        $totalStock = $events->flatMap->ticketTypes->sum('quantity');

        $stats = [
            'total_events' => $events->count(),
            'published_events' => $events->where('status', 'published')->count(),
            'upcoming_events' => $events->where('status', 'published')->where('end_date', '>=', now())->count(),
            'total_tickets_sold' => $soldTickets,
            'total_revenue' => (clone $confirmedBookings)->sum('total_price'),
            'tickets_last_week' => (clone $confirmedBookings)->where('created_at', '>=', now()->subDays(7))->sum('quantity'),
            'revenue_last_week' => (clone $confirmedBookings)->where('created_at', '>=', now()->subDays(7))->sum('total_price'),
            'fill_rate' => $totalStock > 0 ? (int) round($soldTickets / $totalStock * 100) : 0,
        ];

        // Daily ticket sales over the last 30 days, zero-filled for days
        // without any booking so the bar chart keeps a stable scale.
        $salesRows = (clone $confirmedBookings)
            ->where('created_at', '>=', now()->subDays(29)->startOfDay())
            ->selectRaw('DATE(created_at) as day, SUM(quantity) as sold')
            ->groupBy('day')
            ->pluck('sold', 'day');

        $salesChart = collect(range(29, 0))->map(function ($i) use ($salesRows) {
            $date = now()->subDays($i);

            return [
                'date' => $date->translatedFormat('d M'),
                'label' => $date->translatedFormat('l d F'),
                'sold' => (int) ($salesRows[$date->toDateString()] ?? 0),
            ];
        });

        $recentBookings = Booking::whereIn('event_id', $eventIds)
            ->with(['user', 'event', 'ticketType'])
            ->latest()
            ->limit(8)
            ->get();

        return view('organizer.dashboard', compact('events', 'stats', 'recentBookings', 'salesChart'));
    }
}
