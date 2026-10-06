@extends('layouts.app')

@section('title', 'Utilisateurs')

@section('content')
<div class="pb-8">
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="eyebrow">Administration</p>
            <h1 class="page-title mt-3">Utilisateurs.</h1>
            <p class="page-subtitle">Attribuez les rôles et consultez l'activité de chaque compte.</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn-soft">
            Retour au tableau de bord
        </a>
    </div>

    <div class="mt-8 flex items-center gap-2.5 overflow-x-auto pb-1">
        <a href="{{ route('admin.users.index') }}" class="chip {{ !request('role') ? 'chip-active' : '' }}">Tous</a>
        <a href="{{ route('admin.users.index', ['role' => 'admin']) }}" class="chip {{ request('role') === 'admin' ? 'chip-active' : '' }}">Administrateurs</a>
        <a href="{{ route('admin.users.index', ['role' => 'organizer']) }}" class="chip {{ request('role') === 'organizer' ? 'chip-active' : '' }}">Organisateurs</a>
        <a href="{{ route('admin.users.index', ['role' => 'user']) }}" class="chip {{ request('role') === 'user' ? 'chip-active' : '' }}">Participants</a>
    </div>

    <div class="card mt-4 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="border-b border-stone-100 bg-cream/60">
                    <tr>
                        <th class="table-th">Nom</th>
                        <th class="table-th">Email</th>
                        <th class="table-th">Rôle</th>
                        <th class="table-th">Activité</th>
                        <th class="table-th text-right">Permissions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @foreach($users as $u)
                        <tr class="transition-colors hover:bg-cream/60">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-peach text-[11px] font-bold text-ink">
                                        {{ strtoupper(mb_substr($u->name, 0, 1)) }}
                                    </span>
                                    <span class="font-semibold text-ink">{{ $u->name }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-stone-500">{{ $u->email }}</td>
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-[11px] font-semibold
                                    {{ $u->role === 'admin' ? 'bg-ink text-paper' : ($u->role === 'organizer' ? 'bg-peach text-ink' : 'bg-stone-100 text-stone-500') }}">
                                    {{ ucfirst($u->role) }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-stone-500 tnum">{{ $u->events_count }} évén. · {{ $u->bookings_count }} résa.</td>
                            <td class="px-5 py-4 text-right">
                                @if($u->id !== auth()->id())
                                    <form method="POST" action="{{ route('admin.users.role', $u) }}" class="inline-flex items-center gap-2">
                                        @csrf @method('PATCH')
                                        <select name="role" onchange="this.form.submit()" class="input !w-auto cursor-pointer !py-2 text-xs">
                                            <option value="user" @selected($u->role === 'user')>Participant</option>
                                            <option value="organizer" @selected($u->role === 'organizer')>Organisateur</option>
                                            <option value="admin" @selected($u->role === 'admin')>Admin</option>
                                        </select>
                                    </form>
                                @else
                                    <span class="text-xs text-stone-300">—</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-10">{{ $users->links() }}</div>
</div>
@endsection
