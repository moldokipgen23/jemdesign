@extends('layouts.admin')
@section('title', 'New Category')

@section('content')
<div class="admin-page-header">
    <h1 class="admin-page-header__title">New Category</h1>
    <a href="{{ route('admin.categories.index') }}" class="btn-admin btn-admin--outline">← Back</a>
</div>

<div class="admin-card" style="max-width:560px">
    <div class="admin-card__body">
        <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="admin-form-group">
                <label>Name</label>
                <input type="text" name="name" class="admin-input" value="{{ old('name') }}" required placeholder="e.g. Women's">
                @error('name')<p class="admin-error">{{ $message }}</p>@enderror
            </div>
            <div class="admin-form-group">
                <label>Cover Image</label>
                <input type="file" name="image" class="admin-input" accept="image/*">
                <p style="font-size:11px;color:var(--text-muted);margin-top:4px">Thumbnail for category listing. Recommended: 800×600px.</p>
                @error('image')<p class="admin-error">{{ $message }}</p>@enderror
            </div>
            <div class="admin-form-group">
                <label>Sort Order</label>
                <input type="number" name="sort_order" class="admin-input" value="{{ old('sort_order', 0) }}" min="0" style="width:120px">
            </div>
            <div class="admin-form-group">
                <label class="admin-toggle">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }}>
                    <span class="admin-toggle__track"></span>
                    <span class="admin-toggle__label">Active</span>
                </label>
            </div>
            <button type="submit" class="btn-admin btn-admin--gold">Create Category</button>
        </form>
    </div>
</div>
@endsection
