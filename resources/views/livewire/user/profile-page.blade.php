<div class="min-h-screen pt-24 pb-16 bg-stone-50">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="mb-10">
            <h1 class="font-display text-4xl md:text-5xl font-bold text-stone-900 mb-3">
                Profil <span class="gradient-text">Saya</span>
            </h1>
            <p class="text-stone-500 text-lg">
                Kelola informasi pribadi dan keamanan akun kamu
            </p>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-10">
            @foreach([
            ['icon' => '📖', 'label' => 'Resep Aktif', 'value' => $stats['recipes']],
            ['icon' => '⏳', 'label' => 'Pending', 'value' => $stats['pending']],
            ['icon' => '❤️', 'label' => 'Favorit', 'value' => $stats['favorites']],
            ['icon' => '👁️', 'label' => 'Total Views', 'value' => number_format($stats['views'])],
            ] as $stat)
            <div class="bg-white rounded-2xl p-5 shadow-md border border-stone-100
                            hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                <span class="text-3xl block mb-2">{{ $stat['icon'] }}</span>
                <p class="text-xs text-stone-500 uppercase tracking-wider mb-1">
                    {{ $stat['label'] }}
                </p>
                <p class="font-display font-bold text-stone-900 text-2xl">
                    {{ $stat['value'] }}
                </p>
            </div>
            @endforeach
        </div>

        <div class="space-y-6">

            {{-- ═══ PROFILE INFO ═══ --}}
            <section class="bg-white rounded-3xl p-8 shadow-md border border-stone-100">
                <h2 class="font-display text-2xl font-bold text-stone-900 mb-6
                           flex items-center gap-3">
                    👤 Informasi Profil
                </h2>

                <form wire:submit.prevent="updateProfile">
                    <div class="space-y-6">

                        {{-- Avatar --}}
                        <div class="flex items-center gap-6">
                            <div class="relative">
                                @if($newAvatar)
                                <img src="{{ $newAvatar->temporaryUrl() }}"
                                    alt="Preview"
                                    class="w-24 h-24 rounded-full object-cover ring-4 ring-primary-200" />
                                @else
                                <img src="{{ auth()->user()->avatar_url }}"
                                    alt="{{ name }}"
                                    class="w-24 h-24 rounded-full object-cover ring-4 ring-primary-200" />
                                @endif
                                <label class="absolute bottom-0 right-0 w-8 h-8 rounded-full
                                              bg-primary-600 text-white flex items-center justify-center
                                              cursor-pointer hover:bg-primary-700 transition-colors duration-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <input type="file" wire:model="newAvatar" accept="image/*" class="hidden" />
                                </label>
                            </div>
                            <div>
                                <p class="font-semibold text-stone-900 mb-1">Foto Profil</p>
                                <p class="text-sm text-stone-500 mb-2">JPG, PNG. Maksimal 2MB</p>
                                @if($newAvatar)
                                <button type="button" wire:click="$set('newAvatar', null)"
                                    class="text-xs text-red-600 hover:text-red-700">
                                    Batalkan
                                </button>
                                @endif
                            </div>
                        </div>
                        @error('newAvatar')
                        <p class="text-red-500 text-xs">{{ $message }}</p>
                        @enderror

                        {{-- Name & Username --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-stone-700 mb-2">
                                    Nama Lengkap <span class="text-red-500">*</span>
                                </label>
                                <input type="text" wire:model="name"
                                    class="w-full px-4 py-3 rounded-xl border border-stone-200
                                              focus:ring-2 focus:ring-primary-500 focus:border-primary-500
                                              transition-all duration-200" />
                                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-stone-700 mb-2">
                                    Username <span class="text-red-500">*</span>
                                </label>
                                <input type="text" wire:model="username"
                                    class="w-full px-4 py-3 rounded-xl border border-stone-200
                                              focus:ring-2 focus:ring-primary-500 focus:border-primary-500
                                              transition-all duration-200" />
                                @error('username') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        {{-- Email & Phone --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-stone-700 mb-2">
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <input type="email" wire:model="email"
                                    class="w-full px-4 py-3 rounded-xl border border-stone-200
                                              focus:ring-2 focus:ring-primary-500 focus:border-primary-500
                                              transition-all duration-200" />
                                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-stone-700 mb-2">
                                    Nomor Telepon
                                </label>
                                <input type="text" wire:model="phone"
                                    placeholder="+62 xxx-xxxx-xxxx"
                                    class="w-full px-4 py-3 rounded-xl border border-stone-200
                                              focus:ring-2 focus:ring-primary-500 focus:border-primary-500
                                              transition-all duration-200" />
                                @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        {{-- Bio --}}
                        <div>
                            <label class="block text-sm font-semibold text-stone-700 mb-2">
                                Bio
                            </label>
                            <textarea wire:model="bio" rows="4"
                                placeholder="Ceritakan sedikit tentang diri kamu..."
                                class="w-full px-4 py-3 rounded-xl border border-stone-200
                                             focus:ring-2 focus:ring-primary-500 focus:border-primary-500
                                             resize-none transition-all duration-200"></textarea>
                            @error('bio') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Submit --}}
                        <div class="flex justify-end">
                            <button type="submit"
                                wire:loading.attr="disabled"
                                class="px-6 py-3 rounded-xl bg-primary-600 text-white
                                           font-semibold text-sm hover:bg-primary-700
                                           disabled:opacity-50 disabled:cursor-not-allowed
                                           transition-all duration-200">
                                <span wire:loading.remove>💾 Simpan Perubahan</span>
                                <span wire:loading>Menyimpan...</span>
                            </button>
                        </div>
                    </div>
                </form>
            </section>

            {{-- ═══ CHANGE PASSWORD ═══ --}}
            <section class="bg-white rounded-3xl p-8 shadow-md border border-stone-100">
                <h2 class="font-display text-2xl font-bold text-stone-900 mb-6
                           flex items-center gap-3">
                    🔒 Ubah Password
                </h2>

                <form wire:submit.prevent="updatePassword">
                    <div class="space-y-6 max-w-md">
                        <div>
                            <label class="block text-sm font-semibold text-stone-700 mb-2">
                                Password Lama <span class="text-red-500">*</span>
                            </label>
                            <input type="password" wire:model="currentPassword"
                                class="w-full px-4 py-3 rounded-xl border border-stone-200
                                          focus:ring-2 focus:ring-primary-500 focus:border-primary-500
                                          transition-all duration-200" />
                            @error('currentPassword')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-stone-700 mb-2">
                                Password Baru <span class="text-red-500">*</span>
                            </label>
                            <input type="password" wire:model="newPassword"
                                class="w-full px-4 py-3 rounded-xl border border-stone-200
                                          focus:ring-2 focus:ring-primary-500 focus:border-primary-500
                                          transition-all duration-200" />
                            @error('newPassword')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-stone-700 mb-2">
                                Konfirmasi Password Baru <span class="text-red-500">*</span>
                            </label>
                            <input type="password" wire:model="newPasswordConfirmation"
                                class="w-full px-4 py-3 rounded-xl border border-stone-200
                                          focus:ring-2 focus:ring-primary-500 focus:border-primary-500
                                          transition-all duration-200" />
                        </div>

                        <button type="submit"
                            wire:loading.attr="disabled"
                            class="w-full px-6 py-3 rounded-xl bg-stone-900 text-white
                                       font-semibold text-sm hover:bg-stone-800
                                       disabled:opacity-50 disabled:cursor-not-allowed
                                       transition-all duration-200">
                            <span wire:loading.remove>🔐 Ubah Password</span>
                            <span wire:loading>Mengubah...</span>
                        </button>
                    </div>
                </form>
            </section>

            {{-- ═══ DANGER ZONE ═══ --}}
            <section class="bg-red-50 border-2 border-red-200 rounded-3xl p-8">
                <h2 class="font-display text-2xl font-bold text-red-900 mb-3
                           flex items-center gap-3">
                    ⚠️ Zona Berbahaya
                </h2>
                <p class="text-red-700 text-sm mb-6">
                    Tindakan di bawah ini bersifat permanen dan tidak dapat dibatalkan.
                </p>
                <button class="px-6 py-3 rounded-xl bg-red-600 text-white
                               font-semibold text-sm hover:bg-red-700
                               transition-all duration-200">
                    🗑️ Hapus Akun Permanen
                </button>
            </section>
        </div>
    </div>
</div>