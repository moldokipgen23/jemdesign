@extends('layouts.admin')
@section('title', 'Edit Collection')

@section('content')
<div class="admin-page-header">
    <h1 class="admin-page-header__title">Edit: {{ $collection->name }}</h1>
    <a href="{{ route('admin.collections.index') }}" class="btn-admin btn-admin--outline">← Back</a>
</div>

<div class="admin-card" style="max-width:640px">
    <div class="admin-card__body">
        <form action="{{ route('admin.collections.update', $collection) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PATCH')
            <div class="admin-form-group">
                <label>Name</label>
                <input type="text" name="name" class="admin-input" value="{{ old('name', $collection->name) }}" required>
            </div>
            <div class="admin-form-group">
                <label>Description</label>
                <textarea name="description" class="admin-textarea">{{ old('description', $collection->description) }}</textarea>
            </div>
            <div class="admin-form-group">
                <label>Cover Image</label>
                @if($collection->cover_image)
                    <img src="{{ Storage::url($collection->cover_image) }}" style="display:block;width:120px;height:120px;object-fit:cover;border-radius:2px;margin-bottom:10px;border:1px solid var(--border)">
                @endif
                <input type="file" name="cover_image" class="admin-input" accept="image/*">
            </div>
            <div class="admin-form-grid">
                <div class="admin-form-group">
                    <label>Sort Order</label>
                    <input type="number" name="sort_order" class="admin-input" value="{{ old('sort_order', $collection->sort_order) }}" min="0">
                </div>
                <div class="admin-form-group" style="display:flex;align-items:flex-end;padding-bottom:4px">
                    <label class="admin-toggle">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $collection->is_active) ? 'checked' : '' }}>
                        <span class="admin-toggle__track"></span>
                        <span class="admin-toggle__label">Active</span>
                    </label>
                </div>
            </div>
            <button type="submit" class="btn-admin btn-admin--gold">Save Changes</button>
        </form>
    </div>
</div>
@endsection
