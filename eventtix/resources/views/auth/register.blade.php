@extends('layouts.app')
@section('title', 'Inscription')
@section('content')
<div class="max-w-md mx-auto px-4 py-16">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-1">Créer un compte</h1>
        <p class="text-sm text-gray-400 mb-6">Rejoignez EventTix en tant que participant ou organisateur.</p>

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-600 text-sm rounded-lg px-4 py-3 mb-4">
                <ul class="list-disc pl-4">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf
            <div>
                <label class="text-sm font-medium text-gray-700">Nom complet</label>
                <input type="text" name="name" value="{{ old('name') }}" required autofocus class="mt-1 w-full rounded-lg border-gray-200 focus:ring-brand-500 focus:border-brand-500 text-sm">
            </div>
            <div>
                <label class="text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="mt-1 w-full rounded-lg border-gray-200 focus:ring-brand-500 focus:border-brand-500 text-sm">
            </div>
            <div>
                <label class="text-sm font-medium text-gray-700">Téléphone (optionnel)</label>
                <input type="text" name="phone" value="{{ old('phone') }}" class="mt-1 w-full rounded-lg border-gray-200 focus:ring-brand-500 focus:border-brand-500 text-sm">
            </div>
            <div>
                <label class="text-sm font-medium text-gray-700">Je m'inscris en tant que</label>
                <select name="role" class="mt-1 w-full rounded-lg border-gray-200 focus:ring-brand-500 focus:border-brand-500 text-sm">
                    <option value="user">Participant — je réserve des billets</option>
                    <option value="organizer">Organisateur — je crée des événements</option>
                </select>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-700">Mot de passe</label>
                <input type="password" name="password" required class="mt-1 w-full rounded-lg border-gray-200 focus:ring-brand-500 focus:border-brand-500 text-sm">
            </div>
            <div>
                <label class="text-sm font-medium text-gray-700">Confirmer le mot de passe</label>
                <input type="password" name="password_confirmation" required class="mt-1 w-full rounded-lg border-gray-200 focus:ring-brand-500 focus:border-brand-500 text-sm">
            </div>
            <button class="w-full bg-brand-500 hover:bg-brand-600 text-white font-semibold py-3 rounded-xl transition">Créer mon compte</button>
        </form>

        <p class="mt-6 text-sm text-center text-gray-500">
            Déjà inscrit ? <a href="{{ route('login') }}" class="text-brand-600 font-semibold">Connectez-vous</a>
        </p>
    </div>
</div>
@endsection
