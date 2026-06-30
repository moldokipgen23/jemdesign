<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Collection;
use App\Models\Product;
use App\Models\ProductColor;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class DemoProductSeeder extends Seeder
{
    public function run(): void
    {
        $womens = Category::where('slug', 'womens')->first();
        $mens   = Category::where('slug', 'mens')->first();
        $acc    = Category::where('slug', 'accessories')->first();

        $summer = Collection::where('slug', 'summer-2026')->first()
                   ?? Collection::where('name', 'LIKE', '%Summer%')->first();
        $heritage = Collection::where('slug', 'heritage-line')->first()
                   ?? Collection::where('name', 'LIKE', '%Heritage%')->first();

        $products = [
            [
                'name'        => 'Heritage Weave Shirt',
                'slug'        => 'heritage-weave-shirt',
                'description' => 'Handwoven cotton shirt featuring traditional Kuki-Zo weave patterns in indigo and natural tones. Each piece is uniquely crafted by artisans in Northeast India.',
                'price'       => 2499,
                'category_id' => $womens?->id,
                'collections' => [$heritage?->id],
                'is_featured' => true,
                'colors'      => [
                    ['name' => 'Indigo',     'hex' => '#1B3A5C', 'images' => ['https://images.unsplash.com/photo-1596755094514-f87e34085b2c?w=600&h=800&fit=crop', 'https://images.unsplash.com/photo-1598032895397-b9472444bf93?w=600&h=800&fit=crop']],
                    ['name' => 'Natural',     'hex' => '#D4C5A9', 'images' => ['https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?w=600&h=800&fit=crop']],
                ],
            ],
            [
                'name'        => 'Tribal Motif Tote Bag',
                'slug'        => 'tribal-motif-tote-bag',
                'description' => 'Handcrafted canvas tote with embroidered tribal motifs. Spacious interior with leather handles. Perfect for everyday use.',
                'price'       => 1299,
                'category_id' => $acc?->id,
                'collections' => [$heritage?->id],
                'is_featured' => true,
                'colors'      => [
                    ['name' => 'Natural',  'hex' => '#E8DCC8', 'images' => ['https://images.unsplash.com/photo-1544816155-12df9643f363?w=600&h=800&fit=crop']],
                    ['name' => 'Indigo',   'hex' => '#1B3A5C', 'images' => ['https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=600&h=800&fit=crop']],
                ],
            ],
            [
                'name'        => 'Woven Stripe Midi Skirt',
                'slug'        => 'woven-stripe-midi-skirt',
                'description' => 'Elegant midi skirt with handwoven stripe pattern. Features a comfortable elastic waist and flowing silhouette.',
                'price'       => 1899,
                'category_id' => $womens?->id,
                'collections' => [$summer?->id],
                'is_featured' => true,
                'colors'      => [
                    ['name' => 'Earth',    'hex' => '#8B7355', 'images' => ['https://images.unsplash.com/photo-1583496661160-fb5886a0aaaa?w=600&h=800&fit=crop']],
                    ['name' => 'Indigo',   'hex' => '#2C4A6E', 'images' => ['https://images.unsplash.com/photo-1594938298603-c8148c4dae35?w=600&h=800&fit=crop']],
                ],
            ],
            [
                'name'        => 'Artisan Knit Polo',
                'slug'        => 'artisan-knit-polo',
                'description' => 'Premium knit polo shirt with subtle texture pattern. Made from organic cotton with traditional weaving techniques.',
                'price'       => 1999,
                'category_id' => $mens?->id,
                'collections' => [$summer?->id],
                'is_featured' => true,
                'colors'      => [
                    ['name' => 'Sage',     'hex' => '#7C9070', 'images' => ['https://images.unsplash.com/photo-1625910513413-5fc424d6986d?w=600&h=800&fit=crop']],
                    ['name' => 'Charcoal', 'hex' => '#36454F', 'images' => ['https://images.unsplash.com/photo-1620799140408-edc6dcb6d633?w=600&h=800&fit=crop']],
                ],
            ],
            [
                'name'        => 'Heritage Shawl Wrap',
                'slug'        => 'heritage-shawl-wrap',
                'description' => 'Luxurious handwoven shawl wrap with intricate tribal patterns. Can be worn as a scarf, shawl, or light blanket.',
                'price'       => 3299,
                'category_id' => $womens?->id,
                'collections' => [$heritage?->id],
                'is_featured' => true,
                'colors'      => [
                    ['name' => 'Rust',     'hex' => '#B7410E', 'images' => ['https://images.unsplash.com/photo-1601924994987-69e26d50dc26?w=600&h=800&fit=crop']],
                    ['name' => 'Forest',   'hex' => '#228B22', 'images' => ['https://images.unsplash.com/photo-1520903920243-00d872a2d1c9?w=600&h=800&fit=crop']],
                ],
            ],
            [
                'name'        => 'Handloom Linen Shirt',
                'slug'        => 'handloom-linen-shirt',
                'description' => 'Relaxed-fit linen shirt with handloom texture. Perfect for warm weather. Features mother-of-pearl buttons.',
                'price'       => 2199,
                'category_id' => $mens?->id,
                'collections' => [$summer?->id],
                'is_featured' => false,
                'colors'      => [
                    ['name' => 'White',    'hex' => '#F5F5DC', 'images' => ['https://images.unsplash.com/photo-1596755094514-f87e34085b2c?w=600&h=800&fit=crop']],
                    ['name' => 'Sand',     'hex' => '#C2B280', 'images' => ['https://images.unsplash.com/photo-1598032895397-b9472444bf93?w=600&h=800&fit=crop']],
                ],
            ],
            [
                'name'        => 'Embroidered Clutch',
                'slug'        => 'embroidered-clutch',
                'description' => 'Hand-embroidered clutch bag with traditional motifs. Brass clasp closure. Interior lined with cotton.',
                'price'       => 999,
                'category_id' => $acc?->id,
                'collections' => [$heritage?->id],
                'is_featured' => true,
                'colors'      => [
                    ['name' => 'Maroon',   'hex' => '#800000', 'images' => ['https://images.unsplash.com/photo-1584917865442-de89df76afd3?w=600&h=800&fit=crop']],
                    ['name' => 'Black',    'hex' => '#1C1C1C', 'images' => ['https://images.unsplash.com/photo-1566150905458-1bf1fc113f0d?w=600&h=800&fit=crop']],
                ],
            ],
            [
                'name'        => 'Tribal Print Dress',
                'slug'        => 'tribal-print-dress',
                'description' => 'Flowing A-line dress with all-over tribal print. Features a V-neckline and short sleeves. 100% organic cotton.',
                'price'       => 2799,
                'category_id' => $womens?->id,
                'collections' => [$summer?->id, $heritage?->id],
                'is_featured' => true,
                'colors'      => [
                    ['name' => 'Indigo',   'hex' => '#1B3A5C', 'images' => ['https://images.unsplash.com/photo-1572804013309-59a88b7e92f1?w=600&h=800&fit=crop']],
                    ['name' => 'Terracotta','hex' => '#E2725B', 'images' => ['https://images.unsplash.com/photo-1595777457583-95e059d581b8?w=600&h=800&fit=crop']],
                ],
            ],
            [
                'name'        => 'Woven Belt',
                'slug'        => 'woven-belt',
                'description' => 'Handwoven cotton belt with brass buckle. Adjustable length. Adds a heritage touch to any outfit.',
                'price'       => 699,
                'category_id' => $acc?->id,
                'collections' => [],
                'is_featured' => false,
                'colors'      => [
                    ['name' => 'Natural',  'hex' => '#D4C5A9', 'images' => ['https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=600&h=800&fit=crop']],
                    ['name' => 'Black',    'hex' => '#1C1C1C', 'images' => ['https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=600&h=800&fit=crop']],
                ],
            ],
            [
                'name'        => 'Heritage Jacket',
                'slug'        => 'heritage-jacket',
                'description' => 'Structured jacket with handwoven panels. Modern cut meets traditional craftsmanship. Perfect for layering.',
                'price'       => 4499,
                'category_id' => $womens?->id,
                'collections' => [$heritage?->id],
                'is_featured' => true,
                'colors'      => [
                    ['name' => 'Midnight', 'hex' => '#191970', 'images' => ['https://images.unsplash.com/photo-1551028719-00167b16eac5?w=600&h=800&fit=crop']],
                    ['name' => 'Camel',    'hex' => '#C19A6B', 'images' => ['https://images.unsplash.com/photo-1591047139829-d91aecb6caea?w=600&h=800&fit=crop']],
                ],
            ],
        ];

        foreach ($products as $data) {
            $collections = $data['collections'];
            unset($data['collections']);

            $product = Product::create([
                'name'         => $data['name'],
                'slug'         => $data['slug'],
                'description'  => $data['description'],
                'price'        => $data['price'],
                'category_id'  => $data['category_id'],
                'type'         => 'variable',
                'is_featured'  => $data['is_featured'],
                'is_active'    => true,
                'sort_order'   => 0,
                'cover_image'  => null,
            ]);

            if ($collections) {
                $product->collections()->sync(array_filter($collections));
            }

            foreach ($data['colors'] as $colorIdx => $colorData) {
                $color = ProductColor::create([
                    'product_id'  => $product->id,
                    'color_name'  => $colorData['name'],
                    'hex_code'    => $colorData['hex'],
                    'sort_order'  => $colorIdx,
                ]);

                foreach ($colorData['images'] as $imgIdx => $url) {
                    $imageName = strtolower(str_replace(' ', '-', $product->slug)) . '-' . strtolower($colorData['name']) . '-' . ($imgIdx + 1) . '.jpg';
                    $path = "products/{$product->id}/colors/{$color->id}/{$imageName}";

                    try {
                        $imageData = Http::timeout(15)->get($url);
                        if ($imageData->successful()) {
                            Storage::disk('public')->put($path, $imageData->body());
                            ProductImage::create([
                                'product_color_id' => $color->id,
                                'image_path'       => $path,
                                'sort_order'       => $imgIdx,
                            ]);

                            // Set first image as cover
                            if ($colorIdx === 0 && $imgIdx === 0) {
                                $product->update(['cover_image' => $path]);
                            }
                        }
                    } catch (\Exception $e) {
                        // Skip failed downloads silently
                    }
                }
            }

            $this->command->info("  ✓ {$data['name']}");
        }
    }
}
