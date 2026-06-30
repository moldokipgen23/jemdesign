<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Support\Str;

class AttributeSeeder extends Seeder
{
    public function run(): void
    {
        // Color
        $color = Attribute::create(['name' => 'Color', 'slug' => 'color', 'sort_order' => 0]);
        $colors = [
            ['name' => 'Dusty Blue',    'hex' => '#6B8DAE'],
            ['name' => 'Forest Green',  'hex' => '#2D5A3D'],
            ['name' => 'Terracotta',    'hex' => '#C45B3F'],
            ['name' => 'Midnight Black','hex' => '#1A1A2E'],
            ['name' => 'Ivory White',   'hex' => '#F5F0E8'],
            ['name' => 'Rust',          'hex' => '#B7410E'],
            ['name' => 'Navy',          'hex' => '#1B2A4A'],
            ['name' => 'Sage',          'hex' => '#9CAF88'],
        ];
        foreach ($colors as $i => $c) {
            $color->values()->create(['name' => $c['name'], 'slug' => Str::slug($c['name']), 'hex_code' => $c['hex'], 'sort_order' => $i]);
        }

        // Size
        $size = Attribute::create(['name' => 'Size', 'slug' => 'size', 'sort_order' => 1]);
        $sizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'Free Size'];
        foreach ($sizes as $i => $s) {
            $size->values()->create(['name' => $s, 'slug' => Str::slug($s), 'sort_order' => $i]);
        }

        // Material
        $material = Attribute::create(['name' => 'Material', 'slug' => 'material', 'sort_order' => 2]);
        $materials = ['Handloom Cotton', 'Silk Blend', 'Pure Silk', 'Cotton Linen', 'Wool Blend'];
        foreach ($materials as $i => $m) {
            $material->values()->create(['name' => $m, 'slug' => Str::slug($m), 'sort_order' => $i]);
        }
    }
}
