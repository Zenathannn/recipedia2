<div class="space-y-6">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h2 class="font-display text-2xl font-bold text-stone-900">Kelola Komentar</h2>
            <p class="text-sm text-stone-500">Mode baca dan hapus komentar</p>
        </div>
    </div>

    <div class="grid gap-3 md:grid-cols-2">
        <input
            type="text"
            wire:model.live.debounce.300ms="search"
            placeholder="Cari komentar, user, atau resep..."
            class="rounded-xl border border-stone-200 px-4 py-2 focus:ring-2 focus:ring-primary-500"
        >
        <select
            wire:model.live="approvalFilter"
            class="rounded-xl border border-stone-200 px-4 py-2 focus:ring-2 focus:ring-primary-500"
        >
            <option value="all">Semua Status</option>
            <option value="approved">Disetujui</option>
            <option value="pending">Pending</option>
        </select>
    </div>

    <div class="overflow-x-auto rounded-2xl border border-stone-100 bg-white shadow-md">
        <table class="w-full">
            <thead class="border-b border-stone-200 bg-stone-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-stone-600">Komentar</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-stone-600">User</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-stone-600">Resep</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-stone-600">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-stone-600">Aksi</th>
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
                            <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $comment->is_approved ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                                {{ $comment->is_approved ? 'Disetujui' : 'Pending' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <button
                                type="button"
                                wire:click.prevent="confirmDelete({{ $comment->id }})"
                                class="rounded-lg bg-red-100 px-3 py-1.5 text-xs font-medium text-red-700 hover:bg-red-200"
                            >
                                Hapus
                            </button>
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

    @if($showDeleteModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
            <div class="w-full max-w-md space-y-4 rounded-2xl bg-white p-6 shadow-2xl">
                <h3 class="font-display text-xl font-bold text-stone-900">Hapus Komentar</h3>
                <p class="text-sm text-stone-600">Komentar akan dihapus permanen. Lanjutkan?</p>
                <div class="flex gap-3">
                    <button
                        type="button"
                        wire:click.prevent="closeDeleteModal"
                        class="flex-1 rounded-xl bg-stone-100 px-4 py-2 font-semibold text-stone-700 hover:bg-stone-200"
                    >
                        Batal
                    </button>
                    <button
                        type="button"
                        wire:click.prevent="delete"
                        class="flex-1 rounded-xl bg-red-600 px-4 py-2 font-semibold text-white hover:bg-red-700"
                    >
                        Hapus
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

