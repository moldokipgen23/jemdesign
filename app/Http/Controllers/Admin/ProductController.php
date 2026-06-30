<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'collections'])
            ->orderBy('sort_order')
            ->orderByDesc('created_at');

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        if ($request->filled('collection')) {
            $query->whereHas('collections', fn($q) => $q->where('collections.id', $request->collection));
        }
        if ($request->has('top_seller')) {
            $query->where('is_top_seller', true);
        }
        if ($request->has('featured')) {
            $query->where('is_featured', true);
        }

        $products    = $query->paginate(20)->withQueryString();
        $categories  = Category::orderBy('sort_order')->get();
        $collections = Collection::orderBy('sort_order')->get();

        return view('admin.products.index', compact('products', 'categories', 'collections'));
    }

    public function create()
    {
        $categories  = Category::active()->get();
        $collections = Collection::active()->get();
        $attributes  = \App\Models\Attribute::active()->with('values')->get();
        return view('admin.products.create', compact('categories', 'collections', 'attributes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id'      => 'required|exists:categories,id',
            'name'             => 'required|string|max:200',
            'type'             => 'required|in:simple,variable',
            'cover_image'      => 'nullable|image|max:6144',
            'images'           => 'nullable|array|max:20',
            'images.*'         => 'image|max:6144',
            'description'      => 'required|string',
            'heritage_note'    => 'nullable|string',
            'price'            => 'required|numeric|min:0',
            'sku'              => 'nullable|string|max:100',
            'stock'            => 'nullable|integer|min:0',
            'material'         => 'nullable|string|max:200',
            'weight'           => 'nullable|string|max:100',
            'care_instructions' => 'nullable|string',
            'is_top_seller'    => 'nullable|boolean',
            'is_featured'      => 'nullable|boolean',
            'is_active'        => 'nullable|boolean',
            'sort_order'       => 'nullable|integer|min:0',
            'collections'      => 'nullable|array',
            'collections.*'    => 'exists:collections,id',
        ]);

        $data['slug']          = Str::slug($data['name']);
        $data['is_top_seller'] = $request->boolean('is_top_seller');
        $data['is_featured']   = $request->boolean('is_featured');
        $data['is_active']     = $request->boolean('is_active', true);
        $data['stock']         = $request->input('stock', 0);

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('products', 'public');
        }

        $product = Product::create($data);
        $product->collections()->sync($request->input('collections', []));

        // Handle gallery images — create a default color and attach images to it
        $galleryFiles = $request->file('images');
        if (!empty($galleryFiles) && is_array($galleryFiles)) {
            $color = $product->colors()->create([
                'color_name' => 'Default',
                'hex_code'   => '#808080',
                'sort_order' => 0,
            ]);

            $order = 0;
            foreach ($galleryFiles as $file) {
                if ($file->isValid()) {
                    $path = $file->store("products/{$product->id}/colors/{$color->id}", 'public');
                    $color->images()->create([
                        'image_path' => $path,
                        'sort_order' => $order++,
                    ]);
                }
            }

            // Set cover_image from first gallery image if not set
            if (!$product->cover_image && $color->images()->count()) {
                $product->update(['cover_image' => $color->images()->first()->image_path]);
            }
        }

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'product' => $product, 'redirect' => route('admin.products.edit', $product)]);
        }
        return redirect()->route('admin.products.show', $product)
            ->with('success', 'Product created. Now manage colors and variations.');
    }

    public function show(Product $product)
    {
        $product->load(['colors.images', 'videos', 'sizes', 'collections', 'variants.color', 'variants.size']);
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories  = Category::active()->get();
        $collections = Collection::active()->get();
        $attributes  = \App\Models\Attribute::active()->with('values')->get();
        $product->load(['collections', 'attributes.values', 'variations.attributeValues', 'colors.images']);
        return view('admin.products.edit', compact('product', 'categories', 'collections', 'attributes'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'category_id'      => 'required|exists:categories,id',
            'name'             => 'required|string|max:200',
            'cover_image'      => 'nullable|image|max:6144',
            'images'           => 'nullable|array|max:20',
            'images.*'         => 'image|max:6144',
            'description'      => 'required|string',
            'heritage_note'    => 'nullable|string',
            'price'            => 'required|numeric|min:0',
            'sku'              => 'nullable|string|max:100',
            'stock'            => 'nullable|integer|min:0',
            'material'         => 'nullable|string|max:200',
            'weight'           => 'nullable|string|max:100',
            'care_instructions' => 'nullable|string',
            'is_top_seller'    => 'nullable|boolean',
            'is_featured'      => 'nullable|boolean',
            'is_active'        => 'nullable|boolean',
            'sort_order'       => 'nullable|integer|min:0',
            'collections'      => 'nullable|array',
            'collections.*'    => 'exists:collections,id',
        ]);

        $data['slug']          = Str::slug($data['name']);
        $data['is_top_seller'] = $request->boolean('is_top_seller');
        $data['is_featured']   = $request->boolean('is_featured');
        $data['is_active']     = $request->boolean('is_active');

        if ($request->hasFile('cover_image')) {
            if ($product->cover_image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($product->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image')->store('products', 'public');
        }

        $product->update($data);
        $product->collections()->sync($request->input('collections', []));

        // Handle additional gallery images — append to existing default color
        $galleryFiles = $request->file('images');
        if (!empty($galleryFiles) && is_array($galleryFiles)) {
            $color = $product->colors()->first();
            if (!$color) {
                $color = $product->colors()->create([
                    'color_name' => 'Default',
                    'hex_code'   => '#808080',
                    'sort_order' => 0,
                ]);
            }

            $nextOrder = $color->images()->max('sort_order') + 1;
            foreach ($galleryFiles as $file) {
                if ($file->isValid()) {
                    $path = $file->store("products/{$product->id}/colors/{$color->id}", 'public');
                    $color->images()->create([
                        'image_path' => $path,
                        'sort_order' => $nextOrder++,
                    ]);
                }
            }
        }

        return redirect()->route('admin.products.show', $product)
            ->with('success', 'Product updated.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted.');
    }

    public function getVariations(Product $product)
    {
        $variations = $product->variations()->with('attributeValues.attribute')->get();
        return response()->json(['variations' => $variations]);
    }

    public function storeVariations(Request $request, Product $product)
    {
        $request->validate([
            'stock'                   => 'required|integer|min:0',
            'price'                   => 'nullable|numeric|min:0',
            'sku'                     => 'nullable|string|max:50',
            'attributes'              => 'required|array|min:1',
            'attributes.*.attribute_id'       => 'required|integer',
            'attributes.*.attribute_value_id' => 'required|integer',
        ]);

        $variation = $product->variations()->create([
            'price' => $request->price,
            'stock' => $request->stock,
            'sku'   => $request->sku,
        ]);

        foreach ($request->attributes as $attr) {
            $variation->attributeValues()->attach($attr['attribute_value_id']);
        }

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'variation' => $variation->load('attributeValues.attribute')]);
        }
        return redirect()->route('admin.products.show', $product)->with('success', 'Variation saved.');
    }
}
