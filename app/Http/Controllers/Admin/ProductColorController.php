<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductColor;
use Illuminate\Http\Request;

class ProductColorController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $data = $request->validate([
            'color_name' => 'required|string|max:80',
            'hex_code'   => ['required', 'string', 'regex:/^#([0-9A-Fa-f]{3}|[0-9A-Fa-f]{6})$/'],
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $color = $product->colors()->create($data);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'color' => $color]);
        }
        return redirect()->route('admin.products.show', $product)
            ->with('success', "Color \"{$color->color_name}\" added.");
    }

    public function update(Request $request, Product $product, ProductColor $color)
    {
        $data = $request->validate([
            'color_name' => 'required|string|max:80',
            'hex_code'   => ['required', 'string', 'regex:/^#([0-9A-Fa-f]{3}|[0-9A-Fa-f]{6})$/'],
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $color->update($data);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'color' => $color]);
        }
        return redirect()->route('admin.products.show', $product)
            ->with('success', 'Color updated.');
    }

    public function destroy(Request $request, Product $product, ProductColor $color)
    {
        $color->delete();

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }
        return redirect()->route('admin.products.show', $product)
            ->with('success', 'Color deleted.');
    }
}
