@extends('layouts.storefront')
@section('meta_title', 'Our Story — Jem Designs & Co.')
@section('content')
<div class="story-hero">
  <div class="container">
    <span class="section__eyebrow anim-reveal">The Brand</span>
    <h1 class="story-hero__title anim-reveal">Our Story</h1>
    <div class="section__divider anim-reveal"></div>
  </div>
</div>
<section class="section story-content">
  <div class="container container--narrow">
    @if($brandStory)
      @foreach(explode("\n\n", $brandStory) as $block)
        @php $block = trim($block); @endphp
        @if($block)
        <div class="story-content__block anim-reveal">
          {!! nl2br(e($block)) !!}
        </div>
        @endif
      @endforeach
    @else
      <div class="story-content__block anim-reveal">
        <h2>Where It Begins</h2>
        <p>Jem Designs &amp; Co. was born from a simple observation: the geometric weave patterns of the Kuki-Zo people — diamonds within diamonds, rhythmic borders, interlocking tiles — are among the most visually striking textile traditions in the world, yet they remain largely unseen beyond Northeast India.</p>
        <p>We set out to change that. Not by preserving these patterns under glass, but by giving them new life on the silhouettes and fabrics that define how people dress today.</p>
      </div>
      <div class="story-content__block anim-reveal">
        <h2>The Motifs</h2>
        <p>Every pattern in our collection originates from traditional Kuki-Zo textile artistry. The small diamond motifs — often used as borders or repeating tile patterns — carry meanings rooted in community, identity, and craft. We work closely with weavers and textile historians to ensure that our interpretations honor the source while feel entirely contemporary.</p>
      </div>
      <div class="story-content__block anim-reveal">
        <h2>The Philosophy</h2>
        <p>We believe heritage is not a relic. It is a living, evolving force. When a traditional weave pattern meets a modern camp-collar shirt, or when a centuries-old motif is draped as a fashion stole, something powerful happens: culture becomes wearable, and clothing becomes a carrier of story.</p>
        <p>That is what Jem Designs &amp; Co. stands for. Heritage and modernity, woven together.</p>
      </div>
    @endif
  </div>
</section>
@endsection
