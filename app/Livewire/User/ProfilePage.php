<?php

namespace App\Livewire\User;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

#[Title('Profil Saya')]
class ProfilePage extends Component
{
    use WithFileUploads;

    public string $name = '';
    public string $username = '';
    public string $email = '';
    public string $phone = '';
    public string $bio = '';
    public $avatar;
    public $newAvatar;

    public string $currentPassword = '';
    public string $newPassword = '';
    public string $newPasswordConfirmation = '';

    public function mount(): void
    {
        $user = auth()->user();
        $this->name     = $user->name;
        $this->username = $user->username;
        $this->email    = $user->email;
        $this->phone    = $user->phone ?? '';
        $this->bio      = $user->bio ?? '';
        $this->avatar   = $user->avatar;
    }

    public function updateProfile(): void
    {
        $this->validate([
            'name'     => 'required|min:3|max:100',
            'username' => 'required|min:3|max:50|unique:users,username,' . auth()->id(),
            'email'    => 'required|email|unique:users,email,' . auth()->id(),
            'phone'    => 'nullable|max:20',
            'bio'      => 'nullable|max:500',
            'newAvatar' => 'nullable|image|max:2048', // 2MB
        ]);

        $user = auth()->user();
        $data = [
            'name'     => $this->name,
            'username' => $this->username,
            'email'    => $this->email,
            'phone'    => $this->phone,
            'bio'      => $this->bio,
        ];

        // Upload new avatar
        if ($this->newAvatar) {
            // Delete old avatar
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            $data['avatar'] = $this->newAvatar->store('avatars', 'public');
        }

        $user->update($data);

        $this->dispatch('toast', [
            'type'  => 'success',
            'title' => 'Profil berhasil diperbarui! ✅',
        ]);

        $this->reset('newAvatar');
        $this->avatar = $user->avatar;
    }

    public function updatePassword(): void
    {
        $this->validate([
            'currentPassword'         => 'required',
            'newPassword'             => 'required|min:8|confirmed',
            'newPasswordConfirmation' => 'required',
        ], [
            'currentPassword.required' => 'Password lama wajib diisi',
            'newPassword.required'     => 'Password baru wajib diisi',
            'newPassword.min'          => 'Password minimal 8 karakter',
            'newPassword.confirmed'    => 'Konfirmasi password tidak cocok',
        ]);

        $user = auth()->user();

        if (!Hash::check($this->currentPassword, $user->password)) {
            $this->addError('currentPassword', 'Password lama tidak sesuai');
            return;
        }

        $user->update([
            'password' => Hash::make($this->newPassword),
        ]);

        $this->reset(['currentPassword', 'newPassword', 'newPasswordConfirmation']);

        $this->dispatch('toast', [
            'type'  => 'success',
            'title' => 'Password berhasil diubah! 🔒',
        ]);
    }

    public function render()
    {
        $stats = [
            'recipes'   => auth()->user()->recipes()->approved()->count(),
            'pending'   => auth()->user()->recipes()->where('status', 'pending')->count(),
            'favorites' => auth()->user()->favorites()->count(),
            'views'     => auth()->user()->recipes()->approved()->sum('views_count'),
        ];

        return view('livewire.user.profile-page', compact('stats'));
    }
}
