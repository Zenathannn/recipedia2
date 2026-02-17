<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name'     => 'Admin Recipedia',
            'username' => 'admin',
            'email'    => 'admin@recipedia.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        // Demo User
        User::create([
            'name'     => 'Chef Budi',
            'username' => 'chefbudi',
            'email'    => 'user@recipedia.com',
            'password' => Hash::make('password'),
            'role'     => 'user',
        ]);

        // Categories
        $categories = [
            ['name' => 'Makanan Pembuka',  'icon' => '🥗', 'color' => '#10b981', 'order' => 1],
            ['name' => 'Makanan Utama',    'icon' => '🍛', 'color' => '#f97316', 'order' => 2],
            ['name' => 'Makanan Pendamping', 'icon' => '🍚', 'color' => '#8b5cf6', 'order' => 3],
            ['name' => 'Makanan Penutup',  'icon' => '🍰', 'color' => '#ec4899', 'order' => 4],
            ['name' => 'Minuman',          'icon' => '🥤', 'color' => '#3b82f6', 'order' => 5],
        ];

        foreach ($categories as $cat) {
            Category::create([
                'name'        => $cat['name'],
                'slug'        => Str::slug($cat['name']),
                'icon'        => $cat['icon'],
                'color'       => $cat['color'],
                'order'       => $cat['order'],
                'description' => 'Koleksi resep ' . strtolower($cat['name']),
            ]);
        }

        // Tags
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
            'Bakar'
        ];

        foreach ($tags as $tag) {
            Tag::create([
                'name' => $tag,
                'slug' => Str::slug($tag),
            ]);
        }
    }
}
