<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('meta_title', \App\Models\SiteSetting::get('meta_title', 'Jem Designs & Co. — Heritage, Reimagined'))</title>
  <meta name="description" content="@yield('meta_description', \App\Models\SiteSetting::get('meta_description', 'Traditional Kuki-Zo tribal weave motifs reimagined for contemporary wardrobes. Handwoven in Northeast India.'))">
  <meta name="theme-color" content="#0B0B0C">
  <meta property="og:title" content="@yield('meta_title', \App\Models\SiteSetting::get('meta_title', 'Jem Designs & Co.'))">
  <meta property="og:description" content="@yield('meta_description', \App\Models\SiteSetting::get('meta_description'))">
  @php $ogImage = \App\Models\SiteSetting::get('og_image'); @endphp
  @if($ogImage)
  <meta property="og:image" content="{{ asset('storage/' . $ogImage) }}">
  @else
  <meta property="og:image" content="{{ asset('images/logo.jpg') }}">
  @endif
  <meta property="og:type" content="website">
  @php $faviconPath = \App\Models\SiteSetting::get('favicon'); @endphp
  @if($faviconPath && \Illuminate\Support\Facades\Storage::disk('public')->exists($faviconPath))
  <link rel="icon" type="image/{{ pathinfo($faviconPath, PATHINFO_EXTENSION) }}" href="{{ asset('storage/' . $faviconPath) }}">
  @else
  <link rel="icon" type="image/jpeg" href="{{ asset('images/logo.jpg') }}">
  @endif
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400;1,500&family=Montserrat:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/storefront.css') }}">
  @stack('head')
</head>
<body>

@php
  $waNumber   = \App\Models\SiteSetting::get('whatsapp_number', '918368873736');
  $waLink     = 'https://wa.me/' . $waNumber;
  $brandName  = \App\Models\SiteSetting::get('brand_name', 'Jem Designs & Co.');
  $instagramUrl = \App\Models\SiteSetting::get('instagram_url', 'https://www.instagram.com/jem.designsandco');
  $facebookUrl  = \App\Models\SiteSetting::get('facebook_url');
  $email        = \App\Models\SiteSetting::get('email', 'hello@jemdesignsandco.com');
@endphp

{{-- ========== LOADER ========== --}}
<div id="loader" class="loader">
  <div class="loader__content">
    <svg class="loader__logo" viewBox="0 0 400 180" xmlns="http://www.w3.org/2000/svg">
      <path class="loader__diamond" d="M195 18 L210 38 L195 58 L180 38 Z" fill="none" stroke="#C9A04E" stroke-width="1.5"/>
      <path class="loader__diamond-fill" d="M195 24 L205 38 L195 52 L185 38 Z" fill="#C9A04E" opacity="0"/>
      <path class="loader__wordmark" d="M100 130 C100 130 105 75 130 75 C145 75 140 100 155 100 C170 100 175 70 190 70 C205 70 200 105 215 105 C230 105 250 60 260 75 C270 90 260 130 260 130" fill="none" stroke="#F2EFE9" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
      <text class="loader__subtitle" x="195" y="158" text-anchor="middle" fill="#F2EFE9" font-family="Montserrat, sans-serif" font-size="11" font-weight="400" letter-spacing="4" opacity="0">DESIGNS & CO.</text>
    </svg>
  </div>
  <button class="loader__skip" id="loaderSkip">Skip</button>
</div>

