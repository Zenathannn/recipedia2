@props([
'recipe',
'dark' => false,
'isFav' => null,
])

@php
$isFav = $isFav ?? (auth()->check() && auth()->user()->hasFavorited($recipe));
@endphp

<article class="recipe-card group relative overflow-hidden rounded-3xl
                {{ $dark
                    ? 'bg-stone-800 border border-stone-700/50'
                    : 'bg-white border border-stone-100' }}
                shadow-md hover:shadow-2xl cursor-pointer">

    {{-- ── Thumbnail ───────────────────────────────── --}}
    <div class="relative overflow-hidden aspect-[4/3]">
        <a href="{{ route('recipe.detail', $recipe->slug) }}" class="block h-full">
            <img src="{{ $recipe->thumbnail_url }}"
                alt="{{ $recipe->title }}"
                loading="lazy"
                class="w-full h-full object-cover
                        group-hover:scale-110 transition-transform duration-700" />
        </a>

        {{-- Gradient overlay --}}
        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent
                    opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none">
        </div>

        {{-- Badges top-left --}}
        <div class="absolute top-3 left-3 flex gap-2">
            @if($recipe->is_featured)
            <span class="badge bg-gold-400 text-stone-900">⭐ Unggulan</span>
            @endif
            <span class="badge
                         {{ $recipe->difficulty === 'mudah'  ? 'bg-green-500 text-white'  :
                            ($recipe->difficulty === 'sedang' ? 'bg-yellow-500 text-white' :
                            'bg-red-500 text-white') }}">
                {{ ucfirst($recipe->difficulty) }}
            </span>
        </div>

        {{-- Favorite Button --}}
        <button wire:click="toggleFavorite({{ $recipe->id }})"
            wire:loading.attr="disabled"
            class="absolute top-3 right-3 w-9 h-9 rounded-full
                       glass flex items-center justify-center
                       hover:scale-110 active:scale-95
                       transition-all duration-200 z-10">
            <svg class="w-4 h-4 transition-colors duration-200
                        {{ $isFav ? 'text-red-500 fill-red-500' : 'text-white fill-transparent stroke-white' }}"
                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682
                         a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318
                         a4.5 4.5 0 00-6.364 0z" />
            </svg>
        </button>

        {{-- Category badge bottom --}}
        @if($recipe->category)
        <div class="absolute bottom-3 left-3 opacity-0 group-hover:opacity-100
                        transition-all duration-300 translate-y-2 group-hover:translate-y-0">
            <span class="badge bg-white/20 backdrop-blur text-white border border-white/30">
                {{ $recipe->category->icon ?? '' }} {{ $recipe->category->name }}
            </span>
        </div>
        @endif
    </div>

    {{-- ── Card Body ───────────────────────────────── --}}
    <div class="p-5">

        {{-- Author + Time --}}
        <div class="flex items-center justify-between mb-3">
            <div class="flex items-center gap-2">
                <img src="{{ $recipe->user->avatar_url }}"
                    alt="{{ $recipe->user->name }}"
                    class="w-6 h-6 rounded-full object-cover ring-1 ring-primary-200" />
                <span class="text-xs font-medium
                             {{ $dark ? 'text-stone-400' : 'text-stone-500' }}">
                    {{ Str::limit($recipe->user->name, 16) }}
                </span>
            </div>
            <span class="text-xs {{ $dark ? 'text-stone-500' : 'text-stone-400' }}">
                ⏱️ {{ $recipe->total_time }} mnt
            </span>
        </div>

        {{-- Title --}}
        <a href="{{ route('recipe.detail', $recipe->slug) }}">
            <h3 class="font-display font-bold text-base leading-snug mb-2
                       {{ $dark
                           ? 'text-white group-hover:text-primary-400'
                           : 'text-stone-900 group-hover:text-primary-700' }}
                       transition-colors duration-200 line-clamp-2">
                {{ $recipe->title }}
            </h3>
        </a>

        {{-- Description --}}
        <p class="text-xs leading-relaxed mb-4 line-clamp-2
                  {{ $dark ? 'text-stone-400' : 'text-stone-500' }}">
            {{ $recipe->description }}
        </p>

        {{-- Tags --}}
        @if($recipe->tags->count())
        <div class="flex flex-wrap gap-1.5 mb-4">
            @foreach($recipe->tags->take(3) as $tag)
            <span class="badge
                                 {{ $dark
                                     ? 'bg-stone-700 text-stone-300'
                                     : 'bg-stone-100 text-stone-600' }}">
                {{ $tag->name }}
            </span>
            @endforeach
        </div>
        @endif

        {{-- Footer Stats --}}
        <div class="flex items-center justify-between pt-3
                    border-t {{ $dark ? 'border-stone-700' : 'border-stone-100' }}">
            <div class="flex items-center gap-3 text-xs
                        {{ $dark ? 'text-stone-500' : 'text-stone-400' }}">
                <span class="flex items-center gap-1">
                    👁️ {{ number_format($recipe->views_count) }}
                </span>
                <span class="flex items-center gap-1">
                    ❤️ {{ $recipe->favorites_count ?? 0 }}
                </span>
            </div>
            <a href="{{ route('recipe.detail', $recipe->slug) }}"
                class="flex items-center gap-1 text-xs font-semibold
                      text-primary-600 hover:text-primary-700
                      transition-colors duration-200 group/link">
                Lihat Resep
                <svg class="w-3 h-3 group-hover/link:translate-x-0.5 transition-transform duration-200"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
            </a>
        </div>
    </div>
</article>
