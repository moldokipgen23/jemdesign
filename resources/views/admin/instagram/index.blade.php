@extends('layouts.admin')
@section('title', 'Instagram Feed')

@section('content')
<div class="admin-page-header">
    <div>
        <h1 class="admin-page-header__title">Instagram Feed</h1>
        <p class="admin-page-header__sub">Manually curated grid — no API required</p>
    </div>
    <a href="{{ route('admin.instagram.create') }}" class="btn-admin btn-admin--gold">+ Add Post</a>
</div>

<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:16px">
    @forelse($posts as $post)
    <div class="admin-card" style="overflow:hidden">
        <div style="aspect-ratio:1;background:var(--bg-input);position:relative">
            <img src="{{ Storage::url($post->image_path) }}" style="width:100%;height:100%;object-fit:cover">
            @if(!$post->is_active)
            <div style="position:absolute;inset:0;background:rgba(0,0,0,.5);display:flex;align-items:center;justify-content:center">
                <span class="badge badge--gray">Hidden</span>
            </div>
            @endif
        </div>
        <div style="padding:14px">
            @if($post->caption)
            <p style="font-size:12px;color:var(--text-muted);margin-bottom:10px;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical">{{ $post->caption }}</p>
            @endif
            <div style="display:flex;gap:8px">
                <a href="{{ route('admin.instagram.edit', $post) }}" class="btn-admin btn-admin--outline btn-admin--sm" style="flex:1;justify-content:center">Edit</a>
                <form action="{{ route('admin.instagram.destroy', $post) }}" method="POST" onsubmit="return confirm('Delete this post?')">
                    @csrf @method('DELETE')
                    <button class="btn-admin btn-admin--danger btn-admin--sm">Del</button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div style="grid-column:1/-1;text-align:center;padding:80px 0;color:var(--text-muted)">
        <p>No posts yet. <a href="{{ route('admin.instagram.create') }}" style="color:var(--gold)">Add the first one →</a></p>
    </div>
    @endforelse
</div>

@if($posts->hasPages())
<div style="margin-top:24px">{{ $posts->links() }}</div>
@endif
@endsection
