@extends('layouts.admin')
@section('title', 'Add Instagram Post')

@section('content')
<div class="admin-page-header">
    <h1 class="admin-page-header__title">Add Instagram Post</h1>
    <a href="{{ route('admin.instagram.index') }}" class="btn-admin btn-admin--outline">← Back</a>
</div>
<div class="admin-card" style="max-width:560px">
    <div class="admin-card__body">
        <form action="{{ route('admin.instagram.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="admin-form-group">
                <label>Image *</label>
                <input type="file" name="image" class="admin-input" accept="image/*" required>
                @error('image')<p class="admin-error">{{ $message }}</p>@enderror
            </div>
            <div class="admin-form-group">
                <label>Instagram Post Link</label>
                <input type="url" name="post_link" class="admin-input" value="{{ old('post_link') }}" placeholder="https://instagram.com/p/...">
            </div>
            <div class="admin-form-group">
                <label>Caption</label>
                <textarea name="caption" class="admin-textarea" style="min-height:80px" placeholder="Optional caption…">{{ old('caption') }}</textarea>
            </div>
            <div class="admin-form-grid">
                <div class="admin-form-group">
                    <label>Sort Order</label>
                    <input type="number" name="sort_order" class="admin-input" value="{{ old('sort_order', 0) }}" min="0">
                </div>
                <div class="admin-form-group" style="display:flex;align-items:flex-end;padding-bottom:4px">
                    <label class="admin-toggle">
                        <input type="checkbox" name="is_active" value="1" checked>
                        <span class="admin-toggle__track"></span>
                        <span class="admin-toggle__label">Visible</span>
                    </label>
                </div>
            </div>
            <button type="submit" class="btn-admin btn-admin--gold">Add Post</button>
        </form>
    </div>
</div>
@endsection
