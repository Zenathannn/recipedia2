<?php

namespace App\Livewire\Guest;

use App\Models\Category;
use App\Models\Recipe;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;

class CategoryPage extends Component
{
    use WithPagination;

    public string $slug;
    public Category $category;

    public function mount(string $slug): void
    {
        $this->slug = $slug;
        $this->category = Category::where('slug', $slug)->firstOrFail();
    }

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
            $this->dispatch('toast', ['type' => 'info', 'title' => 'Dihapus dari favorit']);
        } else {
            $user->favorites()->create(['recipe_id' => $recipeId]);
            $this->dispatch('toast', ['type' => 'success', 'title' => 'Ditambahkan ke favorit ❤️']);
        }
    }

    public function getTitle(): string
    {
        return $this->category->name;
    }

    public function render()
    {
        $recipes = Recipe::approved()
            ->where('category_id', $this->category->id)
            ->with(['user', 'category', 'tags'])
            ->withCount('favorites')
            ->latest()
            ->paginate(12);

        return view('livewire.guest.category-page', compact('recipes'))
            ->title($this->getTitle());
    }
}
