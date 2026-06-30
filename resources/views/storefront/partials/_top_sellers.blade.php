<section class="section top-sellers" id="topSellers">
  <div class="container">
    <div class="section__header">
      <span class="section__eyebrow anim-reveal">Most Loved</span>
      <h2 class="section__title anim-reveal">Top Sellers</h2>
      <div class="section__divider anim-reveal"></div>
    </div>
    @if($topSellers->isNotEmpty())
    <div class="product-grid product-grid--4">
      @foreach($topSellers as $product)
        @include('storefront.partials._product_card')
      @endforeach
    </div>
    @else
    <p style="text-align:center;color:var(--gray);font-size:14px;padding:60px 0">
      Our top sellers are coming soon. <a href="{{ route('storefront.shop') }}" style="color:var(--gold)">Browse all products →</a>
    </p>
    @endif
  </div>
</section>
