@extends('layouts.app')

@section('title', "Contrôle d'accès")

@section('content')
<div class="mx-auto max-w-2xl pb-8">
    <p class="eyebrow">Contrôle d'accès</p>
    <h1 class="page-title mt-3">Validez les entrées.</h1>
    <p class="page-subtitle">Saisissez ou scannez le code du billet pour valider l'accès du participant.</p>

    @if(session('scan_result'))
        @php
            $status = session('scan_result');
            $tone = match ($status['status']) {
                'success' => ['border-emerald-200/70 bg-emerald-50/90 text-emerald-900', 'text-emerald-700', 'Accès autorisé'],
                'warning' => ['border-amber-200/70 bg-amber-50/90 text-amber-900', 'text-amber-600', 'Attention'],
                default => ['border-red-200/70 bg-red-50/90 text-red-900', 'text-red-600', 'Accès refusé'],
            };
        @endphp
        <div class="mt-8 rounded-3xl border p-5 shadow-soft {{ $tone[0] }}">
            <div class="flex items-start gap-3.5">
                <svg class="mt-0.5 h-5 w-5 shrink-0 {{ $tone[1] }}" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    @if($status['status'] === 'success')
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" />
                    @else
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0 3.75h.008v.008H12v-.008zM21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" />
                    @endif
                </svg>
                <div>
                    <p class="text-sm font-bold">{{ $tone[2] }}</p>
                    <p class="mt-0.5 text-sm">{{ $status['message'] }}</p>
                    @if(isset($status['ticket']))
                        <p class="mt-2 font-mono text-xs font-medium opacity-80">
                            {{ $status['ticket']->ticket_code }} — {{ $status['ticket']->booking->event->title }}
                        </p>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <div class="card mt-8 p-6 sm:p-8">
        <form method="POST" action="{{ route('organizer.validate.check') }}">
            @csrf
            <label for="ticket_code" class="label-micro text-center">Code du billet</label>
            <input id="ticket_code" type="text" name="ticket_code" placeholder="AB12-CD34-EF56" autofocus required
                class="input !py-4 text-center font-mono !text-lg uppercase tracking-[0.2em]">
            <button type="submit" class="btn-dark mt-4 w-full !py-3.5">
                Valider l'entrée
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 17 17 7m0 0H8m9 0v9" />
                </svg>
            </button>
        </form>
        <p class="mt-4 text-center text-xs leading-relaxed text-stone-400">
            Astuce : un lecteur de QR code externe peut saisir automatiquement le code dans ce champ.
        </p>
    </div>
</div>
@endsection
