<?php

namespace App\Livewire\Guest;

use App\Models\Category;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Beranda')]
class HomePage extends Component
{
    public string $search = '';

    // ── Toggle Favorite ──────────────────────────────────
    public function toggleFavorite(int $recipeId): void
    {
        if (!auth()->check()) {
            $this->dispatch('toast', [
                'type'    => 'error',
                'title'   => 'Login diperlukan',
                'message' => 'Silakan login untuk menyimpan resep favorit.',
            ]);
            return;
        }

        $user   = auth()->user();
        $exists = $user->favorites()->where('recipe_id', $recipeId)->exists();

        if ($exists) {
            $user->favorites()->where('recipe_id', $recipeId)->delete();
            $this->dispatch('toast', [
                'type'  => 'info',
                'title' => 'Dihapus dari favorit',
            ]);
        } else {
            $user->favorites()->create(['recipe_id' => $recipeId]);
            $this->dispatch('toast', [
                'type'  => 'success',
                'title' => 'Ditambahkan ke favorit ❤️',
            ]);
        }
    }

    // ── Render ───────────────────────────────────────────
    public function render()
    {
        $homeData = Cache::remember('homepage.data', now()->addMinutes(5), function () {
            $heroImageFiles = File::glob(storage_path('app/public/hero/*.{jpg,jpeg,png,webp,avif}'), GLOB_BRACE) ?: [];
            sort($heroImageFiles);
            $heroImages = array_map(
                static fn(string $path): string => asset('storage/hero/' . basename($path)),
                $heroImageFiles
            );

            return [
                'heroImages' => $heroImages,
                'categories' => Category::active()->withCount([
                    'recipes' => fn($q) => $q->where('status', 'approved'),
                ])->get(),
                'featuredRecipes' => Recipe::approved()
                    ->featured()
                    ->with(['user', 'category', 'tags'])
                    ->withCount('favorites')
                    ->latest()
                    ->take(6)
                    ->get(),
                'latestRecipes' => Recipe::approved()
                    ->with(['user', 'category', 'tags'])
                    ->withCount('favorites')
                    ->latest()
                    ->take(8)
                    ->get(),
                'popularRecipes' => Recipe::approved()
                    ->with(['user', 'category'])
                    ->withCount('favorites')
                    ->orderByDesc('views_count')
                    ->take(4)
                    ->get(),
                'totalRecipes' => Recipe::approved()->count(),
                'totalChefs' => User::where('role', 'user')->count(),
            ];
        });

        $categories = $homeData['categories'];
        $heroImages = $homeData['heroImages'] ?? [];
        $featuredRecipes = $homeData['featuredRecipes'];
        $latestRecipes = $homeData['latestRecipes'];
        $popularRecipes = $homeData['popularRecipes'];
        $totalRecipes = $homeData['totalRecipes'];
        $totalChefs = $homeData['totalChefs'];
        $favoritedRecipeIds = [];

        if (auth()->check()) {
            $visibleRecipeIds = $featuredRecipes->pluck('id')
                ->merge($latestRecipes->pluck('id'))
                ->unique()
                ->values();

            $favoritedRecipeIds = auth()->user()
                ->favorites()
                ->whereIn('recipe_id', $visibleRecipeIds)
                ->pluck('recipe_id')
                ->all();
        }

        return view('livewire.guest.home-page', compact(
            'heroImages',
            'categories',
            'featuredRecipes',
            'latestRecipes',
            'popularRecipes',
            'totalRecipes',
            'totalChefs',
            'favoritedRecipeIds',
        ));
    }
}
