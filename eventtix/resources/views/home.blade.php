@extends('layouts.app')
@section('title', 'Accueil')
@section('content')

<section class="bg-gradient-to-b from-brand-50 to-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center">
        <h1 class="text-3xl sm:text-5xl font-extrabold text-gray-900 tracking-tight">
            Trouvez et réservez<br class="hidden sm:block"> vos prochains <span class="text-brand-500">événements</span>
        </h1>
        <p class="mt-4 text-gray-500 max-w-xl mx-auto">Conférences, concerts, salons professionnels... réservez vos billets en quelques clics et recevez votre QR code instantanément.</p>

        <form action="{{ route('events.index') }}" method="GET" class="mt-8 max-w-2xl mx-auto bg-white rounded-2xl shadow-lg border border-gray-100 p-2 flex flex-col sm:flex-row gap-2">
            <div class="flex items-center flex-1 px-3">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/></svg>
                <input type="text" name="q" placeholder="Rechercher un événement, un lieu..." class="w-full px-3 py-3 outline-none text-sm">
            </div>
            <button class="bg-brand-500 hover:bg-brand-600 text-white font-semibold px-6 py-3 rounded-xl transition">Rechercher</button>
        </form>

        <div class="mt-4 flex flex-wrap justify-center gap-2">
            @foreach($categories as $cat)
                <a href="{{ route('events.index', ['category' => $cat]) }}" class="text-xs font-medium text-brand-700 bg-brand-100 hover:bg-brand-200 px-3 py-1.5 rounded-full transition">{{ $cat }}</a>
            @endforeach
        </div>
    </div>
</section>

<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Événements à la une</h2>
        <a href="{{ route('events.index') }}" class="text-sm font-semibold text-brand-600 hover:text-brand-700">Voir tout →</a>
    </div>

    @if($featured->isEmpty())
        <p class="text-gray-400">Aucun événement publié pour le moment.</p>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($featured as $event)
                <x-event-card :event="$event" />
            @endforeach
        </div>
    @endif
</section>

<section class="bg-brand-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 grid sm:grid-cols-3 gap-8 text-center text-white">
        <div>
            <p class="text-3xl font-extrabold">🎟️</p>
            <p class="mt-2 font-semibold">Réservation instantanée</p>
            <p class="text-sm text-brand-100 mt-1">Choisissez votre billet et recevez votre confirmation immédiatement.</p>
        </div>
        <div>
            <p class="text-3xl font-extrabold">📱</p>
            <p class="mt-2 font-semibold">QR code personnel</p>
            <p class="text-sm text-brand-100 mt-1">Un billet, un QR code unique scanné à l'entrée.</p>
        </div>
        <div>
            <p class="text-3xl font-extrabold">📊</p>
            <p class="mt-2 font-semibold">Tableau de bord organisateur</p>
            <p class="text-sm text-brand-100 mt-1">Suivez vos ventes et vos statistiques en temps réel.</p>
        </div>
    </div>
</section>

@endsection
