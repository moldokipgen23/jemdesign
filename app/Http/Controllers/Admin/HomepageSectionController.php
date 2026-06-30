<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomepageSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomepageSectionController extends Controller
{
    public function index()
    {
        $sections = HomepageSection::orderBy('sort_order')->get();
        return view('admin.homepage.index', compact('sections'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'sections'              => 'required|array',
            'sections.*.id'         => 'required|exists:homepage_sections,id',
            'sections.*.is_enabled' => 'nullable|boolean',
            'sections.*.sort_order' => 'nullable|integer|min:0',
        ]);

        foreach ($request->sections as $item) {
            HomepageSection::where('id', $item['id'])->update([
                'is_enabled' => isset($item['is_enabled']) ? (bool)$item['is_enabled'] : false,
                'sort_order' => $item['sort_order'] ?? 0,
            ]);
        }

        if ($request->expectsJson()) {
            return response()->json(['ok' => true]);
        }

        return redirect()->route('admin.homepage.index')
            ->with('success', 'Homepage sections saved.');
    }

    public function updateImage(Request $request, HomepageSection $section)
    {
        $request->validate([
            'image_path' => 'required|image|max:6144',
        ]);

        if ($section->image_path) {
            Storage::disk('public')->delete($section->image_path);
        }

        $section->update([
            'image_path' => $request->file('image_path')->store('homepage', 'public'),
        ]);

        return back()->with('success', 'Section image updated.');
    }
}
