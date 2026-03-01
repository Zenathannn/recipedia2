<div class="space-y-6">

    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h2 class="font-display text-2xl font-bold text-stone-900">Kelola Resep</h2>
            <p class="text-sm text-stone-500">Approve, tolak, atau hapus resep</p>
        </div>
        <input
            type="text"
            wire:model.live.debounce.300ms="search"
            placeholder="Cari resep..."
            class="rounded-xl border border-stone-200 px-4 py-2 focus:border-primary-500 focus:ring-2 focus:ring-primary-500"
        />
    </div>

    <div class="flex gap-2 rounded-2xl border border-stone-100 bg-white p-2 shadow-md">
        @foreach([
            ['value' => 'all', 'label' => 'Semua', 'count' => $counts['all']],
            ['value' => 'pending', 'label' => 'Pending', 'count' => $counts['pending']],
            ['value' => 'approved', 'label' => 'Aktif', 'count' => $counts['approved']],
            ['value' => 'rejected', 'label' => 'Ditolak', 'count' => $counts['rejected']],
        ] as $tab)
            <button
                type="button"
                wire:click.prevent="$set('filter', '{{ $tab['value'] }}')"
                class="flex-1 rounded-xl px-4 py-2 text-sm font-semibold transition-all duration-200 {{ $filter === $tab['value'] ? 'bg-primary-600 text-white' : 'text-stone-600 hover:bg-stone-50' }}"
            >
                {{ $tab['label'] }}
                <span class="ml-1 {{ $filter === $tab['value'] ? 'text-white/70' : 'text-stone-400' }}">
                    ({{ $tab['count'] }})
                </span>
            </button>
        @endforeach
    </div>

    <div class="overflow-hidden rounded-2xl border border-stone-100 bg-white shadow-md">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="border-b border-stone-200 bg-stone-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-stone-600">Resep</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-stone-600">Pembuat</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-stone-600">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-stone-600">Stats</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-stone-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @forelse($recipes as $recipe)
                        <tr class="transition-colors duration-200 hover:bg-stone-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $recipe->thumbnail_url }}" class="h-16 w-16 rounded-lg object-cover" />
                                    <div>
                                        <p class="text-sm font-semibold text-stone-900">
                                            {{ Str::limit($recipe->title, 40) }}
                                        </p>
                                        <p class="text-xs text-stone-500">{{ $recipe->category->name ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <img src="{{ $recipe->user->avatar_url }}" class="h-8 w-8 rounded-full" />
                                    <span class="text-sm text-stone-700">{{ $recipe->user->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="badge {{ $recipe->status_color }}">{{ ucfirst($recipe->status) }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="space-y-1 text-xs text-stone-500">
                                    <div>View: {{ number_format($recipe->views_count) }}</div>
                                    <div>Fav: {{ $recipe->favorites_count }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <a
                                        href="{{ route('recipe.detail', $recipe->slug) }}"
                                        target="_blank"
                                        class="rounded-lg bg-stone-100 px-3 py-1.5 text-xs font-medium text-stone-700 hover:bg-stone-200"
                                    >
                                        Lihat
                                    </a>
                                    @if($recipe->status === 'pending')
                                        <button
                                            type="button"
                                            wire:click.prevent="approve({{ $recipe->id }})"
                                            class="rounded-lg bg-green-100 px-3 py-1.5 text-xs font-medium text-green-700 hover:bg-green-200"
                                        >
                                            Setujui
                                        </button>
                                        <button
                                            type="button"
                                            wire:click.prevent="openRejectModal({{ $recipe->id }})"
                                            class="rounded-lg bg-amber-100 px-3 py-1.5 text-xs font-medium text-amber-700 hover:bg-amber-200"
                                        >
                                            Tolak
                                        </button>
                                    @endif
                                    <button
                                        type="button"
                                        wire:click.prevent="confirmDelete({{ $recipe->id }})"
                                        class="rounded-lg bg-red-100 px-3 py-1.5 text-xs font-medium text-red-700 hover:bg-red-200"
                                    >
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-stone-500">Tidak ada data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{ $recipes->links() }}

    @if($selectedRecipe)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4 backdrop-blur-sm">
            <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl">
                <h3 class="mb-4 font-display text-xl font-bold text-stone-900">Tolak Resep</h3>
                <textarea
                    wire:model="rejectionReason"
                    rows="4"
                    placeholder="Tulis alasan penolakan..."
                    class="mb-4 w-full resize-none rounded-xl border border-stone-200 px-4 py-3 focus:ring-2 focus:ring-red-500"
                ></textarea>
                @error('rejectionReason')
                    <p class="mb-4 text-xs text-red-500">{{ $message }}</p>
                @enderror
                <div class="flex gap-3">
                    <button
                        type="button"
                        wire:click.prevent="$set('selectedRecipe', null)"
                        class="flex-1 rounded-xl bg-stone-100 px-4 py-2 font-semibold text-stone-700 hover:bg-stone-200"
                    >
                        Batal
                    </button>
                    <button
                        type="button"
                        wire:click.prevent="reject"
                        class="flex-1 rounded-xl bg-red-600 px-4 py-2 font-semibold text-white hover:bg-red-700"
                    >
                        Tolak
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if($deletingRecipe)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4 backdrop-blur-sm">
            <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl">
                <h3 class="mb-2 font-display text-xl font-bold text-stone-900">Hapus Resep</h3>
                <p class="mb-4 text-sm text-stone-600">Resep akan dihapus permanen. Lanjutkan?</p>
                <div class="flex gap-3">
                    <button
                        type="button"
                        wire:click.prevent="cancelDelete"
                        class="flex-1 rounded-xl bg-stone-100 px-4 py-2 font-semibold text-stone-700 hover:bg-stone-200"
                    >
                        Batal
                    </button>
                    <button
                        type="button"
                        wire:click.prevent="deleteRecipe({{ $deletingRecipe }})"
                        class="flex-1 rounded-xl bg-red-600 px-4 py-2 font-semibold text-white hover:bg-red-700"
                    >
                        Hapus
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

