@extends('layouts.app')

@section('title', 'Supervision des événements')

@section('content')
<div class="pb-8">
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="eyebrow">Administration</p>
            <h1 class="page-title mt-3">Tous les événements.</h1>
            <p class="page-subtitle">Modérez les publications et suivez l'activité de la plateforme.</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn-soft">
            Retour au tableau de bord
        </a>
    </div>

    <div class="card mt-10 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="border-b border-stone-100 bg-cream/60">
                    <tr>
                        <th class="table-th">Titre</th>
                        <th class="table-th">Organisateur</th>
                        <th class="table-th">Date</th>
                        <th class="table-th text-right">Statut</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @foreach($events as $event)
                        <tr class="transition-colors hover:bg-cream/60">
                            <td class="px-5 py-4">
                                <a href="{{ route('events.show', $event) }}" class="font-semibold text-ink transition-colors hover:text-coral-600">
                                    {{ $event->title }}
                                </a>
                                <p class="mt-0.5 text-xs text-stone-400">{{ $event->category }} · {{ $event->location }}</p>
                            </td>
                            <td class="px-5 py-4 text-stone-500">{{ $event->organizer->name }}</td>
                            <td class="px-5 py-4 text-stone-500 tnum">{{ $event->start_date->format('d/m/Y') }}</td>
                            <td class="px-5 py-4 text-right">
                                <form method="POST" action="{{ route('admin.events.status', $event) }}" class="inline-flex">
                                    @csrf @method('PATCH')
                                    <select name="status" onchange="this.form.submit()" class="input !w-auto cursor-pointer !py-2 text-xs">
                                        <option value="draft" @selected($event->status === 'draft')>Brouillon</option>
                                        <option value="published" @selected($event->status === 'published')>Publié</option>
                                        <option value="cancelled" @selected($event->status === 'cancelled')>Annulé</option>
                                        <option value="completed" @selected($event->status === 'completed')>Terminé</option>
                                    </select>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-10">{{ $events->links() }}</div>
</div>
@endsection
