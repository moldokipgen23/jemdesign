@extends('layouts.admin')
@section('title', 'Edit: ' . $marketingSection->title)

@section('content')
<div class="admin-page-header">
    <h1 class="admin-page-header__title">Edit Marketing Section</h1>
</div>

<form action="{{ route('admin.marketing.update', $marketingSection) }}" method="POST" id="sectionForm">
    @csrf
    @method('PATCH')

    <div class="admin-card" style="max-width:800px">
        <div class="admin-card__header">
            <span class="admin-card__title">Section Details</span>
        </div>
        <div class="admin-card__body">
            <div class="admin-form-group">
                <label>Section Title *</label>
                <input type="text" name="title" value="{{ old('title', $marketingSection->title) }}" class="admin-input" required>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                <div class="admin-form-group">
                    <label>Content Type *</label>
                    <select name="type" class="admin-input" id="sectionType" onchange="updateForm()" required>
                        <option value="trending" {{ old('type', $marketingSection->type) === 'trending' ? 'selected' : '' }}>Trending (Featured Products)</option>
                        <option value="new_arrivals" {{ old('type', $marketingSection->type) === 'new_arrivals' ? 'selected' : '' }}>New Arrivals</option>
                        <option value="best_selling" {{ old('type', $marketingSection->type) === 'best_selling' ? 'selected' : '' }}>Best Sellers</option>
                        <option value="category" {{ old('type', $marketingSection->type) === 'category' ? 'selected' : '' }}>By Category</option>
                        <option value="collection" {{ old('type', $marketingSection->type) === 'collection' ? 'selected' : '' }}>By Collection</option>
                        <option value="manual" {{ old('type', $marketingSection->type) === 'manual' ? 'selected' : '' }}>Manual Selection</option>
                        <option value="testimonials" {{ old('type', $marketingSection->type) === 'testimonials' ? 'selected' : '' }}>Testimonials</option>
                    </select>
                </div>

                <div class="admin-form-group">
                    <label>Display Style *</label>
                    <select name="display_style" class="admin-input" required>
                        <option value="grid" {{ old('display_style', $marketingSection->display_style) === 'grid' ? 'selected' : '' }}>Grid</option>
                        <option value="carousel" {{ old('display_style', $marketingSection->display_style) === 'carousel' ? 'selected' : '' }}>Carousel</option>
                    </select>
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px">
                <div class="admin-form-group">
                    <label>Items Per Row *</label>
                    <select name="items_per_row" class="admin-input" required>
                        <option value="2" {{ old('items_per_row', $marketingSection->items_per_row) == 2 ? 'selected' : '' }}>2 columns</option>
                        <option value="3" {{ old('items_per_row', $marketingSection->items_per_row) == 3 ? 'selected' : '' }}>3 columns</option>
                        <option value="4" {{ old('items_per_row', $marketingSection->items_per_row) == 4 ? 'selected' : '' }}>4 columns</option>
                    </select>
                </div>

                <div class="admin-form-group">
                    <label>Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $marketingSection->sort_order) }}" class="admin-input" min="0">
                </div>

                <div class="admin-form-group">
                    <label>Status</label>
                    <label style="display:flex;align-items:center;gap:8px;cursor:pointer;margin-top:6px">
                        <input type="checkbox" name="is_enabled" value="1" {{ old('is_enabled', $marketingSection->is_enabled) ? 'checked' : '' }} style="opacity:0;width:0;height:0">
                        <span style="position:relative;display:inline-block;width:44px;height:24px;flex-shrink:0">
                            <span style="position:absolute;cursor:pointer;inset:0;background:var(--border);border-radius:24px;transition:.3s"></span>
                            <span style="position:absolute;height:18px;width:18px;left:3px;bottom:3px;background:#fff;border-radius:50%;transition:.3s;pointer-events:none"></span>
                        </span>
                        <span style="font-size:13px;color:var(--text)">Enabled</span>
                    </label>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter: Category/Collection --}}
    <div id="filterGroup" class="admin-card" style="max-width:800px;margin-top:16px;display:none">
        <div class="admin-card__header">
            <span class="admin-card__title" id="filterTitle">Filter</span>
        </div>
        <div class="admin-card__body">
            <div class="admin-form-group" style="margin-bottom:0">
                <select name="filter_value" class="admin-input" id="filterSelect" onchange="loadProductsForFilter()">
                    <option value="">— Select —</option>
                </select>
            </div>
        </div>
    </div>

    {{-- Product Picker --}}
    <div id="productPicker" class="admin-card" style="max-width:800px;margin-top:16px;display:none">
        <div class="admin-card__header">
            <span class="admin-card__title">Select Products to Display</span>
            <label style="font-size:12px;color:var(--text-muted);cursor:pointer;display:flex;align-items:center;gap:6px">
                <input type="checkbox" id="selectAllProducts" onchange="toggleAllProducts(this.checked)" style="width:14px;height:14px">
                Select All
            </label>
        </div>
        <div class="admin-card__body" style="padding:0">
            <div id="productList" style="max-height:400px;overflow-y:auto">
                @foreach($products as $product)
                <label class="product-pick-item" data-category="{{ $product->category_id }}" data-collection="{{ $product->collections->pluck('id')->implode(',') }}"
                       style="display:flex;align-items:center;gap:12px;padding:12px 20px;cursor:pointer;border-bottom:1px solid var(--bg-input);transition:background .1s"
                       onmouseover="this.style.background='var(--bg-input)'" onmouseout="this.style.background='transparent'">
                    <input type="checkbox" name="product_ids[]" value="{{ $product->id }}" class="product-checkbox" {{ in_array($product->id, $selectedProductIds) ? 'checked' : '' }} style="width:16px;height:16px;flex-shrink:0">
                    @if($product->cover_image)
                        <img src="{{ Storage::url($product->cover_image) }}" style="width:40px;height:50px;object-fit:cover;border-radius:4px;flex-shrink:0">
                    @else
                        <div style="width:40px;height:50px;border-radius:4px;background:var(--bg-input);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--text-muted)" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/></svg>
                        </div>
                    @endif
                    <div style="flex:1;min-width:0">
                        <div style="font-size:13px;font-weight:500;color:var(--text)">{{ $product->name }}</div>
                        <div style="font-size:11px;color:var(--text-muted)">{{ $product->category->name ?? 'Uncategorized' }} — ₹{{ number_format($product->price) }}</div>
                    </div>
                    <span class="badge badge--outline" style="font-size:10px;flex-shrink:0">{{ $product->type }}</span>
                </label>
                @endforeach
            </div>
            <div id="selectedCount" style="padding:12px 20px;font-size:12px;color:var(--text-muted);border-top:1px solid var(--border)">
                {{ count($selectedProductIds) }} product(s) selected
            </div>
        </div>
    </div>

    <div style="margin-top:20px;display:flex;gap:12px;max-width:800px">
        <button type="submit" class="btn-admin btn-admin--gold">Update Section</button>
        <a href="{{ route('admin.marketing.index') }}" class="btn-admin btn-admin--outline">Cancel</a>
    </div>
