@extends('layouts.storefront')

@section('meta_title', $product->name . ' — Jem Designs & Co.')
@section('meta_description', Str::limit(strip_tags($product->description), 155))

@push('head')
<style>
.product-detail__thumbs {
  display: flex;
  gap: 8px;
  margin-top: 10px;
  flex-wrap: wrap;
}
.product-detail__thumb {
  width: 64px;
  height: 84px;
  border-radius: 2px;
  overflow: hidden;
  cursor: pointer;
  border: 1.5px solid transparent;
  flex-shrink: 0;
  transition: border-color 0.25s;
}
.product-detail__thumb.active { border-color: var(--gold); }
.product-detail__thumb img { width: 100%; height: 100%; object-fit: cover; object-position: center 25%; }

.product-detail__videos {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
  gap: 10px;
  margin-top: 12px;
}
.product-detail__video-wrap {
  border-radius: 2px;
  overflow: hidden;
  aspect-ratio: 9/16;
  background: var(--black-card);
}
.product-detail__video-wrap video {
  width: 100%; height: 100%; object-fit: cover; display: block;
}
.size-btn[data-available="false"] {
  opacity: 0.35;
  cursor: not-allowed;
  position: relative;
}
.size-btn[data-available="false"]::after {
  content: '';
  position: absolute;
  left: 50%;
  top: 50%;
  transform: translate(-50%, -50%) rotate(-45deg);
  width: 80%;
  height: 1px;
  background: var(--gray-light);
}
</style>
@endpush

@section('content')

@php
  $colors = $product->colors->map(fn($c) => [
    'id'     => $c->id,
    'name'   => $c->color_name,
    'hex'    => $c->hex_code,
    'images' => $c->images->map(fn($i) => asset('storage/' . $i->image_path))->values()->all(),
  ]);

  // Collect ALL images across all colors (deduplicated) for the full gallery
  $allImages = $product->colors->flatMap(fn($c) => $c->images->map(fn($i) => asset('storage/' . $i->image_path)))->unique()->values()->all();

  $firstColor = $colors->first();
  $coverUrl = $product->cover_image ? asset('storage/' . $product->cover_image) : null;
  $firstImage = $coverUrl ?? ($allImages[0] ?? ($firstColor ? ($firstColor['images'][0] ?? null) : null));

  // Build variants map: key = "colorId_sizeLabel" => { id, price, stock }
  $variantsMap = [];
  foreach ($product->variants as $v) {
      $key = $v->product_color_id . '_' . $v->size->size_label;
      $variantsMap[$key] = [
          'id'    => $v->id,
          'price' => (float) $v->effective_price,
          'stock' => $v->stock,
      ];
  }
  $hasVariants = count($variantsMap) > 0;
@endphp

