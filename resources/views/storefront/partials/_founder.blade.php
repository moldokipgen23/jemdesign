@php
  $founderName  = \App\Models\SiteSetting::get('founder_name',  'Lalringmawii Ralte');
  $founderTitle = \App\Models\SiteSetting::get('founder_title', 'Founder & Creative Director');
  $founderQuote = \App\Models\SiteSetting::get('founder_quote', 'Every thread we weave carries the weight of generations. I wanted to create something that the women of my community could wear with pride — not as a costume of the past, but as a statement of who we are today.');
  $founderPhoto = \App\Models\SiteSetting::get('founder_photo');
  $founderPhotoUrl = $founderPhoto
    ? asset('storage/' . $founderPhoto)
    : asset('images/Screenshot_20260630-015627.png');
  $detailPhotoUrl = asset('images/Screenshot_20260630-015615.png');
  $accentPhotoUrl = asset('images/Screenshot_20260630-015604.png');
@endphp

<section class="about-brand">
  <div class="about-brand__inner">
    <div class="about-brand__images">
      <div class="about-brand__img-main">
        <img src="{{ $founderPhotoUrl }}" alt="{{ $founderName }} — Founder, Jem Designs & Co." loading="lazy">
      </div>
      <div class="about-brand__img-detail">
        <img src="{{ $detailPhotoUrl }}" alt="Heritage textile craft" loading="lazy">
      </div>
      <div class="about-brand__img-accent">
        <img src="{{ $accentPhotoUrl }}" alt="Traditional weave detail" loading="lazy">
      </div>
      <div class="about-brand__gold-block"></div>
    </div>
    <div class="about-brand__content">
      <div class="about-brand__text">
        <span class="section__eyebrow">Meet the Maker</span>
        <h2 class="about-brand__title">The Woman Behind<br>the <em>Weave</em></h2>
        <div class="about-brand__divider"></div>
        <blockquote class="about-brand__quote">
          "{{ $founderQuote }}"
        </blockquote>
        <p class="about-brand__bio">
          {{ $founderName }} founded Jem Designs &amp; Co. with a singular vision: to bring the extraordinary textile heritage of the Kuki-Zo people into the contemporary fashion conversation.
        </p>
        <p class="about-brand__bio">
          What began as a personal project — reimagining her grandmother's weave patterns on modern silhouettes — has grown into a brand that bridges two worlds: the ancient and the now, the ceremonial and the everyday, the local and the global.
        </p>
        <div class="about-brand__stats">
          <div class="about-brand__stat">
            <span class="about-brand__stat-number">100%</span>
            <span class="about-brand__stat-label">Handwoven Textiles</span>
          </div>
          <div class="about-brand__stat">
            <span class="about-brand__stat-number">50+</span>
            <span class="about-brand__stat-label">Heritage Motifs</span>
          </div>
          <div class="about-brand__stat">
            <span class="about-brand__stat-number">1</span>
            <span class="about-brand__stat-label">Northeast India</span>
          </div>
        </div>
        <a href="{{ route('storefront.story') }}" class="btn btn--gold">Read the Full Story</a>
      </div>
    </div>
  </div>
</section>
