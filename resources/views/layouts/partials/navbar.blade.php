<nav x-data="{
        mobileOpen: false,
        scrolled: false,
        init() {
            window.addEventListener('scroll', () => {
                this.scrolled = window.scrollY > 20
            })
        }
     }"
    :class="scrolled
        ? 'bg-white/95 backdrop-blur-xl shadow-lg shadow-stone-200/50 py-3'
        : 'bg-white/90 backdrop-blur-md shadow-sm shadow-stone-200/40 border-b border-stone-200/60 py-4'"
    class="fixed top-0 inset-x-0 z-50 transition-all duration-500">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between">

            {{-- ── Logo ───────────────────────────────── --}}
            <a href="{{ route('home') }}"
                class="flex items-center gap-2 group">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-primary-500 to-primary-700
                            flex items-center justify-center shadow-lg
                            group-hover:scale-110 transition-transform duration-300">
                    <span class="text-white text-lg">🍽️</span>
                </div>
                <div class="flex flex-col leading-none">
                    <span class="font-display font-bold text-xl text-stone-900
                                 group-hover:text-primary-600 transition-colors duration-300">
                        Recipedia
                    </span>
                    <span class="text-[10px] text-primary-500 font-medium tracking-widest uppercase">
                        Resep Nusantara
                    </span>
                </div>
            </a>

            {{-- ── Desktop Nav Links ───────────────────── --}}
            <div class="hidden md:flex items-center gap-1">
                @php
                $navLinks = [
                ['route' => 'home', 'label' => 'Beranda', 'icon' => '🏠'],
                ['route' => 'search', 'label' => 'Cari Resep','icon' => '🔍'],
                ];
                @endphp

                @foreach($navLinks as $link)
                <a href="{{ route($link['route']) }}"
                    class="nav-link relative px-4 py-2 text-sm font-medium rounded-lg
                              text-stone-600 hover:text-primary-600 hover:bg-primary-50
                              transition-all duration-200
                              {{ request()->routeIs($link['route']) ? 'active text-primary-600 bg-primary-50' : '' }}">
                    {{ $link['label'] }}
                </a>
                @endforeach

                @auth
                <a href="{{ route('favourites') }}"
                    class="nav-link relative px-4 py-2 text-sm font-medium rounded-lg
                              text-stone-600 hover:text-primary-600 hover:bg-primary-50
                              transition-all duration-200
                              {{ request()->routeIs('favourites') ? 'active text-primary-600 bg-primary-50' : '' }}">
                    Favorit
                </a>
                @endauth
            </div>

            {{-- ── Right Section ───────────────────────── --}}
            <div class="hidden md:flex items-center gap-3">
                @guest
                <a href="{{ route('login') }}"
                    class="px-5 py-2 text-sm font-medium text-stone-700
                              hover:text-primary-600 transition-colors duration-200">
                    Masuk
                </a>
                <a href="{{ route('register') }}"
                    class="px-5 py-2 text-sm font-semibold text-white rounded-xl
                              bg-gradient-to-r from-primary-500 to-primary-600
                              hover:from-primary-600 hover:to-primary-700
                              shadow-md shadow-primary-200
                              hover:shadow-lg hover:shadow-primary-300
                              hover:-translate-y-0.5
                              transition-all duration-200">
                    Daftar Gratis
                </a>
                @else
                {{-- Submit Resep Button --}}
                @if(auth()->user()->isUser())
                <a href="{{ route('submit-recipe') }}"
                    class="flex items-center gap-2 px-4 py-2 text-sm font-semibold
                                  text-white rounded-xl
                                  bg-gradient-to-r from-yellow-500 to-yellow-600
                                  hover:from-yellow-600 hover:to-yellow-700
                                  shadow-md hover:shadow-lg hover:-translate-y-0.5
                                  transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4v16m8-8H4" />
                    </svg>
                    <span>
                        Bagikan Resep
                    </span>
                </a>
                @endif

                {{-- User Dropdown --}}
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open"
                        class="flex items-center gap-2 p-1.5 rounded-xl
                                       hover:bg-stone-100 transition-colors duration-200">
                        <img src="{{ auth()->user()->avatar_url }}"
                            alt="{{ auth()->user()->name }}"
                            class="w-8 h-8 rounded-lg object-cover ring-2 ring-primary-200" />
                        <div class="text-left hidden lg:block">
                            <p class="text-xs font-semibold text-stone-800 leading-none">
                                {{ Str::limit(auth()->user()->name, 15) }}
                            </p>
                            <p class="text-[10px] text-primary-500 font-medium capitalize mt-0.5">
                                {{ auth()->user()->role }}
                            </p>
                        </div>
                        <svg class="w-4 h-4 text-stone-400 transition-transform duration-200"
                            :class="{ 'rotate-180': open }"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    {{-- Dropdown Menu --}}
                    <div x-show="open"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95 translate-y-1"
                        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                        x-transition:leave-end="opacity-0 scale-95 translate-y-1"
                        @click.outside="open = false"
                        class="absolute right-0 top-full mt-2 w-56 glass rounded-2xl
                                    shadow-xl shadow-stone-200/60 border border-white/60
                                    overflow-hidden z-50">

                        {{-- User Info Header --}}
                        <div class="px-4 py-3 bg-gradient-to-r from-primary-50 to-orange-50
                                        border-b border-stone-100">
                            <p class="text-sm font-semibold text-stone-800">
                                {{ auth()->user()->name }}
                            </p>
                            <p class="text-xs text-stone-500 truncate">
                                {{ auth()->user()->email }}
                            </p>
                        </div>

                        {{-- Menu Items --}}
                        <div class="py-1.5">
                            @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}"
                                class="flex items-center gap-3 px-4 py-2.5 text-sm
                                              text-stone-700 hover:bg-primary-50 hover:text-primary-700
                                              transition-colors duration-150">
                                <span class="text-base">⚙️</span>
                                Dashboard Admin
                            </a>
                            @endif

                            <a href="{{ route('profile') }}"
                                class="flex items-center gap-3 px-4 py-2.5 text-sm
                                          text-stone-700 hover:bg-primary-50 hover:text-primary-700
                                          transition-colors duration-150">
                                <span class="text-base">👤</span>
                                Profil Saya
                            </a>

                            @if(auth()->user()->isUser())
                            <a href="{{ route('my-recipes') }}"
                                class="flex items-center gap-3 px-4 py-2.5 text-sm
                                              text-stone-700 hover:bg-primary-50 hover:text-primary-700
                                              transition-colors duration-150">
                                <span class="text-base">📖</span>
                                Resep Saya
                            </a>
                            <a href="{{ route('favourites') }}"
                                class="flex items-center gap-3 px-4 py-2.5 text-sm
                                              text-stone-700 hover:bg-primary-50 hover:text-primary-700
                                              transition-colors duration-150">
                                <span class="text-base">❤️</span>
                                Resep Favorit
                            </a>
                            @endif

                            <a href="{{ route('contact') }}"
                                class="flex items-center gap-3 px-4 py-2.5 text-sm
                                          text-stone-700 hover:bg-primary-50 hover:text-primary-700
                                          transition-colors duration-150">
                                <span class="text-base">✉️</span>
                                Hubungi Kami
                            </a>

                            <div class="my-1 border-t border-stone-100"></div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="flex items-center gap-3 w-full px-4 py-2.5 text-sm
                                                   text-red-600 hover:bg-red-50
                                                   transition-colors duration-150">
                                    <span class="text-base">🚪</span>
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endguest
            </div>

            {{-- ── Mobile Hamburger ────────────────────── --}}
            <button @click="mobileOpen = !mobileOpen"
                class="md:hidden p-2 rounded-lg text-stone-600 hover:bg-stone-100
                           transition-colors duration-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path x-show="!mobileOpen" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    <path x-show="mobileOpen" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    {{-- ── Mobile Menu ──────────────────────────────── --}}
    <div x-show="mobileOpen"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 -translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-4"
        class="md:hidden glass border-t border-stone-200/60 shadow-xl">
        <div class="max-w-7xl mx-auto px-4 py-4 space-y-1">
            <a href="{{ route('home') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium
                      text-stone-700 hover:bg-primary-50 hover:text-primary-700
                      transition-colors duration-150">
                🏠 Beranda
            </a>
            <a href="{{ route('search') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium
                      text-stone-700 hover:bg-primary-50 hover:text-primary-700
                      transition-colors duration-150">
                🔍 Cari Resep
            </a>
            @auth
            <a href="{{ route('favourites') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium
                          text-stone-700 hover:bg-primary-50 hover:text-primary-700
                          transition-colors duration-150">
                ❤️ Favorit
            </a>
            <a href="{{ route('profile') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium
                          text-stone-700 hover:bg-primary-50 hover:text-primary-700
                          transition-colors duration-150">
                👤 Profil
            </a>
            @if(auth()->user()->isUser())
            <a href="{{ route('submit-recipe') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold
                              text-white bg-gradient-to-r from-primary-500 to-primary-600
                              transition-colors duration-150">
                ✨ Bagikan Resep
            </a>
            @endif
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="flex items-center gap-3 w-full px-4 py-3 rounded-xl text-sm font-medium
                                   text-red-600 hover:bg-red-50 transition-colors duration-150">
                    🚪 Keluar
                </button>
            </form>
            @else
            <div class="flex gap-2 pt-2">
                <a href="{{ route('login') }}"
                    class="flex-1 text-center px-4 py-2.5 text-sm font-medium
                              text-stone-700 border border-stone-300 rounded-xl
                              hover:bg-stone-50 transition-colors duration-150">
                    Masuk
                </a>
                <a href="{{ route('register') }}"
                    class="flex-1 text-center px-4 py-2.5 text-sm font-semibold
                              text-white rounded-xl
                              bg-gradient-to-r from-primary-500 to-primary-600
                              transition-colors duration-150">
                    Daftar
                </a>
            </div>
            @endguest
        </div>
    </div>
</nav>
