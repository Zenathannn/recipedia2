<footer class="bg-stone-900 text-stone-300 mt-24">

    {{-- ── Wave Divider ───────────────────────────── --}}
    <div class="w-full overflow-hidden leading-none -mb-1">
        <svg viewBox="0 0 1440 80" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0,40 C360,80 1080,0 1440,40 L1440,80 L0,80 Z"
                fill="#1c1917" />
        </svg>
    </div>

    {{-- ── Main Footer Content ─────────────────────── --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-10">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">

            {{-- Brand --}}
            <div class="lg:col-span-2">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary-500 to-primary-700
                                flex items-center justify-center shadow-lg">
                        <span class="text-xl">🍽️</span>
                    </div>
                    <div>
                        <h3 class="font-display font-bold text-2xl text-white">Recipedia</h3>
                        <p class="text-xs text-primary-400 tracking-widest uppercase">
                            Resep Nusantara
                        </p>
                    </div>
                </div>
                <p class="text-stone-400 text-sm leading-relaxed max-w-sm mb-6">
                    Platform berbagi resep masakan terlengkap. Temukan inspirasi memasak
                    dari seluruh Nusantara dan bagikan kreasi terbaikmu kepada dunia.
                </p>
                {{-- Social Media --}}
                <div class="flex gap-3">
                    @foreach([
                    ['icon' => '📘', 'label' => 'Facebook', 'href' => '#'],
                    ['icon' => '📸', 'label' => 'Instagram', 'href' => '#'],
                    ['icon' => '🐦', 'label' => 'Twitter', 'href' => '#'],
                    ['icon' => '▶️', 'label' => 'YouTube', 'href' => '#'],
                    ] as $social)
                    <a href="{{ $social['href'] }}"
                        class="w-9 h-9 rounded-lg bg-stone-800 hover:bg-primary-600
                                  flex items-center justify-center text-sm
                                  hover:scale-110 transition-all duration-200"
                        title="{{ $social['label'] }}">
                        {{ $social['icon'] }}
                    </a>
                    @endforeach
                </div>
            </div>

            {{-- Navigasi --}}
            <div>
                <h4 class="font-semibold text-white text-sm tracking-wider uppercase mb-5">
                    Navigasi
                </h4>
                <ul class="space-y-3">
                    @foreach([
                    ['route' => 'home', 'label' => 'Beranda'],
                    ['route' => 'search', 'label' => 'Cari Resep'],
                    ['route' => 'contact','label' => 'Hubungi Kami'],
                    ] as $link)
                    <li>
                        <a href="{{ route($link['route']) }}"
                            class="text-sm text-stone-400 hover:text-primary-400
                                      hover:translate-x-1 transition-all duration-200
                                      flex items-center gap-2">
                            <span class="w-1 h-1 rounded-full bg-primary-500"></span>
                            {{ $link['label'] }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- Kategori --}}
            <div>
                <h4 class="font-semibold text-white text-sm tracking-wider uppercase mb-5">
                    Kategori
                </h4>
                <ul class="space-y-3">
                    @foreach([
                    '🥗 Makanan Pembuka',
                    '🍛 Makanan Utama',
                    '🍚 Makanan Pendamping',
                    '🍰 Makanan Penutup',
                    '🥤 Minuman',
                    ] as $cat)
                    <li>
                        <a href="{{ route('search') }}"
                            class="text-sm text-stone-400 hover:text-primary-400
                                      hover:translate-x-1 transition-all duration-200
                                      flex items-center gap-2">
                            <span class="w-1 h-1 rounded-full bg-primary-500"></span>
                            {{ $cat }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>

        {{-- ── Bottom Bar ──────────────────────────── --}}
        <div class="mt-12 pt-6 border-t border-stone-800
                    flex flex-col sm:flex-row justify-between items-center gap-4">
            <p class="text-xs text-stone-500">
                © {{ date('Y') }}
                <span class="text-primary-400 font-semibold">Recipedia</span>.
                Dibuat dengan ❤️ untuk pecinta kuliner Indonesia.
            </p>
            <div class="flex gap-4">
                <a href="#" class="text-xs text-stone-500 hover:text-primary-400
                                   transition-colors duration-200">
                    Privasi
                </a>
                <a href="#" class="text-xs text-stone-500 hover:text-primary-400
                                   transition-colors duration-200">
                    Syarat & Ketentuan
                </a>
            </div>
        </div>
    </div>
</footer>