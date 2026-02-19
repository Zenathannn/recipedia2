<?php

namespace App\Livewire\Guest;

use App\Models\Category;
use App\Models\Recipe;
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
        $categories = Category::active()->withCount([
            'recipes' => fn($q) => $q->where('status', 'approved'),
        ])->get();

        $featuredRecipes = Recipe::approved()
            ->featured()
            ->with(['user', 'category', 'tags'])
            ->withCount('favorites')
            ->latest()
            ->take(6)
            ->get();

        $latestRecipes = Recipe::approved()
            ->with(['user', 'category'])
            ->withCount('favorites')
            ->latest()
            ->take(8)
            ->get();

        $popularRecipes = Recipe::approved()
            ->with(['user', 'category'])
            ->withCount('favorites')
            ->orderByDesc('views_count')
            ->take(4)
            ->get();

        $totalRecipes = Recipe::approved()->count();
        $totalChefs   = \App\Models\User::where('role', 'user')->count();

        return view('livewire.guest.home-page', compact(
            'categories',
            'featuredRecipes',
            'latestRecipes',
            'popularRecipes',
            'totalRecipes',
            'totalChefs',
        ));
    }
}
