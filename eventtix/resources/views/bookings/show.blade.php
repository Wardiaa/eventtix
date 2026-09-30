@extends('layouts.app')
@section('title', 'Réservation '.$booking->booking_reference)
@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-xs font-semibold text-brand-600 uppercase">Référence {{ $booking->booking_reference }}</p>
                <h1 class="text-2xl font-bold text-gray-900 mt-1">{{ $booking->event->title }}</h1>
                <p class="text-sm text-gray-500 mt-1">{{ $booking->event->start_date->translatedFormat('d F Y, H:i') }} · {{ $booking->event->location }}</p>
            </div>
            <span class="text-xs font-semibold px-3 py-1 rounded-full shrink-0
                {{ $booking->status === 'confirmed' ? 'bg-brand-100 text-brand-700' : 'bg-gray-100 text-gray-500' }}">
                {{ $booking->status === 'confirmed' ? 'Confirmée' : 'Annulée' }}
            </span>
        </div>

        <div class="mt-6 grid sm:grid-cols-3 gap-4 text-sm border-t border-b py-4">
            <div><p class="text-gray-400">Type de billet</p><p class="font-semibold">{{ $booking->ticketType->name }}</p></div>
            <div><p class="text-gray-400">Quantité</p><p class="font-semibold">{{ $booking->quantity }}</p></div>
            <div><p class="text-gray-400">Total payé</p><p class="font-semibold">{{ number_format($booking->total_price, 0, ',', ' ') }} DA</p></div>
        </div>

        @if($booking->status === 'confirmed')
            <div class="mt-8">
                <h2 class="font-bold text-gray-900 mb-4">Vos billets ({{ $booking->tickets->count() }})</h2>
                <div class="grid sm:grid-cols-2 gap-4">
                    @foreach($booking->tickets as $ticket)
                        <div class="border border-dashed border-brand-300 rounded-xl p-4 flex items-center gap-4 {{ $ticket->status === 'used' ? 'opacity-50' : '' }}">
                            <div class="bg-white p-1 rounded-lg border">
                                {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(90)->generate($ticket->ticket_code) !!}
                            </div>
                            <div>
                                <p class="font-mono text-sm font-semibold text-gray-800">{{ $ticket->ticket_code }}</p>
                                <p class="text-xs mt-1 {{ $ticket->status === 'used' ? 'text-gray-400' : 'text-brand-600' }} font-semibold">
                                    {{ $ticket->status === 'used' ? 'Déjà scanné' : ($ticket->status === 'cancelled' ? 'Annulé' : 'Valide') }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            @if($booking->isCancellable())
                <form method="POST" action="{{ route('bookings.cancel', $booking) }}" onsubmit="return confirm('Annuler cette réservation ?');" class="mt-8">
                    @csrf
                    <button class="text-sm font-semibold text-red-500 hover:text-red-600">Annuler cette réservation</button>
                </form>
            @endif
        @endif
    </div>
</div>
@endsection
