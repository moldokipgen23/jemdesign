<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Collection;
use App\Models\MarketingSection;
use App\Models\Product;
use Illuminate\Http\Request;

class MarketingSectionController extends Controller
{
    public function index()
    {
        $sections = MarketingSection::withCount('items')->orderBy('sort_order')->get();
        return view('admin.marketing.index', compact('sections'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $collections = Collection::orderBy('name')->get();
        $products = Product::orderBy('name')->get();
        return view('admin.marketing.create', compact('categories', 'collections', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:trending,new_arrivals,best_selling,category,collection,manual,testimonials',
            'display_style' => 'required|in:grid,carousel',
            'items_per_row' => 'required|integer|in:2,3,4',
            'filter_value' => 'nullable|integer',
            'sort_order' => 'nullable|integer',
            'is_enabled' => 'boolean',
        ]);

        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['is_enabled'] = $request->boolean('is_enabled');

        $section = MarketingSection::create($validated);

        // Handle manual items
        if ($section->type === 'manual' && $request->has('product_ids')) {
            foreach ($request->product_ids as $idx => $productId) {
                $section->items()->create([
                    'itemable_type' => Product::class,
                    'itemable_id' => $productId,
                    'sort_order' => $idx,
                ]);
            }
        }

        return redirect()->route('admin.marketing.index')
            ->with('success', 'Marketing section created.');
    }

    public function edit(MarketingSection $marketingSection)
    {
        $marketingSection->load('items');
        $categories = Category::orderBy('name')->get();
        $collections = Collection::orderBy('name')->get();
        $products = Product::orderBy('name')->get();
        $selectedProductIds = $marketingSection->items()
            ->where('itemable_type', Product::class)
            ->pluck('itemable_id')
            ->toArray();

        return view('admin.marketing.edit', compact('marketingSection', 'categories', 'collections', 'products', 'selectedProductIds'));
    }

    public function update(Request $request, MarketingSection $marketingSection)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:trending,new_arrivals,best_selling,category,collection,manual,testimonials',
            'display_style' => 'required|in:grid,carousel',
            'items_per_row' => 'required|integer|in:2,3,4',
            'filter_value' => 'nullable|integer',
            'sort_order' => 'nullable|integer',
            'is_enabled' => 'boolean',
        ]);

        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['is_enabled'] = $request->boolean('is_enabled');

        $marketingSection->update($validated);

        // Re-sync manual items
        if ($marketingSection->type === 'manual') {
            $marketingSection->items()->delete();
            if ($request->has('product_ids')) {
                foreach ($request->product_ids as $idx => $productId) {
                    $marketingSection->items()->create([
                        'itemable_type' => Product::class,
                        'itemable_id' => $productId,
                        'sort_order' => $idx,
                    ]);
                }
            }
        } else {
            $marketingSection->items()->delete();
        }

        return redirect()->route('admin.marketing.index')
            ->with('success', 'Marketing section updated.');
    }

    public function destroy(MarketingSection $marketingSection)
    {
        $marketingSection->items()->delete();
        $marketingSection->delete();

        return redirect()->route('admin.marketing.index')
            ->with('success', 'Marketing section deleted.');
    }

    public function reorder(Request $request)
    {
        $request->validate(['order' => 'required|array']);
        foreach ($request->order as $index => $id) {
            MarketingSection::where('id', $id)->update(['sort_order' => $index]);
        }
        return response()->json(['success' => true]);
    }

    public function toggle(MarketingSection $marketingSection)
    {
        $marketingSection->update(['is_enabled' => !$marketingSection->is_enabled]);
        return response()->json(['enabled' => $marketingSection->is_enabled]);
    }
}
