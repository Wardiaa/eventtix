<header class="bg-white border-b border-gray-100 sticky top-0 z-40">
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
        <a href="{{ route('home') }}" class="flex items-center gap-2 shrink-0">
            <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-brand-500 text-white font-bold">E</span>
            <span class="text-xl font-extrabold text-brand-800 tracking-tight">Event<span class="text-brand-500">Tix</span></span>
        </a>

        <div class="hidden md:flex items-center gap-6 text-sm font-medium text-gray-600">
            <a href="{{ route('events.index') }}" class="hover:text-brand-600 {{ request()->routeIs('events.*') ? 'text-brand-600' : '' }}">Événements</a>
            @auth
                @if(auth()->user()->role === 'user')
                    <a href="{{ route('bookings.index') }}" class="hover:text-brand-600 {{ request()->routeIs('bookings.*') ? 'text-brand-600' : '' }}">Mes billets</a>
                @endif
                @if(in_array(auth()->user()->role, ['organizer','admin']))
                    <a href="{{ route('organizer.dashboard') }}" class="hover:text-brand-600 {{ request()->routeIs('organizer.*') ? 'text-brand-600' : '' }}">Espace organisateur</a>
                @endif
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-brand-600 {{ request()->routeIs('admin.*') ? 'text-brand-600' : '' }}">Administration</a>
                @endif
            @endauth
        </div>

        <div class="flex items-center gap-3">
            @guest
                <a href="{{ route('login') }}" class="text-sm font-semibold text-gray-600 hover:text-brand-600">Connexion</a>
                <a href="{{ route('register') }}" class="text-sm font-semibold bg-brand-500 text-white px-4 py-2 rounded-full hover:bg-brand-600 transition">Créer un compte</a>
            @else
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" @click.outside="open = false" class="flex items-center gap-2 text-sm font-semibold text-gray-700 hover:text-brand-600">
                        <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-brand-100 text-brand-700 font-bold">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </span>
                        <span class="hidden sm:inline">{{ auth()->user()->name }}</span>
                    </button>
                    <div x-show="open" x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-2" style="display:none;">
                        <span class="block px-4 py-1 text-xs text-gray-400 uppercase tracking-wide">{{ ucfirst(auth()->user()->role) }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="w-full text-left px-4 py-2 text-sm text-gray-600 hover:bg-gray-50">Se déconnecter</button>
                        </form>
                    </div>
                </div>
            @endguest
        </div>
    </nav>
</header>
