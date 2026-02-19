<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

#[Title('Kelola Pengguna')]
class ManageUsers extends Component
{
    use WithPagination;

    public string $search = '';
    public string $roleFilter = 'all';
    public string $statusFilter = 'all';

    public bool $showFormModal = false;
    public bool $showDeleteModal = false;
    public ?int $editingId = null;
    public ?int $deletingId = null;

    public string $name = '';
    public string $username = '';
    public string $email = '';
    public string $phone = '';
    public string $role = 'user';
    public int $is_active = 1;
    public string $password = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedRoleFilter(): void
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
        $user = User::findOrFail($id);

        $this->editingId = $user->id;
        $this->name = $user->name;
        $this->username = $user->username;
        $this->email = $user->email;
        $this->phone = (string) ($user->phone ?? '');
        $this->role = $user->role;
        $this->is_active = $user->is_active ? 1 : 0;
        $this->password = '';
        $this->showFormModal = true;
    }

    public function save(): void
    {
        $isEdit = $this->editingId !== null;

        $rules = [
            'name' => ['required', 'string', 'min:2', 'max:100'],
            'username' => [
                'required',
                'string',
                'min:3',
                'max:50',
                Rule::unique('users', 'username')->ignore($this->editingId),
            ],
            'email' => [
                'required',
                'email',
                'max:150',
                Rule::unique('users', 'email')->ignore($this->editingId),
            ],
            'phone' => ['nullable', 'string', 'max:20'],
            'role' => ['required', Rule::in(['admin', 'user'])],
            'is_active' => ['required', 'integer', Rule::in([0, 1])],
            'password' => $isEdit ? ['nullable', 'string', 'min:8'] : ['required', 'string', 'min:8'],
        ];

        $this->validate($rules);

        $payload = [
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
            'phone' => $this->phone ?: null,
            'role' => $this->role,
            'is_active' => (bool) $this->is_active,
        ];

        if ($this->password !== '') {
            $payload['password'] = $this->password;
        }

        if ($isEdit) {
            User::findOrFail($this->editingId)->update($payload);
        } else {
            User::create($payload);
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

        if (auth()->id() === $this->deletingId) {
            $this->addError('delete', 'Tidak bisa menghapus akun yang sedang login.');
            return;
        }

        User::findOrFail($this->deletingId)->delete();
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
        $this->resetErrorBag('delete');
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->name = '';
        $this->username = '';
        $this->email = '';
        $this->phone = '';
        $this->role = 'user';
        $this->is_active = 1;
        $this->password = '';
        $this->resetValidation();
    }

    public function render()
    {
        $query = User::query();

        if ($this->search !== '') {
            $query->where(function ($builder) {
                $builder->where('name', 'like', "%{$this->search}%")
                    ->orWhere('username', 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%");
            });
        }

        if ($this->roleFilter !== 'all') {
            $query->where('role', $this->roleFilter);
        }

        if ($this->statusFilter !== 'all') {
            $query->where('is_active', $this->statusFilter === 'active');
        }

        $users = $query->latest()->paginate(10);

        return view('livewire.admin.manage-users', compact('users'))
            ->layout('layouts.admin');
    }
}
