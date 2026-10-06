@csrf
@if(isset($ticketType)) @method('PUT') @endif

@if($errors->any())
    <div class="mb-6 rounded-2xl border border-red-200/70 bg-red-50/90 p-4 text-sm text-red-900">
        <ul class="list-disc space-y-0.5 pl-4">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
@endif

<div class="space-y-5">
    <div>
        <label class="label-micro !text-stone-500" for="tt-name">Nom du billet</label>
        <input id="tt-name" type="text" name="name" value="{{ old('name', $ticketType->name ?? '') }}" required placeholder="Standard, VIP, Étudiant…" class="input">
    </div>
    <div>
        <label class="label-micro !text-stone-500" for="tt-description">Description (optionnel)</label>
        <textarea id="tt-description" name="description" rows="2" class="input" placeholder="Avantages inclus, conditions d'accès…">{{ old('description', $ticketType->description ?? '') }}</textarea>
    </div>
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="label-micro !text-stone-500" for="tt-price">Prix (DA)</label>
            <input id="tt-price" type="number" step="0.01" min="0" name="price" value="{{ old('price', $ticketType->price ?? 0) }}" required class="input tnum">
        </div>
        <div>
            <label class="label-micro !text-stone-500" for="tt-quantity">Quantité disponible</label>
            <input id="tt-quantity" type="number" min="1" name="quantity" value="{{ old('quantity', $ticketType->quantity ?? '') }}" required class="input tnum">
        </div>
    </div>
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="label-micro !text-stone-500" for="tt-sales-start">Début des ventes (optionnel)</label>
            <input id="tt-sales-start" type="datetime-local" name="sales_start" value="{{ old('sales_start', isset($ticketType) && $ticketType->sales_start ? $ticketType->sales_start->format('Y-m-d\TH:i') : '') }}" class="input tnum">
        </div>
        <div>
            <label class="label-micro !text-stone-500" for="tt-sales-end">Fin des ventes (optionnel)</label>
            <input id="tt-sales-end" type="datetime-local" name="sales_end" value="{{ old('sales_end', isset($ticketType) && $ticketType->sales_end ? $ticketType->sales_end->format('Y-m-d\TH:i') : '') }}" class="input tnum">
        </div>
    </div>
</div>

<button type="submit" class="btn-dark mt-8 w-full sm:w-auto">
    {{ isset($ticketType) ? 'Mettre à jour' : 'Ajouter le billet' }}
    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M7 17 17 7m0 0H8m9 0v9" />
    </svg>
</button>
