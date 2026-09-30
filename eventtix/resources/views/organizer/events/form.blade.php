@csrf
@if(isset($event)) @method('PUT') @endif

@if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-600 text-sm rounded-lg px-4 py-3 mb-4">
        <ul class="list-disc pl-4">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
@endif

<div class="grid sm:grid-cols-2 gap-5">
    <div class="sm:col-span-2">
        <label class="text-sm font-medium text-gray-700">Titre de l'événement</label>
        <input type="text" name="title" value="{{ old('title', $event->title ?? '') }}" required class="mt-1 w-full rounded-lg border-gray-200 focus:ring-brand-500 focus:border-brand-500 text-sm">
    </div>
    <div class="sm:col-span-2">
        <label class="text-sm font-medium text-gray-700">Description</label>
        <textarea name="description" rows="5" required class="mt-1 w-full rounded-lg border-gray-200 focus:ring-brand-500 focus:border-brand-500 text-sm">{{ old('description', $event->description ?? '') }}</textarea>
    </div>
    <div>
        <label class="text-sm font-medium text-gray-700">Catégorie</label>
        <input type="text" name="category" value="{{ old('category', $event->category ?? '') }}" required placeholder="Technologie, Musique, Business..." class="mt-1 w-full rounded-lg border-gray-200 focus:ring-brand-500 focus:border-brand-500 text-sm">
    </div>
    <div>
        <label class="text-sm font-medium text-gray-700">Lieu</label>
        <input type="text" name="location" value="{{ old('location', $event->location ?? '') }}" required class="mt-1 w-full rounded-lg border-gray-200 focus:ring-brand-500 focus:border-brand-500 text-sm">
    </div>
    <div class="sm:col-span-2">
        <label class="text-sm font-medium text-gray-700">Détails du lieu (optionnel)</label>
        <textarea name="venue_details" rows="2" class="mt-1 w-full rounded-lg border-gray-200 focus:ring-brand-500 focus:border-brand-500 text-sm">{{ old('venue_details', $event->venue_details ?? '') }}</textarea>
    </div>
    <div>
        <label class="text-sm font-medium text-gray-700">Date et heure de début</label>
        <input type="datetime-local" name="start_date" value="{{ old('start_date', isset($event) ? $event->start_date->format('Y-m-d\TH:i') : '') }}" required class="mt-1 w-full rounded-lg border-gray-200 focus:ring-brand-500 focus:border-brand-500 text-sm">
    </div>
    <div>
        <label class="text-sm font-medium text-gray-700">Date et heure de fin</label>
        <input type="datetime-local" name="end_date" value="{{ old('end_date', isset($event) ? $event->end_date->format('Y-m-d\TH:i') : '') }}" required class="mt-1 w-full rounded-lg border-gray-200 focus:ring-brand-500 focus:border-brand-500 text-sm">
    </div>
    <div>
        <label class="text-sm font-medium text-gray-700">Capacité totale</label>
        <input type="number" name="capacity" min="1" value="{{ old('capacity', $event->capacity ?? '') }}" required class="mt-1 w-full rounded-lg border-gray-200 focus:ring-brand-500 focus:border-brand-500 text-sm">
    </div>
    <div>
        <label class="text-sm font-medium text-gray-700">Statut</label>
        <select name="status" class="mt-1 w-full rounded-lg border-gray-200 focus:ring-brand-500 focus:border-brand-500 text-sm">
            <option value="draft" @selected(old('status', $event->status ?? 'draft') === 'draft')>Brouillon</option>
            <option value="published" @selected(old('status', $event->status ?? '') === 'published')>Publié</option>
        </select>
    </div>
    <div class="sm:col-span-2">
        <label class="text-sm font-medium text-gray-700">Image de couverture (optionnel)</label>
        <input type="file" name="cover_image" accept="image/*" class="mt-1 w-full text-sm">
    </div>
</div>

<button class="mt-6 bg-brand-500 hover:bg-brand-600 text-white font-semibold px-6 py-3 rounded-xl transition">
    {{ isset($event) ? 'Mettre à jour l\'événement' : 'Créer l\'événement' }}
</button>
