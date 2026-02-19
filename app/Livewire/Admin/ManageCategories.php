<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use Illuminate\Support\Str;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

#[Title('Kelola Kategori')]
class ManageCategories extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = 'all';

    public bool $showFormModal = false;
    public bool $showDeleteModal = false;
    public ?int $editingId = null;
    public ?int $deletingId = null;

    public string $name = '';
    public string $description = '';
    public string $icon = '';
    public string $color = '#f97316';
    public int $order = 0;
    public int $is_active = 1;

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedStatusFilter(): void
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
        $category = Category::findOrFail($id);

        $this->editingId = $category->id;
        $this->name = $category->name;
        $this->description = (string) ($category->description ?? '');
        $this->icon = (string) ($category->icon ?? '');
        $this->color = $category->color ?: '#f97316';
        $this->order = (int) $category->order;
        $this->is_active = $category->is_active ? 1 : 0;
        $this->showFormModal = true;
    }

    public function save(): void
    {
        $this->validate([
            'name' => [
                'required',
                'string',
                'min:2',
                'max:100',
                Rule::unique('categories', 'name')->ignore($this->editingId),
            ],
            'description' => ['nullable', 'string', 'max:1000'],
            'icon' => ['nullable', 'string', 'max:50'],
            'color' => ['required', 'string', 'max:20'],
            'order' => ['required', 'integer', 'min:0', 'max:999'],
            'is_active' => ['required', 'integer', Rule::in([0, 1])],
        ]);

        $name = trim($this->name);
        $slugBase = Str::slug($name);
        $slug = $this->buildUniqueSlug($slugBase);

        $payload = [
            'name' => $name,
            'slug' => $slug,
            'description' => $this->description ?: null,
            'icon' => $this->icon ?: null,
            'color' => $this->color,
            'order' => $this->order,
            'is_active' => (bool) $this->is_active,
        ];

        if ($this->editingId) {
            Category::findOrFail($this->editingId)->update($payload);
        } else {
            Category::create($payload);
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

        Category::findOrFail($this->deletingId)->delete();
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
        $this->description = '';
        $this->icon = '';
        $this->color = '#f97316';
        $this->order = 0;
        $this->is_active = 1;
        $this->resetValidation();
    }

    private function buildUniqueSlug(string $base): string
    {
        $slug = $base !== '' ? $base : 'kategori';
        $suffix = 2;

        while (
            Category::query()
                ->where('slug', $slug)
                ->when($this->editingId, fn ($q) => $q->where('id', '!=', $this->editingId))
                ->exists()
        ) {
            $slug = ($base !== '' ? $base : 'kategori') . '-' . $suffix;
            $suffix++;
        }

        return $slug;
    }

    public function render()
    {
        $query = Category::query()->withCount('recipes');

        if ($this->search !== '') {
            $query->where(function ($builder) {
                $builder->where('name', 'like', "%{$this->search}%")
                    ->orWhere('slug', 'like', "%{$this->search}%");
            });
        }

        if ($this->statusFilter !== 'all') {
            $query->where('is_active', $this->statusFilter === 'active');
        }

        $categories = $query->orderBy('order')->orderBy('name')->paginate(10);

        return view('livewire.admin.manage-categories', compact('categories'))
            ->layout('layouts.admin');
    }
}
