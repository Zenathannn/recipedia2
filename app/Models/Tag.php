<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'color'];

    // ── Relasi ──────────────────────────────────────
    public function recipes()
    {
        return $this->belongsToMany(Recipe::class, 'recipe_tag');
    }
}
