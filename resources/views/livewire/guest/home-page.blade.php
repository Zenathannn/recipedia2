<div>
    {{-- ══════════════════════════════════════════════
         HERO SECTION
    ══════════════════════════════════════════════ --}}
    <section class="relative min-h-screen flex items-center justify-center overflow-hidden">

        {{-- Background Image --}}
        <div class="absolute inset-0">
            <img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=1920&q=80"
                alt="Hero Background"
                class="w-full h-full object-cover" />
            <div class="hero-overlay absolute inset-0"></div>

            {{-- Decorative blobs --}}
            <div class="absolute top-20 right-20 w-72 h-72 rounded-full
                        bg-primary-400/20 blur-3xl animate-pulse"></div>
            <div class="absolute bottom-20 left-20 w-96 h-96 rounded-full
                        bg-gold-400/10 blur-3xl animate-pulse"
                style="animation-delay: 1s"></div>
        </div>

        {{-- Hero Content --}}
        <div class="relative z-10 max-w-5xl mx-auto px-4 text-center">

            {{-- Badge --}}
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full
                        bg-white/10 backdrop-blur border border-white/20
                        text-white/90 text-sm font-medium mb-8
                        fade-in-up">
                <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
                🍴 Platform Resep #1 di Indonesia
            </div>

            {{-- Headline --}}
            <h1 class="font-display text-5xl md:text-7xl lg:text-8xl
                       font-bold text-white leading-tight mb-6
                       fade-in-up"
                style="animation-delay: 0.1s">
                Masak Lebih
                <span class="block italic text-transparent bg-clip-text
                             bg-gradient-to-r from-gold-300 to-gold-500">
                    Berselera
                </span>
            </h1>

            {{-- Subtitle --}}
            <p class="text-lg md:text-xl text-white/80 max-w-2xl mx-auto mb-10 leading-relaxed
                      fade-in-up"
                style="animation-delay: 0.2s">
                Temukan ribuan resep masakan Nusantara yang lezat,
                mudah dipraktikkan, dan sudah teruji oleh para chef berbakat.
            </p>

            {{-- Search Bar --}}
            <div class="max-w-2xl mx-auto mb-10 fade-in-up" style="animation-delay: 0.3s">
                <form action="{{ route('search') }}" method="GET"
                    class="flex gap-3 p-2 bg-white/10 backdrop-blur-xl
                             rounded-2xl border border-white/20 shadow-2xl">
                    <div class="flex-1 flex items-center gap-3 px-4">
                        <svg class="w-5 h-5 text-white/60 flex-shrink-0"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0" />
                        </svg>
                        <input type="text"
                            name="q"
                            placeholder="Cari resep, bahan, atau kategori..."
                            class="flex-1 bg-transparent text-white placeholder-white/50
                                      text-sm focus:outline-none py-2" />
                    </div>
                    <button type="submit"
                        class="px-6 py-3 bg-gradient-to-r from-primary-500 to-primary-600
                                   hover:from-primary-600 hover:to-primary-700
                                   text-white font-semibold rounded-xl text-sm
                                   shadow-lg hover:shadow-xl hover:-translate-y-0.5
                                   transition-all duration-200 flex-shrink-0">
                        Cari Sekarang
                    </button>
                </form>

                {{-- Quick Tags --}}
                <div class="flex flex-wrap justify-center gap-2 mt-4">
                    @foreach(['Rendang', 'Soto', 'Gado-gado', 'Nasi Goreng', 'Sate'] as $tag)
                    <a href="{{ route('search', ['q' => $tag]) }}"
                        class="px-3 py-1.5 rounded-full bg-white/10 hover:bg-white/20
                                  text-white/80 text-xs font-medium border border-white/20
                                  hover:border-white/40 transition-all duration-200
                                  hover:-translate-y-0.5">
                        {{ $tag }}
                    </a>
                    @endforeach
                </div>
            </div>

            {{-- CTA Buttons --}}
            <div class="flex flex-wrap justify-center gap-4 fade-in-up"
                style="animation-delay: 0.4s">
                <a href="{{ route('search') }}"
                    class="group flex items-center gap-2 px-8 py-4 rounded-2xl
                          bg-white text-primary-700 font-semibold text-sm
                          shadow-xl hover:shadow-2xl hover:-translate-y-1
                          transition-all duration-300">
                    <span>Jelajahi Resep</span>
                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform duration-200"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </a>
                @guest
                <a href="{{ route('register') }}"
                    class="flex items-center gap-2 px-8 py-4 rounded-2xl
                              bg-transparent border-2 border-white/40 hover:border-white
                              text-white font-semibold text-sm
                              hover:bg-white/10 hover:-translate-y-1
                              transition-all duration-300">
                    ✨ Bagikan Resepmu
                </a>
                @endguest
            </div>

            {{-- Stats --}}
            <div class="grid grid-cols-3 gap-6 max-w-md mx-auto mt-16
                        fade-in-up" style="animation-delay: 0.5s">
                @foreach([
                ['value' => $totalRecipes . '+', 'label' => 'Resep'],
                ['value' => $totalChefs . '+', 'label' => 'Chef'],
                ['value' => '5⭐', 'label' => 'Rating'],
                ] as $stat)
                <div class="text-center">
                    <p class="font-display text-3xl font-bold text-white">
                        {{ $stat['value'] }}
                    </p>
                    <p class="text-white/60 text-xs mt-1">{{ $stat['label'] }}</p>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Scroll Indicator --}}
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 animate-bounce">
            <div class="w-6 h-10 rounded-full border-2 border-white/40
                        flex items-start justify-center pt-2">
                <div class="w-1 h-2 rounded-full bg-white/60"></div>
            </div>
        </div>
    </section>


    {{-- ══════════════════════════════════════════════
         KATEGORI SECTION
    ══════════════════════════════════════════════ --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">

        {{-- Section Header --}}
        <div class="text-center mb-14">
            <span class="inline-block px-4 py-1.5 rounded-full bg-primary-100
                         text-primary-700 text-xs font-bold tracking-widest uppercase mb-4">
                Kategori Masakan
            </span>
            <h2 class="font-display text-4xl md:text-5xl font-bold text-stone-900 mb-4">
                Temukan Sesuai
                <span class="gradient-text"> Seleramu</span>
            </h2>
            <p class="text-stone-500 text-lg max-w-xl mx-auto">
                Dari appetizer hingga dessert, kami punya semua yang kamu butuhkan
            </p>
        </div>

        {{-- Category Cards --}}
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
            @foreach($categories as $category)
            <a href="{{ route('category', $category->slug) }}"
                class="group relative overflow-hidden rounded-3xl
                          aspect-square cursor-pointer
                          hover:-translate-y-2 hover:shadow-2xl
                          transition-all duration-500">

                {{-- Background Gradient --}}
                <div class="absolute inset-0 opacity-90 group-hover:opacity-100
                                transition-opacity duration-300"
                    style="background: linear-gradient(135deg,
                                {{ $category->color }}dd,
                                {{ $category->color }}88)">
                </div>

                {{-- Decorative Circle --}}
                <div class="absolute -top-4 -right-4 w-24 h-24 rounded-full
                                bg-white/10 group-hover:scale-150
                                transition-transform duration-700"></div>
                <div class="absolute -bottom-6 -left-6 w-32 h-32 rounded-full
                                bg-black/5 group-hover:scale-125
                                transition-transform duration-700"></div>

                {{-- Content --}}
                <div class="relative z-10 h-full flex flex-col
                                items-center justify-center p-4 text-white text-center">
                    <span class="text-5xl mb-3
                                     group-hover:scale-125 group-hover:-rotate-6
                                     transition-all duration-500 block">
                        {{ $category->icon }}
                    </span>
                    <h3 class="font-display font-bold text-base leading-tight">
                        {{ $category->name }}
                    </h3>
                    <div class="mt-2 px-3 py-1 rounded-full bg-white/20
                                    text-xs font-medium opacity-0 group-hover:opacity-100
                                    translate-y-2 group-hover:translate-y-0
                                    transition-all duration-300">
                        {{ $category->recipes_count }} resep
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </section>


    {{-- ══════════════════════════════════════════════
         FEATURED RECIPES SECTION
    ══════════════════════════════════════════════ --}}
    @if($featuredRecipes->count())
    <section class="bg-gradient-to-br from-stone-900 via-primary-950 to-stone-900 py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-14 gap-4">
                <div>
                    <span class="inline-block px-4 py-1.5 rounded-full bg-primary-500/20
                                 text-primary-300 text-xs font-bold tracking-widest uppercase mb-4">
                        ⭐ Pilihan Editor
                    </span>
                    <h2 class="font-display text-4xl md:text-5xl font-bold text-white">
                        Resep
                        <span class="text-transparent bg-clip-text
                                     bg-gradient-to-r from-gold-400 to-primary-400">
                            Unggulan
                        </span>
                    </h2>
                </div>
                <a href="{{ route('search', ['featured' => 1]) }}"
                    class="flex items-center gap-2 text-sm font-medium text-primary-400
                          hover:text-primary-300 transition-colors duration-200 group">
                    Lihat semua
                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform duration-200"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </a>
            </div>

            {{-- Featured Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($featuredRecipes as $recipe)
                @include('components.recipe-card', [
                'recipe' => $recipe,
                'dark' => true,
                ])
                @endforeach
            </div>
        </div>
    </section>
    @endif


    {{-- ══════════════════════════════════════════════
         LATEST RECIPES SECTION
    ══════════════════════════════════════════════ --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">

        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-14 gap-4">
            <div>
                <span class="inline-block px-4 py-1.5 rounded-full bg-primary-100
                             text-primary-700 text-xs font-bold tracking-widest uppercase mb-4">
                    🆕 Baru Ditambahkan
                </span>
                <h2 class="font-display text-4xl md:text-5xl font-bold text-stone-900">
                    Resep <span class="gradient-text">Terbaru</span>
                </h2>
            </div>
            <a href="{{ route('search') }}"
                class="flex items-center gap-2 text-sm font-medium text-primary-600
                      hover:text-primary-700 transition-colors duration-200 group">
                Lihat semua resep
                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform duration-200"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
            </a>
        </div>

        {{-- Recipe Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($latestRecipes as $recipe)
            @include('components.recipe-card', ['recipe' => $recipe])
            @empty
            <div class="col-span-full text-center py-20">
                <span class="text-6xl block mb-4">🍳</span>
                <p class="text-stone-500 text-lg">
                    Belum ada resep. Jadilah yang pertama berbagi!
                </p>
                @auth
                <a href="{{ route('submit-recipe') }}"
                    class="inline-block mt-4 px-6 py-3 rounded-xl
                                  bg-primary-600 text-white font-semibold text-sm
                                  hover:bg-primary-700 transition-colors duration-200">
                    Bagikan Resep Pertama
                </a>
                @endauth
            </div>
            @endforelse
        </div>
    </section>


    {{-- ══════════════════════════════════════════════
         POPULAR RECIPES SECTION
    ══════════════════════════════════════════════ --}}
    @if($popularRecipes->count())
    <section class="bg-gradient-to-r from-primary-50 via-orange-50 to-amber-50 py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="text-center mb-14">
                <span class="inline-block px-4 py-1.5 rounded-full bg-primary-100
                             text-primary-700 text-xs font-bold tracking-widest uppercase mb-4">
                    🔥 Paling Banyak Dilihat
                </span>
                <h2 class="font-display text-4xl md:text-5xl font-bold text-stone-900">
                    Resep <span class="gradient-text">Populer</span>
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($popularRecipes as $index => $recipe)
                <a href="{{ route('recipe.detail', $recipe->slug) }}"
                    class="group flex gap-5 bg-white rounded-3xl p-5
                          shadow-sm hover:shadow-xl hover:-translate-y-1
                          border border-stone-100 hover:border-primary-100
                          transition-all duration-300">

                    {{-- Rank Number --}}
                    <div class="flex-shrink-0 w-10 h-10 rounded-2xl flex items-center justify-center
                                font-display font-bold text-lg
                                {{ $index === 0 ? 'bg-gold-100 text-gold-600' :
                                   ($index === 1 ? 'bg-stone-100 text-stone-600' :
                                   ($index === 2 ? 'bg-amber-100 text-amber-700' :
                                   'bg-primary-100 text-primary-700')) }}">
                        {{ $index + 1 }}
                    </div>

                    {{-- Thumbnail --}}
                    <div class="w-20 h-20 rounded-2xl overflow-hidden flex-shrink-0">
                        <img src="{{ $recipe->thumbnail_url }}"
                            alt="{{ $recipe->title }}"
                            class="w-full h-full object-cover
                                    group-hover:scale-110 transition-transform duration-500" />
                    </div>

                    {{-- Info --}}
                    <div class="flex-1 min-w-0">
                        <span class="text-xs font-semibold text-primary-500 uppercase tracking-wider">
                            {{ $recipe->category->name ?? 'Umum' }}
                        </span>
                        <h3 class="font-display font-bold text-stone-900 mt-0.5 mb-1
                                   group-hover:text-primary-700 transition-colors duration-200
                                   truncate">
                            {{ $recipe->title }}
                        </h3>
                        <div class="flex items-center gap-4 text-xs text-stone-400">
                            <span class="flex items-center gap-1">
                                👁️ {{ number_format($recipe->views_count) }}
                            </span>
                            <span class="flex items-center gap-1">
                                ❤️ {{ $recipe->favorites_count }}
                            </span>
                            <span class="flex items-center gap-1">
                                ⏱️ {{ $recipe->total_time }} mnt
                            </span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif


    {{-- ══════════════════════════════════════════════
         CTA SECTION — AJAK UPLOAD RESEP
    ══════════════════════════════════════════════ --}}
    @guest
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
        <div class="relative overflow-hidden rounded-[2.5rem]
                    bg-gradient-to-br from-primary-600 via-primary-700 to-primary-900
                    p-12 md:p-20 text-center shadow-2xl shadow-primary-200">

            {{-- Decorative elements --}}
            <div class="absolute top-0 right-0 w-96 h-96 rounded-full
                        bg-white/5 -translate-y-1/2 translate-x-1/2"></div>
            <div class="absolute bottom-0 left-0 w-64 h-64 rounded-full
                        bg-black/10 translate-y-1/2 -translate-x-1/2"></div>
            <div class="absolute inset-0 opacity-5"
                style="background-image: radial-gradient(circle, white 1px, transparent 1px);
                        background-size: 30px 30px;"></div>

            <div class="relative z-10">
                <span class="text-6xl block mb-6">👨‍🍳</span>
                <h2 class="font-display text-4xl md:text-5xl font-bold text-white mb-4">
                    Punya Resep Andalan?
                </h2>
                <p class="text-primary-200 text-lg max-w-xl mx-auto mb-10">
                    Bagikan kreasi masakanmu kepada jutaan pecinta kuliner Indonesia.
                    Daftar gratis dan mulai berbagi sekarang!
                </p>
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="{{ route('register') }}"
                        class="group flex items-center gap-2 px-8 py-4 rounded-2xl
                              bg-white text-primary-700 font-bold text-sm
                              shadow-xl hover:shadow-2xl hover:-translate-y-1
                              transition-all duration-300">
                        ✨ Mulai Berbagi Gratis
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </a>
                    <a href="{{ route('search') }}"
                        class="px-8 py-4 rounded-2xl border-2 border-white/30
                              hover:border-white text-white font-semibold text-sm
                              hover:bg-white/10 hover:-translate-y-1
                              transition-all duration-300">
                        Jelajahi Dulu
                    </a>
                </div>
            </div>
        </div>
    </section>
    @endguest
</div>