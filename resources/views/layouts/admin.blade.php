<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin — {{ $title ?? 'Dashboard' }} | Recipedia</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="bg-stone-100 font-body antialiased">

    <div x-data="{ sidebarOpen: true }" class="flex h-screen overflow-hidden">

        {{-- ── Sidebar ──────────────────────────────── --}}
        <aside :class="sidebarOpen ? 'w-64' : 'w-20'"
            class="bg-stone-900 text-white flex flex-col
                  transition-all duration-300 ease-in-out flex-shrink-0">

            {{-- Logo --}}
            <div class="flex items-center gap-3 px-5 py-5 border-b border-stone-800">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-primary-500 to-primary-700
                        flex items-center justify-center flex-shrink-0">
                    <span class="text-lg">🍽️</span>
                </div>
                <div x-show="sidebarOpen" x-transition class="overflow-hidden">
                    <p class="font-display font-bold text-lg text-white leading-none">Recipedia</p>
                    <p class="text-[10px] text-primary-400 tracking-widest uppercase">Admin Panel</p>
                </div>
            </div>

            {{-- Nav Items --}}
            <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
                @php
                $adminNav = [
                ['route' => 'admin.dashboard', 'icon' => '📊', 'label' => 'Dashboard'],
                ['route' => 'admin.recipes', 'icon' => '📖', 'label' => 'Kelola Resep'],
                ['route' => 'admin.users', 'icon' => '👥', 'label' => 'Kelola User'],
                ['route' => 'admin.categories', 'icon' => '🗂️', 'label' => 'Kategori'],
                ['route' => 'admin.tags', 'icon' => '🏷️', 'label' => 'Tag'],
                ['route' => 'admin.comments', 'icon' => '💬', 'label' => 'Komentar'],
                ['route' => 'admin.messages', 'icon' => '✉️', 'label' => 'Pesan Masuk'],
                ];
                @endphp

                @foreach($adminNav as $item)
                <a href="{{ route($item['route']) }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium
                          transition-all duration-200 group
                          {{ request()->routeIs($item['route'])
                             ? 'bg-primary-600 text-white shadow-lg shadow-primary-900/30'
                             : 'text-stone-400 hover:bg-stone-800 hover:text-white' }}">
                    <span class="text-lg flex-shrink-0">{{ $item['icon'] }}</span>
                    <span x-show="sidebarOpen" x-transition class="overflow-hidden whitespace-nowrap">
                        {{ $item['label'] }}
                    </span>
                </a>
                @endforeach
            </nav>

            {{-- Back to Site --}}
            <div class="px-3 py-4 border-t border-stone-800">
                <a href="{{ route('home') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium
                      text-stone-400 hover:bg-stone-800 hover:text-white
                      transition-all duration-200">
                    <span class="text-lg">🌐</span>
                    <span x-show="sidebarOpen" x-transition>Lihat Website</span>
                </a>
            </div>
        </aside>

        {{-- ── Main Area ────────────────────────────── --}}
        <div class="flex-1 flex flex-col overflow-hidden">

            {{-- Top Bar --}}
            <header class="bg-white border-b border-stone-200 px-6 py-4
                       flex items-center justify-between flex-shrink-0 shadow-sm">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen"
                        class="p-2 rounded-lg text-stone-500 hover:bg-stone-100
                               transition-colors duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <div>
                        <h1 class="font-display font-semibold text-stone-800 text-lg">
                            {{ $title ?? 'Dashboard' }}
                        </h1>
                        <p class="text-xs text-stone-400">
                            Selamat datang, {{ auth()->user()->name }}
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <img src="{{ auth()->user()->avatar_url }}"
                        alt="{{ auth()->user()->name }}"
                        class="w-9 h-9 rounded-xl object-cover ring-2 ring-primary-200" />
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-red-600
                                   hover:bg-red-50 rounded-lg transition-colors duration-200">
                            Keluar
                        </button>
                    </form>
                </div>
            </header>

            {{-- Content --}}
            <main class="flex-1 overflow-y-auto p-6">
                {{ $slot }}
            </main>
        </div>
    </div>

    @include('layouts.partials.toast')
    @livewireScripts
</body>

</html>