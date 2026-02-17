<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'image',
        'color',
        'order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    // ── Relasi ──────────────────────────────────────
    public function recipes()
    {
        return $this->hasMany(Recipe::class);
    }

    // ── Scope ────────────────────────────────────────
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('order');
    }

    // ── Helper ───────────────────────────────────────
    public function getImageUrlAttribute(): string
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return asset('images/category-default.jpg');
    }
}
