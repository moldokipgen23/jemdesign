@extends('layouts.admin')
@section('title', 'Add Testimonial')

@section('content')
<div class="admin-page-header">
    <h1 class="admin-page-header__title">Add Testimonial</h1>
</div>

<form action="{{ route('admin.testimonials.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="admin-card" style="max-width:720px">
        <div class="admin-card__header">
            <span class="admin-card__title">Customer Details</span>
        </div>
        <div class="admin-card__body">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                <div class="admin-form-group">
                    <label>Customer Name *</label>
                    <input type="text" name="customer_name" value="{{ old('customer_name') }}" class="admin-input" required placeholder="e.g. Priya M.">
                </div>
                <div class="admin-form-group">
                    <label>Title / Location</label>
                    <input type="text" name="customer_title" value="{{ old('customer_title') }}" class="admin-input" placeholder="e.g. Mumbai, India">
                </div>
            </div>

            <div class="admin-form-group">
                <label>Review *</label>
                <textarea name="content" class="admin-input" rows="4" required placeholder="What did the customer say?">{{ old('content') }}</textarea>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                <div class="admin-form-group">
                    <label>Photo</label>
                    <input type="file" name="image" accept="image/*" class="admin-input">
                    <p style="font-size:11px;color:var(--text-muted);margin-top:4px">Optional. Square recommended.</p>
                </div>
                <div class="admin-form-group">
                    <label>Rating *</label>
                    <select name="rating" class="admin-input" required>
                        <option value="5" {{ old('rating', 5) == 5 ? 'selected' : '' }}>★★★★★ (5)</option>
                        <option value="4" {{ old('rating') == 4 ? 'selected' : '' }}>★★★★☆ (4)</option>
                        <option value="3" {{ old('rating') == 3 ? 'selected' : '' }}>★★★☆☆ (3)</option>
                        <option value="2" {{ old('rating') == 2 ? 'selected' : '' }}>★★☆☆☆ (2)</option>
                        <option value="1" {{ old('rating') == 1 ? 'selected' : '' }}>★☆☆☆☆ (1)</option>
                    </select>
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                <div class="admin-form-group">
                    <label>Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" class="admin-input" min="0">
                </div>
                <div class="admin-form-group">
                    <label>Status</label>
                    <label style="display:flex;align-items:center;gap:8px;cursor:pointer;margin-top:6px">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }} style="opacity:0;width:0;height:0">
                        <span style="position:relative;display:inline-block;width:44px;height:24px;flex-shrink:0">
                            <span style="position:absolute;cursor:pointer;inset:0;background:var(--border);border-radius:24px;transition:.3s"></span>
                            <span style="position:absolute;height:18px;width:18px;left:3px;bottom:3px;background:#fff;border-radius:50%;transition:.3s;pointer-events:none"></span>
                        </span>
                        <span style="font-size:13px;color:var(--text)">Active</span>
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div style="margin-top:20px;display:flex;gap:12px">
        <button type="submit" class="btn-admin btn-admin--gold">Add Testimonial</button>
        <a href="{{ route('admin.testimonials.index') }}" class="btn-admin btn-admin--outline">Cancel</a>
    </div>
</form>
@endsection
