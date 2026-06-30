/* ============================================================
   JEM DESIGNS & CO. — Storefront JS (Multi-page Laravel)
   ============================================================ */

const JEM      = window.JEM_CONFIG || {};
const WHATSAPP = JEM.whatsapp  || '918368873736';
const WA_NAME  = JEM.waBusinessName || 'Jem Designs & Co.';
const WA_GREET = JEM.waGreeting || 'Hello! 🙏';
const WA_FOOTER = JEM.waFooter || '';
const INQUIRY_URL = JEM.inquiryUrl || '/api/inquiry';
const CSRF     = JEM.csrf      || '';
const PAY_WA   = JEM.paymentWhatsApp !== false;
const PAY_RZP  = JEM.paymentRazorpay === true;

let cart = JSON.parse(localStorage.getItem('jemCart')) || [];

const $ = (sel, ctx = document) => ctx.querySelector(sel);
const $$ = (sel, ctx = document) => [...ctx.querySelectorAll(sel)];

/* ---- LOADER ---- */
function initLoader() {
  const loader   = $('#loader');
  const skipBtn  = $('#loaderSkip');
  if (!loader) return;

  function hideLoader() {
    loader.classList.add('hidden');
    document.body.classList.remove('no-scroll');
    initScrollAnimations();
  }

  if (skipBtn) skipBtn.addEventListener('click', hideLoader);
  setTimeout(hideLoader, 2800);
  setTimeout(() => { if (!loader.classList.contains('hidden')) hideLoader(); }, 4000);
}

/* ---- NAV ---- */
function initNav() {
  const nav         = $('#nav');
  const hamburger   = $('#hamburger');
  const mobileMenu  = $('#mobileMenu');
  const mobileClose = $('#mobileMenuClose');
  if (!nav) return;

  window.addEventListener('scroll', () => {
    nav.classList.toggle('scrolled', window.scrollY > 60);
  }, { passive: true });

  if (hamburger && mobileMenu) {
    hamburger.addEventListener('click', () => {
      hamburger.classList.toggle('active');
      mobileMenu.classList.toggle('open');
      document.body.classList.toggle('no-scroll', mobileMenu.classList.contains('open'));
    });
    if (mobileClose) {
      mobileClose.addEventListener('click', () => {
        hamburger.classList.remove('active');
        mobileMenu.classList.remove('open');
        document.body.classList.remove('no-scroll');
      });
    }
  }
}

/* ---- CART PERSISTENCE ---- */
function saveCart() { localStorage.setItem('jemCart', JSON.stringify(cart)); }

function updateCartCount() {
  const count   = cart.reduce((s, i) => s + (i.qty || 1), 0);
  const countEl = $('#cartCount');
  if (!countEl) return;
  countEl.textContent = count;
  countEl.classList.toggle('visible', count > 0);
}

function renderCartDrawer() {
  const itemsEl  = $('#cartItems');
  const footerEl = $('#cartFooter');
  if (!itemsEl) return;

  if (cart.length === 0) {
    itemsEl.innerHTML = `
      <div class="cart-drawer__empty">
        <p>Your bag is empty</p>
        <a href="/shop" class="btn btn--outline">Start Shopping</a>
      </div>`;
    if (footerEl) footerEl.style.display = 'none';
    return;
  }

  itemsEl.innerHTML = cart.map((item, i) => `
    <div class="cart-item">
      <div class="cart-item__img">${item.image ? `<img src="${item.image}" alt="${item.name}">` : ''}</div>
      <div class="cart-item__details">
        <div class="cart-item__name">${item.name}</div>
        <div class="cart-item__meta">${item.color || ''}${item.size ? ', Size ' + item.size : ''}</div>
        <div class="cart-item__bottom">
          <span class="cart-item__qty">Qty: ${item.qty}</span>
          <button class="cart-item__remove" data-remove="${i}">Remove</button>
        </div>
      </div>
    </div>`).join('');

  $$('.cart-item__remove').forEach(btn => {
    btn.addEventListener('click', () => {
      cart.splice(parseInt(btn.dataset.remove), 1);
      saveCart();
      updateCartCount();
      renderCartDrawer();
    });
  });

  const subtotal    = cart.reduce((s, i) => s + ((i.price || 0) * (i.qty || 1)), 0);
  const subtotalEl  = $('#cartSubtotal');
  if (subtotalEl) subtotalEl.textContent = '₹' + subtotal.toLocaleString('en-IN');
  if (footerEl) footerEl.style.display = 'block';
}

