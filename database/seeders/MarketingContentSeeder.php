<?php

namespace Database\Seeders;

use App\Models\MarketingSection;
use App\Models\MarketingSectionItem;
use App\Models\Testimonial;
use App\Models\Product;
use Illuminate\Database\Seeder;

class MarketingContentSeeder extends Seeder
{
    public function run(): void
    {
        // ─── Testimonials ──────────────────────────────────────────────────────
        $testimonials = [
            [
                'customer_name'  => 'Priya Sharma',
                'customer_title' => 'Mumbai',
                'content'        => 'Absolutely stunning craftsmanship. The heritage weave shirt is unlike anything I own — you can feel the tradition in every thread. The fit is perfect and the fabric quality is exceptional.',
                'rating'         => 5,
                'is_active'      => true,
                'sort_order'     => 0,
            ],
            [
                'customer_name'  => 'Tanya Jamir',
                'customer_title' => 'Kohima',
                'content'        => 'The shawl wrap is breathtaking. I wore it to a wedding and received countless compliments. It\'s a beautiful piece of art that you can wear — proud to own something that honors our Naga heritage.',
                'rating'         => 5,
                'is_active'      => true,
                'sort_order'     => 1,
            ],
            [
                'customer_name'  => 'Rahul Keishing',
                'customer_title' => 'Imphal',
                'content'        => 'I\'ve been looking for authentic Northeast Indian fashion that doesn\'t compromise on modern style. Jem Designs delivers exactly that. The polo shirt is my new everyday favorite.',
                'rating'         => 5,
                'is_active'      => true,
                'sort_order'     => 2,
            ],
            [
                'customer_name'  => 'Devi Thangjam',
                'customer_title' => 'Delhi',
                'content'        => 'The attention to detail in every piece is remarkable. These aren\'t just clothes — they\'re stories woven into fabric. I bought the tote bag as a gift and ended up keeping it for myself!',
                'rating'         => 4,
                'is_active'      => true,
                'sort_order'     => 3,
            ],
        ];

        foreach ($testimonials as $data) {
            Testimonial::create($data);
        }

        $this->command->info('Created ' . count($testimonials) . ' testimonials.');

        // ─── Marketing Sections ────────────────────────────────────────────────

        // Section 1: Trending Now (featured products, grid)
        MarketingSection::create([
            'title'          => 'Trending Now',
            'type'           => 'trending',
            'display_style'  => 'grid',
            'items_per_row'  => 3,
            'filter_value'   => null,
            'sort_order'     => 0,
            'is_enabled'     => true,
        ]);

        // Section 2: Women's Collection (category filter, carousel)
        $womensCat = \App\Models\Category::where('slug', 'womens')->first();
        MarketingSection::create([
            'title'          => 'Women\'s Collection',
            'type'           => 'category',
            'display_style'  => 'carousel',
            'items_per_row'  => 4,
            'filter_value'   => $womensCat?->id,
            'sort_order'     => 1,
            'is_enabled'     => true,
        ]);

        // Section 3: New Arrivals (latest products, grid)
        MarketingSection::create([
            'title'          => 'New Arrivals',
            'type'           => 'new_arrivals',
            'display_style'  => 'grid',
            'items_per_row'  => 4,
            'filter_value'   => null,
            'sort_order'     => 2,
            'is_enabled'     => true,
        ]);

        // Section 4: What Our Customers Say (testimonials, carousel)
        MarketingSection::create([
            'title'          => 'What Our Customers Say',
            'type'           => 'testimonials',
            'display_style'  => 'carousel',
            'items_per_row'  => 3,
            'filter_value'   => null,
            'sort_order'     => 3,
            'is_enabled'     => true,
        ]);

        // Section 5: Manual selection of specific products
        $manual = MarketingSection::create([
            'title'          => 'Editor\'s Picks',
            'type'           => 'manual',
            'display_style'  => 'grid',
            'items_per_row'  => 3,
            'filter_value'   => null,
            'sort_order'     => 4,
            'is_enabled'     => true,
        ]);

        // Link specific products to manual section
        $manualProducts = Product::active()->take(3)->get();
        foreach ($manualProducts as $idx => $product) {
            MarketingSectionItem::create([
                'marketing_section_id' => $manual->id,
                'itemable_type'        => Product::class,
                'itemable_id'          => $product->id,
                'sort_order'           => $idx,
            ]);
        }

        $this->command->info('Created 5 marketing sections with sample data.');
    }
}
