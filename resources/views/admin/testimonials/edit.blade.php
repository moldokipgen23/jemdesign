@extends('layouts.admin')
@section('title', 'Edit Testimonial')

@section('content')
<div class="admin-page-header">
    <h1 class="admin-page-header__title">Edit Testimonial</h1>
</div>

<form action="{{ route('admin.testimonials.update', $testimonial) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PATCH')

    <div class="admin-card" style="max-width:720px">
        <div class="admin-card__header">
            <span class="admin-card__title">Customer Details</span>
        </div>
        <div class="admin-card__body">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                <div class="admin-form-group">
                    <label>Customer Name *</label>
                    <input type="text" name="customer_name" value="{{ old('customer_name', $testimonial->customer_name) }}" class="admin-input" required>
                </div>
                <div class="admin-form-group">
                    <label>Title / Location</label>
                    <input type="text" name="customer_title" value="{{ old('customer_title', $testimonial->customer_title) }}" class="admin-input">
                </div>
            </div>

            <div class="admin-form-group">
                <label>Review *</label>
                <textarea name="content" class="admin-input" rows="4" required>{{ old('content', $testimonial->content) }}</textarea>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                <div class="admin-form-group">
                    <label>Photo</label>
                    @if($testimonial->image)
                        <div style="margin-bottom:8px">
                            <img src="{{ Storage::url($testimonial->image) }}" style="width:60px;height:60px;border-radius:50%;object-fit:cover">
                        </div>
                    @endif
                    <input type="file" name="image" accept="image/*" class="admin-input">
                    <p style="font-size:11px;color:var(--text-muted);margin-top:4px">Leave empty to keep current image.</p>
                </div>
                <div class="admin-form-group">
                    <label>Rating *</label>
                    <select name="rating" class="admin-input" required>
                        @for($i = 5; $i >= 1; $i--)
                            <option value="{{ $i }}" {{ old('rating', $testimonial->rating) == $i ? 'selected' : '' }}>{{ str_repeat('★', $i) }}{{ str_repeat('☆', 5 - $i) }} ({{ $i }})</option>
                        @endfor
                    </select>
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                <div class="admin-form-group">
                    <label>Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $testimonial->sort_order) }}" class="admin-input" min="0">
                </div>
                <div class="admin-form-group">
                    <label>Status</label>
                    <label style="display:flex;align-items:center;gap:8px;cursor:pointer;margin-top:6px">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $testimonial->is_active) ? 'checked' : '' }} style="opacity:0;width:0;height:0">
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
        <button type="submit" class="btn-admin btn-admin--gold">Update Testimonial</button>
        <a href="{{ route('admin.testimonials.index') }}" class="btn-admin btn-admin--outline">Cancel</a>
    </div>
</form>
@endsection
