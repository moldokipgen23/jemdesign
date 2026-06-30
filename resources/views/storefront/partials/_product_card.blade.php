{{-- Reusable product card. Expects $product (Product model with colors.images loaded). --}}
@php
  $mainImg = $product->main_image;
@endphp

<a href="{{ route('storefront.product', $product->slug) }}" class="product-card">
  @if($mainImg)
  <img class="product-card__img"
       src="{{ asset('storage/' . $mainImg) }}"
       alt="{{ $product->name }}"
       loading="lazy">
  @else
  <div class="product-card__img" style="display:flex;align-items:center;justify-content:center;background:var(--black-light)">
    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="var(--gray-light)" stroke-width="1"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
  </div>
  @endif
  <div class="product-card__img-mask"></div>
  <div class="product-card__overlay"></div>
  <div class="product-card__info">
    <span class="product-card__collection">{{ $product->category?->name }}</span>
    <h3 class="product-card__name">{{ $product->name }}</h3>
    <span class="product-card__price">₹{{ number_format($product->price, 0, '.', ',') }}</span>
  </div>
  <div class="product-card__swatches">
    @foreach($product->colors->take(5) as $color)
    <div class="product-card__swatch"
         style="background:{{ $color->hex_code }}"
         title="{{ $color->color_name }}"></div>
    @endforeach
  </div>
</a>
