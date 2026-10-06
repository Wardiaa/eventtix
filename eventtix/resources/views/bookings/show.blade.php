@extends('layouts.app')

@section('title', 'Réservation '.$booking->booking_reference)

@section('content')
<div class="pb-8">
    <!-- Top navigation -->
    <div class="flex flex-wrap items-center justify-between gap-3">
        <a href="{{ route('bookings.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-stone-500 transition-colors hover:text-ink">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Toutes mes réservations
        </a>

        <a href="{{ route('events.index') }}" class="text-xs font-semibold text-stone-500 transition-colors hover:text-ink">
            Explorer d'autres événements
        </a>
    </div>

    <!-- Confirmation banner -->
    @if(session('success') || $booking->status === 'confirmed')
        <div class="mt-6 flex items-start gap-3.5 rounded-3xl border border-emerald-200/70 bg-emerald-50/90 p-5 shadow-soft">
            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-emerald-500/15 text-emerald-700">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" />
                </svg>
            </span>
            <div>
                <p class="text-sm font-bold text-emerald-950">Réservation confirmée ! Vos billets et QR codes sont prêts ci-dessous.</p>
                <p class="mt-0.5 text-xs text-emerald-800">
                    Référence officielle : <strong class="font-mono">{{ $booking->booking_reference }}</strong>
                </p>
            </div>
        </div>
    @endif

    <!-- Summary -->
    <div class="card mt-6 p-6 sm:p-8">
        <div class="flex flex-col gap-5 border-b border-stone-100 pb-6 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="label-micro !mb-1">Réf. {{ $booking->booking_reference }} · {{ $booking->event->category }}</p>
                <h1 class="font-display text-2xl font-bold tracking-tight text-ink sm:text-3xl">{{ $booking->event->title }}</h1>
                <div class="mt-2 flex flex-wrap items-center gap-x-2 gap-y-1 text-xs font-medium text-stone-500">
                    <svg class="h-3.5 w-3.5 text-stone-400" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24">
                        <rect x="3.75" y="5" width="16.5" height="15.25" rx="3" />
                        <path stroke-linecap="round" d="M8.25 3.5v3M15.75 3.5v3M3.75 10.25h16.5" />
                    </svg>
                    <span>{{ $booking->event->start_date->translatedFormat('l d F Y') }}</span>
                    <span class="text-stone-300">·</span>
                    <span>{{ $booking->event->location }}</span>
                </div>
            </div>

            <span class="inline-flex w-fit items-center gap-1.5 rounded-full px-3.5 py-1.5 text-[11px] font-semibold
                {{ $booking->status === 'confirmed' ? 'bg-emerald-50 text-emerald-800' : 'bg-stone-100 text-stone-500' }}">
                <span class="h-1.5 w-1.5 rounded-full {{ $booking->status === 'confirmed' ? 'bg-emerald-500' : 'bg-stone-400' }}"></span>
                {{ $booking->status === 'confirmed' ? 'Confirmée' : 'Annulée' }}
            </span>
        </div>

        <div class="grid grid-cols-1 gap-5 pt-6 sm:grid-cols-3">
            <div>
                <span class="label-micro">Formule</span>
                <span class="font-display text-base font-bold text-ink">{{ $booking->ticketType->name }}</span>
            </div>
            <div>
                <span class="label-micro">Nombre de places</span>
                <span class="font-display text-base font-bold text-ink tnum">{{ $booking->quantity }} billet(s)</span>
            </div>
            <div>
                <span class="label-micro">Montant total</span>
                <span class="font-display text-base font-bold text-coral-600 tnum">{{ number_format($booking->total_price, 0, ',', ' ') }} DZD</span>
            </div>
        </div>
    </div>

    <!-- Tickets -->
    @if($booking->status === 'confirmed')
        <h2 class="mt-10 font-display text-lg font-bold tracking-tight text-ink">Vos billets ({{ $booking->tickets->count() }})</h2>

        <div class="mt-4 space-y-6">
            @foreach($booking->tickets as $ticket)
                <div class="overflow-hidden rounded-[28px] border border-stone-200/70 bg-white shadow-soft">
                    <div class="grid lg:grid-cols-[1fr_280px]">
                        <!-- Pass -->
                        <div class="bg-ink p-6 text-paper sm:p-8">
                            <div class="flex items-start justify-between gap-3">
                                <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-coral-400">
                                    {{ $booking->event->category }} · Réf {{ $booking->booking_reference }}
                                </p>
                                <span class="shrink-0 rounded-full px-3 py-1 text-[10px] font-bold
                                    {{ $ticket->status === 'used' ? 'bg-amber-500/15 text-amber-300' : ($ticket->status === 'cancelled' ? 'bg-white/10 text-paper/60' : 'bg-emerald-500/15 text-emerald-300') }}">
                                    {{ $ticket->status === 'used' ? 'Utilisé' : ($ticket->status === 'cancelled' ? 'Annulé' : 'Billet valide') }}
                                </span>
                            </div>

                            <h3 class="mt-4 font-display text-2xl font-bold leading-tight tracking-tight sm:text-3xl">
                                {{ $booking->event->title }}
                            </h3>

                            <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-2">
                                <div>
                                    <span class="text-[10px] font-semibold uppercase tracking-[0.16em] text-paper/40">Date & heure</span>
                                    <p class="mt-1 text-sm font-semibold tnum">
                                        {{ $booking->event->start_date->translatedFormat('d M Y') }} à {{ $booking->event->start_date->format('H:i') }}
                                    </p>
                                </div>
                                <div>
                                    <span class="text-[10px] font-semibold uppercase tracking-[0.16em] text-paper/40">Lieu</span>
                                    <p class="mt-1 truncate text-sm font-semibold">{{ $booking->event->venue_details ?: $booking->event->location }}</p>
                                </div>
                                <div>
                                    <span class="text-[10px] font-semibold uppercase tracking-[0.16em] text-paper/40">Titulaire</span>
                                    <p class="mt-1 text-sm font-semibold">{{ auth()->user()->name }}</p>
                                </div>
                                <div>
                                    <span class="text-[10px] font-semibold uppercase tracking-[0.16em] text-paper/40">Formule</span>
                                    <p class="mt-1 text-sm font-semibold">{{ $booking->ticketType->name }}</p>
                                </div>
                            </div>

                            <div class="mt-8 flex flex-wrap items-center justify-between gap-3 border-t border-dashed border-white/15 pt-5">
                                <span class="font-mono text-[11px] font-medium tracking-wide text-paper/50">{{ $ticket->ticket_code }}</span>
                                <button type="button" onclick="window.print()"
                                    class="inline-flex cursor-pointer items-center gap-2 rounded-xl bg-white/10 px-4 py-2.5 text-xs font-semibold text-paper transition-colors hover:bg-white/20">
                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                    </svg>
                                    Imprimer le pass
                                </button>
                            </div>
                        </div>

                        <!-- QR stub -->
                        <div class="flex flex-col items-center justify-center gap-3 border-t border-dashed border-ink/15 bg-cream p-6 lg:border-l lg:border-t-0">
                            <span class="text-[10px] font-semibold uppercase tracking-[0.18em] text-stone-400">Pass de contrôle</span>
                            <div class="rounded-2xl border border-stone-200 bg-white p-3 shadow-soft">
                                {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(120)->generate($ticket->ticket_code) !!}
                            </div>
                            <div class="rounded-lg border border-stone-200 bg-white px-3.5 py-1.5 font-mono text-xs font-bold text-ink">
                                {{ $ticket->ticket_code }}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if($booking->isCancellable())
            <form method="POST" action="{{ route('bookings.cancel', $booking) }}" onsubmit="return confirm('Confirmer l\'annulation de cette réservation ?');" class="pt-6 text-right">
                @csrf
                <button type="submit" class="cursor-pointer text-xs font-semibold text-stone-400 transition-colors hover:text-red-700">
                    Annuler cette réservation
                </button>
            </form>
        @endif
    @endif
</div>
@endsection
