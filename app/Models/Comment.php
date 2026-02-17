<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'recipe_id',
        'user_id',
        'parent_id',
        'content',
        'is_approved',
    ];

    protected function casts(): array
    {
        return ['is_approved' => 'boolean'];
    }

    // ── Relasi ──────────────────────────────────────
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }

    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id')
            ->where('is_approved', true)
            ->latest();
    }
}
