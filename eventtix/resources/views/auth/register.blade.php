@extends('layouts.app')

@section('title', 'Inscription')

@section('content')
<div class="mx-auto max-w-md py-6 sm:py-12">
    <p class="eyebrow">Inscription</p>
    <h1 class="page-title mt-3">Créer un compte.</h1>
    <p class="page-subtitle">Rejoignez EventTix en tant que participant ou organisateur.</p>

    <div class="card mt-8 p-6 sm:p-8">
        @if($errors->any())
            <div class="mb-6 rounded-2xl border border-red-200/70 bg-red-50/90 p-4 text-sm text-red-900">
                <ul class="list-disc space-y-0.5 pl-4">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf
            <div>
                <label class="label-micro !text-stone-500" for="register-name">Nom complet</label>
                <input id="register-name" type="text" name="name" value="{{ old('name') }}" required autofocus class="input" placeholder="Votre nom">
            </div>
            <div>
                <label class="label-micro !text-stone-500" for="register-email">Email</label>
                <input id="register-email" type="email" name="email" value="{{ old('email') }}" required class="input" placeholder="vous@email.com">
            </div>
            <div>
                <label class="label-micro !text-stone-500" for="register-phone">Téléphone (optionnel)</label>
                <input id="register-phone" type="text" name="phone" value="{{ old('phone') }}" class="input" placeholder="+213 …">
            </div>
            <div>
                <label class="label-micro !text-stone-500" for="register-role">Je m'inscris en tant que</label>
                <select id="register-role" name="role" class="input cursor-pointer">
                    <option value="user">Participant — je réserve des billets</option>
                    <option value="organizer">Organisateur — je crée des événements</option>
                </select>
            </div>
            <div>
                <label class="label-micro !text-stone-500" for="register-password">Mot de passe</label>
                <input id="register-password" type="password" name="password" required class="input" placeholder="••••••••">
            </div>
            <div>
                <label class="label-micro !text-stone-500" for="register-password-confirm">Confirmer le mot de passe</label>
                <input id="register-password-confirm" type="password" name="password_confirmation" required class="input" placeholder="••••••••">
            </div>
            <button type="submit" class="btn-dark w-full !py-3.5">Créer mon compte</button>
        </form>

        <p class="mt-6 text-center text-sm text-stone-500">
            Déjà inscrit ?
            <a href="{{ route('login') }}" class="font-semibold text-coral-600 transition-colors hover:text-coral-500">Connectez-vous</a>
        </p>
    </div>
</div>
@endsection
