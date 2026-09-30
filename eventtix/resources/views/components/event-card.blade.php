@props(['event'])
<a href="{{ route('events.show', $event) }}" class="group block bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-lg transition overflow-hidden">
    <div class="h-40 bg-gradient-to-br from-brand-400 to-brand-700 relative flex items-end p-4">
        @if($event->cover_image)
            <img src="{{ asset('storage/'.$event->cover_image) }}" class="absolute inset-0 w-full h-full object-cover" alt="{{ $event->title }}">
            <div class="absolute inset-0 bg-black/30"></div>
        @endif
        <span class="relative z-10 text-xs font-semibold text-white bg-white/20 backdrop-blur px-3 py-1 rounded-full">{{ $event->category }}</span>
    </div>
    <div class="p-5">
        <p class="text-xs font-semibold text-brand-600 uppercase tracking-wide">{{ $event->start_date->translatedFormat('d M Y · H:i') }}</p>
        <h3 class="mt-1 text-lg font-bold text-gray-900 group-hover:text-brand-600 line-clamp-2">{{ $event->title }}</h3>
        <p class="mt-1 text-sm text-gray-500 flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            {{ $event->location }}
        </p>
        <div class="mt-4 flex items-center justify-between">
            <span class="text-sm font-bold text-gray-900">
                @if($event->minPrice() > 0)
                    À partir de {{ number_format($event->minPrice(), 0, ',', ' ') }} DA
                @else
                    Gratuit
                @endif
            </span>
            @if($event->isSoldOut())
                <span class="text-xs font-semibold text-red-500">Complet</span>
            @else
                <span class="text-xs font-semibold text-brand-600">Réserver →</span>
            @endif
        </div>
    </div>
</a>
