@extends('layouts.admin')
@section('title', 'Paid Orders')

@section('content')
<div class="admin-page-header">
    <div>
        <h1 class="admin-page-header__title">Paid Orders</h1>
        <p class="admin-page-header__sub">{{ $stats['total'] }} total · {{ $stats['paid'] }} paid · {{ $stats['pending'] }} pending fulfillment</p>
    </div>
</div>

{{-- Stats --}}
<div class="admin-stats" style="margin-bottom:24px">
    <div class="admin-stat-card">
        <div class="admin-stat-card__label">Total Orders</div>
        <div class="admin-stat-card__value">{{ $stats['total'] }}</div>
    </div>
    <div class="admin-stat-card">
        <div class="admin-stat-card__label">Paid</div>
        <div class="admin-stat-card__value">{{ $stats['paid'] }}</div>
    </div>
    <div class="admin-stat-card">
        <div class="admin-stat-card__label">Pending</div>
        <div class="admin-stat-card__value">{{ $stats['pending'] }}</div>
    </div>
    <div class="admin-stat-card">
        <div class="admin-stat-card__label">Shipped</div>
        <div class="admin-stat-card__value">{{ $stats['shipped'] }}</div>
    </div>
</div>

{{-- Filters --}}
<form method="GET" style="display:flex;gap:10px;margin-bottom:20px;flex-wrap:wrap;align-items:center">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search order #, name, email..."
           style="flex:1;min-width:200px;padding:8px 14px;background:var(--bg-input);border:1px solid var(--border);border-radius:4px;color:var(--text);font-size:13px">
    <select name="status" style="padding:8px 14px;background:var(--bg-input);border:1px solid var(--border);border-radius:4px;color:var(--text);font-size:13px">
        <option value="all">All Status</option>
        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
        <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Processing</option>
        <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>Shipped</option>
        <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Delivered</option>
        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
    </select>
    <button type="submit" class="btn-admin btn-admin--outline btn-admin--sm">Filter</button>
</form>

{{-- Orders table --}}
@if($orders->count())
<div class="admin-card">
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Order</th>
                    <th>Customer</th>
                    <th>Items</th>
                    <th>Total</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr>
                    <td>
                        <span style="font-weight:500;color:var(--text);font-size:13px">{{ $order->order_number }}</span>
                    </td>
                    <td>
                        <div style="font-size:13px;color:var(--text)">{{ $order->customer_name }}</div>
                        <div style="font-size:11px;color:var(--text-muted)">{{ $order->customer_email }}</div>
                    </td>
                    <td style="font-size:12px;color:var(--text-muted)">{{ $order->items->count() }} item(s)</td>
                    <td style="font-weight:500;color:var(--gold)">₹{{ number_format($order->total) }}</td>
                    <td><span class="badge {{ $order->payment_badge }}">{{ ucfirst($order->payment_status) }}</span></td>
                    <td><span class="badge {{ $order->status_badge }}">{{ ucfirst($order->status) }}</span></td>
                    <td style="font-size:12px;color:var(--text-muted)">{{ $order->created_at->format('d M, H:i') }}</td>
                    <td>
                        <a href="{{ route('admin.orders.show', $order) }}" class="btn-admin btn-admin--outline btn-admin--sm">View</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{ $orders->links() }}
@else
<div class="admin-card">
    <div class="admin-card__body" style="text-align:center;padding:60px 0">
        <p style="color:var(--text-muted);font-size:13px">No orders found.</p>
    </div>
</div>
@endif
@endsection
