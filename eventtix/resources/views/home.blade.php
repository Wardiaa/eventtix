@extends('layouts.app')

@section('title', 'Explorer')

@section('content')
@php
    $prenom = auth()->check() ? explode(' ', trim(auth()->user()->name))[0] : null;
    $greeting = (int) now()->format('G') < 18 ? 'Bonjour' : 'Bonsoir';
@endphp

<div class="space-y-16">
    <!-- Hero -->
    <section class="pt-2 lg:pt-6">
        <div class="flex flex-col gap-8 2xl:flex-row 2xl:items-end 2xl:justify-between">
            <div class="max-w-3xl">
                <p class="eyebrow">{{ $prenom ? $greeting.', '.$prenom : 'Billetterie culturelle & événements pro — Algérie' }}</p>
                <h1 class="mt-5 font-display text-[1.75rem] font-bold leading-[1.06] tracking-[-0.025em] text-ink sm:text-4xl md:text-5xl xl:text-6xl">
                    <span class="block">Trouvez votre prochain</span>
                    <span class="block"><span class="text-stone-400">moment</span> inoubliable<span class="text-coral-500">.</span></span>
                </h1>
            </div>

            <form action="{{ route('events.index') }}" method="GET" class="w-full shrink-0 sm:max-w-lg 2xl:w-[400px]">
                <label for="hero-search" class="sr-only">Rechercher un événement</label>
                <div class="flex items-center gap-2 rounded-full border border-stone-200/80 bg-white py-1.5 pl-5 pr-1.5 shadow-soft transition-shadow duration-200 focus-within:shadow-lift">
                    <svg class="h-4 w-4 shrink-0 text-stone-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11a6 6 0 1 1-12 0 6 6 0 0 1 12 0z" />
                    </svg>
                    <input id="hero-search" type="text" name="q" value="{{ request('q') }}"
                        placeholder="Rechercher un événement, artiste…"
                        class="w-full bg-transparent py-2 text-sm text-ink placeholder:text-stone-400 focus:outline-none">
                    <button type="submit" class="btn-dark shrink-0 !rounded-full !px-5 !py-2.5 !text-xs">
                        Rechercher
                    </button>
                </div>
            </form>
        </div>
    </section>

    <!-- Populaire près de vous -->
    <section>
        <div class="mb-6 flex items-center justify-between gap-4">
            <h2 class="text-2xl font-bold tracking-tight text-ink">Populaire près de vous</h2>
            <a href="{{ route('events.index') }}" class="flex shrink-0 items-center gap-1.5 text-sm font-semibold text-coral-600 transition-colors hover:text-coral-500">
                Voir tout
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 17 17 7m0 0H8m9 0v9" />
                </svg>
            </a>
        </div>

        @if($featured->isEmpty())
            <div class="card p-10 text-center">
                <p class="text-base font-semibold text-ink">Aucun événement publié pour le moment.</p>
                <p class="mt-1 text-sm text-stone-500">Revenez bientôt, le programme arrive.</p>
            </div>
        @else
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-4">
                @foreach($featured->take(3) as $event)
                    <x-event-card :event="$event" />
                @endforeach

                @if($nextBooking)
                    @php
                        $daysUntil = (int) now()->startOfDay()->diffInDays($nextBooking->event->start_date->startOfDay(), false);
                    @endphp
                    <div class="flex flex-col rounded-3xl bg-peach p-5 sm:p-6">
                        <div class="flex items-center justify-between gap-3">
                            <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-ink/50">Votre prochain événement</p>
                            <span class="shrink-0 rounded-full bg-white/70 px-3 py-1 text-[11px] font-semibold text-ink/70">
                                {{ $daysUntil <= 0 ? "Aujourd'hui" : 'Dans '.$daysUntil.' jour'.($daysUntil > 1 ? 's' : '') }}
                            </span>
                        </div>

                        <h3 class="mt-3 font-display text-2xl font-bold tracking-tight text-ink">{{ $nextBooking->event->title }}</h3>

                        <div class="relative mt-4 overflow-hidden rounded-2xl bg-ink p-5 text-paper">
                            <span class="absolute -right-9 -top-12 h-28 w-28 rounded-full bg-coral-500/90" aria-hidden="true"></span>
                            <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-coral-400">En direct</p>
                            <p class="mt-1.5 max-w-[75%] font-display text-lg font-bold leading-tight">
                                {{ $nextBooking->event->venue_details ?: $nextBooking->event->location }}
                            </p>
                            <div class="mt-5 flex items-end justify-between gap-3">
                                <div>
                                    <p class="text-[10px] uppercase tracking-[0.16em] text-paper/40">{{ $nextBooking->event->start_date->translatedFormat('D, d M Y') }}</p>
                                    <p class="mt-1 text-[11px] font-medium text-paper/70">{{ $nextBooking->event->start_date->format('H:i') }} · {{ $nextBooking->ticketType->name }}</p>
                                </div>
                                <svg class="h-7 w-7 shrink-0 text-paper/80" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M4 4h6v6H4V4zm2 2v2h2V6H6zM14 4h6v6h-6V4zm2 2v2h2V6h-2zM4 14h6v6H4v-6zm2 2v2h2v-2H6zM14 14h2.5v2.5H14V14zm3.5 0H20v2.5h-2.5V14zm-3.5 3.5h2.5V20H14v-2.5zm3.5 0H20V20h-2.5v-2.5z" />
                                </svg>
                            </div>
                        </div>

                        <a href="{{ route('bookings.show', $nextBooking) }}" class="btn-dark mt-4 w-full">
                            Ouvrir mon billet
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 17 17 7m0 0H8m9 0v9" />
                            </svg>
                        </a>
                    </div>
                @else
                    <div class="flex flex-col justify-between rounded-3xl bg-peach p-5 sm:p-6">
                        <div>
                            <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-ink/50">Explorez la billetterie</p>
                            <h3 class="mt-3 font-display text-2xl font-bold leading-snug tracking-tight text-ink">Votre prochain moment vous attend.</h3>
                            <p class="mt-3 text-xs leading-relaxed text-ink/60">
                                Concerts, conférences, sport et culture : réservez en quelques secondes et recevez un QR code officiel.
                            </p>
                        </div>
                        <a href="{{ route('events.index') }}" class="btn-dark mt-6 w-full">
                            Parcourir les événements
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 17 17 7m0 0H8m9 0v9" />
                            </svg>
                        </a>
                    </div>
                @endif
            </div>
        @endif
    </section>

    <!-- Catégories -->
    <section class="border-t border-stone-200 pt-12">
        <div class="mb-6 flex items-end justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-ink">Explorez par catégorie</h2>
                <p class="mt-1 text-sm text-stone-500">Quelque chose pour chaque soirée.</p>
            </div>
            <a href="{{ route('events.index') }}" class="hidden shrink-0 items-center gap-1.5 text-sm font-semibold text-coral-600 transition-colors hover:text-coral-500 sm:flex">
                Tout parcourir
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 17 17 7m0 0H8m9 0v9" />
                </svg>
            </a>
        </div>

        <div class="flex items-center gap-2.5 overflow-x-auto pb-1">
            <a href="{{ route('events.index') }}" class="chip chip-active">Tous les événements</a>
            @foreach($categories as $cat)
                <a href="{{ route('events.index', ['category' => $cat]) }}" class="chip">{{ $cat }}</a>
            @endforeach
        </div>
    </section>
</div>
@endsection
