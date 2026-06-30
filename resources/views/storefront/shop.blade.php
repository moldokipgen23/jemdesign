@extends('layouts.storefront')

@section('meta_title',
  $activeCollection ? $activeCollection->name . " — Jem Designs & Co." :
  ($filter === 'women' ? "Women's Collection — Jem Designs & Co." :
  ($filter === 'men'   ? "Men's Collection — Jem Designs & Co."   :
  'Shop All — Jem Designs & Co.')))

@section('meta_description',
  'Browse the full collection of handwoven Kuki-Zo heritage textiles — shawls, stoles, and shirts reimagined for the modern wardrobe.')

@section('content')

<div class="shop-header">
  <div class="container">
    <span class="section__eyebrow anim-reveal">The Collection</span>
    <h1 class="shop-header__title anim-reveal">
      @if($activeCollection) {{ $activeCollection->name }}
      @elseif($filter === 'women') Women's Collection
      @elseif($filter === 'men') Men's Collection
      @else Shop All
      @endif
    </h1>
    <div class="section__divider anim-reveal"></div>

    <div class="shop-filters anim-reveal">
      <a href="{{ route('storefront.shop') }}" class="shop-filter {{ !$filter && !$activeCollection ? 'active' : '' }}">All</a>
      <a href="{{ route('storefront.shop') }}?filter=women" class="shop-filter {{ $filter === 'women' ? 'active' : '' }}">Women's</a>
      <a href="{{ route('storefront.shop') }}?filter=men" class="shop-filter {{ $filter === 'men' ? 'active' : '' }}">Men's</a>
    </div>
  </div>
</div>

<section class="section" style="padding-top:40px">
  <div class="container">

    {{-- Product count --}}
    @if($products->isNotEmpty())
    <p class="anim-reveal" style="font-size:12px;color:var(--gray);letter-spacing:0.1em;text-align:center;margin-bottom:40px;text-transform:uppercase">
      {{ $products->count() }} {{ Str::plural('piece', $products->count()) }}
    </p>
    @endif

    @if($products->isNotEmpty())
    <div class="product-grid product-grid--4">
      @foreach($products as $product)
        @include('storefront.partials._product_card')
      @endforeach
    </div>

    @if($products->hasPages())
    <div style="display:flex;justify-content:center;gap:8px;margin-top:48px">
      @if($products->previousPageUrl())
        <a href="{{ $products->previousPageUrl() }}" class="btn btn--outline btn--sm">← Previous</a>
      @endif
      @foreach($products->getUrlRange(max(1, $products->currentPage() - 2), min($products->lastPage(), $products->currentPage() + 2)) as $page => $url)
        <a href="{{ $url }}" class="btn {{ $page == $products->currentPage() ? 'btn--gold' : 'btn--outline' }} btn--sm">{{ $page }}</a>
      @endforeach
      @if($products->nextPageUrl())
        <a href="{{ $products->nextPageUrl() }}" class="btn btn--outline btn--sm">Next →</a>
      @endif
    </div>
    @endif

    @else
    <div style="text-align:center;padding:80px 0 120px">
      <p style="font-family:var(--serif);font-size:28px;font-weight:300;color:var(--white-dim);margin-bottom:16px">
        Nothing here yet.
      </p>
      <p style="font-size:13px;color:var(--gray);margin-bottom:32px">
        @if($activeCollection)
          No products in this collection yet — browse the full collection.
        @elseif($filter)
          No {{ $filter === 'women' ? "women's" : "men's" }} products found — browse the full collection.
        @else
          Our collection is coming soon. Follow us on Instagram for updates.
        @endif
      </p>
      @if($activeCollection || $filter)
      <a href="{{ route('storefront.shop') }}" class="btn btn--outline">View All Products</a>
      @else
      <a href="{{ \App\Models\SiteSetting::get('instagram_url', 'https://instagram.com/jem.designsandco') }}"
         target="_blank" rel="noopener" class="btn btn--outline">Follow on Instagram</a>
      @endif
    </div>
    @endif

  </div>
</section>

@endsection
