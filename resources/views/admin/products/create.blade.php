@extends('layouts.admin')
@section('title', 'New Product')

@section('content')
<div class="admin-page-header">
    <div>
        <h1 class="admin-page-header__title" id="pageTitle">New Product</h1>
        <p class="admin-page-header__sub" id="pageSub">Fill in details, upload images, then save to manage attributes & variations</p>
    </div>
    <div style="display:flex;gap:10px">
        <a href="{{ route('admin.products.index') }}" class="btn-admin btn-admin--outline">← Back</a>
    </div>
</div>

{{-- ═══ PHASE 1: Product Details ═══ --}}
<div id="phaseDetails">
<form id="productForm" enctype="multipart/form-data">
@csrf
<div class="admin-grid-2">
    {{-- LEFT COLUMN --}}
    <div style="display:flex;flex-direction:column;gap:20px">

        {{-- Product Details --}}
        <div class="admin-card">
            <div class="admin-card__header"><span class="admin-card__title">Product Details</span></div>
            <div class="admin-card__body">
                <div class="admin-form-group">
                    <label>Product Name *</label>
                    <input type="text" name="name" class="admin-input" required placeholder="e.g. Heritage Camp Shirt">
                </div>
                <div class="admin-form-group">
                    <label>Category *</label>
                    <select name="category_id" class="admin-select" required>
                        <option value="">Select category…</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Product Type --}}
                <div class="admin-form-group">
                    <label>Product Type *</label>
                    <div style="display:flex;gap:12px">
                        <label style="flex:1;display:flex;align-items:center;gap:10px;padding:14px;border:2px solid var(--gold-border);border-radius:6px;cursor:pointer;background:var(--gold-dim);transition:border-color .2s" id="typeSimple">
                            <input type="radio" name="type" value="simple" checked style="accent-color:var(--gold)">
                            <div>
                                <div style="font-size:13px;font-weight:500;color:var(--text)">Simple</div>
                                <div style="font-size:11px;color:var(--text-muted)">One product, no variations</div>
                            </div>
                        </label>
                        <label style="flex:1;display:flex;align-items:center;gap:10px;padding:14px;border:2px solid var(--border);border-radius:6px;cursor:pointer;transition:border-color .2s" id="typeVariable">
                            <input type="radio" name="type" value="variable" style="accent-color:var(--gold)">
                            <div>
                                <div style="font-size:13px;font-weight:500;color:var(--text)">Variable</div>
                                <div style="font-size:11px;color:var(--text-muted)">Multiple options (Color, Size…)</div>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="admin-form-grid">
                    <div class="admin-form-group">
                        <label>Price (₹) *</label>
                        <input type="number" name="price" class="admin-input" required min="0" step="0.01" style="width:180px" placeholder="3490">
                    </div>
                    <div class="admin-form-group">
                        <label>SKU</label>
                        <input type="text" name="sku" class="admin-input" placeholder="JDC-SHR-001">
                    </div>
                </div>
                <div id="simpleStock" class="admin-form-group">
                    <label>Stock</label>
                    <input type="number" name="stock" class="admin-input" value="0" min="0" style="width:120px">
                </div>
                <div class="admin-form-group">
                    <label>Description *</label>
                    <textarea name="description" class="admin-textarea" required placeholder="Product description…"></textarea>
                </div>
                <div class="admin-form-group">
                    <label>Heritage Note</label>
                    <textarea name="heritage_note" class="admin-textarea" placeholder="Cultural context…" style="min-height:80px"></textarea>
                </div>
                <div class="admin-form-grid">
                    <div class="admin-form-group">
                        <label>Material</label>
                        <input type="text" name="material" class="admin-input" placeholder="e.g. Handloom Cotton">
                    </div>
                    <div class="admin-form-group">
                        <label>Weight</label>
                        <input type="text" name="weight" class="admin-input" placeholder="e.g. 250g">
                    </div>
                </div>
                <div class="admin-form-group">
                    <label>Care Instructions</label>
                    <textarea name="care_instructions" class="admin-textarea" placeholder="e.g. Hand wash cold…" style="min-height:60px"></textarea>
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
                <div id="coverDropZone" style="border:2px dashed var(--border);border-radius:var(--radius);padding:30px 20px;text-align:center;cursor:pointer;transition:all .2s;background:var(--bg-input)" onclick="document.getElementById('coverInput').click()">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="var(--text-muted)" stroke-width="1.5" style="margin-bottom:8px"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                    <p style="font-size:13px;font-weight:500;color:var(--text-dim);margin-bottom:2px">Click to upload cover image</p>
                    <p style="font-size:11px;color:var(--text-muted)">JPG, PNG, WebP — Max 6MB — Recommended: 800×1000px portrait</p>
                </div>
                <input type="file" id="coverInput" name="cover_image" accept="image/*" style="display:none" onchange="previewCover(this)">
                <div id="coverPreview" style="margin-top:12px;display:none">
                    <div style="position:relative;display:inline-block;border-radius:6px;overflow:hidden;border:2px solid var(--gold)">
                        <img id="coverPreviewImg" style="width:120px;height:150px;object-fit:cover;display:block">
                        <button type="button" onclick="removeCover()" style="position:absolute;top:4px;right:4px;width:20px;height:20px;border-radius:50%;background:rgba(220,53,69,0.9);color:#fff;border:none;cursor:pointer;font-size:12px;display:flex;align-items:center;justify-content:center">&times;</button>
                    </div>
                    <p style="font-size:11px;color:var(--gold);margin-top:6px">Cover image selected</p>
                </div>
            </div>
        </div>

        {{-- ═══ GALLERY IMAGES ═══ --}}
        <div class="admin-card">
            <div class="admin-card__header">
                <span class="admin-card__title">Gallery Images</span>
                <span style="font-size:12px;color:var(--text-muted)" id="imageCount">0 image(s)</span>
            </div>
            <div class="admin-card__body">
                <div id="dropZone" style="border:2px dashed var(--border);border-radius:var(--radius);padding:40px 20px;text-align:center;cursor:pointer;transition:all .2s;background:var(--bg-input)" onclick="document.getElementById('imageInput').click()">
                    <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="var(--text-muted)" stroke-width="1.5" style="margin-bottom:12px"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                    <p style="font-size:14px;font-weight:500;color:var(--text-dim);margin-bottom:4px">Click to upload or drag & drop</p>
                    <p style="font-size:12px;color:var(--text-muted)">JPG, PNG, WebP — Max 6MB each — Up to 20 images</p>
                </div>
                <input type="file" id="imageInput" name="images[]" multiple accept="image/*" style="display:none">
                <div id="imageGrid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(110px,1fr));gap:10px;margin-top:16px"></div>
                <p id="reorderHint" style="display:none;font-size:11px;color:var(--text-muted);margin-top:10px;text-align:center">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:-2px"><path d="M5 9l4-4 4 4"/><path d="M15 15l4 4-4 4"/><line x1="9" y1="3" x2="9" y2="21"/><line x1="15" y1="3" x2="15" y2="21"/></svg>
                    Drag images to reorder
                </p>
            </div>
        </div>
    </div>

    {{-- RIGHT COLUMN --}}
    <div style="display:flex;flex-direction:column;gap:20px">
        <div class="admin-card">
            <div class="admin-card__header"><span class="admin-card__title">Collections</span></div>
            <div class="admin-card__body">
                @foreach($collections as $col)
                <label style="display:flex;align-items:center;gap:10px;padding:8px 0;border-bottom:1px solid var(--border);cursor:pointer">
                    <input type="checkbox" name="collections[]" value="{{ $col->id }}">
                    <span style="font-size:13px;color:var(--text-dim)">{{ $col->name }}</span>
                </label>
                @endforeach
            </div>
        </div>

        <div class="admin-card">
            <div class="admin-card__header"><span class="admin-card__title">Flags & Status</span></div>
            <div class="admin-card__body" style="display:flex;flex-direction:column;gap:16px">
                <label class="admin-toggle">
                    <input type="checkbox" name="is_active" value="1" checked>
                    <span class="admin-toggle__track"></span>
                    <span class="admin-toggle__label">Active</span>
                </label>
                <label class="admin-toggle">
                    <input type="checkbox" name="is_top_seller" value="1">
                    <span class="admin-toggle__track"></span>
                    <span class="admin-toggle__label">Top Seller</span>
                </label>
                <label class="admin-toggle">
                    <input type="checkbox" name="is_featured" value="1">
                    <span class="admin-toggle__track"></span>
                    <span class="admin-toggle__label">Featured</span>
                </label>
                <div class="admin-form-group" style="margin-bottom:0">
                    <label>Sort Order</label>
                    <input type="number" name="sort_order" class="admin-input" value="0" min="0" style="width:100px">
                </div>
            </div>
        </div>

        <button type="button" class="btn-admin btn-admin--gold" style="width:100%;justify-content:center" onclick="saveProduct()">
            Save Product →
        </button>
    </div>
