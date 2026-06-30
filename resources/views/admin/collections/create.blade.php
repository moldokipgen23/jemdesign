@extends('layouts.admin')
@section('title', 'New Collection')

@section('content')
<div class="admin-page-header">
    <h1 class="admin-page-header__title">New Collection</h1>
    <a href="{{ route('admin.collections.index') }}" class="btn-admin btn-admin--outline">← Back</a>
</div>

<div class="admin-card" style="max-width:640px">
    <div class="admin-card__body">
        <form action="{{ route('admin.collections.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="admin-form-group">
                <label>Name</label>
                <input type="text" name="name" class="admin-input" value="{{ old('name') }}" required placeholder="e.g. Signature Series">
                @error('name')<p class="admin-error">{{ $message }}</p>@enderror
            </div>
            <div class="admin-form-group">
                <label>Description</label>
                <textarea name="description" class="admin-textarea" placeholder="Short description of this collection…">{{ old('description') }}</textarea>
            </div>
            <div class="admin-form-group">
                <label>Cover Image</label>
                <input type="file" name="cover_image" class="admin-input" accept="image/*">
                @error('cover_image')<p class="admin-error">{{ $message }}</p>@enderror
            </div>
            <div class="admin-form-grid">
                <div class="admin-form-group">
                    <label>Sort Order</label>
                    <input type="number" name="sort_order" class="admin-input" value="{{ old('sort_order', 0) }}" min="0">
                </div>
                <div class="admin-form-group" style="display:flex;align-items:flex-end;padding-bottom:4px">
                    <label class="admin-toggle">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }}>
                        <span class="admin-toggle__track"></span>
                        <span class="admin-toggle__label">Active</span>
                    </label>
                </div>
            </div>
            <button type="submit" class="btn-admin btn-admin--gold">Create Collection</button>
        </form>
    </div>
</div>
@endsection
