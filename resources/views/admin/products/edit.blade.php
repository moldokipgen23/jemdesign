@extends('layouts.admin')
@section('title', 'Edit Product')

@section('content')
<div class="admin-page-header">
    <div>
        <h1 class="admin-page-header__title">Edit: {{ $product->name }}</h1>
        <p class="admin-page-header__sub">Manage details, images, and gallery</p>
    </div>
    <div style="display:flex;gap:10px">
        <a href="{{ route('admin.products.show', $product) }}" class="btn-admin btn-admin--outline">Manage Colors</a>
        <a href="{{ route('admin.products.index') }}" class="btn-admin btn-admin--outline">← Back</a>
    </div>
</div>

<form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
    @csrf @method('PATCH')
    <div class="admin-grid-2">
        {{-- LEFT COLUMN --}}
        <div style="display:flex;flex-direction:column;gap:20px">
            <div class="admin-card">
                <div class="admin-card__header"><span class="admin-card__title">Product Details</span></div>
                <div class="admin-card__body">
                    <div class="admin-form-group">
                        <label>Product Name *</label>
                        <input type="text" name="name" class="admin-input" value="{{ old('name', $product->name) }}" required>
                        @error('name')<p class="admin-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="admin-form-group">
                        <label>Category *</label>
                        <select name="category_id" class="admin-select" required>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="admin-form-grid">
                        <div class="admin-form-group">
                            <label>Price (₹) *</label>
                            <input type="number" name="price" class="admin-input" value="{{ old('price', $product->price) }}" required min="0" step="0.01" style="width:180px">
                        </div>
                        <div class="admin-form-group">
                            <label>SKU</label>
                            <input type="text" name="sku" class="admin-input" value="{{ old('sku', $product->sku) }}" placeholder="e.g. JDC-SHR-001">
                        </div>
                    </div>
                    <div class="admin-form-grid">
                        <div class="admin-form-group">
                            <label>Stock (default)</label>
                            <input type="number" name="stock" class="admin-input" value="{{ old('stock', $product->stock) }}" min="0" style="width:120px">
                        </div>
                    </div>
                    <div class="admin-form-group">
                        <label>Description *</label>
                        <textarea name="description" class="admin-textarea" required>{{ old('description', $product->description) }}</textarea>
                    </div>
                    <div class="admin-form-group">
                        <label>Heritage Note</label>
                        <textarea name="heritage_note" class="admin-textarea" style="min-height:80px">{{ old('heritage_note', $product->heritage_note) }}</textarea>
                    </div>
                    <div class="admin-form-grid">
                        <div class="admin-form-group">
                            <label>Material</label>
                            <input type="text" name="material" class="admin-input" value="{{ old('material', $product->material) }}" placeholder="e.g. Handloom Cotton, Silk Blend">
                        </div>
                        <div class="admin-form-group">
                            <label>Weight</label>
                            <input type="text" name="weight" class="admin-input" value="{{ old('weight', $product->weight) }}" placeholder="e.g. 250g, 180gsm">
                        </div>
                    </div>
                    <div class="admin-form-group">
                        <label>Care Instructions</label>
                        <textarea name="care_instructions" class="admin-textarea" style="min-height:60px" placeholder="e.g. Hand wash cold, do not bleach, dry in shade">{{ old('care_instructions', $product->care_instructions) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- ═══ COVER IMAGE ═══ --}}
            <div class="admin-card">
                <div class="admin-card__header">
                    <span class="admin-card__title">Cover Image</span>
                    <span style="font-size:11px;color:var(--text-muted)">Main thumbnail shown in listings</span>
                </div>
                <div class="admin-card__body">
                    @if($product->cover_image)
                        <div style="margin-bottom:12px">
                            <p style="font-size:11px;font-weight:600;letter-spacing:.1em;text-transform:uppercase;color:var(--text-muted);margin-bottom:6px">Current Cover</p>
                            <div style="position:relative;display:inline-block;border-radius:6px;overflow:hidden;border:2px solid var(--gold)">
                                <img src="{{ Storage::url($product->cover_image) }}" style="width:120px;height:150px;object-fit:cover;display:block">
                            </div>
                        </div>
                    @endif
                    <div id="coverDropZone" style="border:2px dashed var(--border);border-radius:var(--radius);padding:24px 20px;text-align:center;cursor:pointer;transition:all .2s;background:var(--bg-input)" onclick="document.getElementById('coverInput').click()">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="var(--text-muted)" stroke-width="1.5" style="margin-bottom:6px"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                        <p style="font-size:12px;font-weight:500;color:var(--text-dim)">{{ $product->cover_image ? 'Replace cover image' : 'Click to upload cover image' }}</p>
                        <p style="font-size:11px;color:var(--text-muted);margin-top:2px">JPG, PNG, WebP — Max 6MB</p>
                    </div>
                    <input type="file" id="coverInput" name="cover_image" accept="image/*" style="display:none" onchange="previewCover(this)">
                    <div id="coverPreview" style="margin-top:12px;display:none">
                        <div style="position:relative;display:inline-block;border-radius:6px;overflow:hidden;border:2px solid var(--gold)">
                            <img id="coverPreviewImg" style="width:120px;height:150px;object-fit:cover;display:block">
                            <button type="button" onclick="removeCover()" style="position:absolute;top:4px;right:4px;width:20px;height:20px;border-radius:50%;background:rgba(220,53,69,0.9);color:#fff;border:none;cursor:pointer;font-size:12px;display:flex;align-items:center;justify-content:center">&times;</button>
                        </div>
                        <p style="font-size:11px;color:var(--gold);margin-top:6px">New cover selected — will replace current</p>
                    </div>
                </div>
            </div>

            {{-- ═══ GALLERY IMAGES ═══ --}}
            @php
                $allImages = $product->colors->flatMap(function($c) { return $c->images; })->sortBy('sort_order')->values();
            @endphp
            <div class="admin-card">
                <div class="admin-card__header">
                    <span class="admin-card__title">Gallery Images</span>
                    <span style="font-size:12px;color:var(--text-muted)">{{ $allImages->count() }} image(s)</span>
                </div>
                <div class="admin-card__body">
                    {{-- Existing Images --}}
                    @if($allImages->count())
                    <div style="margin-bottom:16px">
                        <p style="font-size:11px;font-weight:600;letter-spacing:.1em;text-transform:uppercase;color:var(--text-muted);margin-bottom:8px">Current Gallery</p>
                        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(90px,1fr));gap:8px">
                            @foreach($allImages as $img)
                            <div style="position:relative;border-radius:6px;overflow:hidden;border:1px solid var(--border);aspect-ratio:1">
                                <img src="{{ Storage::url($img->image_path) }}" style="width:100%;height:100%;object-fit:cover;display:block">
                                <span style="position:absolute;top:3px;left:3px;font-size:9px;font-weight:700;padding:2px 5px;border-radius:3px;background:rgba(0,0,0,0.5);color:#fff">#{{ $loop->iteration }}</span>
                                <form action="{{ route('admin.colors.images.destroy', [$img->color->id, $img->id]) }}" method="POST" style="position:absolute;top:3px;right:3px" onsubmit="return confirm('Delete?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" style="width:18px;height:18px;border-radius:50%;background:rgba(220,53,69,0.9);color:#fff;border:none;cursor:pointer;font-size:11px;display:flex;align-items:center;justify-content:center">&times;</button>
                                </form>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Upload More --}}
                    <div id="dropZone" style="border:2px dashed var(--border);border-radius:var(--radius);padding:30px 20px;text-align:center;cursor:pointer;transition:all .2s;background:var(--bg-input)" onclick="document.getElementById('imageInput').click()">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="var(--text-muted)" stroke-width="1.5" style="margin-bottom:8px"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                        <p style="font-size:13px;font-weight:500;color:var(--text-dim);margin-bottom:2px">Click to upload more gallery images</p>
                        <p style="font-size:11px;color:var(--text-muted)">JPG, PNG, WebP — Max 6MB each</p>
                    </div>
                    <input type="file" id="imageInput" name="images[]" multiple accept="image/*" style="display:none">
                    <div id="imageGrid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(90px,1fr));gap:8px;margin-top:12px"></div>
                </div>
            </div>
        </div>

        {{-- RIGHT COLUMN --}}
        <div style="display:flex;flex-direction:column;gap:20px">
            <div class="admin-card">
                <div class="admin-card__header"><span class="admin-card__title">Collections</span></div>
                <div class="admin-card__body">
                    @php $selectedCollections = old('collections', $product->collections->pluck('id')->toArray()); @endphp
                    @foreach($collections as $col)
                    <label style="display:flex;align-items:center;gap:10px;padding:8px 0;border-bottom:1px solid var(--border);cursor:pointer">
                        <input type="checkbox" name="collections[]" value="{{ $col->id }}"
                            {{ in_array($col->id, $selectedCollections) ? 'checked' : '' }}>
                        <span style="font-size:13px;color:var(--text-dim)">{{ $col->name }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <div class="admin-card">
                <div class="admin-card__header"><span class="admin-card__title">Flags & Status</span></div>
                <div class="admin-card__body" style="display:flex;flex-direction:column;gap:16px">
                    <label class="admin-toggle">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                        <span class="admin-toggle__track"></span>
                        <span class="admin-toggle__label">Active</span>
                    </label>
                    <label class="admin-toggle">
                        <input type="checkbox" name="is_top_seller" value="1" {{ old('is_top_seller', $product->is_top_seller) ? 'checked' : '' }}>
                        <span class="admin-toggle__track"></span>
                        <span class="admin-toggle__label">Top Seller</span>
                    </label>
                    <label class="admin-toggle">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                        <span class="admin-toggle__track"></span>
                        <span class="admin-toggle__label">Featured</span>
                    </label>
                    <div class="admin-form-group" style="margin-bottom:0">
                        <label>Sort Order</label>
                        <input type="number" name="sort_order" class="admin-input" value="{{ old('sort_order', $product->sort_order) }}" min="0" style="width:100px">
                    </div>
                </div>
            </div>

            <button type="submit" class="btn-admin btn-admin--gold" style="width:100%;justify-content:center">Save Changes</button>
        </div>
    </div>
</form>

@push('scripts')
<script>
/* ─── Cover Image Preview ─── */
function previewCover(input) {
    const file = input.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('coverPreviewImg').src = e.target.result;
        document.getElementById('coverPreview').style.display = 'block';
    };
    reader.readAsDataURL(file);
}

