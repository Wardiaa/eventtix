@extends('layouts.app')
@section('title', 'Page introuvable')
@section('content')
<div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8 py-24 text-center">
    <p class="text-6xl font-extrabold text-brand-300">404</p>
    <h1 class="mt-4 text-2xl font-bold text-gray-900">Page introuvable</h1>
    <p class="mt-2 text-gray-500">
        La page, l'événement ou la réservation que vous cherchez n'existe pas, a été supprimé(e),
        ou l'adresse est incorrecte.
    </p>
    <div class="mt-8 flex flex-wrap justify-center gap-3">
        <a href="{{ route('home') }}" class="bg-brand-500 hover:bg-brand-600 text-white font-semibold px-5 py-2.5 rounded-xl transition">
            Retour à l'accueil
        </a>
        <a href="{{ route('events.index') }}" class="bg-brand-50 text-brand-700 hover:bg-brand-100 font-semibold px-5 py-2.5 rounded-xl transition">
            Voir les événements
        </a>
    </div>
</div>
@endsection
