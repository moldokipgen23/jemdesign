@extends('layouts.admin')
@section('title', 'Edit Category')

@section('content')
<div class="admin-page-header">
    <h1 class="admin-page-header__title">Edit: {{ $category->name }}</h1>
    <a href="{{ route('admin.categories.index') }}" class="btn-admin btn-admin--outline">← Back</a>
</div>

<div class="admin-card" style="max-width:560px">
    <div class="admin-card__body">
        <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PATCH')
            <div class="admin-form-group">
                <label>Name</label>
                <input type="text" name="name" class="admin-input" value="{{ old('name', $category->name) }}" required>
                @error('name')<p class="admin-error">{{ $message }}</p>@enderror
            </div>
            <div class="admin-form-group">
                <label>Cover Image</label>
                @if($category->image)
                    <img src="{{ Storage::url($category->image) }}" style="display:block;width:120px;height:90px;object-fit:cover;border-radius:6px;margin-bottom:8px;border:1px solid var(--border)">
                @endif
                <input type="file" name="image" class="admin-input" accept="image/*">
                <p style="font-size:11px;color:var(--text-muted);margin-top:4px">Leave empty to keep current image.</p>
                @error('image')<p class="admin-error">{{ $message }}</p>@enderror
            </div>
            <div class="admin-form-group">
                <label>Sort Order</label>
                <input type="number" name="sort_order" class="admin-input" value="{{ old('sort_order', $category->sort_order) }}" min="0" style="width:120px">
            </div>
            <div class="admin-form-group">
                <label class="admin-toggle">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                    <span class="admin-toggle__track"></span>
                    <span class="admin-toggle__label">Active</span>
                </label>
            </div>
            <button type="submit" class="btn-admin btn-admin--gold">Save Changes</button>
        </form>
    </div>
</div>
@endsection
