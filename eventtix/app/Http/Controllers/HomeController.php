<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $featured = Event::published()->upcoming()->with('ticketTypes')
            ->orderBy('start_date')
            ->limit(6)
            ->get();

        $categories = Event::published()->select('category')->distinct()->pluck('category');

        return view('home', compact('featured', 'categories'));
    }
}