</div>
</form>
</div>

{{-- ═══ PHASE 2: Manage Attributes & Variations ═══ --}}
<div id="phaseManage" style="display:none">
    <div class="admin-card" style="margin-bottom:20px">
        <div class="admin-card__body" style="display:flex;align-items:center;gap:16px;flex-wrap:wrap">
            <span style="font-weight:600;color:var(--text);font-size:15px" id="pmName"></span>
            <span style="font-size:13px;font-weight:600;color:var(--gold)" id="pmPrice"></span>
            <span style="font-size:11px;padding:3px 8px;border-radius:3px;background:var(--gold-dim);color:var(--gold);border:1px solid var(--gold-border)" id="pmType"></span>
            <span style="flex:1"></span>
            <a href="#" id="pmEditLink" class="btn-admin btn-admin--outline btn-admin--sm">Edit Details</a>
            <a href="{{ route('admin.products.index') }}" class="btn-admin btn-admin--outline btn-admin--sm">← All Products</a>
        </div>
    </div>

    {{-- Attributes Section (only for variable products) --}}
    <div id="attributesSection" style="display:none">
        <div class="admin-card" style="margin-bottom:20px">
            <div class="admin-card__header">
                <span class="admin-card__title">Product Attributes</span>
                <span style="font-size:12px;color:var(--text-muted)">Select attributes for this product</span>
            </div>
            <div class="admin-card__body">
                <div id="availableAttributes">
                    @foreach($attributes as $attr)
                    <div class="attr-toggle" data-attr-id="{{ $attr->id }}" data-attr-name="{{ $attr->name }}" data-attr-slug="{{ $attr->slug }}" style="border:1px solid var(--border);border-radius:4px;margin-bottom:12px;overflow:hidden">
                        <div style="display:flex;align-items:center;gap:10px;padding:12px 16px;background:var(--bg-input);cursor:pointer" onclick="toggleAttr({{ $attr->id }})">
                            <input type="checkbox" class="attr-check" data-attr="{{ $attr->id }}" style="accent-color:var(--gold)">
                            <span style="font-weight:500;color:var(--text);font-size:13px">{{ $attr->name }}</span>
                            <span style="font-size:11px;color:var(--text-muted)">({{ $attr->values->count() }} values)</span>
                        </div>
                        <div class="attr-values-panel" id="attrPanel{{ $attr->id }}" style="display:none;padding:12px 16px;border-top:1px solid var(--border)">
                            <p style="font-size:10px;font-weight:600;letter-spacing:.1em;text-transform:uppercase;color:var(--text-muted);margin-bottom:8px">Select values:</p>
                            <div style="display:flex;gap:6px;flex-wrap:wrap">
                                @foreach($attr->values as $val)
                                <label style="display:flex;align-items:center;gap:4px;padding:4px 10px;border:1px solid var(--border);border-radius:4px;cursor:pointer;font-size:12px;color:var(--text-dim);transition:all .15s" class="val-chip" data-val-id="{{ $val->id }}">
                                    @if($val->hex_code)
                                        <span style="width:12px;height:12px;border-radius:50%;background:{{ $val->hex_code }};border:1px solid var(--border);flex-shrink:0"></span>
                                    @endif
                                    <input type="checkbox" class="val-check" data-attr="{{ $attr->id }}" data-val="{{ $val->id }}" style="display:none">
                                    {{ $val->name }}
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <button type="button" class="btn-admin btn-admin--gold" style="margin-top:12px" onclick="generateVariations()">Generate Variations</button>
            </div>
        </div>
    </div>

    {{-- Variants Matrix --}}
    <div class="admin-card" style="margin-bottom:20px">
        <div class="admin-card__header">
            <span class="admin-card__title">Variations</span>
            <span style="font-size:12px;color:var(--text-muted)" id="variationCount">0 variation(s)</span>
        </div>
        <div class="admin-card__body" id="variationMatrix">
            <p style="color:var(--text-muted);font-size:13px" id="variationPlaceholder">
                @if(isset($product) && $product->type === 'variable')
                    Select attributes above and click "Generate Variations".
                @else
                    Variations will appear here after saving a variable product.
                @endif
            </p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').content;