function openCart()  { renderCartDrawer(); const d = $('#cartDrawer'); if (d) { d.classList.add('open'); document.body.classList.add('no-scroll'); } }
function closeCart() { const d = $('#cartDrawer'); if (d) { d.classList.remove('open'); document.body.classList.remove('no-scroll'); } }

function initCart() {
  const toggle      = $('#cartToggle');
  const closeBtn    = $('#cartCloseBtn');
  const closeBack   = $('#cartClose');
  const checkoutBtn = $('#checkoutWhatsApp');

  if (toggle)      toggle.addEventListener('click', openCart);
  if (closeBtn)    closeBtn.addEventListener('click', closeCart);
  if (closeBack)   closeBack.addEventListener('click', closeCart);
  if (checkoutBtn) checkoutBtn.addEventListener('click', sendCartToWhatsApp);

  updateCartCount();
}

/* ---- ADD TO CART (called from product detail page) ---- */
function addToCart(item) {
  const idx = cart.findIndex(c => c.id === item.id && c.color === item.color && c.size === item.size);
  if (idx >= 0) {
    cart[idx].qty += item.qty;
  } else {
    cart.push(item);
  }
  saveCart();
  updateCartCount();
  showToast(item.name + ' added to bag');
}

/* ---- WHATSAPP CHECKOUT ---- */
function buildWhatsAppMsg(items, customerInfo = {}) {
  const orderNum = 'JEM-' + Math.random().toString(36).substring(2, 8).toUpperCase();
  const subtotal = items.reduce((s, i) => s + ((i.price || 0) * (i.qty || 1)), 0);

  let msg = `${WA_GREET}\n\n`;
  msg += `*${WA_NAME} — Order Details*\n`;
  msg += `━━━━━━━━━━━━━━━━━\n\n`;
  msg += `*📦 Order #${orderNum}*\n\n`;

  items.forEach((item, idx) => {
    const details = [];
    if (item.color) details.push(item.color);
    if (item.size) details.push(`Size ${item.size}`);
    const detailStr = details.length ? ` — ${details.join(', ')}` : '';
    msg += `${idx + 1}. ${item.name}${detailStr}\n`;
    msg += `   Qty: ${item.qty} × ₹${(item.price || 0).toLocaleString('en-IN')} = ₹${((item.price || 0) * (item.qty || 1)).toLocaleString('en-IN')}\n`;
  });

  msg += `\n━━━━━━━━━━━━━━━━━\n`;
  msg += `💰 *Subtotal: ₹${subtotal.toLocaleString('en-IN')}*\n`;
  msg += `🚚 *Shipping: Free*\n`;
  msg += `━━━━━━━━━━━━━━━━━\n`;
  msg += `🧾 *TOTAL: ₹${subtotal.toLocaleString('en-IN')}*\n`;

  if (customerInfo.name || customerInfo.phone || customerInfo.address) {
    msg += `\n📍 *Delivery Address:*\n`;
    if (customerInfo.name) msg += `${customerInfo.name}\n`;
    if (customerInfo.address) msg += `${customerInfo.address}\n`;
    if (customerInfo.phone) msg += `📞 *Phone:* +91 ${customerInfo.phone}\n`;
    if (customerInfo.email) msg += `📧 *Email:* ${customerInfo.email}\n`;
  }

  if (customerInfo.notes) {
    msg += `\n📝 *Notes:* ${customerInfo.notes}\n`;
  }

  msg += `\n━━━━━━━━━━━━━━━━━\n`;
  if (WA_FOOTER) msg += `${WA_FOOTER}\n`;

  return msg;
}

async function sendCartToWhatsApp() {
  if (!cart.length) return;
  const message = buildWhatsAppMsg(cart);
  try {
    await fetch(INQUIRY_URL, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
      body: JSON.stringify({ cart }),
    });
  } catch (_) { /* proceed even on network failure */ }
  window.open('https://wa.me/' + WHATSAPP + '?text=' + encodeURIComponent(message), '_blank');
}

/* ---- TOAST ---- */
function showToast(msg) {
  const toast    = $('#toast');
  const toastMsg = $('#toastMsg');
  if (!toast) return;
  if (toastMsg) toastMsg.textContent = msg;
  toast.classList.add('visible');
  setTimeout(() => toast.classList.remove('visible'), 2500);
}

/* ---- ACCORDION ---- */
function initAccordion() {
  $$('.accordion-header').forEach(header => {
    header.addEventListener('click', () => {
      const item   = header.parentElement;
      const wasOpen = item.classList.contains('open');
      $$('.accordion-item').forEach(i => i.classList.remove('open'));
      if (!wasOpen) item.classList.add('open');
    });
  });
}

