<div class="min-h-screen bg-stone-50">

    {{-- ══════════════════════════════════════════════
         HERO SECTION WITH IMAGE
    ══════════════════════════════════════════════ --}}
    <section class="relative h-[70vh] min-h-[500px] overflow-hidden">
        {{-- Background Image --}}
        <img src="{{ $recipe->thumbnail_url }}"
            alt="{{ $recipe->title }}"
            class="absolute inset-0 w-full h-full object-cover" />

        {{-- Overlay Gradient --}}
        <div class="absolute inset-0 bg-gradient-to-t from-black via-black/60 to-transparent"></div>

        {{-- Content --}}
        <div class="absolute inset-0 flex items-end">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16 w-full">

                {{-- Breadcrumb --}}
                <nav class="flex items-center gap-2 text-sm text-white/80 mb-6">
                    <a href="{{ route('home') }}" class="hover:text-white transition-colors duration-200">
                        Beranda
                    </a>
                    <span>/</span>
                    <a href="{{ route('search') }}" class="hover:text-white transition-colors duration-200">
                        Resep
                    </a>
                    @if($recipe->category)
                    <span>/</span>
                    <a href="{{ route('category', $recipe->category->slug) }}"
                        class="hover:text-white transition-colors duration-200">
                        {{ $recipe->category->name }}
                    </a>
                    @endif
                </nav>

                {{-- Title & Meta --}}
                <div class="max-w-4xl">
                    {{-- Category Badge --}}
                    @if($recipe->category)
                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full
                                     bg-white/10 backdrop-blur border border-white/20
                                     text-white text-sm font-semibold mb-4">
                        {{ $recipe->category->icon }} {{ $recipe->category->name }}
                    </span>
                    @endif

                    {{-- Title --}}
                    <h1 class="font-display text-4xl md:text-5xl lg:text-6xl
                               font-bold text-white leading-tight mb-6">
                        {{ $recipe->title }}
                    </h1>

                    {{-- Author & Stats --}}
                    <div class="flex flex-wrap items-center gap-6">
                        {{-- Author --}}
                        <div class="flex items-center gap-3">
                            <img src="{{ $recipe->user->avatar_url }}"
                                alt="{{ $recipe->user->name }}"
                                class="w-12 h-12 rounded-full object-cover ring-2 ring-white/50" />
                            <div>
                                <p class="text-white font-semibold text-sm">
                                    {{ $recipe->user->name }}
                                </p>
                                <p class="text-white/70 text-xs">
                                    Chef • {{ $recipe->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>

                        {{-- Stats --}}
                        <div class="flex items-center gap-4 text-white/90 text-sm">
                            <span class="flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                {{ number_format($recipe->views_count) }} dilihat
                            </span>
                            <span class="flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                                </svg>
                                {{ $recipe->favorites_count }} favorit
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="absolute bottom-6 right-6 flex gap-3">
            <button wire:click="toggleFavorite"
                class="flex items-center gap-2 px-5 py-3 rounded-xl
                           glass border border-white/30 text-white font-semibold text-sm
                           hover:bg-white/20 hover:scale-105
                           transition-all duration-200">
                <svg class="w-5 h-5 {{ auth()->check() && auth()->user()->hasFavorited($recipe) ? 'fill-red-500 text-red-500' : 'fill-transparent' }}"
                    stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
                {{ auth()->check() && auth()->user()->hasFavorited($recipe) ? 'Hapus dari Favorit' : 'Simpan' }}
            </button>
        </div>
    </section>


    {{-- ══════════════════════════════════════════════
         QUICK INFO CARDS
    ══════════════════════════════════════════════ --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-16 relative z-10 mb-16">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach([
            ['icon' => '⏱️', 'label' => 'Persiapan', 'value' => $recipe->prep_time . ' mnt'],
            ['icon' => '🔥', 'label' => 'Memasak', 'value' => $recipe->cook_time . ' mnt'],
            ['icon' => '🍽️', 'label' => 'Porsi', 'value' => $recipe->servings . ' orang'],
            ['icon' => '📊', 'label' => 'Tingkat', 'value' => ucfirst($recipe->difficulty)],
            ] as $info)
            <div class="bg-white rounded-2xl p-5 shadow-lg border border-stone-100
                            hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                <span class="text-3xl block mb-2">{{ $info['icon'] }}</span>
                <p class="text-xs text-stone-500 uppercase tracking-wider mb-1">
                    {{ $info['label'] }}
                </p>
                <p class="font-display font-bold text-stone-900 text-lg">
                    {{ $info['value'] }}
                </p>
            </div>
            @endforeach
        </div>
    </section>


    {{-- ══════════════════════════════════════════════
         MAIN CONTENT
    ══════════════════════════════════════════════ --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-24">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

            {{-- ═══ LEFT COLUMN (Main Content) ═══ --}}
            <div class="lg:col-span-2 space-y-10">

                {{-- Description --}}
                <section class="bg-white rounded-3xl p-8 shadow-md border border-stone-100">
                    <h2 class="font-display text-2xl font-bold text-stone-900 mb-4
                               flex items-center gap-3">
                        📖 Deskripsi
                    </h2>
                    <p class="text-stone-600 leading-relaxed">
                        {{ $recipe->description }}
                    </p>
                </section>

                {{-- Tags --}}
                @if($recipe->tags->count())
                <section class="bg-gradient-to-r from-primary-50 to-orange-50
                                    rounded-3xl p-8 border border-primary-100">
                    <h3 class="font-display text-xl font-bold text-stone-900 mb-4
                                   flex items-center gap-3">
                        🏷️ Tag Resep
                    </h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($recipe->tags as $tag)
                        <span class="badge bg-white text-primary-700 border border-primary-200
                                             hover:bg-primary-100 transition-colors duration-200">
                            {{ $tag->name }}
                        </span>
                        @endforeach
                    </div>
                </section>
                @endif

                {{-- Ingredients --}}
                <section class="bg-white rounded-3xl p-8 shadow-md border border-stone-100">
                    <h2 class="font-display text-2xl font-bold text-stone-900 mb-6
                               flex items-center gap-3">
                        🥘 Bahan-Bahan
                        <span class="text-sm font-normal text-stone-500">
                            ({{ $recipe->ingredients->count() }} item)
                        </span>
                    </h2>
                    <div class="space-y-3">
                        @foreach($recipe->ingredients as $ingredient)
                        <div class="flex items-start gap-3 p-3 rounded-xl
                                        hover:bg-stone-50 transition-colors duration-200">
                            <div class="w-2 h-2 rounded-full bg-primary-500 mt-2 flex-shrink-0"></div>
                            <div class="flex-1">
                                <p class="text-stone-900 font-medium">
                                    @if($ingredient->amount || $ingredient->unit)
                                    <span class="text-primary-600 font-bold">
                                        {{ $ingredient->amount }} {{ $ingredient->unit }}
                                    </span>
                                    @endif
                                    {{ $ingredient->name }}
                                </p>
                                @if($ingredient->notes)
                                <p class="text-stone-500 text-sm mt-0.5">
                                    {{ $ingredient->notes }}
                                </p>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </section>

                {{-- Steps --}}
                <section class="bg-white rounded-3xl p-8 shadow-md border border-stone-100">
                    <h2 class="font-display text-2xl font-bold text-stone-900 mb-6
                               flex items-center gap-3">
                        👨‍🍳 Langkah-Langkah
                    </h2>
                    <div class="space-y-6">
                        @foreach($recipe->steps as $step)
                        <div class="flex gap-5">
                            {{-- Step Number --}}
                            <div class="flex-shrink-0 w-12 h-12 rounded-2xl
                                            bg-gradient-to-br from-primary-500 to-primary-600
                                            flex items-center justify-center
                                            shadow-lg shadow-primary-200">
                                <span class="font-display font-bold text-white text-lg">
                                    {{ $step->step_number }}
                                </span>
                            </div>

                            {{-- Step Content --}}
                            <div class="flex-1">
                                <p class="text-stone-700 leading-relaxed mb-3">
                                    {{ $step->instruction }}
                                </p>

                                @if($step->duration)
                                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full
                                                    bg-amber-50 border border-amber-200 text-amber-800 text-xs font-medium">
                                    ⏱️ {{ $step->duration }} menit
                                </div>
                                @endif

                                @if($step->image)
                                <img src="{{ $step->image_url }}"
                                    alt="Langkah {{ $step->step_number }}"
                                    class="mt-3 rounded-2xl w-full max-w-md object-cover shadow-md" />
                                @endif
                            </div>
                        </div>

                        @if(!$loop->last)
                        <div class="border-t border-stone-100 my-6"></div>
                        @endif
                        @endforeach
                    </div>
                </section>

                {{-- Additional Images Gallery --}}
                @if($recipe->images->count())
                <section class="bg-white rounded-3xl p-8 shadow-md border border-stone-100">
                    <h2 class="font-display text-2xl font-bold text-stone-900 mb-6
                                   flex items-center gap-3">
                        📸 Galeri Foto
                    </h2>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach($recipe->images as $image)
                        <div class="aspect-square rounded-2xl overflow-hidden
                                            shadow-md hover:shadow-xl hover:scale-105
                                            transition-all duration-300 cursor-pointer">
                            <img src="{{ $image->url }}"
                                alt="Foto {{ $loop->iteration }}"
                                class="w-full h-full object-cover" />
                        </div>
                        @endforeach
                    </div>
                </section>
                @endif

                {{-- Comments Section --}}
                <section class="bg-white rounded-3xl p-8 shadow-md border border-stone-100">
                    <h2 class="font-display text-2xl font-bold text-stone-900 mb-6
                               flex items-center gap-3">
                        💬 Komentar
                        <span class="text-sm font-normal text-stone-500">
                            ({{ $recipe->comments->count() }})
                        </span>
                    </h2>

                    {{-- Comment Form --}}
                    @auth
                    <form wire:submit.prevent="postComment" class="mb-8">
                        <div class="flex gap-3">
                            <img src="{{ auth()->user()->avatar_url }}"
                                alt="{{ auth()->user()->name }}"
                                class="w-10 h-10 rounded-full object-cover flex-shrink-0" />
                            <div class="flex-1">
                                <textarea wire:model="commentContent"
                                    placeholder="Tulis komentar kamu..."
                                    rows="3"
                                    class="w-full px-4 py-3 rounded-xl border border-stone-200
                                                     focus:ring-2 focus:ring-primary-500 focus:border-primary-500
                                                     resize-none text-sm transition-all duration-200"></textarea>
                                @error('commentContent')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                                <div class="flex justify-end mt-2">
                                    <button type="submit"
                                        wire:loading.attr="disabled"
                                        class="px-5 py-2.5 rounded-xl bg-primary-600 text-white
                                                       font-semibold text-sm hover:bg-primary-700
                                                       disabled:opacity-50 disabled:cursor-not-allowed
                                                       transition-all duration-200">
                                        <span wire:loading.remove>Kirim Komentar</span>
                                        <span wire:loading>Mengirim...</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                    @else
                    <div class="bg-stone-50 rounded-2xl p-6 text-center border border-stone-200 mb-8">
                        <p class="text-stone-600 mb-3">
                            Silakan login untuk berkomentar
                        </p>
                        <a href="{{ route('login') }}"
                            class="inline-block px-6 py-2.5 rounded-xl bg-primary-600
                                      text-white font-semibold text-sm hover:bg-primary-700
                                      transition-all duration-200">
                            Login Sekarang
                        </a>
                    </div>
                    @endguest

                    {{-- Comments List --}}
                    <div class="space-y-6">
                        @forelse($recipe->comments as $comment)
                        <div class="flex gap-3">
                            <img src="{{ $comment->user->avatar_url }}"
                                alt="{{ $comment->user->name }}"
                                class="w-10 h-10 rounded-full object-cover flex-shrink-0" />
                            <div class="flex-1">
                                <div class="bg-stone-50 rounded-2xl p-4">
                                    <div class="flex items-center gap-2 mb-2">
                                        <p class="font-semibold text-stone-900 text-sm">
                                            {{ $comment->user->name }}
                                        </p>
                                        <span class="text-stone-400 text-xs">•</span>
                                        <p class="text-stone-500 text-xs">
                                            {{ $comment->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                    <p class="text-stone-700 text-sm leading-relaxed">
                                        {{ $comment->content }}
                                    </p>
                                </div>

                                {{-- Replies --}}
                                @if($comment->replies->count())
                                <div class="ml-6 mt-3 space-y-3">
                                    @foreach($comment->replies as $reply)
                                    <div class="flex gap-3">
                                        <img src="{{ $reply->user->avatar_url }}"
                                            alt="{{ $reply->user->name }}"
                                            class="w-8 h-8 rounded-full object-cover flex-shrink-0" />
                                        <div class="flex-1">
                                            <div class="bg-white rounded-xl p-3 border border-stone-200">
                                                <div class="flex items-center gap-2 mb-1">
                                                    <p class="font-semibold text-stone-900 text-xs">
                                                        {{ $reply->user->name }}
                                                    </p>
                                                    <span class="text-stone-400 text-xs">•</span>
                                                    <p class="text-stone-500 text-xs">
                                                        {{ $reply->created_at->diffForHumans() }}
                                                    </p>
                                                </div>
                                                <p class="text-stone-700 text-xs leading-relaxed">
                                                    {{ $reply->content }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-12">
                            <span class="text-4xl block mb-3">💬</span>
                            <p class="text-stone-500">
                                Belum ada komentar. Jadilah yang pertama!
                            </p>
                        </div>
                        @endforelse
                    </div>
                </section>
            </div>


            {{-- ═══ RIGHT SIDEBAR ═══ --}}
            <aside class="lg:col-span-1 space-y-6">

                {{-- Author Card --}}
                <div class="bg-white rounded-3xl p-6 shadow-md border border-stone-100 sticky top-24">
                    <div class="text-center mb-6">
                        <img src="{{ $recipe->user->avatar_url }}"
                            alt="{{ $recipe->user->name }}"
                            class="w-24 h-24 rounded-full object-cover mx-auto mb-4
                                    ring-4 ring-primary-100" />
                        <h3 class="font-display font-bold text-stone-900 text-lg mb-1">
                            {{ $recipe->user->name }}
                        </h3>
                        @if($recipe->user->bio)
                        <p class="text-stone-500 text-sm">
                            {{ Str::limit($recipe->user->bio, 80) }}
                        </p>
                        @endif
                    </div>

                    <div class="grid grid-cols-2 gap-4 py-4 border-y border-stone-100">
                        <div class="text-center">
                            <p class="font-display font-bold text-2xl text-primary-600">
                                {{ $recipe->user->recipes()->approved()->count() }}
                            </p>
                            <p class="text-stone-500 text-xs">Resep</p>
                        </div>
                        <div class="text-center">
                            <p class="font-display font-bold text-2xl text-primary-600">
                                {{ $recipe->user->recipes()->approved()->sum('views_count') }}
                            </p>
                            <p class="text-stone-500 text-xs">Total Views</p>
                        </div>
                    </div>

                    <a href="{{ route('search', ['author' => $recipe->user->id]) }}"
                        class="block mt-4 px-5 py-3 rounded-xl text-center
                              bg-gradient-to-r from-primary-500 to-primary-600
                              text-white font-semibold text-sm
                              hover:from-primary-600 hover:to-primary-700
                              shadow-md hover:shadow-lg hover:-translate-y-0.5
                              transition-all duration-200">
                        Lihat Semua Resep
                    </a>
                </div>
            </aside>
        </div>
    </div>


    {{-- ══════════════════════════════════════════════
         RELATED RECIPES
    ══════════════════════════════════════════════ --}}
    @if($relatedRecipes->count())
    <section class="bg-gradient-to-r from-stone-100 to-stone-50 py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10">
                <h2 class="font-display text-3xl md:text-4xl font-bold text-stone-900 mb-3">
                    Resep <span class="gradient-text">Serupa</span>
                </h2>
                <p class="text-stone-500">
                    Resep lain yang mungkin kamu sukai
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($relatedRecipes as $related)
                @include('components.recipe-card', ['recipe' => $related])
                @endforeach
            </div>
        </div>
    </section>
    @endif
</div>