const JSON_HEADERS = { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' };
let PRODUCT_ID = null;
let PRODUCT_NAME = '';
let PRODUCT_PRICE = 0;
let PRODUCT_TYPE = 'simple';
let selectedAttrs = {};
let imageFiles = [];

/* ─── Cover Image Preview ─── */
function previewCover(input) {
    const file = input.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('coverPreviewImg').src = e.target.result;
        document.getElementById('coverPreview').style.display = 'block';
        document.getElementById('coverDropZone').style.display = 'none';
    };
    reader.readAsDataURL(file);
}

function removeCover() {
    document.getElementById('coverInput').value = '';
    document.getElementById('coverPreview').style.display = 'none';
    document.getElementById('coverDropZone').style.display = 'block';
}

/* ─── Gallery Image Upload ─── */
const dropZone = document.getElementById('dropZone');
const imageInput = document.getElementById('imageInput');
const imageGrid = document.getElementById('imageGrid');

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
    document.getElementById('imageCount').textContent = imageFiles.length + ' image(s)';
    document.getElementById('reorderHint').style.display = imageFiles.length > 1 ? 'block' : 'none';

    imageFiles.forEach((file, i) => {
        const div = document.createElement('div');
        div.draggable = true;
        div.dataset.index = i;
        div.style.cssText = 'position:relative;border-radius:6px;overflow:hidden;border:1px solid var(--border);aspect-ratio:1;background:var(--bg-input);cursor:grab;transition:box-shadow .15s';

        const img = document.createElement('img');
        img.src = URL.createObjectURL(file);
        img.style.cssText = 'width:100%;height:100%;object-fit:cover;display:block';

        const badge = document.createElement('span');
        badge.style.cssText = 'position:absolute;top:4px;left:4px;font-size:9px;font-weight:700;padding:2px 6px;border-radius:3px;background:rgba(0,0,0,0.5);color:#fff;letter-spacing:.05em';
        badge.textContent = '#' + (i + 1);

        const del = document.createElement('button');
        del.innerHTML = '&times;';
        del.style.cssText = 'position:absolute;top:4px;right:4px;width:20px;height:20px;border-radius:50%;background:rgba(220,53,69,0.9);color:#fff;border:none;cursor:pointer;font-size:14px;line-height:1;display:flex;align-items:center;justify-content:center;opacity:0;transition:opacity .15s';
        del.onmouseenter = () => del.style.opacity = '1';
        del.onmouseleave = () => del.style.opacity = '0';
        del.onclick = (e) => { e.stopPropagation(); imageFiles.splice(i, 1); renderImageGrid(); };

        div.appendChild(img);
        div.appendChild(badge);
        div.appendChild(del);
        imageGrid.appendChild(div);

        // Drag & drop reorder
        div.addEventListener('dragstart', e => { e.dataTransfer.setData('text/plain', i); div.style.opacity = '0.4'; });
        div.addEventListener('dragend', () => { div.style.opacity = '1'; });
        div.addEventListener('dragover', e => { e.preventDefault(); div.style.boxShadow = '0 0 0 2px var(--gold)'; });
        div.addEventListener('dragleave', () => { div.style.boxShadow = 'none'; });
        div.addEventListener('drop', e => {
            e.preventDefault();
            div.style.boxShadow = 'none';
            const fromIdx = parseInt(e.dataTransfer.getData('text/plain'));
            const toIdx = i;
            if (fromIdx !== toIdx) {
                const [moved] = imageFiles.splice(fromIdx, 1);
                imageFiles.splice(toIdx, 0, moved);
                renderImageGrid();
            }
        });
    });
}