</form>
@endsection

@push('scripts')
<script>
const categories = @json($categories);
const collections = @json($collections);
const currentType = '{{ $marketingSection->type }}';
const currentFilter = {{ $marketingSection->filter_value ?? 'null' }};

function updateForm() {
    const type = document.getElementById('sectionType').value;
    const filterGroup = document.getElementById('filterGroup');
    const filterTitle = document.getElementById('filterTitle');
    const filterSelect = document.getElementById('filterSelect');
    const productPicker = document.getElementById('productPicker');

    filterGroup.style.display = 'none';
    productPicker.style.display = 'none';

    if (['trending', 'new_arrivals', 'best_selling'].includes(type)) {
        productPicker.style.display = 'block';
        showAllProducts();
    }

    if (type === 'category') {
        filterGroup.style.display = 'block';
        filterTitle.textContent = 'Select Category';
        filterSelect.innerHTML = '<option value="">— Choose a category —</option>' +
            categories.map(c => `<option value="${c.id}" ${currentFilter == c.id && currentType === 'category' ? 'selected' : ''}>${c.name}</option>`).join('');
    } else if (type === 'collection') {
        filterGroup.style.display = 'block';
        filterTitle.textContent = 'Select Collection';
        filterSelect.innerHTML = '<option value="">— Choose a collection —</option>' +
            collections.map(c => `<option value="${c.id}" ${currentFilter == c.id && currentType === 'collection' ? 'selected' : ''}>${c.name}</option>`).join('');
    } else if (type === 'manual' || type === 'testimonials') {
        productPicker.style.display = 'block';
        showAllProducts();
    }

    // Re-filter if editing
    if (currentFilter && currentType === type) {
        setTimeout(() => loadProductsForFilter(), 100);
    }
}

function loadProductsForFilter() {
    const type = document.getElementById('sectionType').value;
    const filterVal = document.getElementById('filterSelect').value;
    const productPicker = document.getElementById('productPicker');
    const items = document.querySelectorAll('.product-pick-item');

    if (!filterVal) {
        productPicker.style.display = 'none';
        return;
    }

    productPicker.style.display = 'block';

    items.forEach(item => {
        if (type === 'category') {
            item.style.display = item.dataset.category === filterVal ? 'flex' : 'none';
        } else if (type === 'collection') {
            item.style.display = item.dataset.collection.split(',').includes(filterVal) ? 'flex' : 'none';
        }
    });

    document.querySelectorAll('.product-checkbox').forEach(cb => {
        if (cb.closest('.product-pick-item').style.display === 'none') {
            cb.checked = false;
        }
    });

    updateSelectedCount();
}

function showAllProducts() {
    document.querySelectorAll('.product-pick-item').forEach(item => {
        item.style.display = 'flex';
    });
}

function toggleAllProducts(checked) {
    document.querySelectorAll('.product-pick-item').forEach(item => {
        if (item.style.display !== 'none') {
            item.querySelector('.product-checkbox').checked = checked;
        }
    });
    updateSelectedCount();
}

function updateSelectedCount() {
    const count = document.querySelectorAll('.product-checkbox:checked').length;
    document.getElementById('selectedCount').textContent = count + ' product(s) selected';
}

document.querySelectorAll('.product-checkbox').forEach(cb => {
    cb.addEventListener('change', updateSelectedCount);
});

updateForm();
</script>
@endpush
