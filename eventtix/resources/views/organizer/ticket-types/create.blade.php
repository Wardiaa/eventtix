@extends('layouts.app')

@section('title', 'Ajouter un type de billet')

@section('content')
<div class="mx-auto max-w-xl pb-8">
    <p class="eyebrow">Nouvelle catégorie</p>
    <h1 class="page-title mt-3">Ajouter un billet.</h1>
    <p class="page-subtitle">{{ $event->title }}</p>

    <div class="card mt-10 p-6 sm:p-8">
        <form method="POST" action="{{ route('organizer.ticket-types.store', $event) }}">
            @include('organizer.ticket-types.form')
        </form>
    </div>
</div>
@endsection
