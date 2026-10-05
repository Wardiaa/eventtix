@extends('layouts.app')
@section('title', 'Accès refusé')
@section('content')
<div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8 py-24 text-center">
    <p class="text-6xl font-extrabold text-brand-300">403</p>
    <h1 class="mt-4 text-2xl font-bold text-gray-900">Accès refusé</h1>
    <p class="mt-2 text-gray-500">
        {{ $exception->getMessage() && $exception->getMessage() !== 'This action is unauthorized.' ? $exception->getMessage() : "Vous n'avez pas l'autorisation d'accéder à cette page ou d'effectuer cette action." }}
    </p>
    <div class="mt-8 flex flex-wrap justify-center gap-3">
        <a href="{{ route('home') }}" class="bg-brand-500 hover:bg-brand-600 text-white font-semibold px-5 py-2.5 rounded-xl transition">
            Retour à l'accueil
        </a>
        @auth
            <a href="{{ route('events.index') }}" class="bg-brand-50 text-brand-700 hover:bg-brand-100 font-semibold px-5 py-2.5 rounded-xl transition">
                Voir les événements
            </a>
        @endauth
    </div>
</div>
@endsection
