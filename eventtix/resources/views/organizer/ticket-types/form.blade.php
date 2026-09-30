@csrf
@if(isset($ticketType)) @method('PUT') @endif

@if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-600 text-sm rounded-lg px-4 py-3 mb-4">
        <ul class="list-disc pl-4">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
@endif

<div class="space-y-4">
    <div>
        <label class="text-sm font-medium text-gray-700">Nom du billet</label>
        <input type="text" name="name" value="{{ old('name', $ticketType->name ?? '') }}" required placeholder="Standard, VIP, Étudiant..." class="mt-1 w-full rounded-lg border-gray-200 focus:ring-brand-500 focus:border-brand-500 text-sm">
    </div>
    <div>
        <label class="text-sm font-medium text-gray-700">Description (optionnel)</label>
        <textarea name="description" rows="2" class="mt-1 w-full rounded-lg border-gray-200 focus:ring-brand-500 focus:border-brand-500 text-sm">{{ old('description', $ticketType->description ?? '') }}</textarea>
    </div>
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="text-sm font-medium text-gray-700">Prix (DA)</label>
            <input type="number" step="0.01" min="0" name="price" value="{{ old('price', $ticketType->price ?? 0) }}" required class="mt-1 w-full rounded-lg border-gray-200 focus:ring-brand-500 focus:border-brand-500 text-sm">
        </div>
        <div>
            <label class="text-sm font-medium text-gray-700">Quantité disponible</label>
            <input type="number" min="1" name="quantity" value="{{ old('quantity', $ticketType->quantity ?? '') }}" required class="mt-1 w-full rounded-lg border-gray-200 focus:ring-brand-500 focus:border-brand-500 text-sm">
        </div>
    </div>
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="text-sm font-medium text-gray-700">Début des ventes (optionnel)</label>
            <input type="datetime-local" name="sales_start" value="{{ old('sales_start', isset($ticketType) && $ticketType->sales_start ? $ticketType->sales_start->format('Y-m-d\TH:i') : '') }}" class="mt-1 w-full rounded-lg border-gray-200 focus:ring-brand-500 focus:border-brand-500 text-sm">
        </div>
        <div>
            <label class="text-sm font-medium text-gray-700">Fin des ventes (optionnel)</label>
            <input type="datetime-local" name="sales_end" value="{{ old('sales_end', isset($ticketType) && $ticketType->sales_end ? $ticketType->sales_end->format('Y-m-d\TH:i') : '') }}" class="mt-1 w-full rounded-lg border-gray-200 focus:ring-brand-500 focus:border-brand-500 text-sm">
        </div>
    </div>
</div>

<button class="mt-6 bg-brand-500 hover:bg-brand-600 text-white font-semibold px-6 py-3 rounded-xl transition">
    {{ isset($ticketType) ? 'Mettre à jour' : 'Ajouter le billet' }}
</button>
