@extends('layouts.admin')
@section('title', $product->name)

@section('content')
<div class="admin-page-header">
    <div>
        <h1 class="admin-page-header__title">{{ $product->name }}</h1>
        <p class="admin-page-header__sub">
            {{ $product->category->name }}
            @foreach($product->collections as $col) · {{ $col->name }} @endforeach
            · ₹{{ number_format($product->price) }}
            · <span class="badge {{ $product->is_active ? 'badge--green' : 'badge--gray' }}">{{ $product->is_active ? 'Active' : 'Draft' }}</span>
        </p>
    </div>
    <div style="display:flex;gap:10px">
        <a href="{{ route('admin.products.edit', $product) }}" class="btn-admin btn-admin--gold btn-admin--sm">Edit Details</a>
        <a href="{{ route('admin.products.index') }}" class="btn-admin btn-admin--outline btn-admin--sm">← All Products</a>
    </div>
</div>

<div class="admin-grid-2">
    {{-- ═══ LEFT: IMAGE GALLERY ═══ --}}
    <div style="display:flex;flex-direction:column;gap:20px">
        @php
            $allImages = $product->colors->flatMap(function($c) { return $c->images; })->sortBy('sort_order')->values();
        @endphp

        {{-- Cover Image --}}
        <div class="admin-card">
            <div class="admin-card__header">
                <span class="admin-card__title">Cover Image</span>
                <label style="cursor:pointer;font-size:11px;color:var(--gold);display:flex;align-items:center;gap:4px;font-weight:500">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                    Upload Cover
                    <input type="file" accept="image/*" style="display:none" onchange="uploadCover(this.files)">
                </label>
            </div>
            <div class="admin-card__body">
                <div style="width:100%;max-width:280px;aspect-ratio:4/5;border-radius:var(--radius);overflow:hidden;background:var(--bg-input);display:flex;align-items:center;justify-content:center;border:1px solid var(--border)">
                    @if($product->cover_image)
                        <img src="{{ Storage::url($product->cover_image) }}" style="width:100%;height:100%;object-fit:cover">
                    @else
                        <div style="text-align:center;padding:30px">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--text-muted)" stroke-width="1"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                            <p style="font-size:12px;color:var(--text-muted);margin-top:6px">No cover image</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Gallery Images --}}
        <div class="admin-card">
            <div class="admin-card__header">
                <span class="admin-card__title">Gallery Images</span>
                <span style="font-size:12px;color:var(--text-muted)">{{ $allImages->count() }} image(s)</span>
            </div>
            <div class="admin-card__body">
                {{-- Thumbnail Strip --}}
                @if($allImages->count())
                <div id="thumbStrip" style="display:flex;gap:8px;overflow-x:auto;padding-bottom:4px">
                    @foreach($allImages as $img)
                    <div class="thumb-item" data-image-id="{{ $img->id }}" data-color-id="{{ $img->color->id }}"
                         onclick="selectThumb(this, '{{ Storage::url($img->image_path) }}')"
                         style="width:64px;height:64px;border-radius:6px;overflow:hidden;flex-shrink:0;cursor:pointer;border:2px solid var(--border);transition:border-color .15s;position:relative">
                        <img src="{{ Storage::url($img->image_path) }}" style="width:100%;height:100%;object-fit:cover">
                    </div>
                    @endforeach
                </div>
                @endif

                {{-- Upload More --}}
                <div style="margin-top:12px;display:flex;gap:10px;flex-wrap:wrap;align-items:center">
                    <label style="display:inline-flex;align-items:center;gap:6px;padding:8px 16px;background:var(--gold);color:#fff;border-radius:var(--radius-sm);cursor:pointer;font-size:12px;font-weight:600;transition:opacity .15s" onmouseover="this.style.opacity='0.85'" onmouseout="this.style.opacity='1'">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Add Images
                        <input type="file" multiple accept="image/*" style="display:none" onchange="bulkUpload(this.files)">
                    </label>
                    <span style="font-size:11px;color:var(--text-muted)">Drag thumbnails to reorder</span>
                </div>
            </div>
        </div>

        {{-- Reorder Gallery --}}
        @if($allImages->count() > 1)
        <div class="admin-card">
            <div class="admin-card__header">
                <span class="admin-card__title">Reorder Gallery</span>
                <span style="font-size:11px;color:var(--text-muted)">Drag to reorder</span>
            </div>
            <div class="admin-card__body">
                <div id="reorderGrid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(80px,1fr));gap:8px">
                    @foreach($allImages as $img)
                    <div class="reorder-item" draggable="true" data-image-id="{{ $img->id }}" data-color-id="{{ $img->color->id }}" data-order="{{ $loop->index }}"
                         style="position:relative;border-radius:6px;overflow:hidden;aspect-ratio:1;cursor:grab;border:1px solid var(--border);transition:all .15s;background:var(--bg-input)">
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
        </div>
        @endif
                    @endforeach
                </div>
                @endif

                {{-- Upload More --}}
                <div style="margin-top:16px;display:flex;gap:10px;flex-wrap:wrap">
                    <label style="display:inline-flex;align-items:center;gap:6px;padding:8px 16px;background:var(--gold);color:#fff;border-radius:var(--radius-sm);cursor:pointer;font-size:12px;font-weight:600;transition:opacity .15s" onmouseover="this.style.opacity='0.85'" onmouseout="this.style.opacity='1'">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Upload Images
                        <input type="file" multiple accept="image/*" style="display:none" onchange="bulkUpload(this.files)">
                    </label>
                    <span style="font-size:11px;color:var(--text-muted);align-self:center">JPG, PNG, WebP · Max 6MB · Drag thumbnails to reorder</span>
                </div>
            </div>
        </div>

        {{-- Drag-to-Reorder Gallery --}}
        @if($allImages->count() > 1)
        <div class="admin-card">
            <div class="admin-card__header">
                <span class="admin-card__title">Reorder Gallery</span>
                <span style="font-size:11px;color:var(--text-muted)">Drag to reorder — first image is the cover</span>
            </div>
            <div class="admin-card__body">
                <div id="reorderGrid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(80px,1fr));gap:8px">
                    @foreach($allImages as $img)
                    <div class="reorder-item" draggable="true" data-image-id="{{ $img->id }}" data-color-id="{{ $img->color->id }}" data-order="{{ $loop->index }}"
                         style="position:relative;border-radius:6px;overflow:hidden;aspect-ratio:1;cursor:grab;border:2px solid {{ $loop->first ? 'var(--gold)' : 'var(--border)' }};transition:all .15s;background:var(--bg-input)">
                        <img src="{{ Storage::url($img->image_path) }}" style="width:100%;height:100%;object-fit:cover;display:block">
                        <span style="position:absolute;top:3px;left:3px;font-size:9px;font-weight:700;padding:2px 5px;border-radius:3px;background:{{ $loop->first ? 'var(--gold)' : 'rgba(0,0,0,0.5)' }};color:#fff">{{ $loop->first ? 'COVER' : '#' . $loop->iteration }}</span>
                        <form action="{{ route('admin.colors.images.destroy', [$img->color->id, $img->id]) }}" method="POST" style="position:absolute;top:3px;right:3px" onsubmit="return confirm('Delete?')">
                            @csrf @method('DELETE')
                            <button type="submit" style="width:18px;height:18px;border-radius:50%;background:rgba(220,53,69,0.9);color:#fff;border:none;cursor:pointer;font-size:11px;display:flex;align-items:center;justify-content:center">&times;</button>
                        </form>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- ═══ RIGHT: COLORS + SIZES + VARIANTS ═══ --}}
    <div style="display:flex;flex-direction:column;gap:20px">

        {{-- Colors --}}
        <div class="admin-card">
            <div class="admin-card__header">
                <span class="admin-card__title">Colors</span>
                <span style="font-size:12px;color:var(--text-muted)">{{ $product->colors->count() }} color(s)</span>
            </div>
            <div class="admin-card__body">
                <div style="display:flex;gap:8px;margin-bottom:16px;flex-wrap:wrap">
                    <input type="text" id="colorName" class="admin-input" placeholder="Color name" style="width:140px;padding:8px 10px;font-size:12px">
                    <input type="color" id="colorHex" value="#C9A04E" style="width:36px;height:36px;border:1px solid var(--border);border-radius:4px;background:transparent;cursor:pointer;padding:2px">
                    <input type="text" id="colorHexText" class="admin-input" placeholder="#C9A04E" style="width:80px;padding:8px 10px;font-size:12px">
                    <button type="button" class="btn-admin btn-admin--gold btn-admin--sm" onclick="addColor()">+ Add</button>
                </div>
                <div id="colorList">
                    @forelse($product->colors as $color)
                    <div class="color-card" data-color-id="{{ $color->id }}" style="display:flex;align-items:center;gap:10px;padding:8px 12px;border:1px solid var(--border);border-radius:6px;margin-bottom:6px;transition:border-color .15s" onmouseover="this.style.borderColor='var(--gold-border)'" onmouseout="this.style.borderColor='var(--border)'">
                        <div style="width:24px;height:24px;border-radius:50%;background:{{ $color->hex_code }};border:2px solid var(--border);flex-shrink:0"></div>
                        <span style="font-size:13px;font-weight:500;color:var(--text)">{{ $color->color_name }}</span>
                        <span style="font-size:11px;color:var(--text-muted)">{{ $color->hex_code }}</span>
                        <span style="font-size:11px;color:var(--text-muted)">{{ $color->images->count() }} img</span>
                        <span style="flex:1"></span>
                        <label style="cursor:pointer;font-size:11px;color:var(--gold);display:flex;align-items:center;gap:4px;font-weight:500">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                            Upload
                            <input type="file" multiple accept="image/*" style="display:none" onchange="uploadImages({{ $color->id }}, this.files)">
                        </label>
                        <button onclick="deleteColor({{ $color->id }})" style="background:none;border:none;color:var(--text-muted);cursor:pointer;font-size:14px;padding:0;line-height:1" title="Delete color">×</button>
                    </div>
                    @empty
                    <p style="color:var(--text-muted);font-size:12px;text-align:center;padding:16px 0">No colors added yet.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Sizes --}}
        <div class="admin-card">
            <div class="admin-card__header">
                <span class="admin-card__title">Sizes</span>
                <span style="font-size:11px;color:var(--text-muted)">Optional</span>
            </div>
            <div class="admin-card__body">
                <div style="display:flex;gap:8px;margin-bottom:12px">
                    <input type="text" id="sizeLabel" class="admin-input" placeholder="S, M, L, XL" style="width:120px;padding:8px 10px;font-size:12px">
                    <button type="button" class="btn-admin btn-admin--gold btn-admin--sm" onclick="addSize()">+ Add</button>
                </div>
                <div id="sizeList" style="display:flex;gap:6px;flex-wrap:wrap">
                    @foreach($product->sizes as $size)
                    <div class="size-chip" data-size-id="{{ $size->id }}" style="display:flex;align-items:center;gap:4px;padding:5px 10px;border:1px solid {{ $size->is_available ? 'var(--gold-border)' : 'var(--border)' }};border-radius:4px;background:{{ $size->is_available ? 'var(--gold-dim)' : 'transparent' }}">
                        <span style="font-size:12px;font-weight:500;color:{{ $size->is_available ? 'var(--gold)' : 'var(--text-muted)' }}">{{ $size->size_label }}</span>
                        <button onclick="deleteSize({{ $size->id }})" style="background:none;border:none;color:var(--text-muted);cursor:pointer;font-size:12px;padding:0;line-height:1" title="Remove">×</button>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Variant Matrix --}}
        <div class="admin-card">
            <div class="admin-card__header">
                <span class="admin-card__title">Variant Stock</span>
                <span style="font-size:11px;color:var(--text-muted)">Color × Size</span>
            </div>
            <div class="admin-card__body" id="variantMatrix">
                @if($product->colors->count() && $product->sizes->count())
                    @php $variants = $product->variants()->with(['color','size'])->get(); @endphp
                    <div style="overflow-x:auto">
                        <table class="admin-table" style="min-width:400px;font-size:12px">
                            <thead><tr><th>Color</th>
                                @foreach($product->sizes as $s)<th style="text-align:center;padding:8px 6px">{{ $s->size_label }}</th>@endforeach
                            <th></th></tr></thead>
                            <tbody>
                            @foreach($product->colors as $c)
                            <tr>
                                <td style="padding:8px"><div style="display:flex;align-items:center;gap:6px"><div style="width:16px;height:16px;border-radius:50%;background:{{ $c->hex_code }};border:1px solid var(--border)"></div><span style="font-size:12px">{{ $c->color_name }}</span></div></td>
                                @foreach($product->sizes as $s)
                                    @php $v = $variants->first(fn($x) => $x->product_color_id === $c->id && $x->product_size_id === $s->id); @endphp
                                    <td style="text-align:center;padding:4px">
                                        <div style="display:flex;flex-direction:column;gap:3px;align-items:center">
                                            <input type="number" class="v-stock" data-cid="{{ $c->id }}" data-sid="{{ $s->id }}" value="{{ $v?->stock ?? 0 }}" min="0" style="width:50px;padding:3px;text-align:center;background:var(--bg-input);border:1px solid var(--border);border-radius:3px;color:var(--text);font-size:11px" title="Stock">
                                            <input type="text" class="v-price" data-cid="{{ $c->id }}" data-sid="{{ $s->id }}" value="{{ $v?->price ?? '' }}" placeholder="₹{{ number_format($product->price) }}" style="width:60px;padding:2px;text-align:center;background:var(--bg-input);border:1px solid var(--border);border-radius:3px;color:var(--text);font-size:10px" title="Price override">
                                        </div>
                                    </td>
                                @endforeach
                                <td style="text-align:center;padding:4px"><span style="font-size:10px;color:var(--text-muted)">{{ $c->images->count() }}</span></td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div style="margin-top:12px"><button type="button" class="btn-admin btn-admin--gold btn-admin--sm" onclick="saveVariants()">Save Variants</button></div>
                @else
                    <p style="color:var(--text-muted);font-size:12px;text-align:center;padding:20px 0">Add colors and sizes to generate the variant matrix.</p>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
