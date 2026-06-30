<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InstagramPost;
use Illuminate\Http\Request;

class InstagramPostController extends Controller
{
    public function index()
    {
        $posts = InstagramPost::orderBy('sort_order')->paginate(20);
        return view('admin.instagram.index', compact('posts'));
    }

    public function create()
    {
        return view('admin.instagram.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'image'      => 'required|image|max:4096',
            'post_link'  => 'nullable|url|max:500',
            'caption'    => 'nullable|string|max:1000',
            'sort_order' => 'nullable|integer|min:0',
            'is_active'  => 'nullable|boolean',
        ]);

        $path = $request->file('image')->store('instagram', 'public');

        InstagramPost::create([
            'image_path' => $path,
            'post_link'  => $request->post_link,
            'caption'    => $request->caption,
            'sort_order' => $request->input('sort_order', 0),
            'is_active'  => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.instagram.index')
            ->with('success', 'Post added.');
    }

    public function edit(InstagramPost $instagram)
    {
        return view('admin.instagram.edit', ['post' => $instagram]);
    }

    public function update(Request $request, InstagramPost $instagram)
    {
        $request->validate([
            'image'      => 'nullable|image|max:4096',
            'post_link'  => 'nullable|url|max:500',
            'caption'    => 'nullable|string|max:1000',
            'sort_order' => 'nullable|integer|min:0',
            'is_active'  => 'nullable|boolean',
        ]);

        $data = [
            'post_link'  => $request->post_link,
            'caption'    => $request->caption,
            'sort_order' => $request->input('sort_order', $instagram->sort_order),
            'is_active'  => $request->boolean('is_active'),
        ];

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('instagram', 'public');
        }

        $instagram->update($data);

        return redirect()->route('admin.instagram.index')
            ->with('success', 'Post updated.');
    }

    public function destroy(InstagramPost $instagram)
    {
        $instagram->delete();
        return redirect()->route('admin.instagram.index')
            ->with('success', 'Post deleted.');
    }
}
