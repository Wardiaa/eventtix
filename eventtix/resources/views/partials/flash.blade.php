@if(session('success'))
    <div class="mb-6 flex items-start gap-3 rounded-2xl border border-emerald-200/70 bg-emerald-50/90 px-4 py-3.5 text-sm font-medium text-emerald-900 shadow-soft">
        <svg class="mt-0.5 h-4 w-4 shrink-0 text-emerald-600" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" />
        </svg>
        <span>{{ session('success') }}</span>
    </div>
@endif
@if(session('error'))
    <div class="mb-6 flex items-start gap-3 rounded-2xl border border-red-200/70 bg-red-50/90 px-4 py-3.5 text-sm font-medium text-red-900 shadow-soft">
        <svg class="mt-0.5 h-4 w-4 shrink-0 text-red-600" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0 3.75h.008v.008H12v-.008zM21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" />
        </svg>
        <span>{{ session('error') }}</span>
    </div>
@endif
