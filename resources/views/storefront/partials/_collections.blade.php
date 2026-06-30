<section class="section collections">
  <div class="container">
    <div class="section__header">
      <span class="section__eyebrow anim-reveal">Explore</span>
      <h2 class="section__title anim-reveal">Shop by Collection</h2>
      <div class="section__divider anim-reveal"></div>
    </div>
    <div class="collections__grid">
      @forelse($collections->take(2) as $collection)
      @php
        $coverUrl = $collection->cover_image
          ? asset('storage/' . $collection->cover_image)
          : asset('images/g.jpeg');
      @endphp
      <a href="{{ route('storefront.shop') }}?collection={{ $collection->slug }}" class="collections__panel anim-reveal">
        <div class="collections__panel-img">
          <img src="{{ $coverUrl }}" alt="{{ $collection->name }}" loading="lazy">
          <div class="collections__panel-overlay"></div>
        </div>
        <div class="collections__panel-content">
          <span class="collections__panel-count">{{ $collection->name }}</span>
          <h3>{{ $collection->description ?: $collection->name }}</h3>
          <span class="collections__panel-cta">
            Explore Collection
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
              <line x1="5" y1="12" x2="19" y2="12"/>
              <polyline points="12 5 19 12 12 19"/>
            </svg>
          </span>
        </div>
      </a>
      @empty
      {{-- Fallback panels using prototype images --}}
      <a href="{{ route('storefront.shop') }}?filter=women" class="collections__panel anim-reveal">
        <div class="collections__panel-img">
          <img src="{{ asset('images/g.jpeg') }}" alt="Women's Shawls & Stoles" loading="lazy">
          <div class="collections__panel-overlay"></div>
        </div>
        <div class="collections__panel-content">
          <span class="collections__panel-count">HerEDIT &amp; Blossoms</span>
          <h3>Women's Shawls<br>&amp; Stoles</h3>
          <span class="collections__panel-cta">
            Explore Collection
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
              <line x1="5" y1="12" x2="19" y2="12"/>
              <polyline points="12 5 19 12 12 19"/>
            </svg>
          </span>
        </div>
      </a>
      <a href="{{ route('storefront.shop') }}?filter=men" class="collections__panel anim-reveal">
        <div class="collections__panel-img">
          <img src="{{ asset('images/Screenshot_20260630-015645.png') }}" alt="Men's Heritage Shirts" loading="lazy">
          <div class="collections__panel-overlay"></div>
        </div>
        <div class="collections__panel-content">
          <span class="collections__panel-count">Signature Series</span>
          <h3>Men's<br>Heritage Shirts</h3>
          <span class="collections__panel-cta">
            Explore Collection
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
              <line x1="5" y1="12" x2="19" y2="12"/>
              <polyline points="12 5 19 12 12 19"/>
            </svg>
          </span>
        </div>
      </a>
      @endforelse
    </div>
  </div>
</section>
