@php
    $topbarUser = auth()->user();
    $topbarInitials = $topbarUser
        ? strtoupper(collect(explode(' ', $topbarUser->name))->map(fn ($p) => mb_substr($p, 0, 1))->take(2)->implode(''))
        : '';
@endphp

<header class="flex items-center justify-between gap-4 px-5 pt-6 sm:px-8 lg:px-12">
    <div class="flex min-w-0 items-center gap-3">
        <button type="button" @click="sidebarOpen = true"
            class="cursor-pointer rounded-xl border border-stone-200/80 bg-white p-2.5 text-ink shadow-soft transition-colors hover:bg-cream lg:hidden"
            aria-label="Ouvrir le menu">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5" />
            </svg>
        </button>

        <p class="truncate text-sm font-medium text-ink">
            {{ ucfirst(now()->translatedFormat('l d F Y')) }}
            <span class="mx-1.5 text-stone-300">/</span>
            <span class="font-normal text-stone-500">Algérie</span>
        </p>
    </div>

    <div class="flex shrink-0 items-center gap-2.5">
        <a href="{{ $topbarUser ? route('bookings.index') : route('login') }}"
            class="relative rounded-full border border-stone-200/80 bg-white p-2.5 text-stone-600 shadow-soft transition-colors hover:text-ink"
            aria-label="Notifications">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
            </svg>
            <span class="absolute right-2.5 top-2.5 h-1.5 w-1.5 rounded-full bg-coral-500"></span>
        </a>

        @auth
            <div class="relative" x-data="{ userOpen: false }">
                <button type="button" @click="userOpen = !userOpen" @click.outside="userOpen = false"
                    class="flex h-9 w-9 cursor-pointer items-center justify-center rounded-full bg-peach text-xs font-bold text-ink ring-1 ring-ink/5 transition-transform hover:scale-105">
                    {{ $topbarInitials }}
                </button>

                <div x-show="userOpen" x-transition.opacity style="display: none;"
                    class="absolute right-0 z-50 mt-2 w-60 rounded-2xl border border-stone-200/80 bg-white p-1.5 shadow-lift">
                    <div class="border-b border-stone-100 px-3 pb-2.5 pt-2">
                        <p class="truncate text-sm font-semibold text-ink">{{ $topbarUser->name }}</p>
                        <p class="truncate text-[11px] text-stone-400">{{ $topbarUser->email }}</p>
                    </div>
                    @if($topbarUser->role === 'user')
                        <a href="{{ route('bookings.index') }}" class="mt-1 flex items-center gap-2.5 rounded-xl px-3 py-2.5 text-sm font-medium text-stone-600 transition-colors hover:bg-cream hover:text-ink">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 0 0-2 2v3a2 2 0 1 1 0 4v3a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-3a2 2 0 1 1 0-4V7a2 2 0 0 0-2-2H5z" />
                            </svg>
                            Mes billets
                        </a>
                    @else
                        <a href="{{ $topbarUser->isAdmin() ? route('admin.dashboard') : route('organizer.dashboard') }}" class="mt-1 flex items-center gap-2.5 rounded-xl px-3 py-2.5 text-sm font-medium text-stone-600 transition-colors hover:bg-cream hover:text-ink">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.5 4v16h17" />
                                <path stroke-linecap="round" d="M7.5 14.5v2.5M12 10v7M16.5 6v11" />
                            </svg>
                            Tableau de bord
                        </a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex w-full cursor-pointer items-center gap-2.5 rounded-xl px-3 py-2.5 text-left text-sm font-medium text-stone-600 transition-colors hover:bg-cream hover:text-ink">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3V7a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1" />
                            </svg>
                            Déconnexion
                        </button>
                    </form>
                </div>
            </div>
        @else
            <a href="{{ route('login') }}" class="hidden rounded-xl px-3 py-2 text-sm font-medium text-stone-600 transition-colors hover:text-ink sm:block">
                Connexion
            </a>
            <a href="{{ route('register') }}" class="btn-dark !px-4 !py-2.5 !text-xs">
                Créer un compte
            </a>
        @endauth
    </div>
</header>
