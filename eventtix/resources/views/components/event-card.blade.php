@props(['event'])

<article class="group relative flex flex-col overflow-hidden rounded-3xl border border-stone-200/70 bg-white shadow-soft transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lift"
    x-data="favorite({{ $event->id }})">
    <a href="{{ route('events.show', $event) }}" class="absolute inset-0 z-10" aria-label="Voir {{ $event->title }}"></a>

    <div class="relative p-2.5 pb-0">
        <div class="relative aspect-[16/11] overflow-hidden rounded-[18px] bg-stone-100">
            @if($event->cover_image)
                <img src="{{ asset('storage/'.$event->cover_image) }}" alt="{{ $event->title }}"
                    class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-[1.04]">
            @else
                <div class="flex h-full w-full items-center justify-center bg-peach">
                    <svg class="h-10 w-10 text-ink/20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 0 0-2 2v3a2 2 0 1 1 0 4v3a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-3a2 2 0 1 1 0-4V7a2 2 0 0 0-2-2H5z" />
                    </svg>
                </div>
            @endif

            @if($event->isSoldOut())
                <div class="absolute inset-0 flex items-center justify-center bg-ink/55">
                    <span class="rounded-full bg-white px-4 py-1.5 text-xs font-bold text-ink">Complet</span>
                </div>
            @endif

            <span class="absolute bottom-3 left-3 rounded-full bg-white/90 px-3 py-1 text-[11px] font-semibold text-ink backdrop-blur-sm">
                {{ $event->category }}
            </span>
        </div>

        <button type="button" @click.stop="toggle()"
            class="absolute right-5 top-5 z-20 flex h-9 w-9 cursor-pointer items-center justify-center rounded-full bg-white/95 shadow-soft transition-transform duration-150 hover:scale-110"
            :aria-label="on ? 'Retirer des favoris' : 'Ajouter aux favoris'">
            <svg class="h-4 w-4 transition-colors duration-150" :class="on ? 'fill-coral-500 stroke-coral-500' : 'fill-none stroke-ink'" stroke-width="1.7" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
            </svg>
        </button>
    </div>

    <div class="flex flex-1 flex-col p-4 pt-3.5">
        <div class="flex items-start justify-between gap-3">
            <div class="min-w-0">
                <h3 class="truncate text-lg font-bold tracking-tight text-ink">{{ $event->title }}</h3>
                <p class="mt-0.5 truncate text-xs text-stone-500">{{ $event->organizer->name ?? 'Organisateur' }}</p>
            </div>
            <p class="shrink-0 font-display text-lg font-bold tracking-tight text-coral-600 tnum">
                @if($event->minPrice() > 0)
                    {{ number_format($event->minPrice(), 0, ',', ' ') }} <span class="text-[11px] font-semibold">DZD</span>
                @else
                    Gratuit
                @endif
            </p>
        </div>

        <div class="mt-auto flex items-center gap-2 pt-4 text-xs font-medium text-stone-500">
            <svg class="h-3.5 w-3.5 shrink-0 text-stone-400" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24">
                <rect x="3.75" y="5" width="16.5" height="15.25" rx="3" />
                <path stroke-linecap="round" d="M8.25 3.5v3M15.75 3.5v3M3.75 10.25h16.5" />
            </svg>
            <span class="shrink-0">{{ $event->start_date->translatedFormat('d M Y') }}</span>
            <span class="text-stone-300">·</span>
            <svg class="h-3.5 w-3.5 shrink-0 text-stone-400" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657 13.414 20.9a2 2 0 0 1-2.828 0l-4.243-4.243a8 8 0 1 1 11.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" />
            </svg>
            <span class="truncate">{{ $event->location }}</span>
        </div>
    </div>
</article>