{{-- ========== NAV ========== --}}
<nav id="nav" class="nav">
  <div class="nav__inner">
    <a href="{{ route('storefront.home') }}" class="nav__logo" aria-label="{{ $brandName }}">
      <svg class="nav__logo-svg" viewBox="0 0 140 48" xmlns="http://www.w3.org/2000/svg">
        <path d="M38 6 L44 16 L38 26 L32 16 Z" fill="none" stroke="#C9A04E" stroke-width="1" opacity="0.7"/>
        <text x="52" y="36" fill="#F2EFE9" font-family="'Cormorant Garamond', serif" font-size="34" font-weight="600" font-style="italic" letter-spacing="-1">jem</text>
        <text x="53" y="46" fill="#8A857E" font-family="'Montserrat', sans-serif" font-size="6.5" font-weight="500" letter-spacing="3.5">DESIGNS &amp; CO.</text>
      </svg>
    </a>
    <div class="nav__links">
      <a href="{{ route('storefront.shop') }}" class="nav__link">Shop</a>
      <a href="{{ route('storefront.shop') }}?filter=women" class="nav__link">Women's</a>
      <a href="{{ route('storefront.shop') }}?filter=men" class="nav__link">Men's</a>
      <a href="{{ route('storefront.story') }}" class="nav__link">Our Story</a>
      <a href="{{ route('storefront.founder') }}" class="nav__link">The Founder</a>
      <a href="{{ route('storefront.home') }}#contact" class="nav__link">Contact</a>
    </div>
    <div class="nav__actions">
      <button class="nav__cart" id="cartToggle" aria-label="Cart">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
          <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/>
          <line x1="3" y1="6" x2="21" y2="6"/>
          <path d="M16 10a4 4 0 01-8 0"/>
        </svg>
        <span class="nav__cart-count" id="cartCount">0</span>
      </button>
      <a href="{{ $waLink }}" target="_blank" class="nav__whatsapp" aria-label="WhatsApp">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor">
          <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
        </svg>
      </a>
      <button class="nav__hamburger" id="hamburger" aria-label="Menu">
        <span></span><span></span><span></span>
      </button>
    </div>
  </div>
</nav>

{{-- ========== MOBILE MENU ========== --}}
<div class="mobile-menu" id="mobileMenu">
  <div class="mobile-menu__backdrop" id="mobileMenuClose"></div>
  <div class="mobile-menu__panel">
    <div class="mobile-menu__header">
      <svg class="mobile-menu__logo" viewBox="0 0 140 48" xmlns="http://www.w3.org/2000/svg">
        <path d="M38 6 L44 16 L38 26 L32 16 Z" fill="none" stroke="#C9A04E" stroke-width="1" opacity="0.7"/>
        <text x="52" y="36" fill="#F2EFE9" font-family="'Cormorant Garamond', serif" font-size="34" font-weight="600" font-style="italic" letter-spacing="-1">jem</text>
        <text x="53" y="46" fill="#8A857E" font-family="'Montserrat', sans-serif" font-size="6.5" font-weight="500" letter-spacing="3.5">DESIGNS &amp; CO.</text>
      </svg>
    </div>
    <a href="{{ route('storefront.home') }}" class="mobile-menu__link">Home</a>
    <a href="{{ route('storefront.shop') }}" class="mobile-menu__link">Shop All</a>
    <a href="{{ route('storefront.shop') }}?filter=women" class="mobile-menu__link">Women's</a>
    <a href="{{ route('storefront.shop') }}?filter=men" class="mobile-menu__link">Men's</a>
    <a href="{{ route('storefront.story') }}" class="mobile-menu__link">Our Story</a>
    <a href="{{ route('storefront.founder') }}" class="mobile-menu__link">The Founder</a>
    <a href="{{ $waLink }}" target="_blank" class="mobile-menu__whatsapp">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
      </svg>
      Chat on WhatsApp
    </a>
  </div>
</div>

