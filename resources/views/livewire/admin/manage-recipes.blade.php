<div class="space-y-6">

    {{-- Header & Search --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="font-display text-2xl font-bold text-stone-900">Kelola Resep</h2>
            <p class="text-stone-500 text-sm">Approve atau tolak resep yang disubmit</p>
        </div>
        <input type="text" wire:model.live.debounce.300ms="search"
            placeholder="🔍 Cari resep..."
            class="px-4 py-2 rounded-xl border border-stone-200
                      focus:ring-2 focus:ring-primary-500 focus:border-primary-500" />
    </div>

    {{-- Filter Tabs --}}
    <div class="bg-white rounded-2xl p-2 shadow-md border border-stone-100 flex gap-2">
        @foreach([
        ['value' => 'all', 'label' => 'Semua', 'count' => $counts['all']],
        ['value' => 'pending', 'label' => '⏳ Pending','count' => $counts['pending']],
        ['value' => 'approved', 'label' => '✅ Aktif', 'count' => $counts['approved']],
        ['value' => 'rejected', 'label' => '❌ Ditolak','count' => $counts['rejected']],
        ] as $tab)
        <button wire:click="$set('filter', '{{ $tab['value'] }}')"
            class="flex-1 px-4 py-2 rounded-xl text-sm font-semibold
                           transition-all duration-200
                           {{ $filter === $tab['value']
                               ? 'bg-primary-600 text-white'
                               : 'text-stone-600 hover:bg-stone-50' }}">
            {{ $tab['label'] }}
            <span class="ml-1 {{ $filter === $tab['value'] ? 'text-white/70' : 'text-stone-400' }}">
                ({{ $tab['count'] }})
            </span>
        </button>
        @endforeach
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl shadow-md border border-stone-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-stone-50 border-b border-stone-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-stone-600 uppercase">Resep</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-stone-600 uppercase">Pembuat</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-stone-600 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-stone-600 uppercase">Stats</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-stone-600 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @forelse($recipes as $recipe)
                    <tr class="hover:bg-stone-50 transition-colors duration-200">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <img src="{{ $recipe->thumbnail_url }}"
                                    class="w-16 h-16 rounded-lg object-cover" />
                                <div>
                                    <p class="font-semibold text-stone-900 text-sm">
                                        {{ Str::limit($recipe->title, 40) }}
                                    </p>
                                    <p class="text-xs text-stone-500">
                                        {{ $recipe->category->name ?? '-' }}
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <img src="{{ $recipe->user->avatar_url }}"
                                    class="w-8 h-8 rounded-full" />
                                <span class="text-sm text-stone-700">{{ $recipe->user->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="badge {{ $recipe->status_color }}">
                                {{ ucfirst($recipe->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-xs text-stone-500 space-y-1">
                                <div>👁️ {{ number_format($recipe->views_count) }}</div>
                                <div>❤️ {{ $recipe->favorites_count }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('recipe.detail', $recipe->slug) }}"
                                    target="_blank"
                                    class="px-3 py-1.5 rounded-lg bg-stone-100 text-stone-700
                                              hover:bg-stone-200 text-xs font-medium">
                                    👁️
                                </a>
                                @if($recipe->status === 'pending')
                                <button wire:click="approve({{ $recipe->id }})"
                                    class="px-3 py-1.5 rounded-lg bg-green-100 text-green-700
                                                       hover:bg-green-200 text-xs font-medium">
                                    ✅
                                </button>
                                <button wire:click="openRejectModal({{ $recipe->id }})"
                                    class="px-3 py-1.5 rounded-lg bg-red-100 text-red-700
                                                       hover:bg-red-200 text-xs font-medium">
                                    ❌
                                </button>
                                @endif
                                <button wire:click="deleteRecipe({{ $recipe->id }})"
                                    wire:confirm="Hapus resep ini?"
                                    class="px-3 py-1.5 rounded-lg bg-red-100 text-red-700
                                                   hover:bg-red-200 text-xs font-medium">
                                    🗑️
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-stone-500">
                            Tidak ada data
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{ $recipes->links() }}

    {{-- Reject Modal --}}
    @if($selectedRecipe)
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl p-6 max-w-md w-full shadow-2xl">
            <h3 class="font-display font-bold text-xl text-stone-900 mb-4">
                Tolak Resep
            </h3>
            <textarea wire:model="rejectionReason"
                rows="4"
                placeholder="Tulis alasan penolakan..."
                class="w-full px-4 py-3 rounded-xl border border-stone-200
                                 focus:ring-2 focus:ring-red-500 resize-none mb-4"></textarea>
            @error('rejectionReason')
            <p class="text-red-500 text-xs mb-4">{{ $message }}</p>
            @enderror
            <div class="flex gap-3">
                <button wire:click="$set('selectedRecipe', null)"
                    class="flex-1 px-4 py-2 rounded-xl bg-stone-100 text-stone-700
                                   font-semibold hover:bg-stone-200">
                    Batal
                </button>
                <button wire:click="reject"
                    class="flex-1 px-4 py-2 rounded-xl bg-red-600 text-white
                                   font-semibold hover:bg-red-700">
                    Tolak
                </button>
            </div>
        </div>
    </div>
    @endif
</div>