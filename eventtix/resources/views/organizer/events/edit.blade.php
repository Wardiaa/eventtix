@extends('layouts.app')

@section('title', "Modifier l'événement")

@section('content')
<div class="mx-auto max-w-3xl pb-8">
    <p class="eyebrow">Édition</p>
    <h1 class="page-title mt-3">Modifier « {{ $event->title }} ».</h1>
    <p class="page-subtitle">Mettez à jour les informations de votre événement.</p>

    <div class="card mt-10 p-6 sm:p-8">
        <form method="POST" action="{{ route('organizer.events.update', $event) }}" enctype="multipart/form-data">
            @include('organizer.events.form')
        </form>
    </div>
</div>
@endsection
