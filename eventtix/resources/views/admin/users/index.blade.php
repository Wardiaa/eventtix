@extends('layouts.app')
@section('title', 'Utilisateurs')
@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Utilisateurs</h1>

    <div class="flex gap-2 mb-6">
        <a href="{{ route('admin.users.index') }}" class="text-xs font-semibold px-3 py-1.5 rounded-full {{ !request('role') ? 'bg-brand-500 text-white' : 'bg-gray-100 text-gray-500' }}">Tous</a>
        <a href="{{ route('admin.users.index', ['role'=>'admin']) }}" class="text-xs font-semibold px-3 py-1.5 rounded-full {{ request('role')==='admin' ? 'bg-brand-500 text-white' : 'bg-gray-100 text-gray-500' }}">Admins</a>
        <a href="{{ route('admin.users.index', ['role'=>'organizer']) }}" class="text-xs font-semibold px-3 py-1.5 rounded-full {{ request('role')==='organizer' ? 'bg-brand-500 text-white' : 'bg-gray-100 text-gray-500' }}">Organisateurs</a>
        <a href="{{ route('admin.users.index', ['role'=>'user']) }}" class="text-xs font-semibold px-3 py-1.5 rounded-full {{ request('role')==='user' ? 'bg-brand-500 text-white' : 'bg-gray-100 text-gray-500' }}">Participants</a>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-400 text-xs uppercase">
                <tr><th class="text-left px-5 py-3">Nom</th><th class="text-left px-5 py-3">Email</th><th class="text-left px-5 py-3">Rôle</th><th class="text-left px-5 py-3">Activité</th><th class="px-5 py-3"></th></tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($users as $u)
                    <tr>
                        <td class="px-5 py-3 font-medium text-gray-800">{{ $u->name }}</td>
                        <td class="px-5 py-3 text-gray-500">{{ $u->email }}</td>
                        <td class="px-5 py-3">
                            <span class="text-xs font-semibold px-2 py-1 rounded-full bg-brand-100 text-brand-700">{{ ucfirst($u->role) }}</span>
                        </td>
                        <td class="px-5 py-3 text-gray-500">{{ $u->events_count }} évén. · {{ $u->bookings_count }} résa.</td>
                        <td class="px-5 py-3 text-right">
                            @if($u->id !== auth()->id())
                                <form method="POST" action="{{ route('admin.users.role', $u) }}" class="inline-flex items-center gap-2">
                                    @csrf @method('PATCH')
                                    <select name="role" onchange="this.form.submit()" class="text-xs rounded-lg border-gray-200 focus:ring-brand-500 focus:border-brand-500">
                                        <option value="user" @selected($u->role==='user')>Participant</option>
                                        <option value="organizer" @selected($u->role==='organizer')>Organisateur</option>
                                        <option value="admin" @selected($u->role==='admin')>Admin</option>
                                    </select>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $users->links() }}</div>
</div>
@endsection
