@extends('layouts.app')
@section('title', 'Administration')
@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <h1 class="text-2xl font-bold text-gray-900 mb-8">Tableau de bord administrateur</h1>

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4 mb-10">
        <div class="bg-white rounded-2xl border border-gray-100 p-5"><p class="text-xs text-gray-400">Utilisateurs</p><p class="text-2xl font-extrabold text-gray-900">{{ $stats['total_users'] }}</p></div>
        <div class="bg-white rounded-2xl border border-gray-100 p-5"><p class="text-xs text-gray-400">Organisateurs</p><p class="text-2xl font-extrabold text-gray-900">{{ $stats['total_organizers'] }}</p></div>
        <div class="bg-white rounded-2xl border border-gray-100 p-5"><p class="text-xs text-gray-400">Événements</p><p class="text-2xl font-extrabold text-gray-900">{{ $stats['total_events'] }}</p></div>
        <div class="bg-white rounded-2xl border border-gray-100 p-5"><p class="text-xs text-gray-400">Publiés</p><p class="text-2xl font-extrabold text-gray-900">{{ $stats['published_events'] }}</p></div>
        <div class="bg-white rounded-2xl border border-gray-100 p-5"><p class="text-xs text-gray-400">Réservations</p><p class="text-2xl font-extrabold text-gray-900">{{ $stats['total_bookings'] }}</p></div>
        <div class="bg-white rounded-2xl border border-gray-100 p-5"><p class="text-xs text-gray-400">Revenu</p><p class="text-2xl font-extrabold text-brand-600">{{ number_format($stats['total_revenue'], 0, ',', ' ') }} DA</p></div>
    </div>

    <div class="flex flex-wrap gap-3 mb-10">
        <a href="{{ route('admin.users.index') }}" class="text-sm font-semibold text-brand-600 bg-brand-50 hover:bg-brand-100 px-4 py-2 rounded-xl transition">Gérer les utilisateurs</a>
        <a href="{{ route('admin.events.index') }}" class="text-sm font-semibold text-brand-600 bg-brand-50 hover:bg-brand-100 px-4 py-2 rounded-xl transition">Superviser les événements</a>
    </div>

    <h2 class="font-bold text-gray-900 mb-4">Derniers événements créés</h2>
    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-400 text-xs uppercase">
                <tr><th class="text-left px-5 py-3">Titre</th><th class="text-left px-5 py-3">Organisateur</th><th class="text-left px-5 py-3">Statut</th></tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($recentEvents as $event)
                    <tr>
                        <td class="px-5 py-3 font-medium text-gray-800">{{ $event->title }}</td>
                        <td class="px-5 py-3 text-gray-500">{{ $event->organizer->name }}</td>
                        <td class="px-5 py-3"><span class="text-xs font-semibold px-2 py-1 rounded-full {{ $event->status === 'published' ? 'bg-brand-100 text-brand-700' : 'bg-gray-100 text-gray-500' }}">{{ ucfirst($event->status) }}</span></td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="px-5 py-8 text-center text-gray-400">Aucun événement.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
