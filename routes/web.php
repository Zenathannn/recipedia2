<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Guest\HomePage;
use App\Livewire\Guest\RecipeDetail;
use App\Livewire\Guest\SearchPage;
use App\Livewire\Guest\CategoryPage;
use App\Livewire\Guest\ContactPage;
use App\Livewire\User\FavoritePage;
use App\Livewire\User\ProfilePage;
use App\Livewire\User\MyRecipes;
use App\Livewire\User\SubmitRecipe;
use App\Livewire\User\EditRecipe;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\ManageRecipes;
use App\Livewire\Admin\ManageUsers;
use App\Livewire\Admin\ManageCategories;
use App\Livewire\Admin\ManageTags;
use App\Livewire\Admin\ManageComments;
use App\Livewire\Admin\ContactMessages;

// ═══════════════════════════════════════════
//  PUBLIC ROUTES
// ═══════════════════════════════════════════
Route::get('/', HomePage::class)->name('home');

Route::get('/search', SearchPage::class)->name('search');
Route::get('/category/{slug}', CategoryPage::class)->name('category');
Route::get('/recipe/{slug}', RecipeDetail::class)->name('recipe.detail');
Route::get('/contact', ContactPage::class)->name('contact');

// ═══════════════════════════════════════════
//  AUTH ROUTES
// ═══════════════════════════════════════════
require __DIR__ . '/auth.php';

// ═══════════════════════════════════════════
//  USER ROUTES - sementara di-comment
// ═══════════════════════════════════════════
Route::middleware(['auth', 'role.user'])->group(function () {
    Route::get('/favourites', FavoritePage::class)->name('favourites');
    Route::get('/profile', ProfilePage::class)->name('profile');
    Route::get('/my-recipes', MyRecipes::class)->name('my-recipes');
    Route::get('/submit-recipe', SubmitRecipe::class)->name('submit-recipe');
    Route::get('/edit-recipe/{id}', EditRecipe::class)->name('edit-recipe');
});

// ═══════════════════════════════════════════
//  ADMIN ROUTES - sementara di-comment
// ═══════════════════════════════════════════
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/recipes', ManageRecipes::class)->name('recipes');
    Route::get('/users', ManageUsers::class)->name('users');
    Route::get('/categories', ManageCategories::class)->name('categories');
    Route::get('/tags', ManageTags::class)->name('tags');
    Route::get('/comments', ManageComments::class)->name('comments');
    Route::get('/messages', ContactMessages::class)->name('messages');
});

// ═══════════════════════════════════════════
//  REDIRECT SETELAH LOGIN
// ═══════════════════════════════════════════
Route::middleware('auth')->get('/dashboard', function () {
    if (auth()->user()->isAdmin()) {
        return redirect()->route('home'); // sementara redirect ke home
    }
    return redirect()->route('home');
})->name('dashboard');
