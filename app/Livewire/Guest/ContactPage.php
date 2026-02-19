<?php

namespace App\Livewire\Guest;

use App\Models\ContactMessage;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Hubungi Kami')]
class ContactPage extends Component
{
    public string $name = '';
    public string $email = '';
    public string $subject = '';
    public string $message = '';

    protected array $rules = [
        'name'    => 'required|min:3|max:100',
        'email'   => 'required|email|max:100',
        'subject' => 'required|min:5|max:200',
        'message' => 'required|min:10|max:2000',
    ];

    protected array $messages = [
        'name.required'    => 'Nama wajib diisi',
        'email.required'   => 'Email wajib diisi',
        'email.email'      => 'Format email tidak valid',
        'subject.required' => 'Subjek wajib diisi',
        'message.required' => 'Pesan wajib diisi',
        'message.min'      => 'Pesan minimal 10 karakter',
    ];

    public function mount(): void
    {
        if (auth()->check()) {
            $this->name  = auth()->user()->name;
            $this->email = auth()->user()->email;
        }
    }

    public function submit(): void
    {
        $this->validate();

        ContactMessage::create([
            'name'    => $this->name,
            'email'   => $this->email,
            'subject' => $this->subject,
            'message' => $this->message,
        ]);

        $this->dispatch('toast', [
            'type'    => 'success',
            'title'   => 'Pesan terkirim! ✅',
            'message' => 'Kami akan merespons sesegera mungkin.',
        ]);

        $this->reset(['subject', 'message']);
    }

    public function render()
    {
        return view('livewire.guest.contact-page');
    }
}