<div class="product-detail">
  <div class="container">

    {{-- Breadcrumb --}}
    <p style="padding-top:100px;padding-bottom:8px;font-size:11px;color:var(--gray);letter-spacing:0.08em">
      <a href="{{ route('storefront.home') }}" style="color:var(--gray);transition:color 0.2s"
         onmouseover="this.style.color='var(--gold)'" onmouseout="this.style.color='var(--gray)'">Home</a>
      <span style="margin:0 8px;opacity:0.4">›</span>
      <a href="{{ route('storefront.shop') }}" style="color:var(--gray);transition:color 0.2s"
         onmouseover="this.style.color='var(--gold)'" onmouseout="this.style.color='var(--gray)'">Shop</a>
      <span style="margin:0 8px;opacity:0.4">›</span>
      <span style="color:var(--white-dim)">{{ $product->name }}</span>
    </p>

    <div class="product-detail__inner" style="padding-top: 24px">

      {{-- ===== GALLERY ===== --}}
      <div class="product-detail__gallery">

        {{-- Main image --}}
        <div class="product-detail__main-image">
          @if($firstImage)
          <img id="pdMainImg"
               src="{{ $firstImage }}"
               alt="{{ $product->name }}"
               loading="eager"
               style="transition: opacity 0.4s ease;">
          @else
          <div style="width:100%;height:100%;background:var(--black-card);display:flex;align-items:center;justify-content:center">
            <span style="font-size:12px;color:var(--gray);letter-spacing:0.15em;text-transform:uppercase">No image</span>
          </div>
          @endif
        </div>

        {{-- Thumbnails (shown by JS when color has multiple images) --}}
        <div class="product-detail__thumbs" id="productThumbs" style="display:none"></div>

        {{-- Videos --}}
        @if($product->videos->isNotEmpty())
        <div class="product-detail__videos">
          @foreach($product->videos as $video)
          <div class="product-detail__video-wrap">
            <video src="{{ asset('storage/' . $video->video_path) }}"
                   playsinline muted loop controls
                   preload="metadata"
                   aria-label="{{ $product->name }} video"></video>
          </div>
          @endforeach
        </div>
        @endif

      </div>

      {{-- ===== PRODUCT INFO ===== --}}
      <div class="product-detail__info">

        <span class="product-detail__collection">{{ $product->category?->name }}</span>
        <h1 class="product-detail__name">{{ $product->name }}</h1>
        <p class="product-detail__price">₹{{ number_format($product->price, 0, '.', ',') }}</p>
        <p class="product-detail__desc">{{ $product->description }}</p>

        {{-- Color swatches --}}
        @if($product->colors->isNotEmpty())
        <div class="product-detail__swatches">
          <span class="product-detail__label">Color</span>
          <div class="swatch-group">
            @foreach($product->colors as $i => $color)
            <button class="swatch {{ $i === 0 ? 'active' : '' }}"
                    style="background:{{ $color->hex_code }}"
                    title="{{ $color->color_name }}"
                    aria-label="{{ $color->color_name }}"
                    data-color-index="{{ $i }}"></button>
            @endforeach
          </div>
          <span class="product-detail__color-name" id="pdColorName">
            {{ $product->colors->first()?->color_name }}
          </span>
        </div>
        @endif

        {{-- Sizes --}}
        @if($product->sizes->isNotEmpty())
        <div class="product-detail__sizes">
          <span class="product-detail__label">Size</span>
          <div class="size-group">
            @foreach($product->sizes as $size)
            <button class="size-btn {{ !$size->is_available ? '' : '' }}"
                    data-size="{{ $size->size_label }}"
                    data-available="{{ $size->is_available ? 'true' : 'false' }}"
                    {{ !$size->is_available ? 'disabled' : '' }}>
              {{ $size->size_label }}
            </button>
            @endforeach
          </div>
        </div>
        @endif

        {{-- Quantity --}}
        <div class="product-detail__qty">
          <span class="product-detail__label">Quantity</span>
          <div class="qty-selector">
            <button class="qty-btn" id="pdQtyMinus" aria-label="Decrease quantity">−</button>
            <span class="qty-value" id="pdQty">1</span>
            <button class="qty-btn" id="pdQtyPlus" aria-label="Increase quantity">+</button>
          </div>
        </div>

        {{-- Stock indicator --}}
        @if($hasVariants)
        <div class="product-detail__stock" id="pdStock" style="display:none;margin-top:8px">
          <span id="pdStockText" style="font-size:12px"></span>
        </div>
        @endif

        {{-- Actions --}}
        <div class="product-detail__actions">
          <button class="btn btn--gold btn--full" id="pdAddToCart">Add to Bag</button>
          <button class="btn btn--outline btn--full" id="pdBuyNow">Buy Now via WhatsApp</button>
        </div>

        {{-- Accordion --}}
        <div class="product-detail__accordion">
          <div class="accordion-item">
            <button class="accordion-header">
              <span>Fabric &amp; Care</span>
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
              </svg>
            </button>
            <div class="accordion-body">
              @if($product->material || $product->care_instructions)
                @if($product->material)
                  <p><strong>Material:</strong> {{ $product->material }}</p>
                @endif
                @if($product->weight)
                  <p><strong>Weight:</strong> {{ $product->weight }}</p>
                @endif
                @if($product->care_instructions)
                  <p><strong>Care:</strong> {{ $product->care_instructions }}</p>
                @endif
              @else
                <p>Premium handwoven cotton-silk blend. Dry clean recommended. Store in a cool, dry place away from direct sunlight. The natural dyes and traditional weave patterns may develop a richer patina over time — this is a feature, not a flaw.</p>
              @endif
            </div>
          </div>
          @if($product->heritage_note)
          <div class="accordion-item">
            <button class="accordion-header">
              <span>Heritage Note</span>
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
              </svg>
            </button>
            <div class="accordion-body">
              <p>{{ $product->heritage_note }}</p>
            </div>
          </div>
          @else
          <div class="accordion-item">
            <button class="accordion-header">
              <span>Heritage Note</span>
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
              </svg>
            </button>
            <div class="accordion-body">
              <p>The geometric motifs on this piece are inspired by traditional Kuki-Zo weaving patterns, each symbol carrying cultural significance passed down through generations. By wearing this garment, you carry forward a living tradition.</p>
            </div>
          </div>
          @endif
          <div class="accordion-item">
            <button class="accordion-header">
              <span>Shipping &amp; Returns</span>
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
              </svg>
            </button>
            <div class="accordion-body">
              <p>We ship across India. Orders are typically dispatched within 3–5 business days. For delivery timelines and return queries, please reach out to us via WhatsApp — we're happy to help.</p>
            </div>
          </div>
        </div>

      </div>
    </div>

  </div>
