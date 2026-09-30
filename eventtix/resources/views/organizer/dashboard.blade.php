@extends('layouts.app')
@section('title', 'Tableau de bord organisateur')
@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Tableau de bord</h1>
        <a href="{{ route('organizer.events.create') }}" class="bg-brand-500 hover:bg-brand-600 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition">+ Nouvel événement</a>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-10">
        <div class="bg-white rounded-2xl border border-gray-100 p-5">
            <p class="text-xs text-gray-400">Événements</p>
            <p class="text-2xl font-extrabold text-gray-900">{{ $stats['total_events'] }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-5">
            <p class="text-xs text-gray-400">Publiés</p>
            <p class="text-2xl font-extrabold text-gray-900">{{ $stats['published_events'] }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-5">
            <p class="text-xs text-gray-400">Billets vendus</p>
            <p class="text-2xl font-extrabold text-gray-900">{{ $stats['total_tickets_sold'] }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-5">
            <p class="text-xs text-gray-400">Revenu total</p>
            <p class="text-2xl font-extrabold text-brand-600">{{ number_format($stats['total_revenue'], 0, ',', ' ') }} DA</p>
        </div>
    </div>

    <div class="flex flex-wrap gap-3 mb-10">
        <a href="{{ route('organizer.events.index') }}" class="text-sm font-semibold text-brand-600 bg-brand-50 hover:bg-brand-100 px-4 py-2 rounded-xl transition">Gérer mes événements</a>
        <a href="{{ route('organizer.validate.show') }}" class="text-sm font-semibold text-brand-600 bg-brand-50 hover:bg-brand-100 px-4 py-2 rounded-xl transition">Valider un billet à l'entrée</a>
    </div>

    <h2 class="font-bold text-gray-900 mb-4">Dernières réservations</h2>
    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-400 text-xs uppercase">
                <tr>
                    <th class="text-left px-5 py-3">Participant</th>
                    <th class="text-left px-5 py-3">Événement</th>
                    <th class="text-left px-5 py-3">Billet</th>
                    <th class="text-left px-5 py-3">Montant</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($recentBookings as $b)
                    <tr>
                        <td class="px-5 py-3 font-medium text-gray-800">{{ $b->user->name }}</td>
                        <td class="px-5 py-3 text-gray-500">{{ $b->event->title }}</td>
                        <td class="px-5 py-3 text-gray-500">{{ $b->ticketType->name }} × {{ $b->quantity }}</td>
                        <td class="px-5 py-3 font-semibold text-gray-800">{{ number_format($b->total_price, 0, ',', ' ') }} DA</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-5 py-8 text-center text-gray-400">Aucune réservation pour le moment.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
