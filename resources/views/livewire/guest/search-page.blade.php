<div class="min-h-screen pt-24 pb-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- ══ Header Section ═══════════════════════ --}}
        <div class="mb-10">
            <h1 class="font-display text-4xl md:text-5xl font-bold text-stone-900 mb-3">
                Cari <span class="gradient-text">Resep Favoritmu</span>
            </h1>
            <p class="text-stone-500 text-lg">
                Temukan dari {{ $recipes->total() }} resep pilihan terbaik
            </p>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">

            {{-- ══ SIDEBAR FILTERS ══════════════════ --}}
            <aside class="lg:w-72 flex-shrink-0">

                {{-- Mobile Toggle --}}
                <button @click="$wire.showFilters = !$wire.showFilters"
                    class="lg:hidden w-full flex items-center justify-between
                               px-4 py-3 rounded-xl bg-white border border-stone-200
                               text-stone-700 font-semibold text-sm mb-4">
                    <span>🔍 Filter & Urutkan</span>
                    <svg class="w-5 h-5" :class="{ 'rotate-180': $wire.showFilters }"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div x-show="$wire.showFilters || window.innerWidth >= 1024"
                    x-transition
                    class="space-y-6">

                    {{-- Search Box --}}
                    <div class="bg-white rounded-2xl border border-stone-200 p-5">
                        <label class="block text-sm font-semibold text-stone-700 mb-3">
                            🔍 Kata Kunci
                        </label>
                        <input type="text"
                            wire:model.live.debounce.500ms="search"
                            placeholder="Cari resep, bahan..."
                            class="w-full px-4 py-2.5 rounded-xl border border-stone-200
                                      focus:ring-2 focus:ring-primary-500 focus:border-primary-500
                                      text-sm transition-all duration-200" />
                    </div>

                    {{-- Sort --}}
                    <div class="bg-white rounded-2xl border border-stone-200 p-5">
                        <label class="block text-sm font-semibold text-stone-700 mb-3">
                            📊 Urutkan
                        </label>
                        <select wire:model.live="sortBy"
                            class="w-full px-4 py-2.5 rounded-xl border border-stone-200
                                       focus:ring-2 focus:ring-primary-500 focus:border-primary-500
                                       text-sm transition-all duration-200">
                            <option value="latest">Terbaru</option>
                            <option value="popular">Terpopuler</option>
                            <option value="views">Paling Banyak Dilihat</option>
                        </select>
                    </div>

                    {{-- Categories --}}
                    <div class="bg-white rounded-2xl border border-stone-200 p-5">
                        <label class="block text-sm font-semibold text-stone-700 mb-3">
                            🗂️ Kategori
                        </label>
                        <div class="space-y-2">
                            <button wire:click="$set('category', null)"
                                class="w-full text-left px-3 py-2 rounded-lg text-sm
                                           transition-all duration-200
                                           {{ !$category
                                               ? 'bg-primary-100 text-primary-700 font-semibold'
                                               : 'text-stone-600 hover:bg-stone-50' }}">
                                Semua Kategori
                            </button>
                            @foreach($categories as $cat)
                            <button wire:click="$set('category', '{{ $cat->slug }}')"
                                class="w-full text-left px-3 py-2 rounded-lg text-sm
                                               transition-all duration-200 flex items-center gap-2
                                               {{ $category === $cat->slug
                                                   ? 'bg-primary-100 text-primary-700 font-semibold'
                                                   : 'text-stone-600 hover:bg-stone-50' }}">
                                <span>{{ $cat->icon }}</span>
                                <span>{{ $cat->name }}</span>
                            </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Difficulty --}}
                    <div class="bg-white rounded-2xl border border-stone-200 p-5">
                        <label class="block text-sm font-semibold text-stone-700 mb-3">
                            ⚡ Tingkat Kesulitan
                        </label>
                        <div class="space-y-2">
                            @foreach([
                            ['value' => '', 'label' => 'Semua Level', 'color' => 'stone'],
                            ['value' => 'mudah', 'label' => '✅ Mudah', 'color' => 'green'],
                            ['value' => 'sedang', 'label' => '⚠️ Sedang', 'color' => 'yellow'],
                            ['value' => 'sulit', 'label' => '🔥 Sulit', 'color' => 'red'],
                            ] as $diff)
                            <button wire:click="$set('difficulty', '{{ $diff['value'] }}')"
                                class="w-full text-left px-3 py-2 rounded-lg text-sm
                                               transition-all duration-200
                                               {{ $difficulty === $diff['value']
                                                   ? 'bg-'.$diff['color'].'-100 text-'.$diff['color'].'-700 font-semibold'
                                                   : 'text-stone-600 hover:bg-stone-50' }}">
                                {{ $diff['label'] }}
                            </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Tags --}}
                    <div class="bg-white rounded-2xl border border-stone-200 p-5">
                        <label class="block text-sm font-semibold text-stone-700 mb-3">
                            🏷️ Tag
                        </label>
                        <div class="flex flex-wrap gap-2">
                            @foreach($allTags as $tag)
                            <button wire:click="toggleTag({{ $tag->id }})"
                                class="badge transition-all duration-200
                                               {{ in_array($tag->id, $tags)
                                                   ? 'bg-primary-600 text-white ring-2 ring-primary-300'
                                                   : 'bg-stone-100 text-stone-600 hover:bg-stone-200' }}">
                                {{ $tag->name }}
                            </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Clear Filters --}}
                    @if($hasFilters)
                    <button wire:click="clearFilters"
                        class="w-full px-4 py-3 rounded-xl bg-red-50 text-red-600
                                       hover:bg-red-100 font-semibold text-sm
                                       transition-all duration-200">
                        🗑️ Hapus Semua Filter
                    </button>
                    @endif
                </div>
            </aside>

            {{-- ══ MAIN CONTENT ═════════════════════ --}}
            <main class="flex-1 min-w-0">

                {{-- Active Filters --}}
                @if($hasFilters)
                <div class="bg-primary-50 border border-primary-200 rounded-2xl p-4 mb-6">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-sm font-semibold text-primary-900">
                            🔍 Filter Aktif:
                        </span>
                        <button wire:click="clearFilters"
                            class="text-xs text-primary-600 hover:text-primary-700
                                           font-medium">
                            Hapus semua
                        </button>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        @if($search)
                        <span class="badge bg-primary-600 text-white">
                            "{{ $search }}"
                            <button wire:click="$set('search', '')"
                                class="ml-1 hover:text-red-200">×</button>
                        </span>
                        @endif
                        @if($category)
                        <span class="badge bg-primary-600 text-white">
                            Kategori: {{ $categories->firstWhere('slug', $category)?->name }}
                            <button wire:click="$set('category', null)"
                                class="ml-1 hover:text-red-200">×</button>
                        </span>
                        @endif
                        @if($difficulty)
                        <span class="badge bg-primary-600 text-white">
                            {{ ucfirst($difficulty) }}
                            <button wire:click="$set('difficulty', '')"
                                class="ml-1 hover:text-red-200">×</button>
                        </span>
                        @endif
                        @foreach($tags as $tagId)
                        @php $tag = $allTags->firstWhere('id', $tagId); @endphp
                        @if($tag)
                        <span class="badge bg-primary-600 text-white">
                            {{ $tag->name }}
                            <button wire:click="toggleTag({{ $tagId }})"
                                class="ml-1 hover:text-red-200">×</button>
                        </span>
                        @endif
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Results Info --}}
                <div class="flex items-center justify-between mb-6">
                    <p class="text-sm text-stone-500">
                        Menampilkan
                        <span class="font-semibold text-stone-900">{{ $recipes->total() }}</span>
                        resep
                    </p>
                </div>

                {{-- Recipe Grid --}}
                <div wire:loading.class="opacity-50 pointer-events-none"
                    class="transition-opacity duration-300">

                    @if($recipes->count())
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
                        @foreach($recipes as $recipe)
                        @include('components.recipe-card', ['recipe' => $recipe])
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-8">
                        {{ $recipes->links('vendor.livewire.tailwind') }}
                    </div>
                    @else
                    <div class="text-center py-20">
                        <span class="text-6xl block mb-4">🔍</span>
                        <h3 class="font-display text-2xl font-bold text-stone-900 mb-2">
                            Tidak Ada Hasil
                        </h3>
                        <p class="text-stone-500 mb-6">
                            Coba ubah filter atau kata kunci pencarian
                        </p>
                        @if($hasFilters)
                        <button wire:click="clearFilters"
                            class="px-6 py-3 rounded-xl bg-primary-600
                                               text-white font-semibold text-sm
                                               hover:bg-primary-700 transition-all duration-200">
                            Hapus Filter
                        </button>
                        @endif
                    </div>
                    @endif
                </div>

                {{-- Loading Indicator --}}
                <div wire:loading
                    class="fixed inset-0 bg-black/20 backdrop-blur-sm z-50
                            flex items-center justify-center pointer-events-none">
                    <div class="bg-white rounded-2xl px-8 py-6 shadow-2xl flex items-center gap-3">
                        <div class="w-5 h-5 border-3 border-primary-600 border-t-transparent
                                    rounded-full animate-spin"></div>
                        <span class="font-semibold text-stone-700">Memuat...</span>
                    </div>
                </div>
            </main>
        </div>
    </div>
</div>