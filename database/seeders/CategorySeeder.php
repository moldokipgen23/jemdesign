<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => "Women's",    'slug' => 'womens',     'sort_order' => 1],
            ['name' => "Men's",      'slug' => 'mens',       'sort_order' => 2],
            ['name' => "Accessories",'slug' => 'accessories', 'sort_order' => 3],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(['slug' => $cat['slug']], array_merge($cat, ['is_active' => true]));
        }
    }
}
