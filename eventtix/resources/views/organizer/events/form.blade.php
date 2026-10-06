@csrf
@if(isset($event)) @method('PUT') @endif

@if($errors->any())
    <div class="mb-6 rounded-2xl border border-red-200/70 bg-red-50/90 p-4 text-sm text-red-900">
        <ul class="list-disc space-y-0.5 pl-4">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
@endif

<div class="grid gap-5 sm:grid-cols-2">
    <div class="sm:col-span-2">
        <label class="label-micro !text-stone-500" for="ev-title">Titre de l'événement</label>
        <input id="ev-title" type="text" name="title" value="{{ old('title', $event->title ?? '') }}" required class="input" placeholder="Ex : Nuits Sonores — Édition d'été">
    </div>
    <div class="sm:col-span-2">
        <label class="label-micro !text-stone-500" for="ev-description">Description</label>
        <textarea id="ev-description" name="description" rows="5" required class="input" placeholder="Présentez le programme, les artistes, les informations pratiques…">{{ old('description', $event->description ?? '') }}</textarea>
    </div>
    <div>
        <label class="label-micro !text-stone-500" for="ev-category">Catégorie</label>
        <input id="ev-category" type="text" name="category" value="{{ old('category', $event->category ?? '') }}" required placeholder="Concert, Conférence, Sport…" class="input">
    </div>
    <div>
        <label class="label-micro !text-stone-500" for="ev-location">Lieu</label>
        <input id="ev-location" type="text" name="location" value="{{ old('location', $event->location ?? '') }}" required class="input" placeholder="Alger, Oran, Constantine…">
    </div>
    <div class="sm:col-span-2">
        <label class="label-micro !text-stone-500" for="ev-venue">Détails du lieu (optionnel)</label>
        <textarea id="ev-venue" name="venue_details" rows="2" class="input" placeholder="Salle, adresse précise, accès…">{{ old('venue_details', $event->venue_details ?? '') }}</textarea>
    </div>
    <div>
        <label class="label-micro !text-stone-500" for="ev-start">Début</label>
        <input id="ev-start" type="datetime-local" name="start_date" value="{{ old('start_date', isset($event) ? $event->start_date->format('Y-m-d\TH:i') : '') }}" required class="input tnum">
    </div>
    <div>
        <label class="label-micro !text-stone-500" for="ev-end">Fin</label>
        <input id="ev-end" type="datetime-local" name="end_date" value="{{ old('end_date', isset($event) ? $event->end_date->format('Y-m-d\TH:i') : '') }}" required class="input tnum">
    </div>
    <div>
        <label class="label-micro !text-stone-500" for="ev-capacity">Capacité totale</label>
        <input id="ev-capacity" type="number" name="capacity" min="1" value="{{ old('capacity', $event->capacity ?? '') }}" required class="input tnum">
    </div>
    <div>
        <label class="label-micro !text-stone-500" for="ev-status">Statut</label>
        <select id="ev-status" name="status" class="input cursor-pointer">
            <option value="draft" @selected(old('status', $event->status ?? 'draft') === 'draft')>Brouillon</option>
            <option value="published" @selected(old('status', $event->status ?? '') === 'published')>Publié</option>
        </select>
    </div>
    <div class="sm:col-span-2">
        <label class="label-micro !text-stone-500" for="ev-cover">Image de couverture (optionnel)</label>
        <input id="ev-cover" type="file" name="cover_image" accept="image/*"
            class="block w-full cursor-pointer text-sm text-stone-500 file:mr-4 file:cursor-pointer file:rounded-xl file:border-0 file:bg-ink file:px-4 file:py-2.5 file:text-xs file:font-semibold file:text-paper file:transition-colors hover:file:bg-ink-700">
        @if(isset($event) && $event->cover_image)
            <p class="mt-2 text-xs text-stone-400">Une couverture est déjà en ligne — laissez vide pour la conserver.</p>
        @endif
    </div>
</div>

<button type="submit" class="btn-dark mt-8 w-full sm:w-auto">
    {{ isset($event) ? "Mettre à jour l'événement" : "Créer l'événement" }}
    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M7 17 17 7m0 0H8m9 0v9" />
    </svg>
</button>
