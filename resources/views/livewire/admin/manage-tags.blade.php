<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="font-display text-2xl font-bold text-stone-900">Kelola Tag</h2>
            <p class="text-stone-500 text-sm">Daftar tag untuk klasifikasi resep</p>
        </div>
        <button type="button" wire:click.prevent="openCreateModal"
            class="px-4 py-2 rounded-xl bg-primary-600 text-white text-sm font-semibold hover:bg-primary-700">
            Tambah Tag
        </button>
    </div>

    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama atau slug..."
        class="w-full md:w-96 px-4 py-2 rounded-xl border border-stone-200 focus:ring-2 focus:ring-primary-500">

    <div class="bg-white rounded-2xl shadow-md border border-stone-100 overflow-x-auto">
        <table class="w-full">
            <thead class="bg-stone-50 border-b border-stone-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-stone-600 uppercase">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-stone-600 uppercase">Slug</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-stone-600 uppercase">Color</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-stone-600 uppercase">Resep</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-stone-600 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @forelse($tags as $tag)
                    <tr class="hover:bg-stone-50">
                        <td class="px-6 py-4 font-semibold text-stone-900">{{ $tag->name }}</td>
                        <td class="px-6 py-4 text-sm text-stone-700">{{ $tag->slug }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-2 text-sm text-stone-700">
                                <span class="w-3 h-3 rounded-full border border-stone-200" style="background: {{ $tag->color }}"></span>
                                {{ $tag->color }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-stone-700">{{ $tag->recipes_count }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <button type="button" wire:click.prevent="openEditModal({{ $tag->id }})"
                                    class="px-3 py-1.5 rounded-lg bg-amber-100 text-amber-700 text-xs font-medium hover:bg-amber-200">
                                    Edit
                                </button>
                                <button type="button" wire:click.prevent="confirmDelete({{ $tag->id }})"
                                    class="px-3 py-1.5 rounded-lg bg-red-100 text-red-700 text-xs font-medium hover:bg-red-200">
                                    Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-stone-500">Tidak ada data tag.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $tags->links() }}

    @if($showFormModal)
        <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-2xl space-y-4">
                <h3 class="font-display text-xl font-bold text-stone-900">
                    {{ $editingId ? 'Edit Tag' : 'Tambah Tag' }}
                </h3>
                <div>
                    <label class="text-xs font-semibold text-stone-600">Nama</label>
                    <input type="text" wire:model="name" class="w-full px-3 py-2 rounded-lg border border-stone-200">
                    @error('name') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-xs font-semibold text-stone-600">Color</label>
                    <input type="text" wire:model="color" class="w-full px-3 py-2 rounded-lg border border-stone-200">
                    @error('color') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div class="flex gap-3">
                    <button type="button" wire:click.prevent="closeFormModal"
                        class="flex-1 px-4 py-2 rounded-xl bg-stone-100 text-stone-700 font-semibold hover:bg-stone-200">
                        Batal
                    </button>
                    <button type="button" wire:click.prevent="save"
                        class="flex-1 px-4 py-2 rounded-xl bg-primary-600 text-white font-semibold hover:bg-primary-700">
                        Simpan
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if($showDeleteModal)
        <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-2xl space-y-4">
                <h3 class="font-display text-xl font-bold text-stone-900">Hapus Tag</h3>
                <p class="text-sm text-stone-600">Tag akan dihapus permanen. Lanjutkan?</p>
                <div class="flex gap-3">
                    <button type="button" wire:click.prevent="closeDeleteModal"
                        class="flex-1 px-4 py-2 rounded-xl bg-stone-100 text-stone-700 font-semibold hover:bg-stone-200">
                        Batal
                    </button>
                    <button type="button" wire:click.prevent="delete"
                        class="flex-1 px-4 py-2 rounded-xl bg-red-600 text-white font-semibold hover:bg-red-700">
                        Hapus
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