</div>

@push('scripts')
<script>
const JEM = window.JEM_CONFIG || {};
const COLORS = @json($colors->values());
const ALL_IMAGES = @json($allImages);
const VARIANTS = @json($variantsMap);
const HAS_VARIANTS = {{ $hasVariants ? 'true' : 'false' }};
const PRODUCT_ID    = "{{ $product->id }}";
const PRODUCT_NAME  = @json($product->name);
const PRODUCT_PRICE = {{ (float) $product->price }};

document.addEventListener('DOMContentLoaded', () => {
  const mainImg        = document.getElementById('pdMainImg');
  const colorNameEl    = document.getElementById('pdColorName');
  const thumbsEl       = document.getElementById('productThumbs');
  const qtyEl          = document.getElementById('pdQty');
  const swatches       = document.querySelectorAll('.swatch[data-color-index]');
  const sizeBtns       = document.querySelectorAll('.size-btn');
  const stockEl        = document.getElementById('pdStock');
  const stockTextEl    = document.getElementById('pdStockText');
  const addBtn         = document.getElementById('pdAddToCart');

  let currentColorIdx  = 0;
  let currentImgIdx    = 0;
  let currentSize      = null;
  let currentVariant   = null;
  let qty              = 1;

  /* -- Find variant for current color+size -- */
  function findVariant() {
    if (!HAS_VARIANTS) return null;
    const color = COLORS[currentColorIdx];
    if (!color || !currentSize) return null;
    return VARIANTS[color.id + '_' + currentSize] || null;
  }

  /* -- Update stock display -- */
  function updateStock() {
    if (!HAS_VARIANTS || !stockEl) return;
    const variant = findVariant();
    currentVariant = variant;
    if (!currentSize) {
      stockEl.style.display = 'none';
      addBtn && (addBtn.disabled = false);
      return;
    }
    stockEl.style.display = 'block';
    if (!variant) {
      stockTextEl.textContent = 'Not available in this combination';
      stockTextEl.style.color = '#ef4444';
      addBtn && (addBtn.disabled = true);
    } else if (variant.stock <= 0) {
      stockTextEl.textContent = 'Out of stock';
      stockTextEl.style.color = '#ef4444';
      addBtn && (addBtn.disabled = true);
    } else if (variant.stock <= 5) {
      stockTextEl.textContent = 'Only ' + variant.stock + ' left in stock';
      stockTextEl.style.color = '#C9A04E';
      addBtn && (addBtn.disabled = false);
    } else {
      stockTextEl.textContent = 'In stock';
      stockTextEl.style.color = '#22c55e';
      addBtn && (addBtn.disabled = false);
    }
  }

  /* -- Image switch with crossfade -- */
  function switchImage(src) {
    if (!mainImg || !src) return;
    mainImg.style.opacity = '0';
    setTimeout(() => { mainImg.src = src; mainImg.style.opacity = '1'; }, 280);
  }

  /* -- Thumbnail strip -- */
  function renderThumbs(colorIdx) {
    if (!thumbsEl) return;
    const colorImages = COLORS[colorIdx]?.images || [];
    // Show all images: if color has multiple, show those; otherwise show all product images
    const images = colorImages.length > 1 ? colorImages : ALL_IMAGES;
    if (images.length <= 1) { thumbsEl.style.display = 'none'; return; }
    thumbsEl.style.display = 'flex';
    thumbsEl.innerHTML = images.map((src, i) => `
      <div class="product-detail__thumb${src === mainImg?.src ? ' active' : ''}" data-idx="${i}" data-src="${src}">
        <img src="${src}" alt="View ${i + 1}" loading="lazy">
      </div>`).join('');
    thumbsEl.querySelectorAll('.product-detail__thumb').forEach(t => {
      t.addEventListener('click', () => {
        currentImgIdx = parseInt(t.dataset.idx);
        switchImage(t.dataset.src);
        thumbsEl.querySelectorAll('.product-detail__thumb').forEach(th => th.classList.remove('active'));
        t.classList.add('active');
      });
    });
  }

  /* -- Color selection -- */
  function selectColor(idx) {
    currentColorIdx = idx;
    currentImgIdx   = 0;
    const color     = COLORS[idx];
    if (!color) return;
    switchImage(color.images[0]);
    if (colorNameEl) colorNameEl.textContent = color.name;
    swatches.forEach((s, i) => s.classList.toggle('active', i === idx));
    renderThumbs(idx);
    updateStock();
  }

  swatches.forEach((swatch, i) => {
    swatch.addEventListener('click', () => selectColor(i));
  });

  /* -- Size selection -- */
  sizeBtns.forEach(btn => {
    if (btn.dataset.available === 'false') return;
    btn.addEventListener('click', () => {
      const selecting = !btn.classList.contains('active');
      sizeBtns.forEach(b => b.classList.remove('active'));
      if (selecting) { btn.classList.add('active'); currentSize = btn.dataset.size; }
      else { currentSize = null; }
      updateStock();
    });
  });

  /* -- Quantity -- */
  document.getElementById('pdQtyMinus')?.addEventListener('click', () => {
    if (qty > 1) { qty--; if (qtyEl) qtyEl.textContent = qty; }
  });
  document.getElementById('pdQtyPlus')?.addEventListener('click', () => {
    if (qty < 10) { qty++; if (qtyEl) qtyEl.textContent = qty; }
  });

  /* -- Add to bag -- */
  document.getElementById('pdAddToCart')?.addEventListener('click', () => {
    const color = COLORS[currentColorIdx];
    if (!color) return;
    const price = currentVariant ? currentVariant.price : PRODUCT_PRICE;
    window.jemCart?.add({
      id:         PRODUCT_ID,
      variant_id: currentVariant ? currentVariant.id : null,
      name:       PRODUCT_NAME,
      color:      color.name,
      colorHex:   color.hex,
      size:       currentSize,
      qty:        qty,
      price:      price,
      image:      color.images[0] || '',
    });
    window.jemCart?.open?.();
  });

  /* -- Buy now -- */
  document.getElementById('pdBuyNow')?.addEventListener('click', () => {
    const color = COLORS[currentColorIdx];
    const price = currentVariant ? currentVariant.price : PRODUCT_PRICE;
    const item = [{
      name:  PRODUCT_NAME,
      color: color?.name || '',
      size:  currentSize || '',
      qty:   qty,
      price: price,
    }];
    const msg = buildWhatsAppMsg(item);
    window.open('https://wa.me/' + JEM.whatsapp + '?text=' + encodeURIComponent(msg), '_blank');
  });

  /* -- Init -- */
  renderThumbs(0);
  updateStock();
});
</script>
@endpush

@endsection