const PRODUCT_ID = {{ $product->id }};
const CSRF = document.querySelector('meta[name="csrf-token"]').content;
const JSON_HEADERS = { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' };

/* ─── Image Preview ─── */
function selectThumb(el, src) {
    document.getElementById('mainPreviewImg').src = src;
    document.querySelectorAll('.thumb-item').forEach(t => t.style.borderColor = 'var(--border)');
    el.style.borderColor = 'var(--gold)';
}

/* ─── Cover Upload ─── */
async function uploadCover(files) {
    if (!files.length) return;
    const fd = new FormData();
    fd.append('cover_image', files[0]);
    fd.append('_method', 'PATCH');
    await fetch(`/admin/products/${PRODUCT_ID}`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF },
        body: fd
    });
    location.reload();
}

/* ─── Bulk Upload ─── */
async function bulkUpload(files) {
    // Find or create a color to attach images to
    const firstColorEl = document.querySelector('.color-card');
    let colorId;
    if (firstColorEl) {
        colorId = firstColorEl.dataset.colorId;
    } else {
        const colorRes = await fetch(`/admin/products/${PRODUCT_ID}/colors`, {
            method: 'POST', headers: JSON_HEADERS,
            body: JSON.stringify({ color_name: 'Default', hex_code: '#808080' })
        });
        const colorData = await colorRes.json();
        colorId = colorData.color.id;
    }
    const fd = new FormData();
    for (const f of files) fd.append('images[]', f);
    await fetch(`/admin/colors/${colorId}/images`, { method: 'POST', headers: { 'X-CSRF-TOKEN': CSRF }, body: fd });
    location.reload();
}

