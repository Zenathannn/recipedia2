<?php

namespace App\Livewire\Admin;

use App\Models\Comment;
use App\Models\Recipe;
use App\Models\User;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Kelola Komentar')]
class ManageComments extends Component
{
    use WithPagination;

    public string $search = '';
    public string $approvalFilter = 'all';

    public bool $showFormModal = false;
    public bool $showDeleteModal = false;
    public ?int $editingId = null;
    public ?int $deletingId = null;

    public ?int $recipe_id = null;
    public ?int $user_id = null;
    public string $content = '';
    public int $is_approved = 1;

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedApprovalFilter(): void
    {
        $this->resetPage();
    }

    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->showFormModal = true;
    }

    public function openEditModal(int $id): void
    {
        $comment = Comment::findOrFail($id);
        $this->editingId = $comment->id;
        $this->recipe_id = $comment->recipe_id;
        $this->user_id = $comment->user_id;
        $this->content = $comment->content;
        $this->is_approved = $comment->is_approved ? 1 : 0;
        $this->showFormModal = true;
    }

    public function save(): void
    {
        $this->validate([
            'recipe_id' => ['required', 'exists:recipes,id'],
            'user_id' => ['required', 'exists:users,id'],
            'content' => ['required', 'string', 'min:3', 'max:1000'],
            'is_approved' => ['required', 'integer', 'in:0,1'],
        ]);

        $payload = [
            'recipe_id' => $this->recipe_id,
            'user_id' => $this->user_id,
            'content' => trim($this->content),
            'is_approved' => (bool) $this->is_approved,
            'parent_id' => null,
        ];

        if ($this->editingId) {
            Comment::findOrFail($this->editingId)->update($payload);
        } else {
            Comment::create($payload);
        }

        $this->closeFormModal();
    }

    public function confirmDelete(int $id): void
    {
        $this->deletingId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        if ($this->deletingId === null) {
            return;
        }

        Comment::findOrFail($this->deletingId)->delete();
        $this->closeDeleteModal();
    }

    public function closeFormModal(): void
    {
        $this->showFormModal = false;
        $this->resetForm();
    }

    public function closeDeleteModal(): void
    {
        $this->showDeleteModal = false;
        $this->deletingId = null;
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->recipe_id = null;
        $this->user_id = null;
        $this->content = '';
        $this->is_approved = 1;
        $this->resetValidation();
    }

    public function render()
    {
        $query = Comment::query()->with(['recipe:id,title', 'user:id,name'])->whereNull('parent_id');

        if ($this->search !== '') {
            $query->where(function ($builder) {
                $builder->where('content', 'like', "%{$this->search}%")
                    ->orWhereHas('recipe', fn ($q) => $q->where('title', 'like', "%{$this->search}%"))
                    ->orWhereHas('user', fn ($q) => $q->where('name', 'like', "%{$this->search}%"));
            });
        }

        if ($this->approvalFilter !== 'all') {
            $query->where('is_approved', $this->approvalFilter === 'approved');
        }

        $comments = $query->latest()->paginate(10);
        $recipes = Recipe::query()->select('id', 'title')->latest()->limit(200)->get();
        $users = User::query()->select('id', 'name')->orderBy('name')->limit(200)->get();

        return view('livewire.admin.manage-comments', compact('comments', 'recipes', 'users'))
            ->layout('layouts.admin');
    }
}
