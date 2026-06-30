@extends('layouts.storefront')
@section('meta_title', 'Order Confirmed — Jem Designs & Co.')

@section('content')
<section class="section" style="padding-top:140px;min-height:80vh;display:flex;align-items:center">
  <div class="container" style="max-width:600px;text-align:center">

    <div style="margin-bottom:24px">
      <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#C9A04E" stroke-width="1.5">
        <path d="M22 11.08V12a10 10 0 11-5.93-9.14"/>
        <polyline points="22 4 12 14.01 9 11.01"/>
      </svg>
    </div>

    <span class="section__eyebrow anim-reveal">Thank You</span>
    <h1 style="font-family:var(--serif);font-size:36px;font-weight:300;color:var(--white-dim);margin:12px 0 8px" class="anim-reveal">
      Order Confirmed
    </h1>
    <div class="section__divider anim-reveal"></div>

    <p style="font-size:14px;color:var(--gray);margin:24px 0 32px;line-height:1.7" class="anim-reveal">
      Your order <strong style="color:var(--gold)">{{ $order->order_number }}</strong> has been placed successfully.
      We'll send a confirmation to <strong style="color:var(--white-dim)">{{ $order->customer_email }}</strong> shortly.
    </p>

    <div class="admin-card anim-reveal" style="background:var(--black-card);border:1px solid var(--border);text-align:left;margin-bottom:32px">
      <div style="padding:20px">
        <div style="display:flex;justify-content:space-between;margin-bottom:12px">
          <span style="font-size:12px;color:var(--gray);text-transform:uppercase;letter-spacing:0.1em">Order Number</span>
          <span style="font-size:13px;color:var(--white-dim);font-weight:500">{{ $order->order_number }}</span>
        </div>
        <div style="display:flex;justify-content:space-between;margin-bottom:12px">
          <span style="font-size:12px;color:var(--gray);text-transform:uppercase;letter-spacing:0.1em">Payment Status</span>
          <span style="display:inline-block;padding:3px 10px;border-radius:3px;font-size:11px;font-weight:600;letter-spacing:0.06em;text-transform:uppercase;background:rgba(34,197,94,0.12);color:#22c55e;border:1px solid rgba(34,197,94,0.25)">Paid</span>
        </div>
        <div style="display:flex;justify-content:space-between;margin-bottom:12px">
          <span style="font-size:12px;color:var(--gray);text-transform:uppercase;letter-spacing:0.1em">Total</span>
          <span style="font-size:15px;color:var(--gold);font-weight:600">₹{{ number_format($order->total) }}</span>
        </div>
        <div style="display:flex;justify-content:space-between;margin-bottom:12px">
          <span style="font-size:12px;color:var(--gray);text-transform:uppercase;letter-spacing:0.1em">Items</span>
          <span style="font-size:13px;color:var(--white-dim)">{{ $order->items->count() }} item(s)</span>
        </div>
      </div>

      <div style="border-top:1px solid var(--border);padding:16px 20px">
        <div style="font-size:10px;font-weight:600;letter-spacing:0.12em;text-transform:uppercase;color:var(--gray);margin-bottom:10px">Items</div>
        @foreach($order->items as $item)
        <div style="display:flex;justify-content:space-between;padding:6px 0;font-size:13px">
          <span style="color:var(--white-dim)">
            {{ $item->product_name }}
            <span style="color:var(--gray)">{{ $item->color_name ? '— ' . $item->color_name : '' }} {{ $item->size_label ? '/ ' . $item->size_label : '' }}</span>
            × {{ $item->quantity }}
          </span>
          <span style="color:var(--gold);font-weight:500">₹{{ number_format($item->total_price) }}</span>
        </div>
        @endforeach
      </div>

      <div style="border-top:1px solid var(--border);padding:16px 20px">
        <div style="font-size:10px;font-weight:600;letter-spacing:0.12em;text-transform:uppercase;color:var(--gray);margin-bottom:6px">Shipping To</div>
        <p style="font-size:13px;color:var(--white-dim);line-height:1.6">{{ $order->shipping_address }}</p>
      </div>
    </div>

    <div style="display:flex;gap:12px;justify-content:center" class="anim-reveal">
      <a href="{{ route('storefront.shop') }}" class="btn btn--outline">Continue Shopping</a>
      <a href="{{ route('storefront.home') }}" class="btn btn--gold">Back to Home</a>
    </div>

  </div>
</section>
@endsection