/* ─── Per-Color Upload ─── */
async function uploadImages(colorId, files) {
    const fd = new FormData();
    for (const f of files) fd.append('images[]', f);
    await fetch(`/admin/colors/${colorId}/images`, { method: 'POST', headers: { 'X-CSRF-TOKEN': CSRF }, body: fd });
    location.reload();
}

/* ─── Drag to Reorder ─── */
const reorderGrid = document.getElementById('reorderGrid');
if (reorderGrid) {
    let dragItem = null;

    reorderGrid.querySelectorAll('.reorder-item').forEach(item => {
        item.addEventListener('dragstart', e => {
            dragItem = item;
            item.style.opacity = '0.4';
            e.dataTransfer.effectAllowed = 'move';
        });
        item.addEventListener('dragend', () => {
            item.style.opacity = '1';
            dragItem = null;
            reorderGrid.querySelectorAll('.reorder-item').forEach(i => i.style.boxShadow = 'none');
        });
        item.addEventListener('dragover', e => {
            e.preventDefault();
            if (item !== dragItem) item.style.boxShadow = '0 0 0 2px var(--gold)';
        });
        item.addEventListener('dragleave', () => {
            item.style.boxShadow = 'none';
        });
        item.addEventListener('drop', e => {
            e.preventDefault();
            item.style.boxShadow = 'none';
            if (dragItem && dragItem !== item) {
                const allItems = [...reorderGrid.children];
                const fromIdx = allItems.indexOf(dragItem);
                const toIdx = allItems.indexOf(item);
                if (fromIdx < toIdx) {
                    reorderGrid.insertBefore(dragItem, item.nextSibling);
                } else {
                    reorderGrid.insertBefore(dragItem, item);
                }
                updateReorderNumbers();
                saveReorder();
            }
        });
    });

    function updateReorderNumbers() {
        reorderGrid.querySelectorAll('.reorder-item').forEach((item, i) => {
            const badge = item.querySelector('span');
            if (badge) {
                badge.textContent = i === 0 ? 'COVER' : '#' + (i + 1);
                badge.style.background = i === 0 ? 'var(--gold)' : 'rgba(0,0,0,0.5)';
            }
            item.style.borderColor = i === 0 ? 'var(--gold)' : 'var(--border)';
        });
    }

    async function saveReorder() {
        const items = reorderGrid.querySelectorAll('.reorder-item');
        const order = [];
        items.forEach((item, i) => {
            order.push({ id: parseInt(item.dataset.imageId), sort_order: i });
        });
        // Group by color for the reorder endpoint
        const byColor = {};
        items.forEach((item, i) => {
            const cid = item.dataset.colorId;
            if (!byColor[cid]) byColor[cid] = [];
            byColor[cid].push({ id: parseInt(item.dataset.imageId), sort_order: i });
        });
        for (const [colorId, images] of Object.entries(byColor)) {
            await fetch(`/admin/colors/${colorId}/images/reorder`, {
                method: 'POST', headers: JSON_HEADERS,
                body: JSON.stringify({ images })
            });
        }
    }
}

