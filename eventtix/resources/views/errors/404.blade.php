@extends('layouts.app')

@section('title', 'Page introuvable')

@section('content')
<div class="mx-auto max-w-xl py-16 text-center sm:py-24">
    <p class="font-display text-7xl font-bold tracking-tight text-peach-deep sm:text-8xl">404</p>
    <h1 class="page-title mt-6">Page introuvable.</h1>
    <p class="mx-auto mt-4 max-w-md text-sm leading-relaxed text-stone-500">
        La page, l'événement ou la réservation que vous cherchez n'existe pas, a été supprimé(e),
        ou l'adresse est incorrecte.
    </p>
    <div class="mt-8 flex flex-wrap justify-center gap-3">
        <a href="{{ route('home') }}" class="btn-dark">Retour à l'accueil</a>
        <a href="{{ route('events.index') }}" class="btn-soft">Voir les événements</a>
    </div>
</div>
@endsection
