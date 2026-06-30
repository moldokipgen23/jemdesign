{{-- Marketing Section Partial --}}
{{-- Receives: $section (MarketingSection model) --}}

@if($section->type === 'testimonials')
    @php $testimonials = $section->getTestimonials(); @endphp
    @if($testimonials->isEmpty()) @return @endif

    <section class="section section--dark">
        <div class="container">
            <div style="text-align:center;margin-bottom:48px" class="anim-reveal">
                <span class="section__eyebrow">{{ $section->title }}</span>
                <h2 class="section__title">What Our Customers Say</h2>
                <div class="section__divider"></div>
            </div>

            @if($section->display_style === 'carousel')
                <div class="testimonials-slider">
                    <div class="testimonials-slider__track" id="testimonialTrack_{{ $section->id }}">
                        @foreach($testimonials as $t)
                        <div class="testimonials-slider__slide anim-reveal">
                            <div class="testimonial-card">
                                <div class="testimonial-card__stars">
                                    @for($i = 0; $i < $t->rating; $i++)★@endfor
                                </div>
                                <p class="testimonial-card__text">"{{ $t->content }}"</p>
                                <div class="testimonial-card__author">
                                    @if($t->image)
                                        <img src="{{ Storage::url($t->image) }}" alt="{{ $t->customer_name }}" class="testimonial-card__avatar">
                                    @else
                                        <div class="testimonial-card__avatar testimonial-card__avatar--initial">{{ strtoupper(substr($t->customer_name, 0, 1)) }}</div>
                                    @endif
                                    <div>
                                        <span class="testimonial-card__name">{{ $t->customer_name }}</span>
                                        @if($t->customer_title)
                                            <span class="testimonial-card__location">{{ $t->customer_title }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="testimonials-slider__dots" id="testimonialDots_{{ $section->id }}"></div>
                </div>
            @else
                {{-- Grid --}}
                <div style="display:grid;grid-template-columns:repeat({{ $section->items_per_row }},1fr);gap:24px">
                    @foreach($testimonials as $t)
                    <div class="testimonial-card anim-reveal">
                        <div class="testimonial-card__stars">
                            @for($i = 0; $i < $t->rating; $i++)★@endfor
                        </div>
                        <p class="testimonial-card__text">"{{ $t->content }}"</p>
                        <div class="testimonial-card__author">
                            @if($t->image)
                                <img src="{{ Storage::url($t->image) }}" alt="{{ $t->customer_name }}" class="testimonial-card__avatar">
                            @else
                                <div class="testimonial-card__avatar testimonial-card__avatar--initial">{{ strtoupper(substr($t->customer_name, 0, 1)) }}</div>
                            @endif
                            <div>
                                <span class="testimonial-card__name">{{ $t->customer_name }}</span>
                                @if($t->customer_title)
                                    <span class="testimonial-card__location">{{ $t->customer_title }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

@else
    @php $products = $section->getProducts(); @endphp
    @if($products->isEmpty()) @return @endif

    <section class="section section--dark">
        <div class="container">
            <div style="text-align:center;margin-bottom:48px" class="anim-reveal">
                <span class="section__eyebrow">{{ $section->title }}</span>
                <h2 class="section__title">{{ $section->title }}</h2>
                <div class="section__divider"></div>
            </div>

            @if($section->display_style === 'carousel')
                <div class="products-slider" style="position:relative">
                    <div style="display:flex;gap:24px;overflow-x:auto;scroll-snap-type:x mandatory;-webkit-overflow-scrolling:touch;padding-bottom:16px" id="productSlider_{{ $section->id }}">
                        @foreach($products as $product)
                        <div style="min-width:280px;scroll-snap-align:start;flex:0 0 auto" class="anim-reveal">
                            @include('storefront.partials._product_card', ['product' => $product])
                        </div>
                        @endforeach
                    </div>
                </div>
            @else
                {{-- Grid --}}
                <div style="display:grid;grid-template-columns:repeat({{ $section->items_per_row }},1fr);gap:24px">
                    @foreach($products as $product)
                        @include('storefront.partials._product_card', ['product' => $product])
                    @endforeach
                </div>
            @endif

            <div style="text-align:center;margin-top:40px" class="anim-reveal">
                <a href="{{ route('storefront.shop') }}" class="btn btn--outline">View All</a>
            </div>
        </div>
    </section>
@endif
