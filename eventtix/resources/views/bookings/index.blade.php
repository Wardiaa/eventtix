@extends('layouts.app')
@section('title', 'Mes billets')
@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Mes réservations</h1>

    @if($bookings->isEmpty())
        <div class="text-center py-20 text-gray-400">
            Vous n'avez encore aucune réservation. <a href="{{ route('events.index') }}" class="text-brand-600 font-semibold">Parcourir les événements</a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($bookings as $booking)
                <a href="{{ route('bookings.show', $booking) }}" class="block bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:shadow-md transition">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <div>
                            <p class="font-bold text-gray-900">{{ $booking->event->title }}</p>
                            <p class="text-sm text-gray-400">{{ $booking->event->start_date->translatedFormat('d F Y, H:i') }} · {{ $booking->event->location }}</p>
                            <p class="text-xs text-gray-400 mt-1">Réf. {{ $booking->booking_reference }} · {{ $booking->ticketType->name }} × {{ $booking->quantity }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-gray-900">{{ number_format($booking->total_price, 0, ',', ' ') }} DA</p>
                            <span class="text-xs font-semibold px-2 py-1 rounded-full
                                {{ $booking->status === 'confirmed' ? 'bg-brand-100 text-brand-700' : 'bg-gray-100 text-gray-500' }}">
                                {{ $booking->status === 'confirmed' ? 'Confirmée' : 'Annulée' }}
                            </span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
        <div class="mt-8">{{ $bookings->links() }}</div>
    @endif
</div>
@endsection
