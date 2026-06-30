<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductColor;
use App\Models\ProductSize;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class ProductVariantController extends Controller
{
    public function index(Product $product)
    {
        $variants = $product->variants()->with(['color', 'size'])->get();
        return response()->json(['variants' => $variants]);
    }

    public function store(Request $request, Product $product)
    {
        $data = $request->validate([
            'product_color_id' => 'required|exists:product_colors,id,product_id,' . $product->id,
            'product_size_id'  => 'required|exists:product_sizes,id,product_id,' . $product->id,
            'price'            => 'nullable|numeric|min:0',
            'stock'            => 'required|integer|min:0',
            'sku'              => 'nullable|string|max:50',
        ]);

        $data['product_id'] = $product->id;

        $variant = ProductVariant::updateOrCreate(
            [
                'product_id'       => $product->id,
                'product_color_id' => $data['product_color_id'],
                'product_size_id'  => $data['product_size_id'],
            ],
            $data
        )->load(['color', 'size']);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'variant' => $variant]);
        }
        return redirect()->route('admin.products.show', $product)
            ->with('success', 'Variant saved.');
    }

    public function update(Request $request, Product $product, ProductVariant $variant)
    {
        $data = $request->validate([
            'price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'sku'   => 'nullable|string|max:50',
        ]);

        $variant->update($data);
        $variant->load(['color', 'size']);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'variant' => $variant]);
        }
        return redirect()->route('admin.products.show', $product)
            ->with('success', 'Variant updated.');
    }

    public function destroy(Request $request, Product $product, ProductVariant $variant)
    {
        $variant->delete();

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }
        return redirect()->route('admin.products.show', $product)
            ->with('success', 'Variant deleted.');
    }

    public function bulkUpdate(Request $request, Product $product)
    {
        $request->validate([
            'variants'   => 'required|array',
            'variants.*' => 'array',
        ]);

        foreach ($request->variants as $v) {
            if (empty($v['id'])) continue;
            ProductVariant::where('id', $v['id'])
                ->where('product_id', $product->id)
                ->update([
                    'stock' => $v['stock'] ?? 0,
                    'price' => $v['price'] ?? null,
                    'sku'   => $v['sku'] ?? null,
                ]);
        }

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }
        return redirect()->route('admin.products.show', $product)
            ->with('success', 'Variants updated.');
    }
}
