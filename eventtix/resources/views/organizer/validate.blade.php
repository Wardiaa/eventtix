@extends('layouts.app')
@section('title', 'Valider un billet')
@section('content')
<div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <h1 class="text-2xl font-bold text-gray-900 mb-2">Validation à l'entrée</h1>
    <p class="text-sm text-gray-500 mb-6">Saisissez ou scannez le code du billet pour valider l'entrée du participant.</p>

    @if(session('scan_result'))
        @php $result = session('scan_result'); @endphp
        <div class="mb-6 rounded-2xl p-5 border
            {{ $result['status'] === 'success' ? 'bg-brand-50 border-brand-200 text-brand-800' : '' }}
            {{ $result['status'] === 'warning' ? 'bg-amber-50 border-amber-200 text-amber-800' : '' }}
            {{ $result['status'] === 'error' ? 'bg-red-50 border-red-200 text-red-700' : '' }}">
            <p class="font-bold">
                @if($result['status'] === 'success') ✅ Accès autorisé
                @elseif($result['status'] === 'warning') ⚠️ Attention
                @else ❌ Refusé
                @endif
            </p>
            <p class="text-sm mt-1">{{ $result['message'] }}</p>
            @if(isset($result['ticket']))
                <p class="text-xs mt-2 font-mono">{{ $result['ticket']->ticket_code }} — {{ $result['ticket']->booking->event->title }}</p>
            @endif
        </div>
    @endif

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <form method="POST" action="{{ route('organizer.validate.check') }}" class="flex gap-2">
            @csrf
            <input type="text" name="ticket_code" placeholder="Ex: AB12-CD34-EF56" autofocus required
                   class="flex-1 rounded-lg border-gray-200 focus:ring-brand-500 focus:border-brand-500 text-sm font-mono uppercase">
            <button class="bg-brand-500 hover:bg-brand-600 text-white font-semibold px-6 py-2.5 rounded-xl transition">Valider</button>
        </form>
        <p class="text-xs text-gray-400 mt-3">Astuce : un lecteur de QR code externe peut saisir automatiquement le code dans ce champ.</p>
    </div>
</div>
@endsection
