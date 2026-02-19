<?php

namespace App\Livewire\Guest;

use App\Models\Recipe;
use App\Models\Category;
use App\Models\Tag;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;

#[Title('Cari Resep')]
class SearchPage extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url]
    public ?string $category = null;

    #[Url]
    public array $tags = [];

    #[Url]
    public string $difficulty = '';

    #[Url]
    public string $sortBy = 'latest'; // latest, popular, views

    public bool $showFilters = false;

    // ── Update Search ────────────────────────────────
    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedCategory(): void
    {
        $this->resetPage();
    }

    public function updatedDifficulty(): void
    {
        $this->resetPage();
    }

    public function updatedSortBy(): void
    {
        $this->resetPage();
    }

    // ── Toggle Tag ───────────────────────────────────
    public function toggleTag(int $tagId): void
    {
        if (in_array($tagId, $this->tags)) {
            $this->tags = array_values(array_diff($this->tags, [$tagId]));
        } else {
            $this->tags[] = $tagId;
        }
        $this->resetPage();
    }

    // ── Clear Filters ────────────────────────────────
    public function clearFilters(): void
    {
        $this->reset(['category', 'tags', 'difficulty', 'search']);
        $this->resetPage();
    }

    // ── Toggle Favorite ──────────────────────────────
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

    // ── Render ───────────────────────────────────────
    public function render()
    {
        $query = Recipe::approved()
            ->with(['user', 'category', 'tags'])
            ->withCount('favorites');

        // Search
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', "%{$this->search}%")
                    ->orWhere('description', 'like', "%{$this->search}%")
                    ->orWhereHas('tags', fn($tq) => $tq->where('name', 'like', "%{$this->search}%"));
            });
        }

        // Category Filter
        if ($this->category) {
            $query->whereHas('category', fn($q) => $q->where('slug', $this->category));
        }

        // Tags Filter
        if (!empty($this->tags)) {
            $query->whereHas('tags', fn($q) => $q->whereIn('tags.id', $this->tags));
        }

        // Difficulty Filter
        if ($this->difficulty) {
            $query->where('difficulty', $this->difficulty);
        }

        // Sorting
        $query = match ($this->sortBy) {
            'popular'  => $query->withCount('favorites')->orderByDesc('favorites_count'),
            'views'    => $query->orderByDesc('views_count'),
            default    => $query->latest(),
        };

        $recipes = $query->paginate(12);

        $categories = Category::active()->get();
        $allTags    = Tag::orderBy('name')->get();

        $hasFilters = $this->category || !empty($this->tags) || $this->difficulty || $this->search;

        return view('livewire.guest.search-page', compact(
            'recipes',
            'categories',
            'allTags',
            'hasFilters',
        ));
    }
}
