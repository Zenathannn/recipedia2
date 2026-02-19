<div class="min-h-screen pt-24 pb-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="text-center mb-16">
            <span class="text-6xl block mb-4">✉️</span>
            <h1 class="font-display text-4xl md:text-5xl font-bold text-stone-900 mb-4">
                Hubungi <span class="gradient-text">Kami</span>
            </h1>
            <p class="text-stone-500 text-lg max-w-2xl mx-auto">
                Punya pertanyaan, saran, atau ingin berkolaborasi? Kami siap mendengarkan!
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

            {{-- Contact Info --}}
            <div class="lg:col-span-1 space-y-6">
                @foreach([
                ['icon' => '📧', 'title' => 'Email', 'value' => 'hello@recipedia.com'],
                ['icon' => '📱', 'title' => 'Telepon', 'value' => '+62 812-3456-7890'],
                ['icon' => '📍', 'title' => 'Alamat', 'value' => 'Jakarta, Indonesia'],
                ['icon' => '⏰', 'title' => 'Jam Operasional', 'value' => 'Senin - Jumat, 09:00 - 17:00'],
                ] as $info)
                <div class="bg-white rounded-2xl p-6 shadow-md border border-stone-100
                                hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                    <span class="text-3xl block mb-3">{{ $info['icon'] }}</span>
                    <h3 class="font-semibold text-stone-900 mb-1">{{ $info['title'] }}</h3>
                    <p class="text-stone-600 text-sm">{{ $info['value'] }}</p>
                </div>
                @endforeach

                {{-- Social Media --}}
                <div class="bg-gradient-to-br from-primary-500 to-primary-700
                            rounded-2xl p-6 text-white shadow-lg">
                    <h3 class="font-display font-bold text-lg mb-4">Ikuti Kami</h3>
                    <div class="flex gap-3">
                        @foreach(['📘', '📸', '🐦', '▶️'] as $icon)
                        <a href="#"
                            class="w-10 h-10 rounded-lg bg-white/20 hover:bg-white/30
                                      flex items-center justify-center text-lg
                                      hover:scale-110 transition-all duration-200">
                            {{ $icon }}
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Contact Form --}}
            <div class="lg:col-span-2">
                <form wire:submit.prevent="submit"
                    class="bg-white rounded-3xl p-8 shadow-md border border-stone-100">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        {{-- Name --}}
                        <div>
                            <label class="block text-sm font-semibold text-stone-700 mb-2">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                wire:model="name"
                                placeholder="Masukkan nama kamu"
                                class="w-full px-4 py-3 rounded-xl border border-stone-200
                                          focus:ring-2 focus:ring-primary-500 focus:border-primary-500
                                          transition-all duration-200" />
                            @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div>
                            <label class="block text-sm font-semibold text-stone-700 mb-2">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email"
                                wire:model="email"
                                placeholder="nama@email.com"
                                class="w-full px-4 py-3 rounded-xl border border-stone-200
                                          focus:ring-2 focus:ring-primary-500 focus:border-primary-500
                                          transition-all duration-200" />
                            @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Subject --}}
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-stone-700 mb-2">
                            Subjek <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                            wire:model="subject"
                            placeholder="Tentang apa pesan kamu?"
                            class="w-full px-4 py-3 rounded-xl border border-stone-200
                                      focus:ring-2 focus:ring-primary-500 focus:border-primary-500
                                      transition-all duration-200" />
                        @error('subject')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Message --}}
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-stone-700 mb-2">
                            Pesan <span class="text-red-500">*</span>
                        </label>
                        <textarea wire:model="message"
                            rows="6"
                            placeholder="Tulis pesan kamu di sini..."
                            class="w-full px-4 py-3 rounded-xl border border-stone-200
                                         focus:ring-2 focus:ring-primary-500 focus:border-primary-500
                                         resize-none transition-all duration-200"></textarea>
                        @error('message')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit"
                        wire:loading.attr="disabled"
                        class="w-full px-6 py-4 rounded-xl bg-gradient-to-r from-primary-500 to-primary-600
                                   hover:from-primary-600 hover:to-primary-700
                                   text-white font-bold text-base shadow-lg hover:shadow-xl
                                   hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed
                                   transition-all duration-200">
                        <span wire:loading.remove>✉️ Kirim Pesan</span>
                        <span wire:loading>Mengirim...</span>
                    </button>
                </form>
            </div>
        </div>

        {{-- FAQ Section --}}
        <section class="mt-20">
            <div class="text-center mb-10">
                <h2 class="font-display text-3xl font-bold text-stone-900 mb-3">
                    Pertanyaan <span class="gradient-text">Umum</span>
                </h2>
                <p class="text-stone-500">Mungkin jawabannya sudah ada di sini</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach([
                ['q' => 'Bagaimana cara upload resep?', 'a' => 'Kamu harus login terlebih dahulu, lalu klik tombol "Bagikan Resep" di navbar. Isi semua informasi resep dan tunggu approval dari admin.'],
                ['q' => 'Apakah gratis?', 'a' => 'Ya! Recipedia sepenuhnya gratis untuk digunakan baik untuk melihat resep maupun membagikan resep kamu sendiri.'],
                ['q' => 'Berapa lama approval resep?', 'a' => 'Biasanya resep akan direview dalam 1-2 hari kerja. Kami akan memberitahu kamu melalui email.'],
                ['q' => 'Bisa edit resep yang sudah diupload?', 'a' => 'Tentu! Kamu bisa edit resep kamu sendiri kapan saja di menu "Resep Saya".'],
                ] as $faq)
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-stone-100
                                hover:shadow-md transition-all duration-300">
                    <h3 class="font-semibold text-stone-900 mb-2">{{ $faq['q'] }}</h3>
                    <p class="text-stone-600 text-sm">{{ $faq['a'] }}</p>
                </div>
                @endforeach
            </div>
        </section>
    </div>
</div>