@extends('layouts.app')
@section('title', 'Modifier le type de billet')
@section('content')
<div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Modifier le billet « {{ $ticketType->name }} »</h1>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8">
        <form method="POST" action="{{ route('organizer.ticket-types.update', [$event, $ticketType]) }}">
            @include('organizer.ticket-types.form')
        </form>
    </div>
</div>
@endsection
