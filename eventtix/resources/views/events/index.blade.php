@extends('layouts.app')
@section('title', 'Tous les événements')
@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Découvrez les événements</h1>

    <form method="GET" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 grid grid-cols-1 sm:grid-cols-4 gap-3 mb-8">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Mot-clé..." class="rounded-lg border-gray-200 text-sm focus:ring-brand-500 focus:border-brand-500 sm:col-span-2">
        <select name="category" class="rounded-lg border-gray-200 text-sm focus:ring-brand-500 focus:border-brand-500">
            <option value="">Toutes catégories</option>
            @foreach($categories as $cat)
                <option value="{{ $cat }}" @selected(request('category') === $cat)>{{ $cat }}</option>
            @endforeach
        </select>
        <input type="date" name="date" value="{{ request('date') }}" class="rounded-lg border-gray-200 text-sm focus:ring-brand-500 focus:border-brand-500">
        <div class="sm:col-span-4 flex gap-2">
            <button class="bg-brand-500 hover:bg-brand-600 text-white text-sm font-semibold px-5 py-2 rounded-lg transition">Filtrer</button>
            <a href="{{ route('events.index') }}" class="text-sm text-gray-400 hover:text-gray-600 px-3 py-2">Réinitialiser</a>
        </div>
    </form>

    @if($events->isEmpty())
        <div class="text-center py-20 text-gray-400">Aucun événement ne correspond à votre recherche.</div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($events as $event)
                <x-event-card :event="$event" />
            @endforeach
        </div>
        <div class="mt-8">{{ $events->links() }}</div>
    @endif
</div>
@endsection
