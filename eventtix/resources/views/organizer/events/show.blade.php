@extends('layouts.app')
@section('title', $event->title)
@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex flex-wrap items-start justify-between gap-4 mb-8">
        <div>
            <span class="text-xs font-semibold px-2 py-1 rounded-full {{ $event->status === 'published' ? 'bg-brand-100 text-brand-700' : 'bg-gray-100 text-gray-500' }}">
                {{ ucfirst($event->status) }}
            </span>
            <h1 class="text-2xl font-bold text-gray-900 mt-2">{{ $event->title }}</h1>
            <p class="text-sm text-gray-500">{{ $event->start_date->translatedFormat('d F Y, H:i') }} · {{ $event->location }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('organizer.events.edit', $event) }}" class="text-sm font-semibold text-brand-600 bg-brand-50 hover:bg-brand-100 px-4 py-2 rounded-xl transition">Modifier</a>
            <form method="POST" action="{{ route('organizer.events.destroy', $event) }}" onsubmit="return confirm('Supprimer définitivement cet événement ?');">
                @csrf @method('DELETE')
                <button class="text-sm font-semibold text-red-500 bg-red-50 hover:bg-red-100 px-4 py-2 rounded-xl transition">Supprimer</button>
            </form>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-8">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-bold text-gray-900">Types de billets</h2>
                    <a href="{{ route('organizer.ticket-types.create', $event) }}" class="text-sm font-semibold text-brand-600 hover:underline">+ Ajouter un type de billet</a>
                </div>
                <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 text-gray-400 text-xs uppercase">
                            <tr>
                                <th class="text-left px-5 py-3">Nom</th>
                                <th class="text-left px-5 py-3">Prix</th>
                                <th class="text-left px-5 py-3">Vendus / Total</th>
                                <th class="px-5 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($event->ticketTypes as $tt)
                                <tr>
                                    <td class="px-5 py-3 font-medium text-gray-800">{{ $tt->name }}</td>
                                    <td class="px-5 py-3 text-gray-500">{{ $tt->price > 0 ? number_format($tt->price, 0, ',', ' ').' DA' : 'Gratuit' }}</td>
                                    <td class="px-5 py-3 text-gray-500">{{ $tt->quantity_sold }} / {{ $tt->quantity }}</td>
                                    <td class="px-5 py-3 text-right space-x-3">
                                        <a href="{{ route('organizer.ticket-types.edit', [$event, $tt]) }}" class="text-brand-600 font-semibold hover:underline">Modifier</a>
                                        <form method="POST" action="{{ route('organizer.ticket-types.destroy', [$event, $tt]) }}" class="inline" onsubmit="return confirm('Supprimer ce type de billet ?');">
                                            @csrf @method('DELETE')
                                            <button class="text-red-500 font-semibold hover:underline">Suppr.</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="px-5 py-8 text-center text-gray-400">Aucun type de billet. Ajoutez-en un pour permettre les réservations.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div>
                <h2 class="font-bold text-gray-900 mb-4">Participants ({{ $event->bookings->where('status','confirmed')->sum('quantity') }})</h2>
                <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 text-gray-400 text-xs uppercase">
                            <tr>
                                <th class="text-left px-5 py-3">Participant</th>
                                <th class="text-left px-5 py-3">Billet</th>
                                <th class="text-left px-5 py-3">Qté</th>
                                <th class="text-left px-5 py-3">Statut</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($event->bookings as $b)
                                <tr>
                                    <td class="px-5 py-3 font-medium text-gray-800">{{ $b->user->name }}</td>
                                    <td class="px-5 py-3 text-gray-500">{{ $b->ticketType->name }}</td>
                                    <td class="px-5 py-3 text-gray-500">{{ $b->quantity }}</td>
                                    <td class="px-5 py-3">
                                        <span class="text-xs font-semibold px-2 py-1 rounded-full {{ $b->status === 'confirmed' ? 'bg-brand-100 text-brand-700' : 'bg-gray-100 text-gray-500' }}">
                                            {{ $b->status === 'confirmed' ? 'Confirmée' : 'Annulée' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="px-5 py-8 text-center text-gray-400">Aucune réservation pour le moment.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 h-fit">
            <h2 class="font-bold text-gray-900 mb-4">Résumé</h2>
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between"><dt class="text-gray-400">Capacité</dt><dd class="font-semibold">{{ $event->capacity }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-400">Billets vendus</dt><dd class="font-semibold">{{ $event->ticketTypes->sum('quantity_sold') }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-400">Revenu</dt><dd class="font-semibold text-brand-600">{{ number_format($event->bookings->where('status','confirmed')->sum('total_price'), 0, ',', ' ') }} DA</dd></div>
            </dl>
            <a href="{{ route('organizer.validate.show') }}" class="mt-6 block text-center text-sm font-semibold text-white bg-brand-500 hover:bg-brand-600 px-4 py-2.5 rounded-xl transition">Valider les billets à l'entrée</a>
        </div>
    </div>
</div>
@endsection
