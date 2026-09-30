@extends('layouts.app')
@section('title', 'Supervision des événements')
@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Tous les événements</h1>

    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-400 text-xs uppercase">
                <tr><th class="text-left px-5 py-3">Titre</th><th class="text-left px-5 py-3">Organisateur</th><th class="text-left px-5 py-3">Date</th><th class="text-left px-5 py-3">Statut</th></tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($events as $event)
                    <tr>
                        <td class="px-5 py-3 font-medium text-gray-800">{{ $event->title }}</td>
                        <td class="px-5 py-3 text-gray-500">{{ $event->organizer->name }}</td>
                        <td class="px-5 py-3 text-gray-500">{{ $event->start_date->format('d/m/Y') }}</td>
                        <td class="px-5 py-3">
                            <form method="POST" action="{{ route('admin.events.status', $event) }}">
                                @csrf @method('PATCH')
                                <select name="status" onchange="this.form.submit()" class="text-xs rounded-lg border-gray-200 focus:ring-brand-500 focus:border-brand-500">
                                    <option value="draft" @selected($event->status==='draft')>Brouillon</option>
                                    <option value="published" @selected($event->status==='published')>Publié</option>
                                    <option value="cancelled" @selected($event->status==='cancelled')>Annulé</option>
                                    <option value="completed" @selected($event->status==='completed')>Terminé</option>
                                </select>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $events->links() }}</div>
</div>
@endsection
