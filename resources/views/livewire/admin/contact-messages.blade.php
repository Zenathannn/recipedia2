<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="font-display text-2xl font-bold text-stone-900">Pesan Kontak</h2>
            <p class="text-stone-500 text-sm">Kelola pesan masuk dari halaman kontak</p>
        </div>
        <button wire:click="openCreateModal"
            class="px-4 py-2 rounded-xl bg-primary-600 text-white text-sm font-semibold hover:bg-primary-700">
            Tambah Pesan
        </button>
    </div>

    <div class="grid md:grid-cols-2 gap-3">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama, email, atau subjek..."
            class="px-4 py-2 rounded-xl border border-stone-200 focus:ring-2 focus:ring-primary-500">
        <select wire:model.live="readFilter"
            class="px-4 py-2 rounded-xl border border-stone-200 focus:ring-2 focus:ring-primary-500">
            <option value="all">Semua Status</option>
            <option value="read">Sudah Dibaca</option>
            <option value="unread">Belum Dibaca</option>
        </select>
    </div>

    <div class="bg-white rounded-2xl shadow-md border border-stone-100 overflow-x-auto">
        <table class="w-full">
            <thead class="bg-stone-50 border-b border-stone-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-stone-600 uppercase">Pengirim</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-stone-600 uppercase">Subjek</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-stone-600 uppercase">Pesan</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-stone-600 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-stone-600 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @forelse($messages as $item)
                    <tr class="hover:bg-stone-50">
                        <td class="px-6 py-4">
                            <p class="font-semibold text-stone-900">{{ $item->name }}</p>
                            <p class="text-xs text-stone-500">{{ $item->email }}</p>
                        </td>
                        <td class="px-6 py-4 text-sm text-stone-700">
                            {{ \Illuminate\Support\Str::limit($item->subject, 35) }}
                        </td>
                        <td class="px-6 py-4 text-sm text-stone-700">
                            {{ \Illuminate\Support\Str::limit($item->message, 60) }}
                        </td>
                        <td class="px-6 py-4">
                            <span
                                class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $item->is_read ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                                {{ $item->is_read ? 'Dibaca' : 'Belum Dibaca' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                @if(!$item->is_read)
                                    <button wire:click="markAsRead({{ $item->id }})"
                                        class="px-3 py-1.5 rounded-lg bg-emerald-100 text-emerald-700 text-xs font-medium hover:bg-emerald-200">
                                        Tandai Dibaca
                                    </button>
                                @endif
                                <button wire:click="openEditModal({{ $item->id }})"
                                    class="px-3 py-1.5 rounded-lg bg-amber-100 text-amber-700 text-xs font-medium hover:bg-amber-200">
                                    Edit
                                </button>
                                <button wire:click="confirmDelete({{ $item->id }})"
                                    class="px-3 py-1.5 rounded-lg bg-red-100 text-red-700 text-xs font-medium hover:bg-red-200">
                                    Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-stone-500">Tidak ada pesan kontak.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $messages->links() }}

    @if($showFormModal)
        <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl p-6 w-full max-w-lg shadow-2xl space-y-4">
                <h3 class="font-display text-xl font-bold text-stone-900">
                    {{ $editingId ? 'Edit Pesan' : 'Tambah Pesan' }}
                </h3>
                <div class="grid md:grid-cols-2 gap-3">
                    <div>
                        <label class="text-xs font-semibold text-stone-600">Nama</label>
                        <input type="text" wire:model="name" class="w-full px-3 py-2 rounded-lg border border-stone-200">
                        @error('name') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-stone-600">Email</label>
                        <input type="email" wire:model="email" class="w-full px-3 py-2 rounded-lg border border-stone-200">
                        @error('email') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div>
                    <label class="text-xs font-semibold text-stone-600">Subjek</label>
                    <input type="text" wire:model="subject" class="w-full px-3 py-2 rounded-lg border border-stone-200">
                    @error('subject') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-xs font-semibold text-stone-600">Pesan</label>
                    <textarea wire:model="message" rows="4" class="w-full px-3 py-2 rounded-lg border border-stone-200"></textarea>
                    @error('message') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-xs font-semibold text-stone-600">Status</label>
                    <select wire:model="is_read" class="w-full px-3 py-2 rounded-lg border border-stone-200">
                        <option value="0">Belum Dibaca</option>
                        <option value="1">Dibaca</option>
                    </select>
                    @error('is_read') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
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
                <h3 class="font-display text-xl font-bold text-stone-900">Hapus Pesan</h3>
                <p class="text-sm text-stone-600">Pesan akan dihapus permanen. Lanjutkan?</p>
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
