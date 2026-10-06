@extends('layouts.app')

@section('title', 'Connexion')

@section('content')
<div class="mx-auto max-w-md py-6 sm:py-12">
    <p class="eyebrow">Connexion</p>
    <h1 class="page-title mt-3">Bon retour.</h1>
    <p class="page-subtitle">Connectez-vous pour réserver vos billets et gérer vos événements.</p>

    <div class="card mt-8 p-6 sm:p-8">
        @if($errors->any())
            <div class="mb-6 rounded-2xl border border-red-200/70 bg-red-50/90 p-4 text-sm text-red-900">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf
            <div>
                <label class="label-micro !text-stone-500" for="login-email">Email</label>
                <input id="login-email" type="email" name="email" value="{{ old('email') }}" required autofocus class="input" placeholder="vous@email.com">
            </div>
            <div>
                <label class="label-micro !text-stone-500" for="login-password">Mot de passe</label>
                <input id="login-password" type="password" name="password" required class="input" placeholder="••••••••">
            </div>
            <label class="flex cursor-pointer items-center gap-2.5 text-sm text-stone-500">
                <input type="checkbox" name="remember" class="h-4 w-4 cursor-pointer rounded border-stone-300 accent-ink">
                Se souvenir de moi
            </label>
            <button type="submit" class="btn-dark w-full !py-3.5">Se connecter</button>
        </form>

        <p class="mt-6 text-center text-sm text-stone-500">
            Pas encore de compte ?
            <a href="{{ route('register') }}" class="font-semibold text-coral-600 transition-colors hover:text-coral-500">Inscrivez-vous</a>
        </p>
    </div>

    <div class="mt-4 rounded-3xl bg-peach/80 p-5 text-xs leading-relaxed text-ink/70">
        <p class="font-semibold text-ink/80">Comptes de démonstration</p>
        <p class="mt-1">admin@eventtix.test · organizer@eventtix.test · user@eventtix.test</p>
        <p class="mt-0.5">Mot de passe : <span class="font-mono font-semibold text-ink/80">password</span></p>
    </div>
</div>
@endsection
