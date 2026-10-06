@extends('layouts.app')

@section('title', 'Studio organisateur')

@section('content')
@php
    $chartMax = max(1, $salesChart->max('sold'));
@endphp

<div class="pb-8">
    <!-- Header -->
    <div>
        <p class="eyebrow">Studio organisateur</p>
        <h1 class="page-title mt-3">Pilotez vos événements.</h1>
        <p class="page-subtitle">Une vue claire pour créer, vendre et faire grandir vos expériences.</p>
    </div>

    <!-- KPIs -->
    <div class="mt-10 grid grid-cols-2 gap-4 lg:grid-cols-4 lg:gap-5">
        <div class="card p-5 sm:p-6">
            <p class="label-micro !mb-0">Billets vendus</p>
            <p class="mt-3 font-display text-3xl font-bold tracking-tight text-ink tnum">{{ number_format($stats['total_tickets_sold'], 0, ',', ' ') }}</p>
            <p class="mt-2 text-xs font-medium text-coral-600 tnum">+{{ $stats['tickets_last_week'] }} cette semaine</p>
        </div>

        <div class="card p-5 sm:p-6">
            <p class="label-micro !mb-0">Revenus</p>
            <p class="mt-3 font-display text-3xl font-bold tracking-tight text-ink tnum">
                {{ number_format($stats['total_revenue'], 0, ',', ' ') }}<span class="ml-1 text-sm font-semibold text-stone-400">DA</span>
            </p>
            <p class="mt-2 text-xs font-medium text-coral-600 tnum">+{{ number_format($stats['revenue_last_week'], 0, ',', ' ') }} DA cette semaine</p>
        </div>

        <div class="card p-5 sm:p-6">
            <p class="label-micro !mb-0">Événements actifs</p>
            <p class="mt-3 font-display text-3xl font-bold tracking-tight text-ink tnum">{{ str_pad($stats['published_events'], 2, '0', STR_PAD_LEFT) }}</p>
            <p class="mt-2 text-xs font-medium text-coral-600 tnum">{{ $stats['upcoming_events'] }} à venir</p>
        </div>

        <div class="card p-5 sm:p-6">
            <p class="label-micro !mb-0">Taux de remplissage</p>
            <p class="mt-3 font-display text-3xl font-bold tracking-tight text-ink tnum">{{ $stats['fill_rate'] }}%</p>
            <p class="mt-2 text-xs font-medium text-coral-600">{{ $stats['total_events'] }} événement(s) au total</p>
        </div>
    </div>

    <!-- Chart + quick action -->
    <div class="mt-6 grid grid-cols-1 gap-5 lg:grid-cols-3">
        <div class="card p-6 lg:col-span-2 sm:p-7">
            <div class="flex items-center justify-between gap-4">
                <h2 class="font-display text-lg font-bold tracking-tight text-ink">Performance des ventes</h2>
                <span class="text-xs font-medium text-stone-400">30 derniers jours</span>
            </div>

            <div class="mt-7 flex h-52 items-end gap-1.5">
                @foreach($salesChart as $point)
                    @php $height = $point['sold'] > 0 ? max(6, round($point['sold'] / $chartMax * 100)) : 4; @endphp
                    <div class="group flex h-full flex-1 items-end" title="{{ $point['label'] }} — {{ $point['sold'] }} billet(s)">
                        <div class="w-full rounded-t-md transition-all duration-300 {{ $point['sold'] > 0 ? 'bg-coral-400 group-hover:bg-coral-500' : 'bg-coral-400/25' }}"
                            style="height: {{ $height }}%"></div>
                    </div>
                @endforeach
            </div>

            <div class="mt-3 flex items-center justify-between text-[10px] font-medium uppercase tracking-[0.14em] text-stone-400">
                <span>{{ $salesChart->first()['date'] }}</span>
                <span>{{ $salesChart->last()['date'] }}</span>
            </div>
        </div>

        <div class="flex flex-col justify-between rounded-3xl bg-peach p-6 shadow-soft sm:p-7">
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-ink/50">Action rapide</p>
                <h2 class="mt-4 font-display text-2xl font-bold leading-snug tracking-tight text-ink">Créez votre prochain événement.</h2>
                <p class="mt-3 text-xs leading-relaxed text-ink/60">
                    Mettez votre prochaine date en ligne en quelques minutes.
                </p>
            </div>
            <div class="mt-7 space-y-2.5">
                <a href="{{ route('organizer.events.create') }}" class="btn-dark w-full">
                    Commencer
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 17 17 7m0 0H8m9 0v9" />
                    </svg>
                </a>
                <a href="{{ route('organizer.validate.show') }}" class="block w-full py-1 text-center text-xs font-semibold text-ink/60 transition-colors hover:text-ink">
                    Contrôle d'accès ↗
                </a>
            </div>
        </div>
    </div>

    <!-- Recent bookings -->
    <div class="card mt-6 overflow-hidden">
        <div class="flex flex-wrap items-center justify-between gap-3 p-6 pb-4 sm:px-7">
            <h2 class="font-display text-lg font-bold tracking-tight text-ink">Dernières réservations</h2>
            <a href="{{ route('organizer.events.index') }}" class="flex items-center gap-1.5 text-xs font-semibold text-coral-600 transition-colors hover:text-coral-500">
                Gérer mes événements
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 17 17 7m0 0H8m9 0v9" />
                </svg>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="border-y border-stone-100 bg-cream/60">
                    <tr>
                        <th class="table-th">Participant</th>
                        <th class="table-th">Événement</th>
                        <th class="table-th">Billet</th>
                        <th class="table-th text-right">Montant</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @forelse($recentBookings as $b)
                        <tr class="transition-colors hover:bg-cream/60">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-peach text-[11px] font-bold text-ink">
                                        {{ strtoupper(mb_substr($b->user->name, 0, 1)) }}
                                    </span>
                                    <span class="font-semibold text-ink">{{ $b->user->name }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-stone-500">{{ $b->event->title }}</td>
                            <td class="px-5 py-4 text-stone-500">{{ $b->ticketType->name }} × {{ $b->quantity }}</td>
                            <td class="px-5 py-4 text-right font-semibold text-ink tnum">{{ number_format($b->total_price, 0, ',', ' ') }} DA</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-10 text-center text-sm text-stone-400">Aucune réservation pour le moment.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
