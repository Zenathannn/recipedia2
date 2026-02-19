<div class="min-h-screen pt-24 pb-16 bg-stone-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="mb-10">
            <h1 class="font-display text-4xl md:text-5xl font-bold text-stone-900 mb-3">
                Resep <span class="gradient-text">Favorit</span> ❤️
            </h1>
            <p class="text-stone-500 text-lg">
                Koleksi resep yang kamu simpan ({{ $favorites->total() }} resep)
            </p>
        </div>

        {{-- Content --}}
        @if($favorites->count())
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-10">
            @foreach($favorites as $recipe)
            <article class="recipe-card group relative overflow-hidden rounded-3xl
                                    bg-white border border-stone-100 shadow-md hover:shadow-2xl">

                {{-- Thumbnail --}}
                <div class="relative overflow-hidden aspect-[4/3]">
                    <a href="{{ route('recipe.detail', $recipe->slug) }}" class="block h-full">
                        <img src="{{ $recipe->thumbnail_url }}"
                            alt="{{ $recipe->title }}"
                            loading="lazy"
                            class="w-full h-full object-cover
                                            group-hover:scale-110 transition-transform duration-700" />
                    </a>

                    {{-- Remove Button --}}
                    <button wire:click="removeFavorite({{ $recipe->id }})"
                        wire:confirm="Hapus dari favorit?"
                        class="absolute top-3 right-3 w-9 h-9 rounded-full
                                           bg-red-500 text-white flex items-center justify-center
                                           hover:bg-red-600 hover:scale-110
                                           transition-all duration-200 z-10">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>

                    {{-- Category --}}
                    @if($recipe->category)
                    <div class="absolute bottom-3 left-3">
                        <span class="badge bg-white/20 backdrop-blur text-white border border-white/30">
                            {{ $recipe->category->icon }} {{ $recipe->category->name }}
                        </span>
                    </div>
                    @endif
                </div>

                {{-- Card Body --}}
                <div class="p-5">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <img src="{{ $recipe->user->avatar_url }}"
                                alt="{{ $recipe->user->name }}"
                                class="w-6 h-6 rounded-full object-cover ring-1 ring-primary-200" />
                            <span class="text-xs font-medium text-stone-500">
                                {{ Str::limit($recipe->user->name, 16) }}
                            </span>
                        </div>
                        <span class="text-xs text-stone-400">
                            ⏱️ {{ $recipe->total_time }} mnt
                        </span>
                    </div>

                    <a href="{{ route('recipe.detail', $recipe->slug) }}">
                        <h3 class="font-display font-bold text-base leading-snug mb-2
                                           text-stone-900 group-hover:text-primary-700
                                           transition-colors duration-200 line-clamp-2">
                            {{ $recipe->title }}
                        </h3>
                    </a>

                    <div class="flex items-center justify-between pt-3 border-t border-stone-100">
                        <div class="flex items-center gap-3 text-xs text-stone-400">
                            <span>👁️ {{ number_format($recipe->views_count) }}</span>
                            <span>❤️ {{ $recipe->favorites_count ?? 0 }}</span>
                        </div>
                        <a href="{{ route('recipe.detail', $recipe->slug) }}"
                            class="text-xs font-semibold text-primary-600 hover:text-primary-700">
                            Lihat →
                        </a>
                    </div>
                </div>
            </article>
            @endforeach
        </div>

        {{-- Pagination --}}
        {{ $favorites->links() }}
        @else
        <div class="text-center py-20">
            <span class="text-6xl block mb-4">💔</span>
            <h3 class="font-display text-2xl font-bold text-stone-900 mb-2">
                Belum Ada Favorit
            </h3>
            <p class="text-stone-500 mb-6">
                Mulai simpan resep favoritmu sekarang!
            </p>
            <a href="{{ route('search') }}"
                class="inline-block px-6 py-3 rounded-xl bg-primary-600
                          text-white font-semibold text-sm hover:bg-primary-700
                          transition-all duration-200">
                Jelajahi Resep
            </a>
        </div>
        @endif
    </div>
</div>