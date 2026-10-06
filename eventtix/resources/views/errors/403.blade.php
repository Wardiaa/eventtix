@extends('layouts.app')

@section('title', 'Accès refusé')

@section('content')
<div class="mx-auto max-w-xl py-16 text-center sm:py-24">
    <p class="font-display text-7xl font-bold tracking-tight text-peach-deep sm:text-8xl">403</p>
    <h1 class="page-title mt-6">Accès refusé.</h1>
    <p class="mx-auto mt-4 max-w-md text-sm leading-relaxed text-stone-500">
        {{ $exception->getMessage() && $exception->getMessage() !== 'This action is unauthorized.' ? $exception->getMessage() : "Vous n'avez pas l'autorisation d'accéder à cette page ou d'effectuer cette action." }}
    </p>
    <div class="mt-8 flex flex-wrap justify-center gap-3">
        <a href="{{ route('home') }}" class="btn-dark">Retour à l'accueil</a>
        @auth
            <a href="{{ route('events.index') }}" class="btn-soft">Voir les événements</a>
        @endauth
    </div>
</div>
@endsection
