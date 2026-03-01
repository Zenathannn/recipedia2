<?php

namespace App\Livewire\Admin;

use App\Models\Recipe;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Kelola Resep')]
class ManageRecipes extends Component
{
    use WithPagination;

    public string $filter = 'all';
    public string $search = '';
    public ?int $selectedRecipe = null;
    public ?int $deletingRecipe = null;
    public string $rejectionReason = '';

    public function approve(int $recipeId): void
    {
        $recipe = Recipe::findOrFail($recipeId);
        $recipe->update([
            'status' => 'approved',
            'rejection_reason' => null,
        ]);

        $this->dispatch('toast', type: 'success', title: 'Resep disetujui');
    }

    public function openRejectModal(int $recipeId): void
    {
        $this->selectedRecipe = $recipeId;
        $this->rejectionReason = '';
    }

    public function reject(): void
    {
        if ($this->selectedRecipe === null) {
            return;
        }

        $this->validate([
            'rejectionReason' => 'required|min:10|max:500',
        ]);

        $recipe = Recipe::findOrFail($this->selectedRecipe);
        $recipe->update([
            'status' => 'rejected',
            'rejection_reason' => $this->rejectionReason,
        ]);

        $this->dispatch('toast', type: 'info', title: 'Resep ditolak');
        $this->reset(['selectedRecipe', 'rejectionReason']);
    }

    public function confirmDelete(int $recipeId): void
    {
        $this->deletingRecipe = $recipeId;
    }

    public function cancelDelete(): void
    {
        $this->deletingRecipe = null;
    }

    public function deleteRecipe(int $recipeId): void
    {
        Recipe::findOrFail($recipeId)->delete();
        $this->deletingRecipe = null;

        $this->dispatch('toast', type: 'success', title: 'Resep dihapus');
    }

    public function render()
    {
        $query = Recipe::with(['user', 'category'])
            ->withCount(['favorites', 'comments']);

        if ($this->search) {
            $query->where('title', 'like', "%{$this->search}%");
        }

        if ($this->filter !== 'all') {
            $query->where('status', $this->filter);
        }

        $recipes = $query->latest()->paginate(15);

        $counts = [
            'all' => Recipe::count(),
            'pending' => Recipe::where('status', 'pending')->count(),
            'approved' => Recipe::where('status', 'approved')->count(),
            'rejected' => Recipe::where('status', 'rejected')->count(),
        ];

        return view('livewire.admin.manage-recipes', compact('recipes', 'counts'))
            ->layout('layouts.admin');
    }
}

