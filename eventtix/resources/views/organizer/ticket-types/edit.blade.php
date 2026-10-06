@extends('layouts.app')

@section('title', 'Modifier le type de billet')

@section('content')
<div class="mx-auto max-w-xl pb-8">
    <p class="eyebrow">Édition</p>
    <h1 class="page-title mt-3">Modifier « {{ $ticketType->name }} ».</h1>
    <p class="page-subtitle">{{ $event->title }}</p>

    <div class="card mt-10 p-6 sm:p-8">
        <form method="POST" action="{{ route('organizer.ticket-types.update', [$event, $ticketType]) }}">
            @include('organizer.ticket-types.form')
        </form>
    </div>
</div>
@endsection
