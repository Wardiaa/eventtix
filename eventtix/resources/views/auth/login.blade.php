@extends('layouts.app')
@section('title', 'Connexion')
@section('content')
<div class="max-w-md mx-auto px-4 py-16">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-1">Bon retour parmi nous</h1>
        <p class="text-sm text-gray-400 mb-6">Connectez-vous pour réserver vos billets.</p>

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-600 text-sm rounded-lg px-4 py-3 mb-4">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf
            <div>
                <label class="text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus class="mt-1 w-full rounded-lg border-gray-200 focus:ring-brand-500 focus:border-brand-500 text-sm">
            </div>
            <div>
                <label class="text-sm font-medium text-gray-700">Mot de passe</label>
                <input type="password" name="password" required class="mt-1 w-full rounded-lg border-gray-200 focus:ring-brand-500 focus:border-brand-500 text-sm">
            </div>
            <label class="flex items-center gap-2 text-sm text-gray-500">
                <input type="checkbox" name="remember" class="rounded border-gray-300 text-brand-500 focus:ring-brand-500">
                Se souvenir de moi
            </label>
            <button class="w-full bg-brand-500 hover:bg-brand-600 text-white font-semibold py-3 rounded-xl transition">Se connecter</button>
        </form>

        <p class="mt-6 text-sm text-center text-gray-500">
            Pas encore de compte ? <a href="{{ route('register') }}" class="text-brand-600 font-semibold">Inscrivez-vous</a>
        </p>

        <div class="mt-6 border-t pt-4 text-xs text-gray-400">
            <p class="font-semibold mb-1">Comptes de démonstration :</p>
            <p>admin@eventtix.test / organizer@eventtix.test / user@eventtix.test — mot de passe : password</p>
        </div>
    </div>
</div>
@endsection