/* ─── Product Type Toggle ─── */
document.querySelectorAll('input[name="type"]').forEach(r => {
    r.addEventListener('change', () => {
        PRODUCT_TYPE = r.value;
        document.getElementById('typeSimple').style.borderColor = r.value === 'simple' ? 'var(--gold-border)' : 'var(--border)';
        document.getElementById('typeSimple').style.background = r.value === 'simple' ? 'var(--gold-dim)' : 'transparent';
        document.getElementById('typeVariable').style.borderColor = r.value === 'variable' ? 'var(--gold-border)' : 'var(--border)';
        document.getElementById('typeVariable').style.background = r.value === 'variable' ? 'var(--gold-dim)' : 'transparent';
        document.getElementById('simpleStock').style.display = r.value === 'simple' ? 'block' : 'none';
    });
});

/* ─── Save Product ─── */
async function saveProduct() {
    const form = document.getElementById('productForm');
    const fd = new FormData(form);

    // Remove images[] from FormData and re-add in order
    fd.delete('images[]');
    imageFiles.forEach(file => fd.append('images[]', file));

    try {
        const res = await fetch('{{ route("admin.products.store") }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            body: fd
        });
        const data = await res.json();
        if (data.success && data.product) {
            PRODUCT_ID = data.product.id;
            PRODUCT_NAME = data.product.name;
            PRODUCT_PRICE = parseFloat(data.product.price);
            PRODUCT_TYPE = data.product.type;

            document.getElementById('pageTitle').textContent = 'Manage: ' + PRODUCT_NAME;
            document.getElementById('pageSub').textContent = PRODUCT_TYPE === 'variable' ? 'Select attributes, then generate variations' : 'Product saved — manage colors & images below';
            document.getElementById('pmName').textContent = PRODUCT_NAME;
            document.getElementById('pmPrice').textContent = '₹' + Number(PRODUCT_PRICE).toLocaleString('en-IN');
            document.getElementById('pmType').textContent = PRODUCT_TYPE === 'variable' ? 'Variable Product' : 'Simple Product';
            document.getElementById('pmEditLink').href = '/admin/products/' + PRODUCT_ID + '/edit';
            document.getElementById('phaseDetails').style.display = 'none';
            document.getElementById('phaseManage').style.display = 'block';

            if (PRODUCT_TYPE === 'variable') {
                document.getElementById('attributesSection').style.display = 'block';
            }
            window.scrollTo({ top: 0, behavior: 'smooth' });
        } else {
            const errors = data.errors || {};
            const msg = Object.values(errors).flat().join('\n');
            alert(msg || 'Failed to save. Check all required fields.');
        }
    } catch (e) {
        alert('Network error: ' + e.message);
    }
}

