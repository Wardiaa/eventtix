@extends('layouts.app')

@section('title', 'Mes événements')

@section('content')
<div class="pb-8">
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="eyebrow">Studio organisateur</p>
            <h1 class="page-title mt-3">Mes événements.</h1>
            <p class="page-subtitle">Créez, publiez et suivez les performances de vos dates.</p>
        </div>
        <a href="{{ route('organizer.events.create') }}" class="btn-dark">
            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Nouvel événement
        </a>
    </div>

    <div class="card mt-10 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="border-b border-stone-100 bg-cream/60">
                    <tr>
                        <th class="table-th">Titre</th>
                        <th class="table-th">Date</th>
                        <th class="table-th">Statut</th>
                        <th class="table-th text-right">Réservations</th>
                        <th class="table-th"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @forelse($events as $event)
                        <tr class="transition-colors hover:bg-cream/60">
                            <td class="px-5 py-4">
                                <a href="{{ route('organizer.events.show', $event) }}" class="font-semibold text-ink transition-colors hover:text-coral-600">
                                    {{ $event->title }}
                                </a>
                                <p class="mt-0.5 text-xs text-stone-400">{{ $event->category }} · {{ $event->location }}</p>
                            </td>
                            <td class="px-5 py-4 text-stone-500 tnum">{{ $event->start_date->format('d/m/Y H:i') }}</td>
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-[11px] font-semibold
                                    {{ $event->status === 'published' ? 'bg-emerald-50 text-emerald-800' : 'bg-stone-100 text-stone-500' }}">
                                    <span class="h-1.5 w-1.5 rounded-full {{ $event->status === 'published' ? 'bg-emerald-500' : 'bg-stone-400' }}"></span>
                                    {{ ucfirst($event->status) }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-right font-semibold text-ink tnum">{{ $event->bookings_count }}</td>
                            <td class="px-5 py-4 text-right">
                                <a href="{{ route('organizer.events.show', $event) }}" class="inline-flex items-center gap-1 text-xs font-semibold text-coral-600 transition-colors hover:text-coral-500">
                                    Gérer
                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 17 17 7m0 0H8m9 0v9" />
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center">
                                <p class="text-sm font-semibold text-ink">Aucun événement créé.</p>
                                <p class="mt-1 text-xs text-stone-400">Lancez votre première date en quelques minutes.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-10">{{ $events->links() }}</div>
</div>
@endsection
