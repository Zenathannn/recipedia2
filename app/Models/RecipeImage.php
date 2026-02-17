<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RecipeImage extends Model
{
    use HasFactory;

    protected $fillable = ['recipe_id', 'image_path', 'order'];

    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }

    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->image_path);
    }
}
