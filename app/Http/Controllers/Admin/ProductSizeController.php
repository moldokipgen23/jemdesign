<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductSize;
use Illuminate\Http\Request;

class ProductSizeController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'size_label'   => 'required|string|max:20',
            'is_available' => 'nullable|boolean',
            'sort_order'   => 'nullable|integer|min:0',
        ]);

        $size = $product->sizes()->create([
            'size_label'   => $request->size_label,
            'is_available' => $request->boolean('is_available', true),
            'sort_order'   => $request->input('sort_order', 0),
        ]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'size' => $size]);
        }
        return redirect()->route('admin.products.show', $product)
            ->with('success', 'Size added.');
    }

    public function update(Request $request, Product $product, ProductSize $size)
    {
        $request->validate([
            'size_label'   => 'required|string|max:20',
            'is_available' => 'nullable|boolean',
            'sort_order'   => 'nullable|integer|min:0',
        ]);

        $size->update([
            'size_label'   => $request->size_label,
            'is_available' => $request->boolean('is_available'),
            'sort_order'   => $request->input('sort_order', $size->sort_order),
        ]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'size' => $size]);
        }
        return redirect()->route('admin.products.show', $product)
            ->with('success', 'Size updated.');
    }

    public function destroy(Request $request, Product $product, ProductSize $size)
    {
        $size->delete();

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }
        return redirect()->route('admin.products.show', $product)
            ->with('success', 'Size deleted.');
    }
}
