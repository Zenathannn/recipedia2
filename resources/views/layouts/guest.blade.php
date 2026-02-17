<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Recipedia - Platform berbagi resep masakan terbaik Indonesia" />
    <title>{{ $title ?? 'Recipedia' }} — Berbagi Resep Masakan</title>

    {{-- Favicon --}}
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon" />

    {{-- Vite Assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Livewire Styles --}}
    @livewireStyles
</head>

<body class="bg-stone-50 font-body antialiased">

    {{-- ══ NAVBAR ══════════════════════════════════════ --}}
    @include('layouts.partials.navbar')

    {{-- ══ MAIN CONTENT ═══════════════════════════════ --}}
    <main class="min-h-screen">
        {{ $slot }}
    </main>

    {{-- ══ FOOTER ══════════════════════════════════════ --}}
    @include('layouts.partials.footer')

    {{-- ══ TOAST NOTIFICATION ══════════════════════════ --}}
    @include('layouts.partials.toast')

    {{-- Livewire Scripts --}}
    @livewireScripts
</body>

</html>