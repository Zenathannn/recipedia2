<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Recipe extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'slug',
        'description',
        'thumbnail',
        'prep_time',
        'cook_time',
        'servings',
        'difficulty',
        'status',
        'rejection_reason',
        'views_count',
        'is_featured',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'views_count' => 'integer',
        ];
    }

    // ── Relasi ──────────────────────────────────────
    public function user()
    {
        return $this->belongsTo(User::class); // satu resep dibuat oleh satu user (many-to-one)
    }

    public function category()
    {
        return $this->belongsTo(Category::class); // satu resep masuk ke dalam satu kategori (many-to-one)
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'recipe_tag'); // satu resep bisa punya banyak tag, dan satu tag bisa dipakai di banyak resep (many-to-many)
    }

    public function images()
    {
        return $this->hasMany(RecipeImage::class)->orderBy('order'); // satu resep bisa punya banyak gambar (one-to-many)
    }

    public function ingredients()
    {
        return $this->hasMany(Ingredient::class)->orderBy('order'); // satu resep bisa punya banyak bahan (one-to-many)
    }

    public function steps()
    {
        return $this->hasMany(Step::class)->orderBy('step_number'); // satu resep bisa punya banyak langkah (one-to-many)
    }

    public function comments()
    {
        return $this->hasMany(Comment::class) // satu resep bisa punya banyak komentar (one-to-many)
            ->whereNull('parent_id')
            ->where('is_approved', true)
            ->latest();
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function favoritedByUsers()
    {
        return $this->belongsToMany(User::class, 'favorites');
    }

    // ── Scope ────────────────────────────────────────
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved'); // hanya ambil resep yang sudah disetujui
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true); // hanya ambil resep yang ditandai sebagai unggulan
    }

    public function scopeByCategory($query, $categorySlug)
    {
        return $query->whereHas('category', fn($q) => $q->where('slug', $categorySlug)); // filter resep berdasarkan slug kategori
    }

    // ── Helper ───────────────────────────────────────
    public function getThumbnailUrlAttribute(): string // ambil url gambar thumbnail
    {
        if ($this->thumbnail) {
            return asset('storage/' . $this->thumbnail); 
        }
        return asset('images/recipe-default.jpg');
    }

    public function getTotalTimeAttribute(): int // hitung total waktu (persiapan + memasak)
    {
        return $this->prep_time + $this->cook_time; 
    }

    public function getDifficultyColorAttribute(): string // berikan kelas warna berdasarkan tingkat kesulitan
    {
        return match ($this->difficulty) {
            'mudah'  => 'text-green-600 bg-green-100', 
            'sedang' => 'text-yellow-600 bg-yellow-100',
            'sulit'  => 'text-red-600 bg-red-100',
            default  => 'text-gray-600 bg-gray-100',
        };
    }

    public function getStatusColorAttribute(): string // berikan kelas warna berdasarkan status resep
    {
        return match ($this->status) {
            'approved' => 'text-green-600 bg-green-100',
            'pending'  => 'text-yellow-600 bg-yellow-100',
            'rejected' => 'text-red-600 bg-red-100',
            'draft'    => 'text-gray-600 bg-gray-100',
            default    => 'text-gray-600 bg-gray-100',
        };
    }

    public function incrementViews(): void // fungsi untuk menambah jumlah views saat resep dilihat
    {
        $this->increment('views_count');
    }
}