{{-- ========== CART DRAWER ========== --}}
<div class="cart-drawer" id="cartDrawer">
  <div class="cart-drawer__backdrop" id="cartClose"></div>
  <div class="cart-drawer__panel">
    <div class="cart-drawer__header">
      <h3>Your Bag</h3>
      <button class="cart-drawer__close" id="cartCloseBtn" aria-label="Close cart">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <div class="cart-drawer__items" id="cartItems">
      <div class="cart-drawer__empty">
        <p>Your bag is empty</p>
        <a href="{{ route('storefront.shop') }}" class="btn btn--outline">Start Shopping</a>
      </div>
    </div>
    <div class="cart-drawer__footer" id="cartFooter" style="display:none;">
      <div class="cart-drawer__subtotal">
        <span>Subtotal</span>
        <span id="cartSubtotal">₹0</span>
      </div>
      @php
        $waEnabled = \App\Models\SiteSetting::get('payment_whatsapp', '1') == '1';
        $rzpEnabled = \App\Models\SiteSetting::get('payment_razorpay', '0') == '1';
      @endphp
      @if($waEnabled && !$rzpEnabled)
        <button class="btn btn--full" id="checkoutWhatsApp" style="margin-top:8px;background:#25D366;color:#fff;display:flex;align-items:center;justify-content:center;gap:8px">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="#fff"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
          Order via WhatsApp
        </button>
        <p class="cart-drawer__note">Send your order details via WhatsApp</p>
      @elseif($waEnabled && $rzpEnabled)
        <a href="{{ route('storefront.checkout') }}" class="btn btn--gold btn--full" id="checkoutPayNow" style="text-align:center;text-decoration:none;display:block">
          Pay Now
        </a>
        <button class="btn btn--outline btn--full" id="checkoutWhatsApp" style="margin-top:8px">
          Checkout via WhatsApp
        </button>
        <p class="cart-drawer__note">Pay online or order via WhatsApp</p>
      @else
        <a href="{{ route('storefront.checkout') }}" class="btn btn--gold btn--full" id="checkoutPayNow" style="text-align:center;text-decoration:none;display:block">
          Pay Now
        </a>
        <p class="cart-drawer__note">Pay online via Razorpay</p>
      @endif
    </div>
  </div>
</div>

{{-- ========== TOAST ========== --}}
<div class="toast" id="toast">
  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#C9A04E" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
  <span id="toastMsg">Added to bag</span>
</div>

{{-- ========== PAGE CONTENT ========== --}}
<main>
  @yield('content')
</main>

{{-- ========== FOOTER ========== --}}
<footer class="footer" id="contact">
  <div class="footer__inner">
    <div class="footer__top">
      <div class="footer__brand">
        <svg class="footer__logo" viewBox="0 0 140 48" xmlns="http://www.w3.org/2000/svg">
          <path d="M38 6 L44 16 L38 26 L32 16 Z" fill="none" stroke="#C9A04E" stroke-width="1" opacity="0.7"/>
          <text x="52" y="36" fill="#F2EFE9" font-family="'Cormorant Garamond', serif" font-size="34" font-weight="600" font-style="italic" letter-spacing="-1">jem</text>
          <text x="53" y="46" fill="#8A857E" font-family="'Montserrat', sans-serif" font-size="6.5" font-weight="500" letter-spacing="3.5">DESIGNS &amp; CO.</text>
        </svg>
        <p class="footer__tagline">A seamless blend of heritage and modern design. Traditional Kuki-Zo tribal weave motifs reimagined for contemporary wardrobes.</p>
        <div class="footer__social">
          @if($instagramUrl)
          <a href="{{ $instagramUrl }}" target="_blank" rel="noopener" aria-label="Instagram" class="footer__social-link">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
              <rect x="2" y="2" width="20" height="20" rx="5" ry="5"/>
              <path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z"/>
              <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/>
            </svg>
          </a>
          @endif
          <a href="{{ $waLink }}" target="_blank" rel="noopener" aria-label="WhatsApp" class="footer__social-link footer__social-link--wa">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
              <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
            </svg>
          </a>
          @if($facebookUrl)
          <a href="{{ $facebookUrl }}" target="_blank" rel="noopener" aria-label="Facebook" class="footer__social-link">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
              <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/>
            </svg>
          </a>
          @endif
          @if($email)
          <a href="mailto:{{ $email }}" aria-label="Email" class="footer__social-link">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
              <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
              <polyline points="22,6 12,13 2,6"/>
            </svg>
          </a>
          @endif
        </div>
      </div>

      <div class="footer__nav">
        <div class="footer__col">
          <h4>Shop</h4>
          <a href="{{ route('storefront.shop') }}">All Products</a>
          <a href="{{ route('storefront.shop') }}?filter=women">Women's Collection</a>
          <a href="{{ route('storefront.shop') }}?filter=men">Men's Collection</a>
          <a href="{{ route('storefront.shop') }}?filter=women">Shawls &amp; Stoles</a>
          <a href="{{ route('storefront.shop') }}?filter=men">Heritage Shirts</a>
        </div>
        <div class="footer__col">
          <h4>About</h4>
          <a href="{{ route('storefront.story') }}">Our Story</a>
          <a href="{{ route('storefront.founder') }}">The Founder</a>
          <a href="{{ route('storefront.story') }}">Heritage &amp; Craft</a>
        </div>
        <div class="footer__col">
          <h4>Help</h4>
          <a href="{{ $waLink }}" target="_blank">Contact Us</a>
          <a href="#">Shipping &amp; Delivery</a>
          <a href="#">Returns &amp; Exchanges</a>
          <a href="#">Size Guide</a>
        </div>
      </div>
    </div>

    <div class="footer__newsletter">
      <div class="footer__newsletter-text">
        <h4>Stay in the Loop</h4>
        <p>New collections, heritage stories, and behind-the-scenes — delivered to your inbox.</p>
      </div>
      <form class="footer__newsletter-form" onsubmit="return false;">
        <input type="email" placeholder="Your email address" class="footer__newsletter-input" aria-label="Email address">
        <button type="submit" class="btn btn--gold btn--newsletter">Subscribe</button>
      </form>
    </div>

    <div class="footer__bottom">
      <p>&copy; {{ date('Y') }} Jem Designs &amp; Co. All rights reserved.</p>
      <div class="footer__bottom-links">
        <a href="#">Privacy Policy</a>
        <a href="#">Terms of Service</a>
      </div>
      <p class="footer__credit">Designed by <strong>Ehlom</strong></p>
    </div>
  </div>
