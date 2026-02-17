<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ingredient extends Model
{
    use HasFactory;

    protected $fillable = [
        'recipe_id',
        'name',
        'amount',
        'unit',
        'notes',
        'order',
    ];

    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }

    // Contoh: "200 gram bawang merah, cincang halus"
    public function getFormattedAttribute(): string
    {
        $text = trim("{$this->amount} {$this->unit} {$this->name}");
        if ($this->notes) {
            $text .= ", {$this->notes}";
        }
        return $text;
    }
}
