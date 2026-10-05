@extends('layouts.app')
@section('title', 'Erreur serveur')
@section('content')
<div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8 py-24 text-center">
    <p class="text-6xl font-extrabold text-brand-300">500</p>
    <h1 class="mt-4 text-2xl font-bold text-gray-900">Une erreur inattendue est survenue</h1>
    <p class="mt-2 text-gray-500">
        Quelque chose s'est mal passé de notre côté. L'équipe technique a été notifiée.
        Merci de réessayer dans quelques instants.
    </p>
    <div class="mt-8 flex flex-wrap justify-center gap-3">
        <a href="{{ route('home') }}" class="bg-brand-500 hover:bg-brand-600 text-white font-semibold px-5 py-2.5 rounded-xl transition">
            Retour à l'accueil
        </a>
    </div>
</div>
@endsection
