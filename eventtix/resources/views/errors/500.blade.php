@extends('layouts.app')

@section('title', 'Erreur serveur')

@section('content')
<div class="mx-auto max-w-xl py-16 text-center sm:py-24">
    <p class="font-display text-7xl font-bold tracking-tight text-peach-deep sm:text-8xl">500</p>
    <h1 class="page-title mt-6">Erreur inattendue.</h1>
    <p class="mx-auto mt-4 max-w-md text-sm leading-relaxed text-stone-500">
        Quelque chose s'est mal passé de notre côté. L'équipe technique a été notifiée.
        Merci de réessayer dans quelques instants.
    </p>
    <div class="mt-8 flex flex-wrap justify-center gap-3">
        <a href="{{ route('home') }}" class="btn-dark">Retour à l'accueil</a>
    </div>
</div>
@endsection
