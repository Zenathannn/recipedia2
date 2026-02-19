<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="font-display text-2xl font-bold text-stone-900">Kelola Kategori</h2>
            <p class="text-stone-500 text-sm">Atur kategori resep untuk frontend</p>
        </div>
        <button wire:click="openCreateModal"
            class="px-4 py-2 rounded-xl bg-primary-600 text-white text-sm font-semibold hover:bg-primary-700">
            Tambah Kategori
        </button>
    </div>

    <div class="grid md:grid-cols-2 gap-3">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama atau slug..."
            class="px-4 py-2 rounded-xl border border-stone-200 focus:ring-2 focus:ring-primary-500">
        <select wire:model.live="statusFilter"
            class="px-4 py-2 rounded-xl border border-stone-200 focus:ring-2 focus:ring-primary-500">
            <option value="all">Semua Status</option>
            <option value="active">Aktif</option>
            <option value="inactive">Nonaktif</option>
        </select>
    </div>

    <div class="bg-white rounded-2xl shadow-md border border-stone-100 overflow-x-auto">
        <table class="w-full">
            <thead class="bg-stone-50 border-b border-stone-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-stone-600 uppercase">Kategori</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-stone-600 uppercase">Slug</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-stone-600 uppercase">Urutan</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-stone-600 uppercase">Resep</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-stone-600 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-stone-600 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @forelse($categories as $category)
                    <tr class="hover:bg-stone-50">
                        <td class="px-6 py-4">
                            <p class="font-semibold text-stone-900">{{ $category->name }}</p>
                            <p class="text-xs text-stone-500">{{ $category->icon ?: '-' }}</p>
                        </td>
                        <td class="px-6 py-4 text-sm text-stone-700">{{ $category->slug }}</td>
                        <td class="px-6 py-4 text-sm text-stone-700">{{ $category->order }}</td>
                        <td class="px-6 py-4 text-sm text-stone-700">{{ $category->recipes_count }}</td>
                        <td class="px-6 py-4">
                            <span
                                class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $category->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $category->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <button wire:click="openEditModal({{ $category->id }})"
                                    class="px-3 py-1.5 rounded-lg bg-amber-100 text-amber-700 text-xs font-medium hover:bg-amber-200">
                                    Edit
                                </button>
                                <button wire:click="confirmDelete({{ $category->id }})"
                                    class="px-3 py-1.5 rounded-lg bg-red-100 text-red-700 text-xs font-medium hover:bg-red-200">
                                    Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-stone-500">Tidak ada data kategori.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $categories->links() }}

    @if($showFormModal)
        <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl p-6 w-full max-w-lg shadow-2xl space-y-4">
                <h3 class="font-display text-xl font-bold text-stone-900">
                    {{ $editingId ? 'Edit Kategori' : 'Tambah Kategori' }}
                </h3>
                <div class="space-y-3">
                    <div>
                        <label class="text-xs font-semibold text-stone-600">Nama</label>
                        <input type="text" wire:model="name" class="w-full px-3 py-2 rounded-lg border border-stone-200">
                        @error('name') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-stone-600">Deskripsi</label>
                        <textarea wire:model="description" rows="3" class="w-full px-3 py-2 rounded-lg border border-stone-200"></textarea>
                        @error('description') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div class="grid md:grid-cols-3 gap-3">
                        <div>
                            <label class="text-xs font-semibold text-stone-600">Icon</label>
                            <input type="text" wire:model="icon" class="w-full px-3 py-2 rounded-lg border border-stone-200">
                            @error('icon') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-stone-600">Color</label>
                            <input type="text" wire:model="color" class="w-full px-3 py-2 rounded-lg border border-stone-200">
                            @error('color') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-stone-600">Urutan</label>
                            <input type="number" wire:model="order" class="w-full px-3 py-2 rounded-lg border border-stone-200">
                            @error('order') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-stone-600">Status</label>
                        <select wire:model="is_active" class="w-full px-3 py-2 rounded-lg border border-stone-200">
                            <option value="1">Aktif</option>
                            <option value="0">Nonaktif</option>
                        </select>
                        @error('is_active') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="flex gap-3">
                    <button wire:click="closeFormModal"
                        class="flex-1 px-4 py-2 rounded-xl bg-stone-100 text-stone-700 font-semibold hover:bg-stone-200">
                        Batal
                    </button>
                    <button wire:click="save"
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
                <h3 class="font-display text-xl font-bold text-stone-900">Hapus Kategori</h3>
                <p class="text-sm text-stone-600">Kategori akan dihapus permanen. Lanjutkan?</p>
                <div class="flex gap-3">
                    <button wire:click="closeDeleteModal"
                        class="flex-1 px-4 py-2 rounded-xl bg-stone-100 text-stone-700 font-semibold hover:bg-stone-200">
                        Batal
                    </button>
                    <button wire:click="delete"
                        class="flex-1 px-4 py-2 rounded-xl bg-red-600 text-white font-semibold hover:bg-red-700">
                        Hapus
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
