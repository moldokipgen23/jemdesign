<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVideo;
use Illuminate\Http\Request;

class ProductVideoController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'video' => 'required|file|mimetypes:video/mp4,video/webm,video/quicktime|max:51200',
        ]);

        $nextOrder = $product->videos()->max('sort_order') + 1;
        $path = $request->file('video')
            ->store("products/{$product->id}/videos", 'public');

        $product->videos()->create([
            'video_path' => $path,
            'sort_order' => $nextOrder,
        ]);

        return redirect()->route('admin.products.show', $product)
            ->with('success', 'Video uploaded.');
    }

    public function destroy(Product $product, ProductVideo $video)
    {
        $video->delete();
        return redirect()->route('admin.products.show', $product)
            ->with('success', 'Video deleted.');
    }
}
