<?php

namespace App\Livewire\Admin;

use App\Models\ContactMessage;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Pesan Kontak')]
class ContactMessages extends Component
{
    use WithPagination;

    public string $search = '';
    public string $readFilter = 'all';

    public bool $showFormModal = false;
    public bool $showDeleteModal = false;
    public ?int $editingId = null;
    public ?int $deletingId = null;

    public string $name = '';
    public string $email = '';
    public string $subject = '';
    public string $message = '';
    public int $is_read = 0;

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedReadFilter(): void
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
        $contact = ContactMessage::findOrFail($id);

        $this->editingId = $contact->id;
        $this->name = $contact->name;
        $this->email = $contact->email;
        $this->subject = $contact->subject;
        $this->message = $contact->message;
        $this->is_read = $contact->is_read ? 1 : 0;
        $this->showFormModal = true;
    }

    public function save(): void
    {
        $this->validate([
            'name' => ['required', 'string', 'min:2', 'max:100'],
            'email' => ['required', 'email', 'max:150'],
            'subject' => ['required', 'string', 'min:3', 'max:150'],
            'message' => ['required', 'string', 'min:5', 'max:3000'],
            'is_read' => ['required', 'integer', 'in:0,1'],
        ]);

        $payload = [
            'name' => trim($this->name),
            'email' => trim($this->email),
            'subject' => trim($this->subject),
            'message' => trim($this->message),
            'is_read' => (bool) $this->is_read,
        ];

        if ($this->editingId) {
            ContactMessage::findOrFail($this->editingId)->update($payload);
        } else {
            ContactMessage::create($payload);
        }

        $this->closeFormModal();
    }

    public function markAsRead(int $id): void
    {
        ContactMessage::findOrFail($id)->update(['is_read' => true]);
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

        ContactMessage::findOrFail($this->deletingId)->delete();
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
        $this->email = '';
        $this->subject = '';
        $this->message = '';
        $this->is_read = 0;
        $this->resetValidation();
    }

    public function render()
    {
        $query = ContactMessage::query();

        if ($this->search !== '') {
            $query->where(function ($builder) {
                $builder->where('name', 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%")
                    ->orWhere('subject', 'like', "%{$this->search}%");
            });
        }

        if ($this->readFilter !== 'all') {
            $query->where('is_read', $this->readFilter === 'read');
        }

        $messages = $query->latest()->paginate(10);

        return view('livewire.admin.contact-messages', compact('messages'))
            ->layout('layouts.admin');
    }
}
