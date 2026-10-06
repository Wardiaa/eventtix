@php
    $authUser = auth()->user();
    $isOrganizerSide = $authUser && in_array($authUser->role, ['organizer', 'admin']);

    $upcomingTicketsCount = 0;
    $walletValue = 0;
    $walletPercent = 0;
    $walletLabel = 'Votre solde';
    $walletCaption = '';

    if ($authUser) {
        $upcomingTicketsCount = \App\Models\Ticket::whereHas('booking', function ($q) use ($authUser) {
            $q->where('user_id', $authUser->id)
                ->where('status', 'confirmed')
                ->whereHas('event', fn ($e) => $e->where('end_date', '>=', now()));
        })->count();

        if ($isOrganizerSide) {
            $organizerEvents = $authUser->events()->with('ticketTypes')->get();
            $sold = $organizerEvents->flatMap->ticketTypes->sum('quantity_sold');
            $stock = $organizerEvents->flatMap->ticketTypes->sum('quantity');
            $walletValue = \App\Models\Booking::whereIn('event_id', $organizerEvents->pluck('id'))
                ->where('status', 'confirmed')->sum('total_price');
            $walletPercent = $stock > 0 ? min(100, (int) round($sold / $stock * 100)) : 0;
            $walletLabel = 'Vos revenus';
            $walletCaption = $stock > 0
                ? $sold.' billets vendus sur '.$stock.' places'
                : 'Aucun billet en vente pour le moment';
        } else {
            $walletValue = \App\Models\Booking::where('user_id', $authUser->id)
                ->where('status', 'confirmed')->sum('total_price');
            $ticketsTotal = \App\Models\Ticket::whereHas('booking', fn ($q) => $q->where('user_id', $authUser->id))->count();
            $ticketsUsed = \App\Models\Ticket::whereHas('booking', fn ($q) => $q->where('user_id', $authUser->id))
                ->where('status', 'used')->count();
            $walletPercent = $ticketsTotal > 0 ? (int) round($ticketsUsed / $ticketsTotal * 100) : 0;
            $walletLabel = 'Vos dépenses';
            $walletCaption = $ticketsTotal > 0
                ? $ticketsUsed.' billet(s) utilisé(s) sur '.$ticketsTotal
                : 'Aucun billet pour le moment';
        }
    }

    $initials = $authUser
        ? strtoupper(collect(explode(' ', $authUser->name))->map(fn ($p) => mb_substr($p, 0, 1))->take(2)->implode(''))
        : '';
@endphp

