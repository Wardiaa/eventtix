<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Event;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_organizers' => User::where('role', 'organizer')->count(),
            'total_events' => Event::count(),
            'published_events' => Event::where('status', 'published')->count(),
            'total_bookings' => Booking::where('status', 'confirmed')->count(),
            'total_revenue' => Booking::where('status', 'confirmed')->sum('total_price'),
        ];

        $recentEvents = Event::with('organizer')->latest()->limit(6)->get();

        return view('admin.dashboard', compact('stats', 'recentEvents'));
    }
}