/* ---- SCROLL REVEAL ---- */
function initScrollAnimations() {
  const observer = new IntersectionObserver(entries => {
    entries.forEach(entry => {
      if (!entry.isIntersecting) return;
      const siblings = entry.target.parentElement.querySelectorAll('.anim-reveal:not(.revealed)');
      const idx = Array.from(siblings).indexOf(entry.target);
      setTimeout(() => entry.target.classList.add('revealed'), idx * 100);
      observer.unobserve(entry.target);
    });
  }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });

  $$('.anim-reveal:not(.revealed)').forEach(el => observer.observe(el));

  const divObs = new IntersectionObserver(entries => {
    entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('revealed'); divObs.unobserve(e.target); } });
  }, { threshold: 0.5 });
  $$('.section__divider:not(.revealed)').forEach(d => divObs.observe(d));
}

/* ---- TESTIMONIALS SLIDER ---- */
function initTestimonialSlider() {
  const track         = $('#testimonialTrack');
  const dotsContainer = $('#testimonialDots');
  const wrapper       = track ? track.parentElement : null;
  if (!track || !dotsContainer || !wrapper) return;

  const allSlides = $$('.testimonials-slider__slide', track);
  if (!allSlides.length) return;

  let current = 0, autoTimer;

  function getVisible()  { return window.innerWidth <= 640 ? 1 : 2; }
  function getMaxIndex() { return Math.max(0, allSlides.length - getVisible()); }

  function setSlideWidths() {
    const visible     = getVisible();
    const gap         = parseFloat(getComputedStyle(track).gap) || 24;
    const slideWidth  = (wrapper.offsetWidth - (visible - 1) * gap) / visible;
    allSlides.forEach(s => { s.style.flex = `0 0 ${slideWidth}px`; s.style.maxWidth = `${slideWidth}px`; });
    return slideWidth;
  }

  function buildDots() {
    dotsContainer.innerHTML = '';
    for (let i = 0; i <= getMaxIndex(); i++) {
      const dot = document.createElement('button');
      dot.className = 'testimonials-slider__dot' + (i === current ? ' active' : '');
      dot.setAttribute('aria-label', 'Slide ' + (i + 1));
      dot.addEventListener('click', () => { goTo(i); resetTimer(); });
      dotsContainer.appendChild(dot);
    }
  }

  function goTo(index) {
    current = Math.min(Math.max(0, index), getMaxIndex());
    const slideWidth = allSlides[0].offsetWidth;
    const gap = parseFloat(getComputedStyle(track).gap) || 24;
    track.style.transform = `translateX(-${current * (slideWidth + gap)}px)`;
    $$('.testimonials-slider__dot', dotsContainer).forEach((d, i) => d.classList.toggle('active', i === current));
  }

  function next() { goTo(current >= getMaxIndex() ? 0 : current + 1); }
  function resetTimer() { clearInterval(autoTimer); autoTimer = setInterval(next, 3500); }

  setSlideWidths(); buildDots(); goTo(0); resetTimer();

  track.addEventListener('mouseenter', () => clearInterval(autoTimer));
  track.addEventListener('mouseleave', resetTimer);

  let startX = 0, dragging = false;
  track.addEventListener('touchstart', e => { startX = e.touches[0].clientX; dragging = true; clearInterval(autoTimer); }, { passive: true });
  track.addEventListener('touchend',   e => {
    if (!dragging) return;
    dragging = false;
    const diff = startX - e.changedTouches[0].clientX;
    if (Math.abs(diff) > 50) diff > 0 ? next() : goTo(current - 1);
    resetTimer();
  }, { passive: true });

  let resizeTimer;
  window.addEventListener('resize', () => {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(() => { setSlideWidths(); buildDots(); goTo(Math.min(current, getMaxIndex())); }, 150);
  });
}

/* ---- SHOP FILTERS (shop page) ---- */
function initShopFilters() {
  const params = new URLSearchParams(window.location.search);
  const active = params.get('filter') || 'all';
  $$('.shop-filter').forEach(btn => {
    btn.classList.toggle('active', btn.dataset.filter === active);
    btn.addEventListener('click', () => {
      const f = btn.dataset.filter;
      const url = f === 'all' ? window.location.pathname : window.location.pathname + '?filter=' + f;
      window.location.href = url;
    });
  });
}

/* ---- GLOBAL EXPOSE ---- */
window.jemCart = { add: addToCart, toast: showToast, open: openCart };

/* ---- INIT ---- */
document.addEventListener('DOMContentLoaded', () => {
  document.body.classList.add('no-scroll');
  initLoader();
  initNav();
  initCart();
  initAccordion();
  initScrollAnimations();
  initTestimonialSlider();
  initShopFilters();
});
