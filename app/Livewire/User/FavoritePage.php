<?php

namespace App\Livewire\User;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;

#[Title('Resep Favorit')]
class FavoritePage extends Component
{
    use WithPagination;

    public function removeFavorite(int $recipeId): void
    {
        auth()->user()->favorites()->where('recipe_id', $recipeId)->delete();

        $this->dispatch('toast', [
            'type'  => 'success',
            'title' => 'Dihapus dari favorit',
        ]);
    }

    public function render()
    {
        $favorites = auth()->user()
            ->favoriteRecipes()
            ->with(['user', 'category', 'tags'])
            ->withCount('favorites')
            ->latest('favorites.created_at')
            ->paginate(12);

        return view('livewire.user.favorite-page', compact('favorites'));
    }
}
