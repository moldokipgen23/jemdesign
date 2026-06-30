@extends('layouts.admin')
@section('title', 'Products')

@section('content')
<div class="admin-page-header">
    <div>
        <h1 class="admin-page-header__title">Products</h1>
        <p class="admin-page-header__sub">{{ $products->total() }} total</p>
    </div>
    <a href="{{ route('admin.products.create') }}" class="btn-admin btn-admin--gold">+ New Product</a>
</div>

{{-- Filters --}}
<form method="GET" style="display:flex;gap:10px;margin-bottom:24px;flex-wrap:wrap;align-items:center">
    <select name="category" class="admin-select" style="width:160px" onchange="this.form.submit()">
        <option value="">All Categories</option>
        @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
        @endforeach
    </select>
    <select name="collection" class="admin-select" style="width:180px" onchange="this.form.submit()">
        <option value="">All Collections</option>
        @foreach($collections as $col)
            <option value="{{ $col->id }}" {{ request('collection') == $col->id ? 'selected' : '' }}>{{ $col->name }}</option>
        @endforeach
    </select>
    <label style="display:flex;align-items:center;gap:6px;font-size:12px;color:var(--text-dim);cursor:pointer">
        <input type="checkbox" name="top_seller" value="1" {{ request()->has('top_seller') ? 'checked' : '' }} onchange="this.form.submit()">
        Top Sellers only
    </label>
    <label style="display:flex;align-items:center;gap:6px;font-size:12px;color:var(--text-dim);cursor:pointer">
        <input type="checkbox" name="featured" value="1" {{ request()->has('featured') ? 'checked' : '' }} onchange="this.form.submit()">
        Featured only
    </label>
    @if(request()->hasAny(['category','collection','top_seller','featured']))
        <a href="{{ route('admin.products.index') }}" style="font-size:11px;color:var(--text-muted);text-decoration:underline">Clear filters</a>
    @endif
</form>

<div class="admin-card">
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Collections</th>
                    <th>Price</th>
                    <th>Flags</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr>
                    <td>
                        @php $img = $product->main_image @endphp
                        @if($img)
                            <img src="{{ Storage::url($img) }}" class="admin-thumb">
                        @else
                            <div class="admin-thumb" style="border:1px solid var(--border);display:flex;align-items:center;justify-content:center">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--text-muted)" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                            </div>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.products.show', $product) }}" style="font-weight:500;color:var(--text);text-decoration:none">{{ $product->name }}</a>
                        <div style="font-size:11px;color:var(--text-muted);margin-top:2px">{{ $product->colors()->count() }} color(s)</div>
                    </td>
                    <td style="color:var(--text-dim);font-size:13px">{{ $product->category->name }}</td>
                    <td>
                        @foreach($product->collections as $col)
                            <span class="badge badge--gold" style="margin-right:4px;margin-bottom:4px">{{ $col->name }}</span>
                        @endforeach
                    </td>
                    <td style="font-weight:500;color:var(--text)">₹{{ number_format($product->price) }}</td>
                    <td style="white-space:nowrap">
                        @if($product->is_top_seller) <span class="badge badge--gold">Top Seller</span> @endif
                        @if($product->is_featured)   <span class="badge badge--green" style="margin-top:4px">Featured</span> @endif
                    </td>
                    <td>
                        @if($product->is_active)
                            <span class="badge badge--green">Active</span>
                        @else
                            <span class="badge badge--gray">Inactive</span>
                        @endif
                    </td>
                    <td style="text-align:right;white-space:nowrap">
                        <a href="{{ route('admin.products.show', $product) }}" class="btn-admin btn-admin--outline btn-admin--sm">Manage</a>
                        <a href="{{ route('admin.products.edit', $product) }}" class="btn-admin btn-admin--outline btn-admin--sm">Edit</a>
                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" style="display:inline" onsubmit="return confirm('Delete {{ addslashes($product->name) }}?')">
                            @csrf @method('DELETE')
                            <button class="btn-admin btn-admin--danger btn-admin--sm">Del</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" style="text-align:center;color:var(--text-muted);padding:60px">No products yet. <a href="{{ route('admin.products.create') }}" style="color:var(--gold)">Add one →</a></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($products->hasPages())
    <div style="padding:16px 24px;border-top:1px solid var(--border)">
        {{ $products->links() }}
    </div>
    @endif
</div>
@endsection
