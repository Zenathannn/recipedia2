<?php

namespace App\Livewire\Admin;

use App\Models\Tag;
use Illuminate\Support\Str;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

#[Title('Kelola Tag')]
class ManageTags extends Component
{
    use WithPagination;

    public string $search = '';
    public bool $showFormModal = false;
    public bool $showDeleteModal = false;
    public ?int $editingId = null;
    public ?int $deletingId = null;

    public string $name = '';
    public string $color = '#6b7280';

    public function updatedSearch(): void
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
        $tag = Tag::findOrFail($id);
        $this->editingId = $tag->id;
        $this->name = $tag->name;
        $this->color = $tag->color ?: '#6b7280';
        $this->showFormModal = true;
    }

    public function save(): void
    {
        $this->validate([
            'name' => [
                'required',
                'string',
                'min:2',
                'max:50',
                Rule::unique('tags', 'name')->ignore($this->editingId),
            ],
            'color' => ['required', 'string', 'max:20'],
        ]);

        $name = trim($this->name);
        $slug = $this->buildUniqueSlug(Str::slug($name));

        $payload = [
            'name' => $name,
            'slug' => $slug,
            'color' => $this->color,
        ];

        if ($this->editingId) {
            Tag::findOrFail($this->editingId)->update($payload);
        } else {
            Tag::create($payload);
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

        Tag::findOrFail($this->deletingId)->delete();
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
        $this->name = '';
        $this->color = '#6b7280';
        $this->resetValidation();
    }

    private function buildUniqueSlug(string $base): string
    {
        $slug = $base !== '' ? $base : 'tag';
        $suffix = 2;

        while (
            Tag::query()
                ->where('slug', $slug)
                ->when($this->editingId, fn ($q) => $q->where('id', '!=', $this->editingId))
                ->exists()
        ) {
            $slug = ($base !== '' ? $base : 'tag') . '-' . $suffix;
            $suffix++;
        }

        return $slug;
    }

    public function render()
    {
        $query = Tag::query()->withCount('recipes');

        if ($this->search !== '') {
            $query->where(function ($builder) {
                $builder->where('name', 'like', "%{$this->search}%")
                    ->orWhere('slug', 'like', "%{$this->search}%");
            });
        }

        $tags = $query->orderBy('name')->paginate(10);

        return view('livewire.admin.manage-tags', compact('tags'))
            ->layout('layouts.admin');
    }
}
