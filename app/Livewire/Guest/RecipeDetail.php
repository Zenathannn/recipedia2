<?php

namespace App\Livewire\Guest;

use App\Models\Recipe;
use App\Models\Comment;
use Livewire\Component;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Cookie;

class RecipeDetail extends Component
{
    public string $slug;
    public Recipe $recipe;
    public string $commentContent = '';

    public function mount(string $slug): void
    {
        $this->slug = $slug;
        $this->recipe = Recipe::with([
            'user',
            'category',
            'tags',
            'images',
            'ingredients',
            'steps',
            'comments.user',
            'comments.replies.user',
        ])
            ->withCount('favorites')
            ->where('slug', $slug)
            ->firstOrFail();

        // Increment views (hanya sekali per session)
        $cookieKey = 'viewed_recipe_' . $this->recipe->id;
        if (!Cookie::get($cookieKey)) {
            $this->recipe->incrementViews();
            Cookie::queue($cookieKey, true, 60 * 24); // 24 jam
        }
    }

    public function toggleFavorite(): void
    {
        if (!auth()->check()) {
            $this->dispatch('toast', [
                'type'    => 'error',
                'title'   => 'Login diperlukan',
                'message' => 'Silakan login untuk menyimpan resep favorit.',
            ]);
            return;
        }

        $user = auth()->user();
        $exists = $user->favorites()->where('recipe_id', $this->recipe->id)->exists();

        if ($exists) {
            $user->favorites()->where('recipe_id', $this->recipe->id)->delete();
            $this->dispatch('toast', ['type' => 'info', 'title' => 'Dihapus dari favorit']);
        } else {
            $user->favorites()->create(['recipe_id' => $this->recipe->id]);
            $this->dispatch('toast', ['type' => 'success', 'title' => 'Ditambahkan ke favorit ❤️']);
        }

        $this->recipe->refresh();
        $this->recipe->loadCount('favorites');
    }

    public function postComment(): void
    {
        if (!auth()->check()) {
            $this->dispatch('toast', [
                'type'    => 'error',
                'title'   => 'Login diperlukan',
            ]);
            return;
        }

        $this->validate([
            'commentContent' => 'required|min:3|max:1000',
        ], [
            'commentContent.required' => 'Komentar tidak boleh kosong',
            'commentContent.min'      => 'Komentar minimal 3 karakter',
            'commentContent.max'      => 'Komentar maksimal 1000 karakter',
        ]);

        Comment::create([
            'recipe_id' => $this->recipe->id,
            'user_id'   => auth()->id(),
            'content'   => $this->commentContent,
        ]);

        $this->commentContent = '';
        $this->recipe->load('comments.user', 'comments.replies.user');

        $this->dispatch('toast', [
            'type'  => 'success',
            'title' => 'Komentar berhasil ditambahkan! 💬',
        ]);
    }

    public function getTitle(): string
    {
        return $this->recipe->title;
    }

    public function render()
    {
        $relatedRecipes = Recipe::approved()
            ->where('category_id', $this->recipe->category_id)
            ->where('id', '!=', $this->recipe->id)
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('livewire.guest.recipe-detail', compact('relatedRecipes'))
            ->title($this->getTitle());
    }
}
