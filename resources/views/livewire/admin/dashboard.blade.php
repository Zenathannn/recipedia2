<div class="space-y-6">

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach([
        ['label' => 'Total User', 'value' => $stats['total_users'], 'icon' => '👥', 'color' => 'blue'],
        ['label' => 'Total Resep', 'value' => $stats['total_recipes'], 'icon' => '📖', 'color' => 'green'],
        ['label' => 'Pending', 'value' => $stats['pending_recipes'], 'icon' => '⏳', 'color' => 'yellow'],
        ['label' => 'Total Views', 'value' => number_format($stats['total_views']), 'icon' => '👁️', 'color' => 'purple'],
        ] as $stat)
        <div class="bg-white rounded-2xl p-6 shadow-md border border-stone-100">
            <div class="flex items-center justify-between mb-4">
                <span class="text-3xl">{{ $stat['icon'] }}</span>
                <div class="w-10 h-10 rounded-xl bg-{{ $stat['color'] }}-100 
                                flex items-center justify-center">
                    <div class="w-2 h-2 rounded-full bg-{{ $stat['color'] }}-500"></div>
                </div>
            </div>
            <p class="text-sm text-stone-500 uppercase tracking-wider mb-1">
                {{ $stat['label'] }}
            </p>
            <p class="font-display font-bold text-3xl text-stone-900">
                {{ $stat['value'] }}
            </p>
        </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Pending Recipes --}}
        <div class="bg-white rounded-2xl p-6 shadow-md border border-stone-100">
            <h3 class="font-display font-bold text-xl text-stone-900 mb-4">
                ⏳ Resep Pending Approval
            </h3>
            @forelse($pendingRecipes as $recipe)
            <a href="{{ route('admin.recipes') }}"
                class="flex items-center gap-3 p-3 rounded-xl hover:bg-stone-50
                          transition-colors duration-200 mb-2">
                <img src="{{ $recipe->thumbnail_url }}"
                    class="w-12 h-12 rounded-lg object-cover" />
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-stone-900 text-sm truncate">
                        {{ $recipe->title }}
                    </p>
                    <p class="text-xs text-stone-500">
                        oleh {{ $recipe->user->name }}
                    </p>
                </div>
                <span class="badge bg-yellow-100 text-yellow-700">Pending</span>
            </a>
            @empty
            <p class="text-stone-500 text-sm text-center py-8">
                Tidak ada resep pending
            </p>
            @endforelse
        </div>

        {{-- Recent Users --}}
        <div class="bg-white rounded-2xl p-6 shadow-md border border-stone-100">
            <h3 class="font-display font-bold text-xl text-stone-900 mb-4">
                👥 User Terbaru
            </h3>
            @forelse($recentUsers as $user)
            <div class="flex items-center gap-3 p-3 rounded-xl hover:bg-stone-50
                            transition-colors duration-200 mb-2">
                <img src="{{ $user->avatar_url }}"
                    class="w-12 h-12 rounded-full object-cover" />
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-stone-900 text-sm">
                        {{ $user->name }}
                    </p>
                    <p class="text-xs text-stone-500">
                        {{ $user->email }}
                    </p>
                </div>
                <span class="text-xs text-stone-400">
                    {{ $user->created_at->diffForHumans() }}
                </span>
            </div>
            @empty
            <p class="text-stone-500 text-sm text-center py-8">
                Belum ada user
            </p>
            @endforelse
        </div>
    </div>
</div>