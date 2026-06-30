<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductColor;
use App\Models\ProductImage;
use Illuminate\Http\Request;

class ProductImageController extends Controller
{
    public function index(ProductColor $color)
    {
        $images = $color->images()->orderBy('sort_order')->get();
        return response()->json(['images' => $images]);
    }

    public function store(Request $request, ProductColor $color)
    {
        $request->validate([
            'images'   => 'required|array|min:1',
            'images.*' => 'image|max:6144',
        ]);

        $nextOrder = $color->images()->max('sort_order') + 1;

        foreach ($request->file('images') as $file) {
            $path = $file->store("products/{$color->product_id}/colors/{$color->id}", 'public');
            $color->images()->create([
                'image_path' => $path,
                'sort_order' => $nextOrder++,
            ]);
        }

        if ($request->expectsJson()) {
            $images = $color->images()->orderBy('sort_order')->get();
            return response()->json(['success' => true, 'images' => $images]);
        }
        return redirect()->route('admin.products.show', $color->product_id)
            ->with('success', count($request->file('images')) . ' image(s) uploaded.');
    }

    public function destroy(Request $request, ProductColor $color, ProductImage $image)
    {
        $productId = $color->product_id;
        $image->delete();
        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }
        return redirect()->route('admin.products.show', $productId)
            ->with('success', 'Image deleted.');
    }

    public function updateOrder(Request $request, ProductColor $color)
    {
        $request->validate([
            'images'   => 'required|array',
            'images.*' => 'array',
            'images.*.id' => 'required|integer',
            'images.*.sort_order' => 'required|integer',
        ]);

        foreach ($request->images as $item) {
            $color->images()->where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
        }

        return response()->json(['ok' => true]);
    }
}
