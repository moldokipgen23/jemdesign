@extends('layouts.storefront')
@section('meta_title', 'The Founder — Jem Designs & Co.')
@section('content')
@php
  $founderName  = \App\Models\SiteSetting::get('founder_name',  'Lalringmawii Ralte');
  $founderTitle = \App\Models\SiteSetting::get('founder_title', 'Founder & Creative Director');
  $founderQuote = \App\Models\SiteSetting::get('founder_quote', 'I grew up watching my grandmother weave — her hands moving with a precision that no machine could replicate. I realized that if these patterns didn\'t find a place in modern fashion, they would slowly fade from memory. Jem is my way of making sure that never happens.');
  $founderPhoto = \App\Models\SiteSetting::get('founder_photo');
  $founderPhotoUrl = $founderPhoto ? asset('storage/' . $founderPhoto) : asset('images/Screenshot_20260630-015627.png');
@endphp
<div class="story-hero">
  <div class="container">
    <span class="section__eyebrow anim-reveal">Leadership</span>
    <h1 class="story-hero__title anim-reveal">The Founder</h1>
    <div class="section__divider anim-reveal"></div>
  </div>
</div>
<section class="section founder-content">
  <div class="container">
    <div class="founder-content__inner">
      <div class="founder-content__image anim-reveal">
        <img src="{{ $founderPhotoUrl }}" alt="{{ $founderName }} — Founder" loading="lazy">
      </div>
      <div class="founder-content__text">
        <h2 class="anim-reveal">{{ $founderName }}</h2>
        <span class="founder-content__title anim-reveal">{{ $founderTitle }}</span>
        <blockquote class="founder-content__quote anim-reveal">
          "{{ $founderQuote }}"
        </blockquote>
        <div class="founder-content__bio anim-reveal">
          <p>{{ $founderName }} hails from Churachandpur, Manipur, where the Kuki-Zo community has preserved its weaving traditions for centuries. After studying fashion design in Mumbai, she returned home with a clear mission: to bridge the gap between ancestral craft and contemporary style.</p>
          <p>Today, Jem Designs collaborates directly with master weavers across Manipur and Mizoram, ensuring fair wages while creating pieces that resonate with a global audience. Every garment carries a story — of hands that wove it, a culture that inspired it, and a founder who believed it deserved to be seen.</p>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
