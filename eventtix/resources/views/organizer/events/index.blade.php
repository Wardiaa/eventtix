@extends('layouts.app')
@section('title', 'Mes événements')
@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Mes événements</h1>
        <a href="{{ route('organizer.events.create') }}" class="bg-brand-500 hover:bg-brand-600 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition">+ Nouvel événement</a>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-400 text-xs uppercase">
                <tr>
                    <th class="text-left px-5 py-3">Titre</th>
                    <th class="text-left px-5 py-3">Date</th>
                    <th class="text-left px-5 py-3">Statut</th>
                    <th class="text-left px-5 py-3">Réservations</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($events as $event)
                    <tr>
                        <td class="px-5 py-3 font-medium text-gray-800">{{ $event->title }}</td>
                        <td class="px-5 py-3 text-gray-500">{{ $event->start_date->format('d/m/Y H:i') }}</td>
                        <td class="px-5 py-3">
                            <span class="text-xs font-semibold px-2 py-1 rounded-full
                                {{ $event->status === 'published' ? 'bg-brand-100 text-brand-700' : 'bg-gray-100 text-gray-500' }}">
                                {{ ucfirst($event->status) }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-gray-500">{{ $event->bookings_count }}</td>
                        <td class="px-5 py-3 text-right">
                            <a href="{{ route('organizer.events.show', $event) }}" class="text-brand-600 font-semibold hover:underline">Gérer</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-5 py-8 text-center text-gray-400">Aucun événement créé.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $events->links() }}</div>
</div>
@endsection
