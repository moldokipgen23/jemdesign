<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Collection;

class CollectionSeeder extends Seeder
{
    public function run(): void
    {
        $collections = [
            [
                'name'       => 'Signature Series',
                'slug'       => 'signature-series',
                'description'=> 'Our flagship heritage shirts — modern silhouettes carrying traditional Kuki-Zo motifs.',
                'sort_order' => 1,
            ],
            [
                'name'       => 'New Arrivals',
                'slug'       => 'new-arrivals',
                'description'=> 'The latest additions to the Jem Designs family.',
                'sort_order' => 2,
            ],
            [
                'name'       => 'HerEDIT',
                'slug'       => 'heredit',
                'description'=> "Women's stoles and shawls woven with heritage precision.",
                'sort_order' => 3,
            ],
            [
                'name'       => 'Blossoms',
                'slug'       => 'blossoms',
                'description'=> 'Soft, feminine pieces in bloom-inspired palettes.',
                'sort_order' => 4,
            ],
        ];

        foreach ($collections as $col) {
            Collection::firstOrCreate(['slug' => $col['slug']], array_merge($col, ['is_active' => true]));
        }
    }
}
