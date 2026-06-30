<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HomepageSection;

class HomepageSectionSeeder extends Seeder
{
    public function run(): void
    {
        $sections = [
            ['section_key' => 'hero',        'sort_order' => 1],
            ['section_key' => 'top_sellers', 'sort_order' => 2],
            ['section_key' => 'story',       'sort_order' => 3],
            ['section_key' => 'collections', 'sort_order' => 4],
            ['section_key' => 'founder',     'sort_order' => 5],
            ['section_key' => 'testimonials','sort_order' => 6],
            ['section_key' => 'instagram',   'sort_order' => 7],
        ];

        foreach ($sections as $section) {
            HomepageSection::firstOrCreate(
                ['section_key' => $section['section_key']],
                array_merge($section, ['is_enabled' => true])
            );
        }
    }
}
