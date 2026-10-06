@extends('layouts.app')

@section('title', 'Nouvel événement')

@section('content')
<div class="mx-auto max-w-3xl pb-8">
    <p class="eyebrow">Nouvel événement</p>
    <h1 class="page-title mt-3">Créez votre événement.</h1>
    <p class="page-subtitle">Décrivez votre date, ajoutez une couverture et publiez quand vous êtes prêt.</p>

    <div class="card mt-10 p-6 sm:p-8">
        <form method="POST" action="{{ route('organizer.events.store') }}" enctype="multipart/form-data">
            @include('organizer.events.form')
        </form>
    </div>
</div>
@endsection
