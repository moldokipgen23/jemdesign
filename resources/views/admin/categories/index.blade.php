@extends('layouts.admin')
@section('title', 'Categories')

@section('content')
<div class="admin-page-header">
    <div>
        <h1 class="admin-page-header__title">Categories</h1>
        <p class="admin-page-header__sub">Women's, Men's — the top-level taxonomy</p>
    </div>
    <a href="{{ route('admin.categories.create') }}" class="btn-admin btn-admin--gold">+ New Category</a>
</div>

<div class="admin-card">
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Order</th>
                    <th>Status</th>
                    <th>Products</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $cat)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px">
                            @if($cat->image)
                                <img src="{{ Storage::url($cat->image) }}" style="width:40px;height:40px;object-fit:cover;border-radius:6px;border:1px solid var(--border);flex-shrink:0">
                            @else
                                <div style="width:40px;height:40px;border-radius:6px;background:var(--bg-input);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;flex-shrink:0;color:var(--text-muted);font-size:14px">—</div>
                            @endif
                            <span style="font-weight:500;color:var(--text)">{{ $cat->name }}</span>
                        </div>
                    </td>
                    <td><code style="font-size:11px;color:var(--text-muted)">{{ $cat->slug }}</code></td>
                    <td>{{ $cat->sort_order }}</td>
                    <td>
                        @if($cat->is_active)
                            <span class="badge badge--green">Active</span>
                        @else
                            <span class="badge badge--gray">Inactive</span>
                        @endif
                    </td>
                    <td>{{ $cat->products()->count() }}</td>
                    <td style="text-align:right;white-space:nowrap;">
                        <a href="{{ route('admin.categories.edit', $cat) }}" class="btn-admin btn-admin--outline btn-admin--sm">Edit</a>
                        <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST" style="display:inline" onsubmit="return confirm('Delete {{ $cat->name }}?')">
                            @csrf @method('DELETE')
                            <button class="btn-admin btn-admin--danger btn-admin--sm">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align:center;color:var(--text-muted);padding:40px">No categories yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
