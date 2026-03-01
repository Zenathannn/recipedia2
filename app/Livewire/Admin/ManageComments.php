<?php

namespace App\Livewire\Admin;

use App\Models\Comment;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Kelola Komentar')]
class ManageComments extends Component
{
    use WithPagination;

    public string $search = '';
    public string $approvalFilter = 'all';

    public bool $showDeleteModal = false;
    public ?int $deletingId = null;

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedApprovalFilter(): void
    {
        $this->resetPage();
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

    public function closeDeleteModal(): void
    {
        $this->showDeleteModal = false;
        $this->deletingId = null;
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
        return view('livewire.admin.manage-comments', compact('comments'))
            ->layout('layouts.admin');
    }
}
