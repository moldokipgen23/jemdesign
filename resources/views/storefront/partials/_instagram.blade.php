@if($instagramPosts->isNotEmpty())
@php
  $instagramUrl = \App\Models\SiteSetting::get('instagram_url', 'https://www.instagram.com/jem.designsandco');
@endphp

<section class="section" style="padding-bottom: 80px">
  <div class="container">
    <div class="section__header">
      <span class="section__eyebrow anim-reveal">Follow Along</span>
      <h2 class="section__title anim-reveal">On Instagram</h2>
      <div class="section__divider anim-reveal"></div>
    </div>
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:4px;border-radius:2px;overflow:hidden">
      @foreach($instagramPosts as $post)
      @php
        $postImgUrl = $post->image_path ? asset('storage/' . $post->image_path) : '';
      @endphp
      @if($postImgUrl)
      <a href="{{ $post->post_link ?: $instagramUrl }}"
         target="_blank"
         rel="noopener"
         style="display:block;aspect-ratio:1;overflow:hidden;position:relative;background:var(--black-card)">
        <img src="{{ $postImgUrl }}"
             alt="{{ $post->caption ? Str::limit($post->caption, 60) : 'Jem Designs Instagram' }}"
             loading="lazy"
             style="width:100%;height:100%;object-fit:cover;transition:transform 0.6s var(--ease-luxury);"
             onmouseover="this.style.transform='scale(1.05)'"
             onmouseout="this.style.transform='scale(1)'">
      </a>
      @endif
      @endforeach
    </div>
    @if($instagramUrl)
    <div style="text-align:center;margin-top:40px">
      <a href="{{ $instagramUrl }}" target="_blank" rel="noopener" class="btn btn--outline">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right:4px">
          <rect x="2" y="2" width="20" height="20" rx="5" ry="5"/>
          <path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z"/>
          <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/>
        </svg>
        Follow @jem.designsandco
      </a>
    </div>
    @endif
  </div>
</section>
@endif
