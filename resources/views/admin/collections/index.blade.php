@extends('layouts.admin')
@section('title', 'Collections')

@section('content')
<div class="admin-page-header">
    <div>
        <h1 class="admin-page-header__title">Collections</h1>
        <p class="admin-page-header__sub">Signature Series, HerEDIT, Blossoms, New Arrivals…</p>
    </div>
    <a href="{{ route('admin.collections.create') }}" class="btn-admin btn-admin--gold">+ New Collection</a>
</div>

<div class="admin-card">
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Cover</th>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Products</th>
                    <th>Order</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($collections as $col)
                <tr>
                    <td>
                        @if($col->cover_image)
                            <img src="{{ Storage::url($col->cover_image) }}" class="admin-thumb" style="width:48px;height:48px;object-fit:cover;border-radius:2px">
                        @else
                            <div style="width:48px;height:48px;background:var(--bg-input);border-radius:2px;border:1px solid var(--border)"></div>
                        @endif
                    </td>
                    <td style="font-weight:500;color:var(--text)">{{ $col->name }}</td>
                    <td><code style="font-size:11px;color:var(--text-muted)">{{ $col->slug }}</code></td>
                    <td>{{ $col->products()->count() }}</td>
                    <td>{{ $col->sort_order }}</td>
                    <td>
                        @if($col->is_active)
                            <span class="badge badge--green">Active</span>
                        @else
                            <span class="badge badge--gray">Inactive</span>
                        @endif
                    </td>
                    <td style="text-align:right;white-space:nowrap;">
                        <a href="{{ route('admin.collections.edit', $col) }}" class="btn-admin btn-admin--outline btn-admin--sm">Edit</a>
                        <form action="{{ route('admin.collections.destroy', $col) }}" method="POST" style="display:inline" onsubmit="return confirm('Delete this collection?')">
                            @csrf @method('DELETE')
                            <button class="btn-admin btn-admin--danger btn-admin--sm">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align:center;color:var(--text-muted);padding:40px">No collections yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
