@extends('layouts.app')

@section('title', 'Tous les événements')

@section('content')
@php
    $hasFilters = request()->filled('q') || request()->filled('category') || request()->filled('date');
@endphp

<div class="pb-8">
    <div>
        <p class="eyebrow">Catalogue</p>
        <h1 class="page-title mt-3">Tous les événements.</h1>
        <p class="page-subtitle">
            Parcourez l'agenda complet des conférences, concerts et salons en Algérie.
        </p>
    </div>

    <!-- Category pills -->
    <div class="mt-8 flex items-center gap-2.5 overflow-x-auto pb-1">
        <a href="{{ route('events.index', request()->only('q', 'date')) }}"
            class="chip {{ !request()->filled('category') ? 'chip-active' : '' }}">Toutes catégories</a>
        @foreach($categories as $cat)
            <a href="{{ route('events.index', array_merge(request()->only('q', 'date'), ['category' => $cat])) }}"
                class="chip {{ request('category') === $cat ? 'chip-active' : '' }}">{{ $cat }}</a>
        @endforeach
    </div>

    <!-- Filter bar -->
    <form method="GET" class="mt-4 rounded-3xl border border-stone-200/70 bg-white p-4 shadow-soft sm:p-5">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
            <div class="relative flex-1">
                <svg class="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-stone-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11a6 6 0 1 1-12 0 6 6 0 0 1 12 0z" />
                </svg>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Rechercher par mot-clé, ville…"
                    class="input !bg-cream !pl-11">
            </div>

            <div class="sm:w-52">
                <input type="date" name="date" value="{{ request('date') }}" class="input tnum">
            </div>

            <button type="submit" class="btn-dark shrink-0 sm:!px-7">Filtrer</button>

            @if($hasFilters)
                <a href="{{ route('events.index') }}" class="shrink-0 px-2 py-2 text-center text-xs font-medium text-stone-500 transition-colors hover:text-ink">
                    Réinitialiser
                </a>
            @endif
        </div>
    </form>

    @if($events->isEmpty())
        <div class="card mt-8 p-12 text-center">
            <p class="text-base font-semibold text-ink">Aucun événement ne correspond à vos critères.</p>
            <p class="mt-1 text-sm text-stone-500">Essayez d'élargir votre recherche ou de réinitialiser les filtres.</p>
            <a href="{{ route('events.index') }}" class="btn-soft mt-6">Réinitialiser les filtres</a>
        </div>
    @else
        <div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($events as $event)
                <x-event-card :event="$event" />
            @endforeach
        </div>
        <div class="mt-10">{{ $events->links() }}</div>
    @endif
</div>
@endsection