<aside
    class="fixed inset-y-0 left-0 z-50 flex w-[272px] -translate-x-full flex-col overflow-y-auto bg-ink px-4 py-5 transition-transform duration-300 ease-out lg:translate-x-0"
    :class="sidebarOpen ? 'translate-x-0' : ''">

    <!-- Brand -->
    <div class="flex items-center justify-between px-1.5">
        <a href="{{ route('home') }}" class="flex items-center gap-3">
            <span class="flex h-10 w-10 items-center justify-center rounded-[14px] bg-coral-500 text-white shadow-[0_10px_24px_-10px_rgba(228,96,60,0.8)]">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 0 0-2 2v3a2 2 0 1 1 0 4v3a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-3a2 2 0 1 1 0-4V7a2 2 0 0 0-2-2H5z" />
                </svg>
            </span>
            <span class="font-display text-xl font-bold tracking-tight text-paper">EventTix</span>
        </a>
        <button type="button" @click="sidebarOpen = false" class="cursor-pointer rounded-lg p-2 text-paper/50 transition-colors hover:bg-white/5 hover:text-paper lg:hidden" aria-label="Fermer le menu">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Menu -->
    <p class="px-3.5 pb-2 pt-9 text-[10px] font-semibold uppercase tracking-[0.24em] text-paper/35">Menu</p>
    <nav class="space-y-1.5">
        <a href="{{ route('home') }}" class="nav-item {{ request()->routeIs('home') ? 'nav-item-active' : 'nav-item-idle' }}">
            <svg class="h-[18px] w-[18px] shrink-0" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24">
                <rect x="3.75" y="3.75" width="6.5" height="6.5" rx="2" />
                <rect x="13.75" y="3.75" width="6.5" height="6.5" rx="2" />
                <rect x="3.75" y="13.75" width="6.5" height="6.5" rx="2" />
                <rect x="13.75" y="13.75" width="6.5" height="6.5" rx="2" />
            </svg>
            <span>Explorer</span>
        </a>

        <a href="{{ route('events.index') }}" class="nav-item {{ request()->routeIs('events.*') ? 'nav-item-active' : 'nav-item-idle' }}">
            <svg class="h-[18px] w-[18px] shrink-0" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24">
                <rect x="3.75" y="5" width="16.5" height="15.25" rx="3" />
                <path stroke-linecap="round" d="M8.25 3.5v3M15.75 3.5v3M3.75 10.25h16.5" />
            </svg>
            <span>Événements</span>
        </a>

        @auth
            @if(auth()->user()->role === 'user')
                <a href="{{ route('bookings.index') }}" class="nav-item {{ request()->routeIs('bookings.*') ? 'nav-item-active' : 'nav-item-idle' }}">
                    <svg class="h-[18px] w-[18px] shrink-0" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 0 0-2 2v3a2 2 0 1 1 0 4v3a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-3a2 2 0 1 1 0-4V7a2 2 0 0 0-2-2H5z" />
                    </svg>
                    <span>Mes billets</span>
                    @if($upcomingTicketsCount > 0)
                        <span class="ml-auto rounded-full bg-coral-500 px-2 py-[3px] text-[10px] font-bold leading-none text-white">{{ $upcomingTicketsCount }}</span>
                    @endif
                </a>
            @endif

            @if(in_array(auth()->user()->role, ['organizer', 'admin']))
                <a href="{{ route('organizer.dashboard') }}" class="nav-item {{ request()->routeIs('organizer.*') && !request()->routeIs('organizer.validate.*') ? 'nav-item-active' : 'nav-item-idle' }}">
                    <svg class="h-[18px] w-[18px] shrink-0" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.5 4v16h17" />
                        <path stroke-linecap="round" d="M7.5 14.5v2.5M12 10v7M16.5 6v11" />
                    </svg>
                    <span>Espaces organisateur</span>
                </a>

                <a href="{{ route('organizer.validate.show') }}" class="nav-item {{ request()->routeIs('organizer.validate.*') ? 'nav-item-active' : 'nav-item-idle' }}">
                    <svg class="h-[18px] w-[18px] shrink-0" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24">
                        <rect x="3.75" y="3.75" width="6.5" height="6.5" rx="1.5" />
                        <rect x="13.75" y="3.75" width="6.5" height="6.5" rx="1.5" />
                        <rect x="3.75" y="13.75" width="6.5" height="6.5" rx="1.5" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.75 14h2.5v2.5h-2.5zM18 14h2.25v2.5H18zM13.75 18.5h2.5v2.25h-2.5zM18 18.5h2.25v2.25H18z" />
                    </svg>
                    <span>Contrôle d'accès</span>
                </a>
            @endif

            @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.*') ? 'nav-item-active' : 'nav-item-idle' }}">
                    <svg class="h-[18px] w-[18px] shrink-0" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 1 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 1 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 1 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 1 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z" />
                    </svg>
                    <span>Administration</span>
                </a>
            @endif
        @endauth
    </nav>

    <div class="mt-auto pt-8">
        @auth
            <!-- Wallet summary -->
            <div class="rounded-2xl bg-white/[0.06] p-4">
                <div class="flex items-center justify-between">
                    <p class="text-[11px] font-medium text-paper/50">{{ $walletLabel }}</p>
                    <svg class="h-4 w-4 text-paper/30" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <circle cx="5" cy="12" r="1.6" /><circle cx="12" cy="12" r="1.6" /><circle cx="19" cy="12" r="1.6" />
                    </svg>
                </div>
                <p class="mt-3 font-display text-2xl font-bold tracking-tight text-paper tnum">
                    {{ number_format($walletValue, 0, ',', ' ') }} <span class="text-xs font-medium text-paper/50">DA</span>
                </p>
                <div class="mt-3 h-1.5 w-full overflow-hidden rounded-full bg-white/10">
                    <div class="h-full rounded-full bg-coral-500 transition-all duration-500" style="width: {{ $walletPercent }}%"></div>
                </div>
                <p class="mt-2 text-[10px] leading-relaxed text-paper/40">{{ $walletCaption }}</p>
            </div>
        @endauth

        <div class="mx-2 my-5 border-t border-white/10"></div>

        @auth
            <!-- Account -->
            <div class="relative" x-data="{ accountOpen: false }">
                <button type="button" @click="accountOpen = !accountOpen" @click.outside="accountOpen = false"
                    class="flex w-full cursor-pointer items-center gap-3 rounded-xl px-2 py-2 text-left transition-colors hover:bg-white/[0.06]">
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-peach text-xs font-bold text-ink">{{ $initials }}</span>
                    <span class="min-w-0 flex-1">
                        <span class="block truncate text-sm font-semibold text-paper">{{ auth()->user()->name }}</span>
                        <span class="block truncate text-[11px] text-paper/45">{{ auth()->user()->email }}</span>
                    </span>
                    <svg class="h-4 w-4 shrink-0 text-paper/40" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 1 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 1 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 1 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 1 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z" />
                    </svg>
                </button>

                <div x-show="accountOpen" x-transition.opacity style="display: none;"
                    class="absolute bottom-full left-0 z-50 mb-2 w-full rounded-2xl border border-white/10 bg-ink-800 p-1.5 shadow-lift">
                    <p class="px-3 pb-1.5 pt-2 text-[11px] text-paper/40">{{ ucfirst(auth()->user()->role) }}</p>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex w-full cursor-pointer items-center gap-2.5 rounded-xl px-3 py-2.5 text-left text-sm font-medium text-paper/70 transition-colors hover:bg-white/5 hover:text-paper">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3V7a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1" />
                            </svg>
                            Déconnexion
                        </button>
                    </form>
                </div>
            </div>
        @else
            <div class="space-y-2">
                <a href="{{ route('login') }}" class="block w-full rounded-xl border border-white/15 px-4 py-2.5 text-center text-sm font-medium text-paper/80 transition-colors hover:bg-white/5 hover:text-paper">
                    Connexion
                </a>
                <a href="{{ route('register') }}" class="block w-full rounded-xl bg-coral-500 px-4 py-2.5 text-center text-sm font-semibold text-white transition-colors hover:bg-coral-600">
                    Créer un compte
                </a>
            </div>
        @endauth
    </div>
</aside>

<!-- Mobile backdrop -->
<div x-show="sidebarOpen" x-transition.opacity @click="sidebarOpen = false" @keydown.escape.window="sidebarOpen = false"
    style="display: none;"
    class="fixed inset-0 z-40 bg-ink/60 backdrop-blur-[2px] lg:hidden"></div>
