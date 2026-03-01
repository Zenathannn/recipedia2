<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategoryTagSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Makanan Pembuka', 'icon' => '🥗', 'color' => '#10b981', 'order' => 1],
            ['name' => 'Makanan Utama', 'icon' => '🍛', 'color' => '#f97316', 'order' => 2],
            ['name' => 'Makanan Pendamping', 'icon' => '🍚', 'color' => '#8b5cf6', 'order' => 3],
            ['name' => 'Makanan Penutup', 'icon' => '🍰', 'color' => '#ec4899', 'order' => 4],
            ['name' => 'Minuman', 'icon' => '🥤', 'color' => '#3b82f6', 'order' => 5],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['slug' => Str::slug($category['name'])],
                [
                    'name' => $category['name'],
                    'description' => 'Koleksi resep ' . strtolower($category['name']),
                    'icon' => $category['icon'],
                    'color' => $category['color'],
                    'order' => $category['order'],
                    'is_active' => true,
                ]
            );
        }

        $tags = [
            'Pedas',
            'Manis',
            'Gurih',
            'Vegetarian',
            'Vegan',
            'Tanpa Gluten',
            'Diet',
            'Cepat Saji',
            'Tradisional',
            'Modern',
            'Anak-anak',
            'Sehat',
            'Berkuah',
            'Bakar',
        ];

        foreach ($tags as $tag) {
            Tag::updateOrCreate(
                ['slug' => Str::slug($tag)],
                ['name' => $tag]
            );
        }
    }
}
