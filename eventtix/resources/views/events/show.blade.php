@extends('layouts.app')

@section('title', $event->title)

@section('content')
<div class="pb-8">
    <!-- Back -->
    <a href="{{ route('events.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-stone-500 transition-colors hover:text-ink">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Retour aux événements
    </a>

    <!-- Hero banner -->
    <div class="relative mt-5 h-[280px] overflow-hidden rounded-[28px] bg-stone-100 sm:h-[380px]">
        @if($event->cover_image)
            <img src="{{ asset('storage/'.$event->cover_image) }}" class="h-full w-full object-cover" alt="{{ $event->title }}">
        @endif
        <div class="absolute inset-0 bg-gradient-to-t from-ink/80 via-ink/20 to-transparent"></div>

        <div class="absolute bottom-6 left-6 right-6 sm:bottom-8 sm:left-8 sm:right-8">
            <span class="rounded-full bg-white/90 px-3.5 py-1.5 text-[11px] font-semibold text-ink backdrop-blur-sm">
                {{ $event->category }}
            </span>
            <h1 class="mt-3 max-w-3xl font-display text-3xl font-bold leading-[1.08] tracking-tight text-white sm:text-4xl lg:text-5xl">
                {{ $event->title }}
            </h1>
        </div>
    </div>

    <!-- Content -->
    <div class="mt-8 grid grid-cols-1 gap-8 lg:grid-cols-12">
        <!-- Left: details -->
        <div class="space-y-6 lg:col-span-7">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div class="card flex items-start gap-3.5 p-5">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-peach text-ink">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24">
                            <rect x="3.75" y="5" width="16.5" height="15.25" rx="3" />
                            <path stroke-linecap="round" d="M8.25 3.5v3M15.75 3.5v3M3.75 10.25h16.5" />
                        </svg>
                    </span>
                    <div class="min-w-0">
                        <span class="label-micro !mb-0.5">Date</span>
                        <span class="block text-sm font-semibold capitalize text-ink">{{ $event->start_date->translatedFormat('l d F Y') }}</span>
                        <span class="mt-0.5 block text-xs text-stone-500 tnum">{{ $event->start_date->format('H:i') }} — {{ $event->end_date->format('H:i') }}</span>
                    </div>
                </div>

                <div class="card flex items-start gap-3.5 p-5">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-peach text-ink">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657 13.414 20.9a2 2 0 0 1-2.828 0l-4.243-4.243a8 8 0 1 1 11.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" />
                        </svg>
                    </span>
                    <div class="min-w-0">
                        <span class="label-micro !mb-0.5">Lieu</span>
                        <span class="block truncate text-sm font-semibold text-ink">{{ $event->venue_details ?: $event->location }}</span>
                        <span class="mt-0.5 block truncate text-xs text-stone-500">{{ $event->location }}</span>
                    </div>
                </div>
            </div>

            <div class="card p-6 sm:p-8">
                <h2 class="font-display text-lg font-bold tracking-tight text-ink">Description de l'événement</h2>
                <p class="mt-3 whitespace-pre-line text-sm leading-relaxed text-stone-600">{{ $event->description }}</p>
            </div>

            <div class="card flex flex-wrap items-center justify-between gap-4 p-6">
                <div class="flex items-center gap-3.5">
                    <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-ink font-display text-base font-bold text-paper">
                        {{ strtoupper(substr($event->organizer->name, 0, 1)) }}
                    </span>
                    <div>
                        <span class="label-micro !mb-0.5">Organisé par</span>
                        <h4 class="font-display text-base font-bold text-ink">{{ $event->organizer->name }}</h4>
                        <p class="mt-0.5 text-xs text-stone-500">Organisateur certifié EventTix</p>
                    </div>
                </div>
                <span class="inline-flex items-center gap-1.5 rounded-full bg-peach/70 px-3.5 py-1.5 text-[11px] font-semibold text-ink/80">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" />
                    </svg>
                    Compte vérifié
                </span>
            </div>
        </div>

        <!-- Right: booking widget -->
        <div class="lg:col-span-5">
            <div class="sticky top-8 card p-6 sm:p-7">
                <div class="border-b border-stone-100 pb-5">
                    <h3 class="font-display text-xl font-bold tracking-tight text-ink">Sélection des billets</h3>
                    <p class="mt-1 text-xs text-stone-500">Choisissez votre formule pour cet événement.</p>
                </div>

                @if($event->ticketTypes->isEmpty())
                    <p class="mt-5 text-sm text-stone-400">Aucun billet n'est actuellement en vente pour cet événement.</p>
                @else
                    <form method="POST" action="{{ route('bookings.store', $event) }}"
                          class="mt-5"
                          x-data="{
                              selectedId: {{ old('ticket_type_id', $event->ticketTypes->first()->id) }},
                              unitPrice: {{ old('ticket_type_id') ? $event->ticketTypes->firstWhere('id', (int) old('ticket_type_id'))?->price ?? 0 : $event->ticketTypes->first()->price }},
                              qty: {{ (int) old('quantity', 1) }},
                              submitting: false
                          }"
                          @submit="submitting = true">
                        @csrf

                        <div class="space-y-3">
                            <span class="label-micro">Catégorie</span>

                            @foreach($event->ticketTypes as $tt)
                                @php
                                    $available = $tt->available();
                                    $isSoldOut = $tt->isSoldOut();
                                    $isChecked = old('ticket_type_id') ? (int) old('ticket_type_id') === $tt->id : $loop->first;
                                @endphp
                                <label class="relative block cursor-pointer rounded-2xl border p-4 transition-colors duration-150 {{ $isSoldOut ? 'pointer-events-none bg-cream opacity-60' : '' }}"
                                       :class="selectedId === {{ $tt->id }} ? 'border-ink bg-cream' : 'border-stone-200 bg-white hover:border-stone-300'">
                                    <input type="radio" name="ticket_type_id" value="{{ $tt->id }}" class="hidden"
                                           @change="selectedId = {{ $tt->id }}; unitPrice = {{ $tt->price }};"
                                           @if($isChecked) checked @endif>
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="min-w-0 flex-1">
                                            <div class="flex flex-wrap items-center gap-2">
                                                <span class="font-display text-base font-bold text-ink">{{ $tt->name }}</span>
                                                @if($available > 0 && $available <= 3)
                                                    <span class="rounded-full bg-coral-500/10 px-2.5 py-1 text-[10px] font-bold text-coral-600">Plus que {{ $available }} place(s)</span>
                                                @elseif($isSoldOut)
                                                    <span class="rounded-full bg-stone-100 px-2.5 py-1 text-[10px] font-semibold text-stone-400">Épuisé</span>
                                                @endif
                                            </div>
                                            @if($tt->description)
                                                <p class="mt-1 text-xs leading-relaxed text-stone-500">{{ $tt->description }}</p>
                                            @endif
                                        </div>
                                        <span class="shrink-0 font-display text-lg font-bold text-coral-600 tnum">
                                            {{ number_format($tt->price, 0, ',', ' ') }} <span class="text-[11px] font-semibold">DZD</span>
                                        </span>
                                    </div>
                                </label>
                            @endforeach
                        </div>

                        <!-- Quantity -->
                        <div class="mt-5 flex items-center justify-between rounded-2xl border border-stone-200 bg-cream p-4">
                            <span class="text-xs font-semibold text-ink">Quantité</span>
                            <div class="flex items-center gap-1.5 rounded-xl border border-stone-200 bg-white p-1">
                                <button type="button" @click="if (qty > 1) qty--"
                                    class="flex h-8 w-8 cursor-pointer items-center justify-center rounded-lg text-ink transition-colors hover:bg-cream">−</button>
                                <input type="number" name="quantity" x-model="qty" min="1" max="10"
                                    class="w-10 border-none bg-transparent p-0 text-center font-display text-base font-bold text-ink outline-none tnum">
                                <button type="button" @click="qty++"
                                    class="flex h-8 w-8 cursor-pointer items-center justify-center rounded-lg text-ink transition-colors hover:bg-cream">+</button>
                            </div>
                        </div>

                        <!-- Total -->
                        <div class="mt-5 rounded-2xl bg-ink p-5 text-paper">
                            <div class="flex items-baseline justify-between">
                                <span class="text-xs font-medium text-paper/60">Total</span>
                                <span class="font-display text-2xl font-bold tracking-tight tnum">
                                    <span x-text="(unitPrice * qty).toLocaleString('fr-FR')"></span>
                                    <span class="text-xs font-medium text-paper/50">DZD</span>
                                </span>
                            </div>
                            <p class="mt-1 text-[10px] text-paper/40">Sous-total confirmé à l'étape suivante.</p>
                        </div>

                        <!-- Inline error -->
                        @if(session('error') || $errors->any())
                            <div class="mt-4 flex items-start gap-2.5 rounded-2xl border border-red-200/70 bg-red-50/90 p-3.5 text-xs font-medium text-red-900">
                                <svg class="mt-0.5 h-4 w-4 shrink-0 text-red-600" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0 3.75h.008v.008H12v-.008zM21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" />
                                </svg>
                                <span>{{ session('error') ?: $errors->first() }}</span>
                            </div>
                        @endif

                        <!-- Submit -->
                        @auth
                            @if(auth()->user()->role === 'user')
                                <button type="submit"
                                        :disabled="submitting || {{ $event->isSoldOut() ? 'true' : 'false' }}"
                                        class="mt-5 flex w-full items-center justify-center gap-2 rounded-xl py-3.5 text-sm font-medium transition-all duration-200 {{ $event->isSoldOut() ? 'cursor-not-allowed bg-stone-200 text-stone-400' : 'btn-dark !rounded-xl' }}">
                                    <template x-if="submitting">
                                        <span class="flex items-center gap-2">
                                            <span class="h-4 w-4 animate-spin rounded-full border-2 border-paper/30 border-t-paper"></span>
                                            <span>Réservation en cours…</span>
                                        </span>
                                    </template>
                                    <template x-if="!submitting">
                                        <span>{{ $event->isSoldOut() ? 'Événement complet' : 'Confirmer la réservation' }}</span>
                                    </template>
                                </button>
                            @else
                                <p class="mt-5 text-center text-xs text-stone-500">Seuls les comptes participants peuvent réserver des billets.</p>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn-dark mt-5 w-full">Se connecter pour réserver</a>
                        @endauth
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
