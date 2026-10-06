@extends('layouts.app')

@section('title', $event->title)

@section('content')
<div class="pb-8">
    <a href="{{ route('organizer.events.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-stone-500 transition-colors hover:text-ink">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Mes événements
    </a>

    <div class="mt-5 flex flex-wrap items-start justify-between gap-4">
        <div>
            <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-[11px] font-semibold
                {{ $event->status === 'published' ? 'bg-emerald-50 text-emerald-800' : 'bg-stone-100 text-stone-500' }}">
                <span class="h-1.5 w-1.5 rounded-full {{ $event->status === 'published' ? 'bg-emerald-500' : 'bg-stone-400' }}"></span>
                {{ ucfirst($event->status) }}
            </span>
            <h1 class="mt-3 font-display text-3xl font-bold tracking-tight text-ink sm:text-4xl">{{ $event->title }}</h1>
            <p class="mt-2 text-sm font-medium text-stone-500 tnum">
                {{ $event->start_date->translatedFormat('d F Y, H:i') }}
                <span class="mx-1.5 text-stone-300">·</span>
                {{ $event->location }}
            </p>
        </div>
        <div class="flex gap-2.5">
            <a href="{{ route('organizer.events.edit', $event) }}" class="btn-soft">Modifier</a>
            <form method="POST" action="{{ route('organizer.events.destroy', $event) }}" onsubmit="return confirm('Supprimer définitivement cet événement ?');">
                @csrf @method('DELETE')
                <button type="submit" class="inline-flex cursor-pointer items-center gap-2 rounded-xl border border-red-200/70 bg-red-50 px-5 py-3 text-sm font-medium text-red-700 transition-colors hover:bg-red-100">
                    Supprimer
                </button>
            </form>
        </div>
    </div>

    <div class="mt-10 grid gap-6 lg:grid-cols-3">
        <div class="space-y-6 lg:col-span-2">
            <div class="card overflow-hidden">
                <div class="flex flex-wrap items-center justify-between gap-3 p-6 pb-4 sm:px-7">
                    <h2 class="font-display text-lg font-bold tracking-tight text-ink">Types de billets</h2>
                    <a href="{{ route('organizer.ticket-types.create', $event) }}" class="flex items-center gap-1.5 text-xs font-semibold text-coral-600 transition-colors hover:text-coral-500">
                        Ajouter un type
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="border-y border-stone-100 bg-cream/60">
                            <tr>
                                <th class="table-th">Nom</th>
                                <th class="table-th">Prix</th>
                                <th class="table-th">Vendus / Total</th>
                                <th class="table-th"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100">
                            @forelse($event->ticketTypes as $tt)
                                @php $pct = $tt->quantity > 0 ? (int) round($tt->quantity_sold / $tt->quantity * 100) : 0; @endphp
                                <tr class="transition-colors hover:bg-cream/60">
                                    <td class="px-5 py-4 font-semibold text-ink">{{ $tt->name }}</td>
                                    <td class="px-5 py-4 text-stone-500 tnum">{{ $tt->price > 0 ? number_format($tt->price, 0, ',', ' ').' DA' : 'Gratuit' }}</td>
                                    <td class="px-5 py-4">
                                        <div class="flex items-center gap-3">
                                            <span class="text-xs font-medium text-stone-500 tnum">{{ $tt->quantity_sold }} / {{ $tt->quantity }}</span>
                                            <span class="hidden h-1.5 w-24 overflow-hidden rounded-full bg-stone-100 sm:block">
                                                <span class="block h-full rounded-full bg-coral-500" style="width: {{ $pct }}%"></span>
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-5 py-4 text-right">
                                        <div class="inline-flex items-center gap-3">
                                            <a href="{{ route('organizer.ticket-types.edit', [$event, $tt]) }}" class="text-xs font-semibold text-coral-600 transition-colors hover:text-coral-500">Modifier</a>
                                            <form method="POST" action="{{ route('organizer.ticket-types.destroy', [$event, $tt]) }}" class="inline" onsubmit="return confirm('Supprimer ce type de billet ?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="cursor-pointer text-xs font-semibold text-stone-400 transition-colors hover:text-red-700">Suppr.</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-5 py-10 text-center text-sm text-stone-400">
                                        Aucun type de billet. Ajoutez-en un pour permettre les réservations.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card overflow-hidden">
                <h2 class="p-6 pb-4 font-display text-lg font-bold tracking-tight text-ink sm:px-7">
                    Participants ({{ $event->bookings->where('status', 'confirmed')->sum('quantity') }})
                </h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="border-y border-stone-100 bg-cream/60">
                            <tr>
                                <th class="table-th">Participant</th>
                                <th class="table-th">Billet</th>
                                <th class="table-th">Qté</th>
                                <th class="table-th">Statut</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100">
                            @forelse($event->bookings as $b)
                                <tr class="transition-colors hover:bg-cream/60">
                                    <td class="px-5 py-4 font-semibold text-ink">{{ $b->user->name }}</td>
                                    <td class="px-5 py-4 text-stone-500">{{ $b->ticketType->name }}</td>
                                    <td class="px-5 py-4 text-stone-500 tnum">{{ $b->quantity }}</td>
                                    <td class="px-5 py-4">
                                        <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-[11px] font-semibold
                                            {{ $b->status === 'confirmed' ? 'bg-emerald-50 text-emerald-800' : 'bg-stone-100 text-stone-500' }}">
                                            {{ $b->status === 'confirmed' ? 'Confirmée' : 'Annulée' }}
                                        </span>
                                    </td>
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

        <div class="card h-fit p-6 sm:p-7">
            <h2 class="font-display text-lg font-bold tracking-tight text-ink">Résumé</h2>
            <dl class="mt-5 space-y-4 text-sm">
                <div class="flex items-center justify-between">
                    <dt class="text-stone-500">Capacité</dt>
                    <dd class="font-semibold text-ink tnum">{{ $event->capacity }}</dd>
                </div>
                <div class="flex items-center justify-between">
                    <dt class="text-stone-500">Billets vendus</dt>
                    <dd class="font-semibold text-ink tnum">{{ $event->ticketTypes->sum('quantity_sold') }}</dd>
                </div>
                <div class="flex items-center justify-between border-t border-dashed border-stone-200 pt-4">
                    <dt class="text-stone-500">Revenu</dt>
                    <dd class="font-display text-base font-bold text-coral-600 tnum">
                        {{ number_format($event->bookings->where('status', 'confirmed')->sum('total_price'), 0, ',', ' ') }} DA
                    </dd>
                </div>
            </dl>
            <a href="{{ route('organizer.validate.show') }}" class="btn-dark mt-6 w-full">
                Valider les billets à l'entrée
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 17 17 7m0 0H8m9 0v9" />
                </svg>
            </a>
        </div>
    </div>
</div>
@endsection
