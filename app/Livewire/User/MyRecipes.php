<?php

namespace App\Livewire\User;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;

#[Title('Resep Saya')]
class MyRecipes extends Component
{
    use WithPagination;

    public string $filter = 'all'; // all, approved, pending, rejected, draft

    public function deleteRecipe(int $recipeId): void
    {
        $recipe = auth()->user()->recipes()->findOrFail($recipeId);
        $recipe->delete();

        $this->dispatch('toast', [
            'type'  => 'success',
            'title' => 'Resep berhasil dihapus',
        ]);
    }

    public function render()
    {
        $query = auth()->user()->recipes()
            ->with(['category', 'tags'])
            ->withCount(['favorites', 'comments']);

        if ($this->filter !== 'all') {
            $query->where('status', $this->filter);
        }

        $recipes = $query->latest()->paginate(9);

        $counts = [
            'all'      => auth()->user()->recipes()->count(),
            'approved' => auth()->user()->recipes()->where('status', 'approved')->count(),
            'pending'  => auth()->user()->recipes()->where('status', 'pending')->count(),
            'rejected' => auth()->user()->recipes()->where('status', 'rejected')->count(),
            'draft'    => auth()->user()->recipes()->where('status', 'draft')->count(),
        ];

        return view('livewire.user.my-recipes', compact('recipes', 'counts'));
    }
}