/* ─── Colors ─── */
document.getElementById('colorHex')?.addEventListener('input', e => {
    document.getElementById('colorHexText').value = e.target.value;
});
document.getElementById('colorHexText')?.addEventListener('input', e => {
    if (/^#[0-9A-Fa-f]{6}$/.test(e.target.value)) document.getElementById('colorHex').value = e.target.value;
});

async function addColor() {
    const name = document.getElementById('colorName').value.trim();
    const hex  = document.getElementById('colorHexText').value.trim();
    if (!name || !hex) return alert('Enter color name and hex code');
    await fetch(`/admin/products/${PRODUCT_ID}/colors`, {
        method: 'POST', headers: JSON_HEADERS,
        body: JSON.stringify({ color_name: name, hex_code: hex })
    });
    location.reload();
}

async function deleteColor(colorId) {
    if (!confirm('Delete this color and all its images?')) return;
    await fetch(`/admin/products/${PRODUCT_ID}/colors/${colorId}`, { method: 'DELETE', headers: JSON_HEADERS });
    location.reload();
}

/* ─── Sizes ─── */
async function addSize() {
    const label = document.getElementById('sizeLabel').value.trim();
    if (!label) return alert('Enter a size label');
    await fetch(`/admin/products/${PRODUCT_ID}/sizes`, {
        method: 'POST', headers: JSON_HEADERS,
        body: JSON.stringify({ size_label: label, is_available: true })
    });
    location.reload();
}

async function deleteSize(sizeId) {
    if (!confirm('Remove this size?')) return;
    await fetch(`/admin/products/${PRODUCT_ID}/sizes/${sizeId}`, { method: 'DELETE', headers: JSON_HEADERS });
    location.reload();
}

/* ─── Variant Matrix ─── */
async function saveVariants() {
    const inputs = document.querySelectorAll('.v-stock');
    const saves = [];
    inputs.forEach(inp => {
        const cid = parseInt(inp.dataset.cid);
        const sid = parseInt(inp.dataset.sid);
        const stock = parseInt(inp.value) || 0;
        const priceEl = document.querySelector(`.v-price[data-cid="${cid}"][data-sid="${sid}"]`);
        saves.push(
            fetch(`/admin/products/${PRODUCT_ID}/variants`, {
                method: 'POST', headers: JSON_HEADERS,
                body: JSON.stringify({ product_color_id: cid, product_size_id: sid, stock, price: priceEl?.value ? parseFloat(priceEl.value) : null })
            }).then(r => r.json())
        );
    });
    await Promise.all(saves);
    alert('Variants saved!');
    location.reload();
}
</script>
@endpush
