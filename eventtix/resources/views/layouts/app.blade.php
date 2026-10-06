<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'EventTix') — Billetterie & Réservation d'Événements</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                        display: ['"Inter Tight"', 'Inter', 'system-ui', 'sans-serif'],
                        mono: ['"JetBrains Mono"', 'monospace'],
                    },
                    colors: {
                        ink: { DEFAULT: '#171412', 800: '#211D1A', 700: '#2C2723', 600: '#3B342E' },
                        paper: '#F4F1EC',
                        cream: '#FBF9F6',
                        coral: { 300: '#F5B29B', 400: '#EF8A6B', 500: '#E4603C', 600: '#CE5230', 700: '#AF4626' },
                        peach: { DEFAULT: '#EDD8C9', deep: '#E6CCBA' },
                    },
                    boxShadow: {
                        soft: '0 1px 2px rgba(23,20,18,0.04), 0 10px 30px -14px rgba(23,20,18,0.16)',
                        lift: '0 2px 4px rgba(23,20,18,0.05), 0 18px 42px -18px rgba(23,20,18,0.24)',
                    },
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Inter+Tight:wght@600;700;800&family=JetBrains+Mono:wght@500;600&display=swap" rel="stylesheet">
    <style type="text/tailwindcss">
        @layer components {
            .eyebrow { @apply text-[11px] font-semibold uppercase tracking-[0.2em] text-coral-600; }
            .page-title { @apply font-display text-3xl font-bold leading-[1.06] tracking-tight text-ink sm:text-4xl lg:text-5xl; }
            .page-subtitle { @apply mt-3 max-w-xl text-sm leading-relaxed text-stone-500; }
            .card { @apply rounded-3xl border border-stone-200/70 bg-white shadow-soft; }
            .btn-dark { @apply inline-flex cursor-pointer items-center justify-center gap-2 rounded-xl bg-ink px-5 py-3 text-sm font-medium text-paper transition-all duration-200 hover:bg-ink-700 active:scale-[0.98]; }
            .btn-soft { @apply inline-flex cursor-pointer items-center justify-center gap-2 rounded-xl border border-stone-200 bg-white px-5 py-3 text-sm font-medium text-ink transition-all duration-200 hover:border-stone-300 hover:bg-cream active:scale-[0.98]; }
            .chip { @apply inline-flex shrink-0 cursor-pointer items-center gap-2 rounded-full border border-stone-200 bg-white px-4 py-2 text-xs font-medium text-stone-600 transition-colors duration-150 hover:border-stone-300 hover:text-ink; }
            .chip-active { @apply border-ink bg-ink text-paper hover:border-ink hover:text-paper; }
            .input { @apply block w-full rounded-xl border border-stone-200 bg-cream px-4 py-3 text-sm text-ink placeholder:text-stone-400 transition-colors focus:border-ink focus:outline-none; }
            .label-micro { @apply mb-1.5 block text-[11px] font-semibold uppercase tracking-[0.14em] text-stone-400; }
            .table-th { @apply px-5 py-3.5 text-left text-[11px] font-semibold uppercase tracking-[0.14em] text-stone-400; }
            .nav-item { @apply flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm font-medium transition-colors duration-150; }
            .nav-item-active { @apply bg-white text-ink shadow-soft hover:text-ink; }
            .nav-item-idle { @apply text-paper/55 hover:bg-white/[0.06] hover:text-paper; }
        }
        @layer utilities {
            .tnum { font-variant-numeric: tabular-nums; }
        }
    </style>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('favorite', (id) => ({
                on: false,
                init() {
                    this.on = (JSON.parse(localStorage.getItem('eventtix_favs') || '[]')).includes(id);
                },
                toggle() {
                    let favs = JSON.parse(localStorage.getItem('eventtix_favs') || '[]');
                    favs = this.on ? favs.filter((x) => x !== id) : [...favs, id];
                    localStorage.setItem('eventtix_favs', JSON.stringify(favs));
                    this.on = !this.on;
                },
            }));
        });
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="min-h-screen bg-paper font-sans text-ink antialiased selection:bg-ink selection:text-paper" x-data="{ sidebarOpen: false }">
    @include('partials.sidebar')

    <div class="flex min-h-screen flex-col lg:pl-[272px]">
        @include('partials.topbar')

        <main class="flex-1 px-5 pb-24 pt-6 sm:px-8 lg:px-12">
            <div class="mx-auto w-full max-w-[1200px]">
                @include('partials.flash')
                {{ $slot ?? '' }}
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
