@extends('layouts.app')

@section('title', 'Administration')

@section('content')
<div x-data="{ tab: 'events' }" class="pb-8">
    <!-- Header -->
    <div>
        <p class="eyebrow">Supervision</p>
        <h1 class="page-title mt-3">Administration.</h1>
        <p class="page-subtitle">Modération des événements et attribution des rôles utilisateurs.</p>
    </div>

    <!-- KPIs -->
    <div class="mt-10 grid grid-cols-2 gap-4 lg:grid-cols-4 lg:gap-5">
        <button type="button" @click="tab = 'users'" class="card cursor-pointer p-5 text-left transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lift sm:p-6">
            <div class="flex items-center justify-between">
                <p class="label-micro !mb-0">Utilisateurs</p>
                <svg class="h-4 w-4 text-stone-300" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 1 1 0 5.292M15 21H3v-1a6 6 0 0 1 12 0v1zm0 0h6v-1a6 6 0 0 0-9-5.197M13 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0z" />
                </svg>
            </div>
            <p class="mt-3 font-display text-3xl font-bold tracking-tight text-ink tnum">{{ number_format($stats['total_users'], 0, ',', ' ') }}</p>
            <p class="mt-2 text-xs font-medium text-stone-400">Comptes enregistrés</p>
        </button>

        <button type="button" @click="tab = 'events'" class="card cursor-pointer p-5 text-left transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lift sm:p-6">
            <div class="flex items-center justify-between">
                <p class="label-micro !mb-0">Événements</p>
                <svg class="h-4 w-4 text-stone-300" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24">
                    <rect x="3.75" y="5" width="16.5" height="15.25" rx="3" />
                    <path stroke-linecap="round" d="M8.25 3.5v3M15.75 3.5v3M3.75 10.25h16.5" />
                </svg>
            </div>
            <p class="mt-3 font-display text-3xl font-bold tracking-tight text-ink tnum">{{ number_format($stats['total_events'], 0, ',', ' ') }}</p>
            <p class="mt-2 text-xs font-medium text-stone-400">Total référencés</p>
        </button>

        <div class="card p-5 sm:p-6">
            <div class="flex items-center justify-between">
                <p class="label-micro !mb-0">Volume des ventes</p>
                <svg class="h-4 w-4 text-stone-300" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" />
                </svg>
            </div>
            <p class="mt-3 font-display text-3xl font-bold tracking-tight text-ink tnum">
                {{ number_format($stats['total_revenue'], 0, ',', ' ') }}<span class="ml-1 text-sm font-semibold text-stone-400">DA</span>
            </p>
            <p class="mt-2 text-xs font-medium text-stone-400">Transactions confirmées</p>
        </div>

        <div class="card p-5 sm:p-6">
            <div class="flex items-center justify-between">
                <p class="label-micro !mb-0">Réservations</p>
                <svg class="h-4 w-4 text-stone-300" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 0 0-2 2v3a2 2 0 1 1 0 4v3a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-3a2 2 0 1 1 0-4V7a2 2 0 0 0-2-2H5z" />
                </svg>
            </div>
            <p class="mt-3 font-display text-3xl font-bold tracking-tight text-ink tnum">{{ number_format($stats['total_bookings'], 0, ',', ' ') }}</p>
            <p class="mt-2 text-xs font-medium text-stone-400">Commandes passées</p>
        </div>
    </div>

    <!-- Tabs -->
    <div class="mt-10 flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-2">
            <button type="button" @click="tab = 'events'"
                class="chip cursor-pointer"
                :class="tab === 'events' ? 'chip-active' : ''">
                Gestion des événements
            </button>
            <button type="button" @click="tab = 'users'"
                class="chip cursor-pointer"
                :class="tab === 'users' ? 'chip-active' : ''">
                Gestion des comptes
            </button>
            <button type="button" @click="tab = 'all'"
                class="chip cursor-pointer"
                :class="tab === 'all' ? 'chip-active' : ''">
                Vue d'ensemble
            </button>
        </div>

        <div class="flex items-center gap-3 text-xs font-semibold">
            <a href="{{ route('admin.events.index') }}" class="text-coral-600 transition-colors hover:text-coral-500">Tous les événements ↗</a>
            <span class="text-stone-300">·</span>
            <a href="{{ route('admin.users.index') }}" class="text-coral-600 transition-colors hover:text-coral-500">Tous les utilisateurs ↗</a>
        </div>
    </div>

    <div class="mt-6 space-y-6">
        <!-- Events moderation -->
        <div x-show="tab === 'events' || tab === 'all'" class="card overflow-hidden">
            <div class="flex flex-wrap items-center justify-between gap-3 p-6 pb-4 sm:px-7">
                <div>
                    <h3 class="font-display text-lg font-bold tracking-tight text-ink">Derniers événements créés</h3>
                    <p class="mt-0.5 text-xs text-stone-500">Supervisez les dates et les publications des organisateurs.</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="border-y border-stone-100 bg-cream/60">
                        <tr>
                            <th class="table-th">Titre</th>
                            <th class="table-th">Organisateur</th>
                            <th class="table-th">Date</th>
                            <th class="table-th">Statut</th>
                            <th class="table-th text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100">
                        @forelse($recentEvents as $event)
                            <tr class="transition-colors hover:bg-cream/60">
                                <td class="px-5 py-4 font-semibold text-ink">
                                    <a href="{{ route('events.show', $event) }}" class="transition-colors hover:text-coral-600">{{ $event->title }}</a>
                                </td>
                                <td class="px-5 py-4 text-stone-500">{{ $event->organizer->name }}</td>
                                <td class="px-5 py-4 text-stone-500 tnum">{{ $event->start_date->translatedFormat('d M Y') }}</td>
                                <td class="px-5 py-4">
                                    <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-[11px] font-semibold
                                        {{ $event->status === 'published' ? 'bg-emerald-50 text-emerald-800' : 'bg-stone-100 text-stone-500' }}">
                                        <span class="h-1.5 w-1.5 rounded-full {{ $event->status === 'published' ? 'bg-emerald-500' : 'bg-stone-400' }}"></span>
                                        {{ ucfirst($event->status) }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <a href="{{ route('events.show', $event) }}" class="text-xs font-semibold text-coral-600 transition-colors hover:text-coral-500">Voir ↗</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-10 text-center text-sm text-stone-400">Aucun événement récent.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Accounts -->
        <div x-show="tab === 'users' || tab === 'all'" class="card overflow-hidden" style="display: none;">
            <div class="flex flex-wrap items-center justify-between gap-3 p-6 pb-4 sm:px-7">
                <div>
                    <h3 class="font-display text-lg font-bold tracking-tight text-ink">Comptes et rôles</h3>
                    <p class="mt-0.5 text-xs text-stone-500">Accédez au répertoire complet et modifiez les permissions.</p>
                </div>
                <a href="{{ route('admin.users.index') }}" class="btn-dark !px-4 !py-2.5 !text-xs">
                    Ouvrir la liste des utilisateurs
                </a>
            </div>

            <div class="flex flex-wrap items-center justify-between gap-3 border-t border-stone-100 bg-cream/60 px-6 py-4 text-xs text-stone-500 sm:px-7">
                <span>
                    Total : <strong class="font-semibold text-ink tnum">{{ $stats['total_users'] }}</strong> comptes enregistrés
                    (<strong class="font-semibold text-ink tnum">{{ $stats['total_organizers'] }}</strong> organisateurs)
                </span>
                <span class="text-stone-400">Permissions : Participant · Organisateur · Administrateur</span>
            </div>
        </div>
    </div>
</div>
@endsection
