@extends('layouts.admin')
@section('title', 'Order ' . $order->order_number)

@section('content')
<div class="admin-page-header">
    <div>
        <h1 class="admin-page-header__title">Order {{ $order->order_number }}</h1>
        <p class="admin-page-header__sub">Placed {{ $order->created_at->format('d M Y, H:i') }}</p>
    </div>
    <a href="{{ route('admin.orders.index') }}" class="btn-admin btn-admin--outline">← All Orders</a>
</div>

<div class="admin-grid-2">

    {{-- Order items --}}
    <div class="admin-card">
        <div class="admin-card__header">
            <span class="admin-card__title">Items</span>
            <span style="font-size:13px;font-weight:500;color:var(--gold)">₹{{ number_format($order->total) }}</span>
        </div>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr><th>Product</th><th>Variant</th><th>Qty</th><th>Price</th><th>Total</th></tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td style="color:var(--text);font-weight:500;font-size:13px">{{ $item->product_name }}</td>
                        <td style="font-size:12px;color:var(--text-muted)">
                            {{ $item->color_name ?? '—' }} {{ $item->size_label ? '/ ' . $item->size_label : '' }}
                        </td>
                        <td>{{ $item->quantity }}</td>
                        <td style="font-size:12px">₹{{ number_format($item->unit_price) }}</td>
                        <td style="font-weight:500">₹{{ number_format($item->total_price) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Customer + Payment + Status --}}
    <div style="display:flex;flex-direction:column;gap:20px">

        {{-- Customer --}}
        <div class="admin-card">
            <div class="admin-card__header"><span class="admin-card__title">Customer</span></div>
            <div class="admin-card__body">
                <div style="margin-bottom:12px">
                    <div style="font-size:10px;font-weight:600;letter-spacing:.15em;text-transform:uppercase;color:var(--text-muted);margin-bottom:4px">Name</div>
                    <div style="font-size:14px;color:var(--text)">{{ $order->customer_name }}</div>
                </div>
                <div style="margin-bottom:12px">
                    <div style="font-size:10px;font-weight:600;letter-spacing:.15em;text-transform:uppercase;color:var(--text-muted);margin-bottom:4px">Email</div>
                    <div style="font-size:14px;color:var(--text)">{{ $order->customer_email }}</div>
                </div>
                <div style="margin-bottom:12px">
                    <div style="font-size:10px;font-weight:600;letter-spacing:.15em;text-transform:uppercase;color:var(--text-muted);margin-bottom:4px">Phone</div>
                    <div style="font-size:14px;color:var(--text)">{{ $order->customer_phone ?: '—' }}</div>
                </div>
                <div>
                    <div style="font-size:10px;font-weight:600;letter-spacing:.15em;text-transform:uppercase;color:var(--text-muted);margin-bottom:4px">Shipping Address</div>
                    <div style="font-size:13px;color:var(--text);line-height:1.6">{{ $order->shipping_address }}</div>
                </div>
                @if($order->notes)
                <div style="margin-top:12px">
                    <div style="font-size:10px;font-weight:600;letter-spacing:.15em;text-transform:uppercase;color:var(--text-muted);margin-bottom:4px">Notes</div>
                    <div style="font-size:13px;color:var(--text)">{{ $order->notes }}</div>
                </div>
                @endif
            </div>
        </div>

        {{-- Payment --}}
        <div class="admin-card">
            <div class="admin-card__header"><span class="admin-card__title">Payment</span></div>
            <div class="admin-card__body">
                <div style="display:flex;justify-content:space-between;margin-bottom:8px">
                    <span style="font-size:12px;color:var(--text-muted)">Method</span>
                    <span style="font-size:13px;color:var(--text)">{{ ucfirst($order->payment_method) }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;margin-bottom:8px">
                    <span style="font-size:12px;color:var(--text-muted)">Status</span>
                    <span class="badge {{ $order->payment_badge }}">{{ ucfirst($order->payment_status) }}</span>
                </div>
                @if($order->payment_id)
                <div style="display:flex;justify-content:space-between;margin-bottom:8px">
                    <span style="font-size:12px;color:var(--text-muted)">Payment ID</span>
                    <span style="font-size:11px;color:var(--text);font-family:monospace">{{ $order->payment_id }}</span>
                </div>
                @endif
                @if($order->payment_order_id)
                <div style="display:flex;justify-content:space-between">
                    <span style="font-size:12px;color:var(--text-muted)">Razorpay Order</span>
                    <span style="font-size:11px;color:var(--text);font-family:monospace">{{ $order->payment_order_id }}</span>
                </div>
                @endif
            </div>
        </div>

        {{-- Status update --}}
        <div class="admin-card">
            <div class="admin-card__header"><span class="admin-card__title">Update Status</span></div>
            <div class="admin-card__body">
                <p style="font-size:12px;color:var(--text-muted);margin-bottom:12px">
                    Current: <strong style="color:var(--text)">{{ ucfirst($order->status) }}</strong>
                </p>
                <div style="display:flex;flex-direction:column;gap:8px">
                    @foreach(['pending' => 'Pending', 'processing' => 'Processing', 'shipped' => 'Shipped', 'delivered' => 'Delivered', 'cancelled' => 'Cancelled'] as $status => $label)
                    <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="{{ $status }}">
                        <button type="submit"
                            class="btn-admin {{ $order->status === $status ? 'btn-admin--gold' : 'btn-admin--outline' }}"
                            style="width:100%;justify-content:center">
                            {{ $label }}
                        </button>
                    </form>
                    @endforeach
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
