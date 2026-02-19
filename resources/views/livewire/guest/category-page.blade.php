<div class="min-h-screen">

    {{-- Hero Category --}}
    <section class="relative py-20 overflow-hidden"
        style="background: linear-gradient(135deg, {{ $category->color }}dd, {{ $category->color }}88)">

        <div class="absolute inset-0 opacity-10"
            style="background-image: radial-gradient(circle, white 1px, transparent 1px);
                    background-size: 30px 30px;"></div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="text-7xl block mb-4">{{ $category->icon }}</span>
            <h1 class="font-display text-5xl md:text-6xl font-bold text-white mb-4">
                {{ $category->name }}
            </h1>
            <p class="text-white/90 text-lg max-w-2xl mx-auto">
                {{ $category->description ?? 'Koleksi resep ' . strtolower($category->name) . ' terbaik' }}
            </p>
        </div>
    </section>

    {{-- Recipes --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="flex items-center justify-between mb-8">
            <p class="text-sm text-stone-500">
                Ditemukan <span class="font-semibold text-stone-900">{{ $recipes->total() }}</span> resep
            </p>
            <a href="{{ route('search') }}"
                class="text-sm text-primary-600 hover:text-primary-700 font-medium">
                ← Kembali ke Pencarian
            </a>
        </div>

        @if($recipes->count())
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-10">
            @foreach($recipes as $recipe)
            @include('components.recipe-card', ['recipe' => $recipe])
            @endforeach
        </div>

        {{ $recipes->links() }}
        @else
        <div class="text-center py-20">
            <span class="text-6xl block mb-4">{{ $category->icon }}</span>
            <h3 class="font-display text-2xl font-bold text-stone-900 mb-2">
                Belum Ada Resep
            </h3>
            <p class="text-stone-500">
                Jadilah yang pertama berbagi resep {{ strtolower($category->name) }}!
            </p>
        </div>
        @endif
    </section>
</div>