</footer>

{{-- ========== FLOATING WHATSAPP ========== --}}
<a href="{{ $waLink }}?text={{ urlencode("Hi! I'd like to know more about your collection.") }}" class="whatsapp-float" target="_blank" rel="noopener" aria-label="Chat on WhatsApp">
  <svg class="whatsapp-float__icon" viewBox="0 0 32 32" fill="currentColor">
    <path d="M16.004 0h-.008C7.174 0 0 7.176 0 16c0 3.5 1.132 6.744 3.054 9.374L1.054 31.25l6.118-1.97A15.906 15.906 0 0016.004 32C24.826 32 32 24.822 32 16S24.826 0 16.004 0zm9.31 22.586c-.39 1.096-1.932 2.008-3.16 2.27-.84.18-1.934.322-5.626-1.21-4.728-1.962-7.772-6.764-8.006-7.076-.226-.312-1.896-2.524-1.896-4.814s1.2-3.41 1.626-3.878c.39-.426.922-.57 1.228-.57.148 0 .28.008.4.014.4.016.946-.152 1.486 1.134.594 1.414 2.016 4.922 2.19 5.278.174.356.29.772.094 1.226-.18.466-.458.752-.844 1.072-.346.286-.676.516-.97.736-.346.26-.716.538-.296 1.054.42.516 1.864 3.074 4.002 4.982 2.754 2.456 5.076 3.218 5.792 3.57.536.264.852.22 1.166-.134.314-.354 1.344-1.564 1.702-2.1.356-.536.714-.446 1.204-.268.494.178 3.138 1.48 3.676 1.75.536.27.894.406 1.024.634.128.228.128 1.312-.262 2.408z"/>
  </svg>
  <span class="whatsapp-float__pulse"></span>
</a>

<script>
window.JEM_CONFIG = {
  whatsapp:       "{{ $waNumber }}",
  waBusinessName: "{{ \App\Models\SiteSetting::get('wa_business_name', 'Jem Designs & Co.') }}",
  waGreeting:     "{{ \App\Models\SiteSetting::get('wa_greeting', 'Hello! 🙏') }}",
  waFooter:       @json(\App\Models\SiteSetting::get('wa_footer', '')),
  inquiryUrl:     "{{ route('inquiry.store') }}",
  csrf:           "{{ csrf_token() }}",
  paymentWhatsApp: {{ \App\Models\SiteSetting::get('payment_whatsapp', '1') == '1' ? 'true' : 'false' }},
  paymentRazorpay: {{ \App\Models\SiteSetting::get('payment_razorpay', '0') == '1' ? 'true' : 'false' }},
};
</script>
<script src="{{ asset('js/storefront.js') }}"></script>
@stack('scripts')

</body>
</html>
