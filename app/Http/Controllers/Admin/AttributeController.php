<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AttributeController extends Controller
{
    public function index()
    {
        $attributes = Attribute::with('values')->orderBy('sort_order')->get();
        return view('admin.attributes.index', compact('attributes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100|unique:attributes,name',
        ]);

        Attribute::create([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
        ]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }
        return redirect()->route('admin.attributes.index')->with('success', 'Attribute created.');
    }

    public function destroy(Attribute $attribute)
    {
        $attribute->delete();
        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }
        return redirect()->route('admin.attributes.index')->with('success', 'Attribute deleted.');
    }

    public function storeValue(Request $request, Attribute $attribute)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:100',
            'hex_code' => 'nullable|string|max:7',
        ]);

        $value = $attribute->values()->create([
            'name'     => $data['name'],
            'slug'     => Str::slug($data['name']),
            'hex_code' => $data['hex_code'] ?? null,
        ]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'value' => $value]);
        }
        return redirect()->route('admin.attributes.index')->with('success', "Value \"{$value->name}\" added.");
    }

    public function destroyValue(Attribute $attribute, AttributeValue $value)
    {
        $value->delete();
        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }
        return redirect()->route('admin.attributes.index')->with('success', 'Value deleted.');
    }
}
