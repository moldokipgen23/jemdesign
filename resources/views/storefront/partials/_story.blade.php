@php
  $storyBody  = \App\Models\SiteSetting::get('brand_story', 'Every motif in our collection carries the weight of generations. We take the geometric precision of traditional Kuki-Zo weave patterns — small diamonds, interlocking tiles, and rhythmic borders — and translate them onto fabrics and silhouettes that belong in the modern wardrobe.');
  $storyImage = \App\Models\SiteSetting::get('story_image');
  $storyImageUrl = $storyImage
    ? asset('storage/' . $storyImage)
    : asset('images/WhatsApp Image 2026-06-30 at 01.58.32.jpeg');
@endphp

<section class="story-strip">
  <div class="story-strip__inner">
    <div class="story-strip__text anim-reveal">
      <span class="section__eyebrow">Our Craft</span>
      <h2 class="story-strip__title">Woven With<br><em>Intention</em></h2>
      <p>{{ $storyBody }}</p>
      <a href="{{ route('storefront.story') }}" class="btn btn--outline">Read Our Story</a>
    </div>
    <div class="story-strip__image anim-reveal">
      <img src="{{ $storyImageUrl }}" alt="Heritage meets modern" loading="lazy">
    </div>
  </div>
</section>
