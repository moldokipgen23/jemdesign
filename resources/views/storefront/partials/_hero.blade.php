@php
  $heroImage = \App\Models\SiteSetting::get('hero_image');
  $heroImageUrl = $heroImage
    ? asset('storage/' . $heroImage)
    : asset('images/Screenshot_20260630-015444.png');
@endphp

<section class="hero">
  <div class="hero__bg">
    <img src="{{ $heroImageUrl }}"
         alt="Jem Designs & Co. — Heritage editorial"
         class="hero__img"
         loading="eager">
    <div class="hero__overlay"></div>
  </div>
  <div class="hero__content">
    <span class="hero__eyebrow anim-fade-up">Jem Designs &amp; Co.</span>
    <h1 class="hero__title anim-fade-up" style="animation-delay:0.15s">
      Where Heritage<br>Meets the<br><em>Modern Silhouette</em>
    </h1>
    <p class="hero__sub anim-fade-up" style="animation-delay:0.3s">
      Traditional Kuki-Zo weave motifs reimagined<br>for contemporary wardrobes.
    </p>
    <a href="{{ route('storefront.shop') }}"
       class="btn btn--gold anim-fade-up"
       style="animation-delay:0.45s">
      Discover the Collection
    </a>
  </div>
  <div class="hero__scroll-indicator anim-fade-up" style="animation-delay:0.7s">
    <span>Scroll</span>
    <div class="hero__scroll-line"></div>
  </div>
</section>
