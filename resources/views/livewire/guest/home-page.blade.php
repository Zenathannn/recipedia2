<div class="guest-home-page">
    @php($hasHeroImages = count($heroImages) > 0)
    {{-- ══════════════════════════════════════════════
         HERO SECTION - Enhanced with Parallax & Better Typography
    ══════════════════════════════════════════════ --}}
    <section class="relative min-h-screen flex items-center justify-center overflow-hidden pt-24 md:pt-28"
        x-data="{
            images: @js($heroImages),
            current: 0,
            next: 0,
            fading: false,
            intervalMs: 7000,
            fadeMs: 1000,
            init() {
                if (this.images.length < 2) return;

                setInterval(() => {
                    this.next = (this.current + 1) % this.images.length;
                    this.fading = true;

                    setTimeout(() => {
                        this.current = this.next;
                        this.fading = false;
                    }, this.fadeMs);
                }, this.intervalMs);
            }
        }">

        {{-- Animated Background with Ken Burns Effect --}}
        <div class="absolute inset-0">
            @if($hasHeroImages)
            <div class="absolute inset-0 animate-ken-burns bg-cover bg-center bg-no-repeat transition-opacity duration-1000"
                :class="fading ? 'opacity-0' : 'opacity-100'"
                :style="`background-image: url('${images[current]}');`">
            </div>
            <div class="absolute inset-0 animate-ken-burns bg-cover bg-center bg-no-repeat transition-opacity duration-1000"
                :class="fading ? 'opacity-100' : 'opacity-0'"
                :style="`background-image: url('${images[next]}');`">
            </div>
            @else
            <div class="w-full h-full bg-gradient-to-br from-stone-900 via-primary-900 to-amber-900 animate-ken-burns"></div>
            @endif
            <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/40 to-black/70"></div>

            {{-- Animated Particles --}}
            <div class="absolute inset-0 overflow-hidden pointer-events-none">
                <div class="absolute top-1/4 left-1/4 w-2 h-2 bg-gold-400/30 rounded-full animate-float blur-sm"></div>
                <div class="absolute top-1/3 right-1/3 w-3 h-3 bg-primary-400/20 rounded-full animate-float blur-sm" style="animation-delay: 1s"></div>
                <div class="absolute bottom-1/3 left-1/2 w-2 h-2 bg-white/20 rounded-full animate-float blur-sm" style="animation-delay: 2s"></div>
            </div>
        </div>

        {{-- Hero Content --}}
        <div class="relative z-10 max-w-6xl mx-auto px-4 text-center">

            {{-- Animated Badge --}}
            <div class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full
                        bg-white/10 backdrop-blur-md border border-white/20
                        text-white/90 text-sm font-medium mb-8
                        hover:bg-white/20 transition-all duration-300 cursor-default
                        animate-fade-in-up">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-green-400"></span>
                </span>
                <span class="bg-gradient-to-r from-white to-white/80 bg-clip-text text-transparent">
                    Platform Resep #1 di Indonesia
                </span>
            </div>

            {{-- Enhanced Headline with Better Typography --}}
            <h1 class="font-display text-5xl md:text-7xl lg:text-8xl xl:text-9xl
                       font-bold text-white leading-[0.9] mb-8
                       animate-fade-in-up tracking-tight"
                style="animation-delay: 0.1s">
                <span class="block mb-2">Masak Lebih</span>
                <span class="block italic bg-clip-text text-transparent bg-gradient-to-r from-gold-300 via-gold-400 to-amber-300 drop-shadow-2xl">
                    Berselera
                </span>
            </h1>

            {{-- Improved Subtitle --}}
            <p class="text-lg md:text-xl lg:text-2xl text-white/70 max-w-2xl mx-auto mb-12 leading-relaxed
                      font-light animate-fade-in-up"
                style="animation-delay: 0.2s">
                Temukan ribuan resep masakan Nusantara yang lezat,
                <span class="text-white font-medium">mudah dipraktikkan</span>,
                dan sudah teruji oleh para chef berbakat.
            </p>

            {{-- Enhanced Search Bar with Glassmorphism --}}
            <div class="max-w-3xl mx-auto mb-12 animate-fade-in-up" style="animation-delay: 0.3s">
                <form action="{{ route('search') }}" method="GET"
                    class="relative group">
                    <div class="absolute -inset-0.5 bg-gradient-to-r from-primary-500 to-gold-500 rounded-2xl blur opacity-30 group-hover:opacity-50 transition duration-500"></div>
                    <div class="relative flex gap-2 p-2 bg-white/10 backdrop-blur-xl
                                rounded-2xl border border-white/20 shadow-2xl
                                hover:bg-white/15 transition-all duration-300">
                        <div class="flex-1 flex items-center gap-3 px-4">
                            <svg class="w-5 h-5 text-white/60 flex-shrink-0 group-focus-within:text-primary-400 transition-colors"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0" />
                            </svg>
                            <input type="text"
                                name="q"
                                placeholder="Cari resep, bahan, atau kategori..."
                                class="flex-1 bg-transparent text-white placeholder-white/40
                                       text-base focus:outline-none py-3" />
                        </div>
                        <button type="submit"
                            class="px-8 py-3 bg-gradient-to-r from-primary-500 to-primary-600
                                   hover:from-primary-600 hover:to-primary-700
                                   text-white font-bold rounded-xl text-sm
                                   shadow-lg hover:shadow-primary-500/25 hover:-translate-y-0.5
                                   transition-all duration-300 flex-shrink-0">
                            Cari
                        </button>
                    </div>
                </form>

                {{-- Quick Tags with Better Hover Effects --}}
                <div class="flex flex-wrap justify-center gap-3 mt-6">
                    @foreach(['Rendang', 'Soto', 'Gado-gado', 'Nasi Goreng', 'Sate'] as $tag)
                    <a href="{{ route('search', ['q' => $tag]) }}"
                        class="group px-4 py-2 rounded-full bg-white/5 hover:bg-white/20
                               text-white/70 hover:text-white text-sm font-medium 
                               border border-white/10 hover:border-white/30
                               backdrop-blur-sm transition-all duration-300
                               hover:-translate-y-1 hover:shadow-lg">
                        <span class="group-hover:scale-110 inline-block transition-transform">{{ $tag }}</span>
                    </a>
                    @endforeach
                </div>
            </div>

            {{-- Enhanced CTA Buttons --}}
            <div class="flex flex-wrap justify-center gap-4 animate-fade-in-up"
                style="animation-delay: 0.4s">
                <a href="{{ route('search') }}"
                    class="group relative px-8 py-4 rounded-2xl
                           bg-white text-primary-700 font-bold text-sm
                           shadow-2xl hover:shadow-white/25 hover:-translate-y-1
                           transition-all duration-300 overflow-hidden">
                    <span class="relative z-10 flex items-center gap-2">
                        Jelajahi Resep
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform duration-200"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </span>
                    <div class="absolute inset-0 bg-gradient-to-r from-primary-50 to-white opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                </a>
                @guest
                <a href="{{ route('register') }}"
                    class="group relative px-8 py-4 rounded-2xl
                           bg-transparent border-2 border-white/30 hover:border-white
                           text-white font-bold text-sm overflow-hidden
                           hover:bg-white/10 hover:-translate-y-1
                           transition-all duration-300">
                    <span class="relative z-10 flex items-center gap-2">
                        <span class="group-hover:rotate-12 transition-transform duration-300">✨</span>
                        Bagikan Resepmu
                    </span>
                </a>
                @endguest
            </div>

            {{-- Enhanced Stats with Better Visual Hierarchy --}}
            <div class="grid grid-cols-3 gap-8 max-w-lg mx-auto mt-10
                        animate-fade-in-up pb-12" style="animation-delay: 0.5s">
                @foreach([
                ['value' => $totalRecipes, 'suffix' => '+', 'label' => 'Resep'],
                ['value' => $totalChefs, 'suffix' => '+', 'label' => 'Chef'],
                ['value' => '4.9', 'suffix' => '', 'label' => 'Rating'],
                ] as $stat)
                <div class="relative group">
                    <div class="absolute inset-0 bg-white/5 rounded-2xl blur-xl group-hover:bg-white/10 transition-colors duration-300"></div>
                    <div class="relative p-4">
                        <p class="font-display text-4xl md:text-5xl font-bold text-white mb-1">
                            {{ $stat['value'] }}{{ $stat['suffix'] }}
                        </p>
                        <p class="text-white/50 text-sm font-medium uppercase tracking-wider">{{ $stat['label'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Enhanced Scroll Indicator --}}
        <div class="absolute bottom-10 left-1/2 -translate-x-1/2 animate-bounce">
            <div class="w-7 h-12 rounded-full border-2 border-white/30
                        flex items-start justify-center pt-2 hover:border-white/50 transition-colors cursor-pointer">
                <div class="w-1.5 h-3 rounded-full bg-white/60 animate-pulse"></div>
            </div>
        </div>
    </section>


    {{-- ══════════════════════════════════════════════
         KATEGORI SECTION - Enhanced with 3D Cards
    ══════════════════════════════════════════════ --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-32 relative">

        {{-- Section Header with Better Typography --}}
        <div class="text-center mb-20">
            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary-100/80
                         text-primary-700 text-xs font-bold tracking-widest uppercase mb-6
                         border border-primary-200">
                <span class="w-1.5 h-1.5 rounded-full bg-primary-500"></span>
                Kategori Masakan
            </span>
            <h2 class="font-display text-4xl md:text-5xl lg:text-6xl font-bold text-stone-900 mb-6 tracking-tight">
                Temukan Sesuai
                <span class="relative inline-block">
                    <span class="relative z-10 gradient-text">Seleramu</span>
                    <svg class="absolute -bottom-2 left-0 w-full h-3 text-primary-200 -z-0" viewBox="0 0 100 10" preserveAspectRatio="none">
                        <path d="M0 5 Q 50 10 100 5" stroke="currentColor" stroke-width="8" fill="none" opacity="0.3" />
                    </svg>
                </span>
            </h2>
            <p class="text-stone-500 text-lg md:text-xl max-w-2xl mx-auto leading-relaxed">
                Dari appetizer hingga dessert, kami punya semua yang kamu butuhkan untuk memuaskan selera makanmu
            </p>
        </div>

        {{-- Enhanced Category Cards with 3D Effect --}}
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6 perspective-1000">
            @foreach($categories as $category)
            <a href="{{ route('category', $category->slug) }}"
                class="group relative overflow-hidden rounded-3xl aspect-square cursor-pointer
                       transform-gpu transition-all duration-500 hover:-translate-y-3 hover:scale-105
                       hover:shadow-2xl hover:shadow-primary-500/20"
                style="transform-style: preserve-3d;">

                {{-- Dynamic Background --}}
                <div class="absolute inset-0 transition-all duration-500 group-hover:scale-110"
                    style="background: linear-gradient(135deg, {{ $category->color }} 0%, {{ $category->color }}dd 100%)">
                </div>

                {{-- Pattern Overlay --}}
                <div class="absolute inset-0 opacity-10 group-hover:opacity-20 transition-opacity duration-500"
                    style="background-image: url('data:image/svg+xml,%3Csvg width=\'20\' height=\'20\' viewBox=\'0 0 20 20\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\' fill-rule=\'evenodd\'%3E%3Ccircle cx=\'3\' cy=\'3\' r=\'3\'/%3E%3Ccircle cx=\'13\' cy=\'13\' r=\'3\'/%3E%3C/g%3E%3C/svg%3E')">
                </div>

                {{-- Floating Decorative Elements --}}
                <div class="absolute -top-6 -right-6 w-24 h-24 rounded-full bg-white/10 blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
                <div class="absolute -bottom-8 -left-8 w-32 h-32 rounded-full bg-black/10 blur-2xl group-hover:scale-125 transition-transform duration-700"></div>

                {{-- Content --}}
                <div class="relative z-10 h-full flex flex-col items-center justify-center p-6 text-white text-center">
                    <div class="mb-4 transform transition-all duration-500 group-hover:scale-125 group-hover:-rotate-6 group-hover:mb-6">
                        <span class="text-6xl filter drop-shadow-lg">{{ $category->icon }}</span>
                    </div>
                    <h3 class="font-display font-bold text-lg leading-tight mb-2 group-hover:scale-105 transition-transform duration-300">
                        {{ $category->name }}
                    </h3>
                    <div class="px-4 py-1.5 rounded-full bg-white/20 backdrop-blur-sm
                                text-xs font-semibold opacity-0 group-hover:opacity-100
                                translate-y-4 group-hover:translate-y-0
                                transition-all duration-300 shadow-lg">
                        {{ $category->recipes_count }} resep
                    </div>
                </div>

                {{-- Shine Effect --}}
                <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-700 pointer-events-none"
                    style="background: linear-gradient(105deg, transparent 40%, rgba(255,255,255,0.2) 45%, rgba(255,255,255,0.3) 50%, rgba(255,255,255,0.2) 55%, transparent 60%)">
                </div>
            </a>
            @endforeach
        </div>
    </section>


    {{-- ══════════════════════════════════════════════
         FEATURED RECIPES SECTION - Dark Theme Enhancement
    ══════════════════════════════════════════════ --}}
    @if($featuredRecipes->count())
    <section class="relative py-32 overflow-hidden">
        {{-- Background with Texture --}}
        <div class="absolute inset-0 bg-gradient-to-br from-stone-950 via-primary-950 to-stone-900"></div>
        <div class="absolute inset-0 opacity-20" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.05\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-16 gap-6">
                <div>
                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-gold-500/10 border border-gold-500/20
                                 text-gold-400 text-xs font-bold tracking-widest uppercase mb-4">
                        <span class="text-gold-400">⭐</span>
                        Pilihan Editor
                    </span>
                    <h2 class="font-display text-4xl md:text-5xl lg:text-6xl font-bold text-white tracking-tight">
                        Resep
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-gold-400 via-amber-300 to-gold-500">
                            Unggulan
                        </span>
                    </h2>
                </div>
                <a href="{{ route('search', ['featured' => 1]) }}"
                    class="group flex items-center gap-2 text-sm font-medium text-primary-400
                           hover:text-primary-300 transition-colors duration-200">
                    <span class="border-b border-transparent group-hover:border-primary-400 transition-all">Lihat semua</span>
                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform duration-200"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </a>
            </div>

            {{-- Featured Grid with Hover Effects --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($featuredRecipes as $recipe)
                <div class="group transform hover:-translate-y-2 transition-all duration-500">
                    @include('components.recipe-card', [
                    'recipe' => $recipe,
                    'dark' => true,
                    'isFav' => in_array($recipe->id, $favoritedRecipeIds ?? [], true),
                    ])
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif


    {{-- ══════════════════════════════════════════════
         LATEST RECIPES SECTION - Clean & Modern
    ══════════════════════════════════════════════ --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-32">

        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-16 gap-6">
            <div>
                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary-100
                             text-primary-700 text-xs font-bold tracking-widest uppercase mb-4 border border-primary-200">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-primary-500"></span>
                    </span>
                    Baru Ditambahkan
                </span>
                <h2 class="font-display text-4xl md:text-5xl lg:text-6xl font-bold text-stone-900 tracking-tight">
                    Resep <span class="gradient-text">Terbaru</span>
                </h2>
            </div>
            <a href="{{ route('search') }}"
                class="group flex items-center gap-2 text-sm font-medium text-primary-600
                       hover:text-primary-700 transition-colors duration-200">
                <span class="border-b border-transparent group-hover:border-primary-600 transition-all">Lihat semua resep</span>
                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform duration-200"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
            </a>
        </div>

        {{-- Recipe Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @forelse($latestRecipes as $recipe)
            <div class="group transform hover:-translate-y-2 transition-all duration-500">
                @include('components.recipe-card', [
                'recipe' => $recipe,
                'isFav' => in_array($recipe->id, $favoritedRecipeIds ?? [], true),
                ])
            </div>
            @empty
            <div class="col-span-full text-center py-24 bg-stone-50 rounded-3xl border-2 border-dashed border-stone-200">
                <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-stone-100 flex items-center justify-center text-4xl">
                    🍳
                </div>
                <p class="text-stone-600 text-lg font-medium mb-2">
                    Belum ada resep yang tersedia
                </p>
                <p class="text-stone-400 text-sm mb-6">Jadilah yang pertama berbagi kreasi masakanmu!</p>
                @auth
                <a href="{{ route('submit-recipe') }}"
                    class="inline-flex items-center gap-2 px-6 py-3 rounded-xl
                           bg-primary-600 text-white font-semibold text-sm
                           hover:bg-primary-700 hover:shadow-lg hover:shadow-primary-500/25
                           transition-all duration-300">
                    <span>Bagikan Resep Pertama</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                </a>
                @endauth
            </div>
            @endforelse
        </div>
    </section>


    {{-- ══════════════════════════════════════════════
         POPULAR RECIPES SECTION - Ranked List Style
    ══════════════════════════════════════════════ --}}
    @if($popularRecipes->count())
    <section class="relative py-32 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-amber-50 via-orange-50 to-primary-50"></div>
        <div class="absolute top-0 left-0 w-full h-px bg-gradient-to-r from-transparent via-primary-200 to-transparent"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="text-center mb-16">
                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white
                             text-orange-600 text-xs font-bold tracking-widest uppercase mb-4 shadow-sm border border-orange-100">
                    <span class="text-lg">🔥</span>
                    Paling Banyak Dilihat
                </span>
                <h2 class="font-display text-4xl md:text-5xl lg:text-6xl font-bold text-stone-900 tracking-tight">
                    Resep <span class="gradient-text">Populer</span>
                </h2>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                @foreach($popularRecipes as $index => $recipe)
                <a href="{{ route('recipe.detail', $recipe->slug) }}"
                    class="group flex gap-5 bg-white rounded-3xl p-5
                           shadow-sm hover:shadow-2xl hover:-translate-y-1
                           border border-stone-100 hover:border-primary-100
                           transition-all duration-300 relative overflow-hidden">

                    {{-- Rank Badge --}}
                    <div class="flex-shrink-0">
                        <div class="w-14 h-14 rounded-2xl flex items-center justify-center
                                    font-display font-bold text-xl shadow-lg
                                    {{ $index === 0 ? 'bg-gradient-to-br from-yellow-300 to-yellow-500 text-yellow-900 shadow-yellow-500/30' :
                                       ($index === 1 ? 'bg-gradient-to-br from-stone-200 to-stone-400 text-stone-700 shadow-stone-500/30' :
                                       ($index === 2 ? 'bg-gradient-to-br from-amber-600 to-amber-800 text-amber-100 shadow-amber-800/30' :
                                       'bg-gradient-to-br from-primary-100 to-primary-200 text-primary-700 shadow-primary-500/30')) }}">
                            {{ $index + 1 }}
                        </div>
                    </div>

                    {{-- Thumbnail --}}
                    <div class="w-24 h-24 rounded-2xl overflow-hidden flex-shrink-0 shadow-md group-hover:shadow-xl transition-shadow duration-300">
                        <img src="{{ $recipe->thumbnail_url }}"
                            alt="{{ $recipe->title }}"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" />
                    </div>

                    {{-- Info --}}
                    <div class="flex-1 min-w-0 flex flex-col justify-center">
                        <span class="text-xs font-bold text-primary-500 uppercase tracking-wider mb-1">
                            {{ $recipe->category->name ?? 'Umum' }}
                        </span>
                        <h3 class="font-display font-bold text-xl text-stone-900 mb-2
                                   group-hover:text-primary-700 transition-colors duration-200
                                   line-clamp-1">
                            {{ $recipe->title }}
                        </h3>
                        <div class="flex items-center gap-4 text-sm text-stone-500">
                            <span class="flex items-center gap-1.5 bg-stone-50 px-2 py-1 rounded-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                {{ number_format($recipe->views_count) }}
                            </span>
                            <span class="flex items-center gap-1.5 bg-stone-50 px-2 py-1 rounded-lg">
                                <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                                </svg>
                                {{ $recipe->favorites_count }}
                            </span>
                            <span class="flex items-center gap-1.5 bg-stone-50 px-2 py-1 rounded-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ $recipe->total_time }} mnt
                            </span>
                        </div>
                    </div>

                    {{-- Hover Arrow --}}
                    <div class="absolute right-5 top-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 translate-x-2 group-hover:translate-x-0 transition-all duration-300">
                        <div class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center text-primary-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif


    {{-- ══════════════════════════════════════════════
         CTA SECTION - Enhanced Visual Impact
    ══════════════════════════════════════════════ --}}
    @guest
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
        <div class="relative overflow-hidden rounded-[3rem]
                    bg-gradient-to-br from-primary-600 via-primary-700 to-primary-900
                    p-12 md:p-20 text-center shadow-2xl shadow-primary-500/20">

            {{-- Animated Background Elements --}}
            <div class="absolute top-0 right-0 w-[500px] h-[500px] rounded-full
                        bg-white/5 -translate-y-1/2 translate-x-1/2 blur-3xl animate-pulse"></div>
            <div class="absolute bottom-0 left-0 w-[400px] h-[400px] rounded-full
                        bg-primary-500/20 translate-y-1/2 -translate-x-1/2 blur-3xl animate-pulse" style="animation-delay: 1s"></div>

            {{-- Grid Pattern --}}
            <div class="absolute inset-0 opacity-10"
                style="background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0);
                       background-size: 40px 40px;"></div>

            {{-- Floating Elements --}}
            <div class="absolute top-10 left-10 text-6xl animate-bounce" style="animation-duration: 3s">🥘</div>
            <div class="absolute bottom-10 right-10 text-6xl animate-bounce" style="animation-duration: 4s; animation-delay: 1s">🍜</div>
            <div class="absolute top-1/2 right-20 text-4xl animate-pulse" style="animation-duration: 2s">✨</div>

            <div class="relative z-10 max-w-3xl mx-auto">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-white/10 backdrop-blur-sm mb-8 border border-white/20 shadow-xl">
                    <span class="text-4xl">👨‍🍳</span>
                </div>

                <h2 class="font-display text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-6 tracking-tight">
                    Punya Resep Andalan?
                </h2>
                <p class="text-primary-100 text-lg md:text-xl max-w-2xl mx-auto mb-10 leading-relaxed">
                    Bagikan kreasi masakanmu kepada jutaan pecinta kuliner Indonesia.
                    Daftar gratis dan mulai berbagi pengalaman memasakmu sekarang!
                </p>

                <div class="flex flex-wrap justify-center gap-4">
                    <a href="{{ route('register') }}"
                        class="group relative px-8 py-4 rounded-2xl
                               bg-white text-primary-700 font-bold text-base
                               shadow-2xl hover:shadow-white/25 hover:-translate-y-1
                               transition-all duration-300 overflow-hidden">
                        <span class="relative z-10 flex items-center gap-2">
                            <span class="group-hover:rotate-12 transition-transform duration-300">✨</span>
                            Mulai Berbagi Gratis
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </span>
                        <div class="absolute inset-0 bg-gradient-to-r from-primary-50 to-white opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </a>
                    <a href="{{ route('search') }}"
                        class="px-8 py-4 rounded-2xl border-2 border-white/30
                               hover:border-white text-white font-semibold text-base
                               hover:bg-white/10 hover:-translate-y-1 backdrop-blur-sm
                               transition-all duration-300">
                        Jelajahi Dulu
                    </a>
                </div>

                {{-- Trust Indicators --}}
                <div class="mt-12 pt-8 border-t border-white/10 flex flex-wrap justify-center gap-8 text-primary-200 text-sm">
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Gratis Selamanya
                    </span>
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Mudah Digunakan
                    </span>
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Komunitas Aktif
                    </span>
                </div>
            </div>
        </div>
    </section>
    @endguest

    {{-- Custom Animations CSS --}}
    <style>
        @keyframes ken-burns {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }

            100% {
                transform: scale(1);
            }
        }

        .animate-ken-burns {
            animation: ken-burns 20s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes fade-in-up {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-up {
            animation: fade-in-up 0.8s ease-out forwards;
            opacity: 0;
        }

        .perspective-1000 {
            perspective: 1000px;
        }

        .line-clamp-1 {
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</div>