function removeCover() {
    document.getElementById('coverInput').value = '';
    document.getElementById('coverPreview').style.display = 'none';
}

/* ─── Gallery Upload ─── */
const dropZone = document.getElementById('dropZone');
const imageInput = document.getElementById('imageInput');
const imageGrid = document.getElementById('imageGrid');
let imageFiles = [];

dropZone.addEventListener('dragover', e => { e.preventDefault(); dropZone.style.borderColor = 'var(--gold)'; dropZone.style.background = 'var(--gold-dim)'; });
dropZone.addEventListener('dragleave', () => { dropZone.style.borderColor = 'var(--border)'; dropZone.style.background = 'var(--bg-input)'; });
dropZone.addEventListener('drop', e => {
    e.preventDefault();
    dropZone.style.borderColor = 'var(--border)';
    dropZone.style.background = 'var(--bg-input)';
    addFiles(e.dataTransfer.files);
});
imageInput.addEventListener('change', () => { addFiles(imageInput.files); imageInput.value = ''; });

function addFiles(fileList) {
    for (const file of fileList) {
        if (!file.type.startsWith('image/')) continue;
        if (imageFiles.length >= 20) break;
        imageFiles.push(file);
    }
    renderImageGrid();
}

function renderImageGrid() {
    imageGrid.innerHTML = '';
    imageFiles.forEach((file, i) => {
        const div = document.createElement('div');
        div.style.cssText = 'position:relative;border-radius:6px;overflow:hidden;border:1px solid var(--border);aspect-ratio:1;background:var(--bg-input)';
        const img = document.createElement('img');
        img.src = URL.createObjectURL(file);
        img.style.cssText = 'width:100%;height:100%;object-fit:cover;display:block';
        const del = document.createElement('button');
        del.innerHTML = '&times;';
        del.style.cssText = 'position:absolute;top:4px;right:4px;width:20px;height:20px;border-radius:50%;background:rgba(220,53,69,0.9);color:#fff;border:none;cursor:pointer;font-size:12px;display:flex;align-items:center;justify-content:center';
        del.onclick = (e) => { e.stopPropagation(); imageFiles.splice(i, 1); renderImageGrid(); };
        div.appendChild(img);
        div.appendChild(del);
        imageGrid.appendChild(div);
    });
}
</script>
@endpush
@endsection