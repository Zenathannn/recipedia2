<?php

namespace App\Livewire\Admin;

use App\Models\Recipe;
use App\Models\User;
use App\Models\Comment;
use App\Models\ContactMessage;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Admin Dashboard')]
class Dashboard extends Component
{
    public function render()
    {
        $stats = [
            'total_users'      => User::where('role', 'user')->count(),
            'total_recipes'    => Recipe::count(),
            'pending_recipes'  => Recipe::where('status', 'pending')->count(),
            'approved_recipes' => Recipe::where('status', 'approved')->count(),
            'total_views'      => Recipe::sum('views_count'),
            'unread_messages'  => ContactMessage::where('is_read', false)->count(),
        ];

        $pendingRecipes = Recipe::where('status', 'pending')
            ->with('user')
            ->latest()
            ->take(5)
            ->get();

        $recentUsers = User::where('role', 'user')
            ->latest()
            ->take(5)
            ->get();

        return view('livewire.admin.dashboard', compact('stats', 'pendingRecipes', 'recentUsers'))
            ->layout('layouts.admin');
    }
}
