@extends('layouts.app')
@section('title', 'Modifier l\'événement')
@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Modifier « {{ $event->title }} »</h1>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8">
        <form method="POST" action="{{ route('organizer.events.update', $event) }}" enctype="multipart/form-data">
            @include('organizer.events.form')
        </form>
    </div>
</div>
@endsection
