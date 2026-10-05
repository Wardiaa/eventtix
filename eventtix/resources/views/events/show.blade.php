@extends('layouts.app')
@section('title', $event->title)
@section('content')

<div class="h-64 sm:h-80 bg-gradient-to-br from-brand-500 to-brand-800 relative">
    @if($event->cover_image)
        <img src="{{ asset('storage/'.$event->cover_image) }}" class="absolute inset-0 w-full h-full object-cover" alt="">
        <div class="absolute inset-0 bg-black/40"></div>
    @endif
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex flex-col justify-end pb-8 relative z-10">
        <span class="text-xs font-semibold text-white bg-white/20 backdrop-blur px-3 py-1 rounded-full w-fit">{{ $event->category }}</span>
        <h1 class="mt-3 text-2xl sm:text-4xl font-extrabold text-white max-w-3xl">{{ $event->title }}</h1>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 grid grid-cols-1 lg:grid-cols-3 gap-10">
    <div class="lg:col-span-2 space-y-8">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 grid sm:grid-cols-3 gap-4 text-sm">
            <div>
                <p class="text-gray-400">Date de début</p>
                <p class="font-semibold text-gray-900">{{ $event->start_date->translatedFormat('d F Y, H:i') }}</p>
            </div>
            <div>
                <p class="text-gray-400">Date de fin</p>
                <p class="font-semibold text-gray-900">{{ $event->end_date->translatedFormat('d F Y, H:i') }}</p>
            </div>
            <div>
                <p class="text-gray-400">Lieu</p>
                <p class="font-semibold text-gray-900">{{ $event->location }}</p>
            </div>
        </div>

        <div>
            <h2 class="text-lg font-bold text-gray-900 mb-2">À propos de l'événement</h2>
            <p class="text-gray-600 leading-relaxed whitespace-pre-line">{{ $event->description }}</p>
            @if($event->venue_details)
                <h3 class="mt-6 font-semibold text-gray-900">Informations sur le lieu</h3>
                <p class="text-gray-600 mt-1 whitespace-pre-line">{{ $event->venue_details }}</p>
            @endif
        </div>
    </div>

    <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sticky top-24">
            <h2 class="text-lg font-bold text-gray-900 mb-4">Billets</h2>

            @if($event->ticketTypes->isEmpty())
                <p class="text-sm text-gray-400">Aucun billet disponible pour cet événement.</p>
            @else
                <form method="POST" action="{{ route('bookings.store', $event) }}" x-data="{ selected: {{ old('ticket_type_id', $event->ticketTypes->first()->id) }}, price: {{ old('ticket_type_id') ? $event->ticketTypes->firstWhere('id', (int) old('ticket_type_id'))?->price ?? 0 : $event->ticketTypes->first()->price }}, qty: {{ (int) old('quantity', 1) }} }">
                    @csrf

                    @error('ticket_type_id')
                        <p class="text-xs font-semibold text-red-500 bg-red-50 border border-red-100 rounded-lg px-3 py-2 mb-3">{{ $message }}</p>
                    @enderror

                    <div class="space-y-3">
                        @foreach($event->ticketTypes as $tt)
                            @php $isChecked = old('ticket_type_id') ? (int) old('ticket_type_id') === $tt->id : $loop->first; @endphp
                            <label class="block border rounded-xl p-4 cursor-pointer transition {{ $tt->isOnSale() ? 'hover:border-brand-400' : 'opacity-50 pointer-events-none' }}"
                                   :class="selected == {{ $tt->id }} ? 'border-brand-500 ring-2 ring-brand-100' : 'border-gray-200'">
                                <input type="radio" name="ticket_type_id" value="{{ $tt->id }}" class="hidden"
                                       @change="selected = {{ $tt->id }}; price = {{ $tt->price }}" @if($isChecked) checked @endif>
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $tt->name }}</p>
                                        @if($tt->description)
                                            <p class="text-xs text-gray-400">{{ $tt->description }}</p>
                                        @endif
                                        @if($tt->saleNotStarted())
                                            <p class="text-xs text-amber-500 font-semibold mt-1">Vente pas encore ouverte</p>
                                        @elseif($tt->saleEnded())
                                            <p class="text-xs text-red-400 font-semibold mt-1">Vente terminée</p>
                                        @elseif($tt->isSoldOut())
                                            <p class="text-xs text-red-400 font-semibold mt-1">Complet</p>
                                        @else
                                            <p class="text-xs text-gray-400 mt-1">{{ $tt->available() }} place(s) restante(s)</p>
                                        @endif
                                    </div>
                                    <p class="font-bold text-brand-600">{{ $tt->price > 0 ? number_format($tt->price, 0, ',', ' ').' DA' : 'Gratuit' }}</p>
                                </div>
                            </label>
                        @endforeach
                    </div>

                    <div class="mt-4 flex items-center justify-between">
                        <label class="text-sm font-medium text-gray-600">Quantité</label>
                        <input type="number" name="quantity" x-model="qty" min="1" max="10" class="w-20 rounded-lg border-gray-200 text-sm text-center focus:ring-brand-500 focus:border-brand-500">
                    </div>
                    @error('quantity')
                        <p class="text-xs font-semibold text-red-500 mt-1">{{ $message }}</p>
                    @enderror

                    <div class="mt-4 flex items-center justify-between border-t pt-4">
                        <span class="text-sm text-gray-500">Total</span>
                        <span class="text-lg font-extrabold text-gray-900" x-text="(price * qty).toLocaleString('fr-FR') + ' DA'"></span>
                    </div>

                    @auth
                        @if(auth()->user()->role === 'user')
                            <button class="mt-4 w-full bg-brand-500 hover:bg-brand-600 text-white font-semibold py-3 rounded-xl transition {{ $event->isSoldOut() ? 'opacity-50 pointer-events-none' : '' }}">
                                {{ $event->isSoldOut() ? 'Complet' : 'Réserver maintenant' }}
                            </button>
                        @else
                            <p class="mt-4 text-xs text-center text-gray-400">Seuls les comptes "participant" peuvent réserver des billets.</p>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="mt-4 block text-center w-full bg-brand-500 hover:bg-brand-600 text-white font-semibold py-3 rounded-xl transition">
                            Se connecter pour réserver
                        </a>
                    @endauth
                </form>
            @endif
        </div>
    </div>
</div>
@endsection
