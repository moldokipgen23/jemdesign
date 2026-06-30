@extends('layouts.admin')
@section('title', 'Edit Instagram Post')

@section('content')
<div class="admin-page-header">
    <h1 class="admin-page-header__title">Edit Post</h1>
    <a href="{{ route('admin.instagram.index') }}" class="btn-admin btn-admin--outline">← Back</a>
</div>
<div class="admin-card" style="max-width:560px">
    <div class="admin-card__body">
        <img src="{{ Storage::url($post->image_path) }}" style="width:100%;max-height:300px;object-fit:cover;border-radius:2px;margin-bottom:20px">
        <form action="{{ route('admin.instagram.update', $post) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PATCH')
            <div class="admin-form-group">
                <label>Replace Image</label>
                <input type="file" name="image" class="admin-input" accept="image/*">
            </div>
            <div class="admin-form-group">
                <label>Instagram Post Link</label>
                <input type="url" name="post_link" class="admin-input" value="{{ old('post_link', $post->post_link) }}">
            </div>
            <div class="admin-form-group">
                <label>Caption</label>
                <textarea name="caption" class="admin-textarea" style="min-height:80px">{{ old('caption', $post->caption) }}</textarea>
            </div>
            <div class="admin-form-grid">
                <div class="admin-form-group">
                    <label>Sort Order</label>
                    <input type="number" name="sort_order" class="admin-input" value="{{ old('sort_order', $post->sort_order) }}" min="0">
                </div>
                <div class="admin-form-group" style="display:flex;align-items:flex-end;padding-bottom:4px">
                    <label class="admin-toggle">
                        <input type="checkbox" name="is_active" value="1" {{ $post->is_active ? 'checked' : '' }}>
                        <span class="admin-toggle__track"></span>
                        <span class="admin-toggle__label">Visible</span>
                    </label>
                </div>
            </div>
            <button type="submit" class="btn-admin btn-admin--gold">Save Changes</button>
        </form>
    </div>
</div>
@endsection
