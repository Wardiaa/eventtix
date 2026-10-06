@extends('layouts.app')

@section('title', 'Mes billets')

@section('content')
<div class="pb-8">
    <div>
        <p class="eyebrow">Votre espace</p>
        <h1 class="page-title mt-3">Mes billets</h1>
        <p class="page-subtitle">
            Retrouvez vos billets et préparez vos prochaines sorties en un coup d'œil.
        </p>
    </div>

    @if($bookings->isEmpty())
        <div class="card mt-10 p-12 text-center">
            <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-peach text-ink">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 0 0-2 2v3a2 2 0 1 1 0 4v3a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-3a2 2 0 1 1 0-4V7a2 2 0 0 0-2-2H5z" />
                </svg>
            </span>
            <p class="mt-5 text-base font-semibold text-ink">Aucune réservation pour le moment</p>
            <p class="mx-auto mt-1 max-w-md text-sm text-stone-500">
                Vous n'avez pas encore réservé de places. Explorez les prochains événements et recevez un QR code officiel.
            </p>
            <a href="{{ route('events.index') }}" class="btn-dark mt-6">
                Découvrir les événements
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 17 17 7m0 0H8m9 0v9" />
                </svg>
            </a>
        </div>
    @else
        <div class="mt-10 grid grid-cols-1 gap-6 lg:grid-cols-2">
            @foreach($bookings as $booking)
                @php
                    $isDark = $booking->status === 'confirmed' && $loop->index % 2 === 0;
                    $isCancelled = $booking->status !== 'confirmed';
                @endphp

                <div class="relative flex flex-col rounded-3xl p-6 shadow-soft transition-all duration-200 sm:p-7
                    {{ $isCancelled ? 'bg-stone-200/50 text-ink' : ($isDark ? 'bg-ink text-paper' : 'bg-peach text-ink') }}">
                    <a href="{{ route('bookings.show', $booking) }}" class="absolute inset-0 z-10 rounded-3xl" aria-label="Voir le billet {{ $booking->booking_reference }}"></a>

                    <div class="flex items-start justify-between gap-4">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.18em] {{ $isCancelled ? 'text-stone-500' : ($isDark ? 'text-coral-400' : 'text-ink/50') }}">
                            {{ $isCancelled ? 'Réservation annulée' : 'Billet confirmé' }}
                        </p>
                        <svg class="h-7 w-7 shrink-0 {{ $isCancelled ? 'text-ink/25' : ($isDark ? 'text-paper/70' : 'text-ink/40') }}" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M4 4h6v6H4V4zm2 2v2h2V6H6zM14 4h6v6h-6V4zm2 2v2h2V6h-2zM4 14h6v6H4v-6zm2 2v2h2v-2H6zM14 14h2.5v2.5H14V14zm3.5 0H20v2.5h-2.5V14zm-3.5 3.5h2.5V20H14v-2.5zm3.5 0H20V20h-2.5v-2.5z" />
                        </svg>
                    </div>

                    <h2 class="mt-5 font-display text-2xl font-bold leading-tight tracking-tight">{{ $booking->event->title }}</h2>
                    <p class="mt-1.5 text-xs font-medium {{ $isCancelled ? 'text-stone-500' : ($isDark ? 'text-paper/60' : 'text-ink/60') }}">
                        {{ $booking->event->venue_details ?: $booking->event->location }}
                        <span class="mx-1 {{ $isDark && !$isCancelled ? 'text-paper/30' : 'text-ink/25' }}">·</span>
                        {{ $booking->event->start_date->translatedFormat('D, d M Y') }}
                        <span class="mx-1 {{ $isDark && !$isCancelled ? 'text-paper/30' : 'text-ink/25' }}">·</span>
                        {{ $booking->event->start_date->format('H:i') }}
                    </p>

                    <div class="mt-8 flex items-center justify-between gap-3 border-t border-dashed pt-4
                        {{ $isCancelled ? 'border-ink/15' : ($isDark ? 'border-white/15' : 'border-ink/15') }}">
                        <span class="font-mono text-[11px] font-medium tracking-wide {{ $isCancelled ? 'text-stone-500' : ($isDark ? 'text-paper/50' : 'text-ink/50') }}">
                            {{ $booking->booking_reference }}
                        </span>
                        <a href="{{ route('bookings.show', $booking) }}"
                            class="relative z-20 inline-flex items-center gap-2 rounded-xl px-4 py-2.5 text-xs font-semibold transition-colors duration-150
                            {{ $isDark ? 'bg-white/10 text-paper hover:bg-white/20' : 'bg-white/70 text-ink hover:bg-white' }}">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                            </svg>
                            Télécharger
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-10">{{ $bookings->links() }}</div>
    @endif
</div>
@endsection
