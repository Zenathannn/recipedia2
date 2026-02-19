<div class="min-h-screen pt-24 pb-16 bg-stone-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-10 gap-4">
            <div>
                <h1 class="font-display text-4xl md:text-5xl font-bold text-stone-900 mb-3">
                    Resep <span class="gradient-text">Saya</span>
                </h1>
                <p class="text-stone-500 text-lg">
                    Kelola semua resep yang kamu bagikan
                </p>
            </div>
            <a href="{{ route('submit-recipe') }}"
                class="flex items-center justify-center gap-2 px-6 py-3 rounded-xl
                      bg-gradient-to-r from-primary-500 to-primary-600
                      hover:from-primary-600 hover:to-primary-700
                      text-white font-semibold text-sm shadow-lg hover:shadow-xl
                      hover:-translate-y-0.5 transition-all duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4v16m8-8H4" />
                </svg>
                Buat Resep Baru
            </a>
        </div>

        {{-- Filter Tabs --}}
        <div class="bg-white rounded-2xl p-2 shadow-md border border-stone-100 mb-8
                    flex gap-2 overflow-x-auto">
            @foreach([
            ['value' => 'all', 'label' => 'Semua', 'count' => $counts['all']],
            ['value' => 'approved', 'label' => '✅ Aktif', 'count' => $counts['approved']],
            ['value' => 'pending', 'label' => '⏳ Pending','count' => $counts['pending']],
            ['value' => 'rejected', 'label' => '❌ Ditolak','count' => $counts['rejected']],
            ['value' => 'draft', 'label' => '📝 Draft', 'count' => $counts['draft']],
            ] as $tab)
            <button wire:click="$set('filter', '{{ $tab['value'] }}')"
                class="flex-1 px-4 py-2.5 rounded-xl text-sm font-semibold
                               whitespace-nowrap transition-all duration-200
                               {{ $filter === $tab['value']
                                   ? 'bg-primary-600 text-white shadow-md'
                                   : 'text-stone-600 hover:bg-stone-50' }}">
                {{ $tab['label'] }}
                <span class="ml-1.5 {{ $filter === $tab['value'] ? 'text-white/80' : 'text-stone-400' }}">
                    ({{ $tab['count'] }})
                </span>
            </button>
            @endforeach
        </div>

        {{-- Recipes Grid --}}
        @if($recipes->count())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
            @foreach($recipes as $recipe)
            <article class="bg-white rounded-3xl overflow-hidden shadow-md border border-stone-100
                                    hover:shadow-xl hover:-translate-y-1 transition-all duration-300">

                {{-- Thumbnail --}}
                <div class="relative aspect-[4/3] overflow-hidden">
                    <img src="{{ $recipe->thumbnail_url }}"
                        alt="{{ $recipe->title }}"
                        class="w-full h-full object-cover" />

                    {{-- Status Badge --}}
                    <div class="absolute top-3 left-3">
                        <span class="badge {{ $recipe->status_color }}">
                            {{ ucfirst($recipe->status) }}
                        </span>
                    </div>
                </div>

                {{-- Body --}}
                <div class="p-5">
                    <h3 class="font-display font-bold text-lg text-stone-900 mb-2 line-clamp-2">
                        {{ $recipe->title }}
                    </h3>

                    <div class="flex items-center gap-4 text-xs text-stone-500 mb-4">
                        <span>👁️ {{ number_format($recipe->views_count) }}</span>
                        <span>❤️ {{ $recipe->favorites_count }}</span>
                        <span>💬 {{ $recipe->comments_count }}</span>
                    </div>

                    @if($recipe->status === 'rejected' && $recipe->rejection_reason)
                    <div class="bg-red-50 border border-red-200 rounded-xl p-3 mb-4">
                        <p class="text-xs font-semibold text-red-900 mb-1">
                            Alasan Penolakan:
                        </p>
                        <p class="text-xs text-red-700">
                            {{ $recipe->rejection_reason }}
                        </p>
                    </div>
                    @endif

                    {{-- Actions --}}
                    <div class="flex gap-2 pt-4 border-t border-stone-100">
                        @if($recipe->status === 'approved')
                        <a href="{{ route('recipe.detail', $recipe->slug) }}"
                            target="_blank"
                            class="flex-1 text-center px-4 py-2 rounded-lg
                                              bg-stone-100 text-stone-700 text-sm font-medium
                                              hover:bg-stone-200 transition-colors duration-200">
                            👁️ Lihat
                        </a>
                        @endif
                        <a href="{{ route('edit-recipe', $recipe->id) }}"
                            class="flex-1 text-center px-4 py-2 rounded-lg
                                          bg-primary-100 text-primary-700 text-sm font-medium
                                          hover:bg-primary-200 transition-colors duration-200">
                            ✏️ Edit
                        </a>
                        <button wire:click="deleteRecipe({{ $recipe->id }})"
                            wire:confirm="Yakin ingin menghapus resep ini?"
                            class="px-4 py-2 rounded-lg bg-red-100 text-red-700 text-sm font-medium
                                               hover:bg-red-200 transition-colors duration-200">
                            🗑️
                        </button>
                    </div>
                </div>
            </article>
            @endforeach
        </div>

        {{ $recipes->links() }}
        @else
        <div class="text-center py-20">
            <span class="text-6xl block mb-4">📖</span>
            <h3 class="font-display text-2xl font-bold text-stone-900 mb-2">
                Belum Ada Resep
            </h3>
            <p class="text-stone-500 mb-6">
                Mulai bagikan resep pertamamu sekarang!
            </p>
            <a href="{{ route('submit-recipe') }}"
                class="inline-block px-6 py-3 rounded-xl bg-primary-600
                          text-white font-semibold text-sm hover:bg-primary-700
                          transition-all duration-200">
                ✨ Buat Resep Pertama
            </a>
        </div>
        @endif
    </div>
</div>