/* ─── Attribute Selection ─── */
function toggleAttr(attrId) {
    const panel = document.getElementById('attrPanel' + attrId);
    panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
}

document.querySelectorAll('.val-chip').forEach(chip => {
    chip.addEventListener('click', () => {
        const input = chip.querySelector('.val-check');
        input.checked = !input.checked;
        chip.style.borderColor = input.checked ? 'var(--gold)' : 'var(--border)';
        chip.style.background = input.checked ? 'var(--gold-dim)' : 'transparent';
        chip.style.color = input.checked ? 'var(--gold)' : 'var(--text-dim)';
    });
});

document.querySelectorAll('.attr-check').forEach(check => {
    check.addEventListener('change', () => {
        const attrId = check.dataset.attr;
        if (check.checked) {
            selectedAttrs[attrId] = [];
        } else {
            delete selectedAttrs[attrId];
            document.getElementById('attrPanel' + attrId).style.display = 'none';
        }
    });
});

/* ─── Generate Variations ─── */
function generateVariations() {
    selectedAttrs = {};
    document.querySelectorAll('.attr-check:checked').forEach(c => {
        const attrId = c.dataset.attr;
        const vals = [];
        document.querySelectorAll('.val-check[data-attr="' + attrId + '"]:checked').forEach(v => {
            vals.push(parseInt(v.dataset.val));
        });
        if (vals.length) selectedAttrs[attrId] = vals;
    });

    const attrIds = Object.keys(selectedAttrs);
    if (attrIds.length === 0) {
        alert('Select at least one attribute and its values.');
        return;
    }

    const combos = attrIds.reduce((acc, attrId) => {
        const newAcc = [];
        acc.forEach(combo => {
            selectedAttrs[attrId].forEach(valId => {
                newAcc.push([...combo, { attribute_id: parseInt(attrId), attribute_value_id: valId }]);
            });
        });
        return newAcc;
    }, [[]]);

    const el = document.getElementById('variationMatrix');
    document.getElementById('variationPlaceholder').style.display = 'none';
    document.getElementById('variationCount').textContent = combos.length + ' variation(s)';

    let html = '<div style="overflow-x:auto"><table class="admin-table" style="min-width:400px"><thead><tr><th>Attributes</th><th>Stock</th><th>Price Override</th><th>SKU</th></tr></thead><tbody>';

    combos.forEach((combo, i) => {
        const label = combo.map(c => {
            const attrEl = document.querySelector(`#attrPanel${c.attribute_id}`);
            const chip = attrEl?.querySelector(`[data-val="${c.attribute_value_id}"]`);
            return chip?.textContent?.trim() || c.attribute_value_id;
        }).join(' / ');

        html += `<tr>
            <td><span style="font-size:13px;color:var(--text)">${label}</span></td>
            <td><input type="number" class="var-stock" data-idx="${i}" value="0" min="0" style="width:70px;padding:6px 8px;background:var(--bg-input);border:1px solid var(--border);border-radius:3px;color:var(--text);font-size:13px"></td>
            <td><input type="number" class="var-price" data-idx="${i}" placeholder="₹${PRODUCT_PRICE}" step="0.01" min="0" style="width:100px;padding:6px 8px;background:var(--bg-input);border:1px solid var(--border);border-radius:3px;color:var(--text);font-size:13px"></td>
            <td><input type="text" class="var-sku" data-idx="${i}" placeholder="SKU" style="width:100px;padding:6px 8px;background:var(--bg-input);border:1px solid var(--border);border-radius:3px;color:var(--text-muted);font-size:13px"></td>
        </tr>`;
    });

    html += '</tbody></table></div>';
    html += '<div style="margin-top:16px"><button type="button" class="btn-admin btn-admin--gold" onclick="saveVariations()">Save All Variations</button></div>';
    el.innerHTML = html;

    window._combos = combos;
}

async function saveVariations() {
    if (!PRODUCT_ID || !window._combos) return;
    const saves = [];

    document.querySelectorAll('.var-stock').forEach((inp, i) => {
        const stock = parseInt(inp.value) || 0;
        const priceEl = document.querySelector(`.var-price[data-idx="${i}"]`);
        const skuEl = document.querySelector(`.var-sku[data-idx="${i}"]`);
        const combo = window._combos[i];

        saves.push(
            fetch(`/admin/products/${PRODUCT_ID}/variations`, {
                method: 'POST', headers: JSON_HEADERS,
                body: JSON.stringify({
                    stock,
                    price: priceEl?.value ? parseFloat(priceEl.value) : null,
                    sku: skuEl?.value || null,
                    attributes: combo
                })
            }).then(r => r.json())
        );
    });

    await Promise.all(saves);
    alert('Variations saved!');
}
</script>
@endpush
