<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="font-display text-2xl font-bold text-stone-900">Kelola Komentar</h2>
            <p class="text-stone-500 text-sm">Moderasi komentar pada resep</p>
        </div>
        <button wire:click="openCreateModal"
            class="px-4 py-2 rounded-xl bg-primary-600 text-white text-sm font-semibold hover:bg-primary-700">
            Tambah Komentar
        </button>
    </div>

    <div class="grid md:grid-cols-2 gap-3">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari komentar, user, atau resep..."
            class="px-4 py-2 rounded-xl border border-stone-200 focus:ring-2 focus:ring-primary-500">
        <select wire:model.live="approvalFilter"
            class="px-4 py-2 rounded-xl border border-stone-200 focus:ring-2 focus:ring-primary-500">
            <option value="all">Semua Status</option>
            <option value="approved">Disetujui</option>
            <option value="pending">Pending</option>
        </select>
    </div>

    <div class="bg-white rounded-2xl shadow-md border border-stone-100 overflow-x-auto">
        <table class="w-full">
            <thead class="bg-stone-50 border-b border-stone-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-stone-600 uppercase">Komentar</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-stone-600 uppercase">User</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-stone-600 uppercase">Resep</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-stone-600 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-stone-600 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @forelse($comments as $comment)
                    <tr class="hover:bg-stone-50">
                        <td class="px-6 py-4 text-sm text-stone-700">
                            {{ \Illuminate\Support\Str::limit($comment->content, 80) }}
                        </td>
                        <td class="px-6 py-4 text-sm text-stone-700">{{ $comment->user->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-stone-700">
                            {{ \Illuminate\Support\Str::limit($comment->recipe->title ?? '-', 35) }}
                        </td>
                        <td class="px-6 py-4">
                            <span
                                class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $comment->is_approved ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                                {{ $comment->is_approved ? 'Disetujui' : 'Pending' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <button wire:click="openEditModal({{ $comment->id }})"
                                    class="px-3 py-1.5 rounded-lg bg-amber-100 text-amber-700 text-xs font-medium hover:bg-amber-200">
                                    Edit
                                </button>
                                <button wire:click="confirmDelete({{ $comment->id }})"
                                    class="px-3 py-1.5 rounded-lg bg-red-100 text-red-700 text-xs font-medium hover:bg-red-200">
                                    Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-stone-500">Tidak ada data komentar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $comments->links() }}

    @if($showFormModal)
        <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl p-6 w-full max-w-lg shadow-2xl space-y-4">
                <h3 class="font-display text-xl font-bold text-stone-900">
                    {{ $editingId ? 'Edit Komentar' : 'Tambah Komentar' }}
                </h3>
                <div class="grid md:grid-cols-2 gap-3">
                    <div>
                        <label class="text-xs font-semibold text-stone-600">User</label>
                        <select wire:model="user_id" class="w-full px-3 py-2 rounded-lg border border-stone-200">
                            <option value="">Pilih user</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                        @error('user_id') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-stone-600">Resep</label>
                        <select wire:model="recipe_id" class="w-full px-3 py-2 rounded-lg border border-stone-200">
                            <option value="">Pilih resep</option>
                            @foreach($recipes as $recipe)
                                <option value="{{ $recipe->id }}">{{ \Illuminate\Support\Str::limit($recipe->title, 40) }}</option>
                            @endforeach
                        </select>
                        @error('recipe_id') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div>
                    <label class="text-xs font-semibold text-stone-600">Isi komentar</label>
                    <textarea wire:model="content" rows="4" class="w-full px-3 py-2 rounded-lg border border-stone-200"></textarea>
                    @error('content') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-xs font-semibold text-stone-600">Status</label>
                    <select wire:model="is_approved" class="w-full px-3 py-2 rounded-lg border border-stone-200">
                        <option value="1">Disetujui</option>
                        <option value="0">Pending</option>
                    </select>
                    @error('is_approved') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
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
                <h3 class="font-display text-xl font-bold text-stone-900">Hapus Komentar</h3>
                <p class="text-sm text-stone-600">Komentar akan dihapus permanen. Lanjutkan?</p>
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
