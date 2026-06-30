/* ============================================
   JEM DESIGNS & CO. — App Logic
   ============================================ */

const WHATSAPP_NUMBER = "918368873736";

const products = [
  {
    id: "mens-camp-blue",
    name: "Heritage Camp Shirt",
    collection: "Signature Series",
    category: "men",
    price: 3490,
    description: "A relaxed camp-collar shirt in dusty blue, adorned with hand-drawn diamond motifs inspired by traditional Kuki-Zo weave patterns. Crafted from a premium cotton-silk blend that drapes with quiet luxury.",
    sizes: ["S", "M", "L", "XL"],
    colors: [
      { name: "Dusty Blue", hex: "#6B8FA3", image: "images/Screenshot_20260630-015416.png" }
    ]
  },
  {
    id: "mens-mandarin-charcoal",
    name: "Heritage Mandarin Shirt",
    collection: "Signature Series",
    category: "men",
    price: 3790,
    description: "A modern mandarin-collar shirt in deep charcoal, featuring hexagonal heritage motifs that echo traditional textile geometry. Short sleeves, relaxed fit, and a collar that frames the face with quiet authority.",
    sizes: ["S", "M", "L", "XL"],
    colors: [
      { name: "Charcoal", hex: "#2C2C2E", image: "images/Screenshot_20260630-015426.png" }
    ]
  },
  {
    id: "shawl-teal",
    name: "Heritage Stole — Teal",
    collection: "HerEDIT",
    category: "women",
    price: 2990,
    description: "A generous stole in rich teal green, bordered with diamond motifs woven in the traditional Kuki-Zo style. Light enough to drape effortlessly, bold enough to anchor any outfit.",
    sizes: null,
    colors: [
      { name: "Teal Green", hex: "#2D8B7A", image: "images/Screenshot_20260630-015700.png" }
    ]
  },
  {
    id: "shawl-crimson",
    name: "Heritage Stole — Crimson",
    collection: "HerEDIT",
    category: "women",
    price: 2990,
    description: "Deep crimson red meets geometric precision. This stole carries the warmth of tradition with the confidence of contemporary design — a statement piece that tells a story.",
    sizes: null,
    colors: [
      { name: "Crimson Red", hex: "#8B3A3A", image: "images/Screenshot_20260630-015502.png" }
    ]
  },
  {
    id: "shawl-blush",
    name: "Heritage Stole — Blush",
    collection: "Blossoms",
    category: "women",
    price: 3290,
    description: "Soft blush pink with delicate gold fringe detailing and subtle diamond motifs. Part of the Blossoms collection, this piece is designed to feel like wearing a story passed through generations.",
    sizes: null,
    colors: [
      { name: "Blush Pink", hex: "#C4958A", image: "images/Screenshot_20260630-015552.png" }
    ]
  },
  {
    id: "shawl-aubergine",
    name: "Heritage Stole — Aubergine",
    collection: "HerEDIT",
    category: "women",
    price: 3290,
    description: "Deep aubergine purple with intricate geometric borders and gold tassel fringe. A color that commands attention while remaining deeply rooted in heritage textile traditions.",
    sizes: null,
    colors: [
      { name: "Deep Aubergine", hex: "#5B3256", image: "images/Screenshot_20260630-015444.png" }
    ]
  },
  {
    id: "shawl-ivory",
    name: "Heritage Stole — Ivory",
    collection: "HerEDIT",
    category: "women",
    price: 2790,
    description: "Warm ivory cream with charcoal and grey geometric border patterns. The most versatile piece in the collection — pairs with everything from tailored trousers to flowing dresses.",
    sizes: null,
    colors: [
      { name: "Ivory Cream", hex: "#E8E0D0", image: "images/Screenshot_20260630-015604.png" }
    ]
  },
  {
    id: "shawl-multi",
    name: "Heritage Stole — Multi",
    collection: "Blossoms",
    category: "women",
    price: 3490,
    description: "A celebration of color — dusty blue and sage green geometric motifs interweave across a warm cream base. Finished with delicate tassel fringe, this stole is a wearable gallery of heritage craft.",
    sizes: null,
    colors: [
      { name: "Blue & Green", hex: "#6B8FA3", image: "images/Screenshot_20260630-015645.png" }
    ]
  }
];

const topSellerIds = ["mens-camp-blue", "shawl-teal", "shawl-aubergine", "mens-mandarin-charcoal"];

let cart = JSON.parse(localStorage.getItem("jemCart")) || [];
let currentView = "home";
let currentProduct = null;
let currentColorIndex = 0;
let currentSize = null;
let currentQty = 1;
let activeFilter = "all";

const $ = (sel, ctx = document) => ctx.querySelector(sel);
const $$ = (sel, ctx = document) => [...ctx.querySelectorAll(sel)];

/* ---- LOADER ---- */
function initLoader() {
  const loader = $("#loader");
  const skipBtn = $("#loaderSkip");
  function hideLoader() {
    loader.classList.add("hidden");
    document.body.classList.remove("no-scroll");
    initScrollAnimations();
  }
  skipBtn.addEventListener("click", hideLoader);
  setTimeout(hideLoader, 2800);
  setTimeout(() => { if (!loader.classList.contains("hidden")) hideLoader(); }, 4000);
}

/* ---- NAV ---- */
function initNav() {
  const nav = $("#nav");
  const hamburger = $("#hamburger");
  const mobileMenu = $("#mobileMenu");
  const mobileClose = $("#mobileMenuClose");

  window.addEventListener("scroll", () => {
    nav.classList.toggle("scrolled", window.scrollY > 60);
  }, { passive: true });

  hamburger.addEventListener("click", () => {
    hamburger.classList.toggle("active");
    mobileMenu.classList.toggle("open");
    if (mobileMenu.classList.contains("open")) {
      document.body.classList.add("no-scroll");
    } else {
      document.body.classList.remove("no-scroll");
    }
  });

  mobileClose.addEventListener("click", () => {
    hamburger.classList.remove("active");
    mobileMenu.classList.remove("open");
    document.body.classList.remove("no-scroll");
  });

  // Mobile menu links handled by event delegation in initNavigation
}

/* ---- ROUTING ---- */
function navigate(view, options = {}) {
  $$(".view").forEach(v => v.style.display = "none");
  const target = $(`#view-${view}`);
  if (target) {
    target.style.display = "block";
    window.scrollTo(0, 0);
  }
  currentView = view;
  if (options.filter) activeFilter = options.filter;

  if (view === "shop") renderShopGrid();
  else if (view === "home") { renderTopSellers(); renderAllProducts(); }
  else if (view === "product" && options.productId) renderProductDetail(options.productId);

  setTimeout(initScrollAnimations, 100);
  if (options.scrollTo) {
    setTimeout(() => {
      const el = document.getElementById(options.scrollTo);
      if (el) el.scrollIntoView({ behavior: "smooth" });
    }, 200);
  }
}

function initNavigation() {
  // Use event delegation on the entire document
  document.addEventListener("click", (e) => {
    const el = e.target.closest("[data-navigate]");
    if (!el) return;
    e.preventDefault();
    const view = el.dataset.navigate;
    const filter = el.dataset.filter || null;
    const scrollTo = el.dataset.scroll || null;

    // Close mobile menu if open
    const mobileMenu = $("#mobileMenu");
    const hamburger = $("#hamburger");
    if (mobileMenu.classList.contains("open")) {
      hamburger.classList.remove("active");
      mobileMenu.classList.remove("open");
    }

    // Close cart if open
    closeCart();

    navigate(view, { filter, scrollTo });
  });
}

/* ---- PRODUCT CARDS ---- */
function createProductCard(product) {
  const card = document.createElement("div");
  card.className = "product-card";
  card.dataset.productId = product.id;
  const mainImage = product.colors[0].image;

  card.innerHTML = `
    <img class="product-card__img" src="${mainImage}" alt="${product.name}" loading="lazy">
    <div class="product-card__img-mask"></div>
    <div class="product-card__overlay"></div>
    <div class="product-card__info">
      <span class="product-card__collection">${product.collection}</span>
      <h3 class="product-card__name">${product.name}</h3>
      <span class="product-card__price">₹${product.price.toLocaleString("en-IN")}</span>
    </div>
    <div class="product-card__swatches">
      ${product.colors.map(c => `<div class="product-card__swatch" style="background:${c.hex}" title="${c.name}"></div>`).join("")}
    </div>
  `;
  card.addEventListener("click", () => navigate("product", { productId: product.id }));
  return card;
}

function renderTopSellers() {
  const grid = $("#topSellersGrid");
  grid.innerHTML = "";
  topSellerIds.forEach(id => {
    const product = products.find(p => p.id === id);
    if (product) grid.appendChild(createProductCard(product));
  });
}

function renderAllProducts() {
  const grid = $("#allProductsGrid");
  grid.innerHTML = "";
  products.forEach(product => grid.appendChild(createProductCard(product)));
}

function renderShopGrid() {
  const grid = $("#shopGrid");
  grid.innerHTML = "";
  const filtered = activeFilter === "all" ? products : products.filter(p => p.category === activeFilter);
  filtered.forEach((product, i) => {
    const card = createProductCard(product);
    card.style.animationDelay = `${i * 0.08}s`;
    grid.appendChild(card);
  });
  $$(".shop-filter").forEach(btn => btn.classList.toggle("active", btn.dataset.filter === activeFilter));
  setTimeout(initScrollAnimations, 50);
}

function initShopFilters() {
  $$(".shop-filter").forEach(btn => {
    btn.addEventListener("click", () => { activeFilter = btn.dataset.filter; renderShopGrid(); });
  });
}

/* ---- PRODUCT DETAIL ---- */
function renderProductDetail(productId) {
  const product = products.find(p => p.id === productId);
  if (!product) return;
  currentProduct = product;
  currentColorIndex = 0;
  currentSize = product.sizes ? product.sizes[0] : null;
  currentQty = 1;

  $("#productCollection").textContent = product.collection;
  $("#productName").textContent = product.name;
  $("#productPrice").textContent = `₹${product.price.toLocaleString("en-IN")}`;
  $("#productDesc").textContent = product.description;
  updateMainImage();

  const swatchGroup = $("#swatchGroup");
  swatchGroup.innerHTML = "";
  product.colors.forEach((color, i) => {
    const swatch = document.createElement("button");
    swatch.className = `swatch ${i === 0 ? "active" : ""}`;
    swatch.style.background = color.hex;
    swatch.title = color.name;
    swatch.setAttribute("aria-label", color.name);
    swatch.addEventListener("click", () => selectColor(i));
    swatchGroup.appendChild(swatch);
  });
  $("#colorName").textContent = product.colors[0].name;

  const sizeSection = $("#productSizes");
  const sizeGroup = $("#sizeGroup");
  if (product.sizes) {
    sizeSection.style.display = "block";
    sizeGroup.innerHTML = "";
    product.sizes.forEach((size, i) => {
      const btn = document.createElement("button");
      btn.className = `size-btn ${i === 0 ? "active" : ""}`;
      btn.textContent = size;
      btn.addEventListener("click", () => selectSize(size));
      sizeGroup.appendChild(btn);
    });
  } else {
    sizeSection.style.display = "none";
  }

  $("#qtyValue").textContent = "1";
  $$(".accordion-item").forEach(item => item.classList.remove("open"));
}

function selectColor(index) {
  currentColorIndex = index;
  updateMainImage();
  $$(".swatch").forEach((s, i) => s.classList.toggle("active", i === index));
  $("#colorName").textContent = currentProduct.colors[index].name;
}

function updateMainImage() {
  const img = $("#productMainImage");
  const color = currentProduct.colors[currentColorIndex];
  img.style.opacity = "0";
  setTimeout(() => {
    img.src = color.image;
    img.alt = `${currentProduct.name} — ${color.name}`;
    img.style.opacity = "1";
  }, 250);
}

function selectSize(size) {
  currentSize = size;
  $$(".size-btn").forEach(btn => btn.classList.toggle("active", btn.textContent === size));
}

function initQtyControls() {
  $("#qtyMinus").addEventListener("click", () => { if (currentQty > 1) { currentQty--; $("#qtyValue").textContent = currentQty; } });
  $("#qtyPlus").addEventListener("click", () => { if (currentQty < 10) { currentQty++; $("#qtyValue").textContent = currentQty; } });
}

/* ---- CART ---- */
function addToCart(product, colorIndex, size, qty) {
  const color = product.colors[colorIndex];
  const existingIndex = cart.findIndex(item => item.id === product.id && item.color === color.name && item.size === size);
  if (existingIndex >= 0) {
    cart[existingIndex].qty += qty;
  } else {
    cart.push({ id: product.id, name: product.name, color: color.name, colorHex: color.hex, size, qty, price: product.price, image: color.image });
  }
  saveCart();
  updateCartCount();
  showToast(`${product.name} added to bag`);
}

function removeFromCart(index) {
  cart.splice(index, 1);
  saveCart();
  updateCartCount();
  renderCartDrawer();
}

function saveCart() { localStorage.setItem("jemCart", JSON.stringify(cart)); }

function updateCartCount() {
  const count = cart.reduce((sum, item) => sum + item.qty, 0);
  const countEl = $("#cartCount");
  countEl.textContent = count;
  countEl.classList.toggle("visible", count > 0);
}

function renderCartDrawer() {
  const itemsEl = $("#cartItems");
  const footerEl = $("#cartFooter");
  if (cart.length === 0) {
    itemsEl.innerHTML = `<div class="cart-drawer__empty"><p>Your bag is empty</p><a href="#" class="btn btn--outline" data-navigate="shop">Start Shopping</a></div>`;
    footerEl.style.display = "none";
    return;
  }
  itemsEl.innerHTML = cart.map((item, i) => `
    <div class="cart-item">
      <div class="cart-item__img"><img src="${item.image}" alt="${item.name}"></div>
      <div class="cart-item__details">
        <div class="cart-item__name">${item.name}</div>
        <div class="cart-item__meta">${item.color}${item.size ? ", Size " + item.size : ""}</div>
        <div class="cart-item__bottom">
          <span class="cart-item__qty">Qty: ${item.qty}</span>
          <button class="cart-item__remove" data-remove="${i}">Remove</button>
        </div>
      </div>
    </div>
  `).join("");
  $$(".cart-item__remove").forEach(btn => btn.addEventListener("click", () => removeFromCart(parseInt(btn.dataset.remove))));
  const subtotal = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
  $("#cartSubtotal").textContent = `₹${subtotal.toLocaleString("en-IN")}`;
  footerEl.style.display = "block";
}

function openCart() { renderCartDrawer(); $("#cartDrawer").classList.add("open"); document.body.classList.add("no-scroll"); }
function closeCart() { $("#cartDrawer").classList.remove("open"); document.body.classList.remove("no-scroll"); }

function initCart() {
  $("#cartToggle").addEventListener("click", openCart);
  $("#cartCloseBtn").addEventListener("click", closeCart);
  $("#cartClose").addEventListener("click", closeCart);
  $("#addToCart").addEventListener("click", () => { if (currentProduct) addToCart(currentProduct, currentColorIndex, currentSize, currentQty); });
  const handleBuyNow = () => {
    if (!currentProduct) return;
    const color = currentProduct.colors[currentColorIndex];
    sendToWhatsApp([{ name: currentProduct.name, color: color.name, size: currentSize, qty: currentQty, price: currentProduct.price }]);
  };
  $("#buyNow").addEventListener("click", handleBuyNow);
  $("#checkoutWhatsApp").addEventListener("click", () => {
    sendToWhatsApp(cart.map(item => ({ name: item.name, color: item.color, size: item.size, qty: item.qty, price: item.price })));
  });
  updateCartCount();
}

/* ---- WHATSAPP ---- */
function buildWhatsAppMessage(items) {
  let msg = "Hi! I'd like to order:\n\n";
  items.forEach(item => { msg += `• ${item.name} — ${item.color}${item.size ? ", Size " + item.size : ""} x${item.qty}\n`; });
  msg += "\nPlease confirm availability and total. Thank you!";
  return msg;
}

function sendToWhatsApp(items) {
  const message = buildWhatsAppMessage(items);
  window.open(`https://wa.me/${WHATSAPP_NUMBER}?text=${encodeURIComponent(message)}`, "_blank");
}

/* ---- TOAST ---- */
function showToast(msg) {
  const toast = $("#toast");
  $("#toastMsg").textContent = msg;
  toast.classList.add("visible");
  setTimeout(() => toast.classList.remove("visible"), 2500);
}

/* ---- ACCORDION ---- */
function initAccordion() {
  $$(".accordion-header").forEach(header => {
    header.addEventListener("click", () => {
      const item = header.parentElement;
      const wasOpen = item.classList.contains("open");
      $$(".accordion-item").forEach(i => i.classList.remove("open"));
      if (!wasOpen) item.classList.add("open");
    });
  });
}

/* ---- SCROLL REVEAL ---- */
function initScrollAnimations() {
  const reveals = $$(".anim-reveal:not(.revealed)");
  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        const siblings = entry.target.parentElement.querySelectorAll(".anim-reveal:not(.revealed)");
        const idx = Array.from(siblings).indexOf(entry.target);
        setTimeout(() => entry.target.classList.add("revealed"), idx * 100);
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.1, rootMargin: "0px 0px -40px 0px" });
  reveals.forEach(el => observer.observe(el));

  $$(".section__divider:not(.revealed)").forEach(div => {
    const dividerObserver = new IntersectionObserver((entries) => {
      entries.forEach(entry => { if (entry.isIntersecting) { entry.target.classList.add("revealed"); dividerObserver.unobserve(entry.target); } });
    }, { threshold: 0.5 });
    dividerObserver.observe(div);
  });
}

/* ---- TESTIMONIALS SLIDER (Carousel) ---- */
function initTestimonialSlider() {
  const track = $("#testimonialTrack");
  const dotsContainer = $("#testimonialDots");
  const wrapper = track ? track.parentElement : null;
  if (!track || !dotsContainer || !wrapper) return;

  const allSlides = $$(".testimonials-slider__slide", track);
  if (allSlides.length === 0) return;

  let current = 0;
  let autoTimer;

  function getVisible() {
    return window.innerWidth <= 640 ? 1 : 2;
  }

  function getMaxIndex() {
    return Math.max(0, allSlides.length - getVisible());
  }

  function setSlideWidths() {
    const visible = getVisible();
    const containerWidth = wrapper.offsetWidth;
    const gap = parseFloat(getComputedStyle(track).gap) || 24;
    const totalGaps = (visible - 1) * gap;
    const slideWidth = (containerWidth - totalGaps) / visible;

    allSlides.forEach(slide => {
      slide.style.flex = `0 0 ${slideWidth}px`;
      slide.style.maxWidth = `${slideWidth}px`;
    });

    return slideWidth;
  }

  function buildDots() {
    const dotCount = getMaxIndex() + 1;
    dotsContainer.innerHTML = "";
    for (let i = 0; i < dotCount; i++) {
      const dot = document.createElement("button");
      dot.className = `testimonials-slider__dot ${i === current ? "active" : ""}`;
      dot.setAttribute("aria-label", `Slide ${i + 1}`);
      dot.addEventListener("click", () => { goTo(i); resetTimer(); });
      dotsContainer.appendChild(dot);
    }
  }

  function goTo(index) {
    const max = getMaxIndex();
    current = Math.min(Math.max(0, index), max);

    const slide = allSlides[0];
    if (!slide) return;
    const slideWidth = slide.offsetWidth;
    const gap = parseFloat(getComputedStyle(track).gap) || 24;
    const offset = current * (slideWidth + gap);
    track.style.transform = `translateX(-${offset}px)`;

    $$(".testimonials-slider__dot", dotsContainer).forEach((d, i) => {
      d.classList.toggle("active", i === current);
    });
  }

  function next() {
    const max = getMaxIndex();
    goTo(current >= max ? 0 : current + 1);
  }

  function resetTimer() {
    clearInterval(autoTimer);
    autoTimer = setInterval(next, 3500);
  }

  setSlideWidths();
  buildDots();
  goTo(0);
  resetTimer();

  track.addEventListener("mouseenter", () => clearInterval(autoTimer));
  track.addEventListener("mouseleave", resetTimer);

  let startX = 0;
  let isDragging = false;

  track.addEventListener("touchstart", (e) => {
    startX = e.touches[0].clientX;
    isDragging = true;
    clearInterval(autoTimer);
  }, { passive: true });

  track.addEventListener("touchend", (e) => {
    if (!isDragging) return;
    isDragging = false;
    const diff = startX - e.changedTouches[0].clientX;
    if (Math.abs(diff) > 50) {
      diff > 0 ? next() : goTo(current - 1);
    }
    resetTimer();
  }, { passive: true });

  let resizeTimer;
  window.addEventListener("resize", () => {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(() => {
      setSlideWidths();
      buildDots();
      goTo(Math.min(current, getMaxIndex()));
    }, 150);
  });
}

/* ---- INIT ---- */
document.addEventListener("DOMContentLoaded", () => {
  document.body.classList.add("no-scroll");
  initLoader();
  initNav();
  initNavigation();
  initShopFilters();
  initQtyControls();
  initCart();
  initAccordion();
  initTestimonialSlider();
  renderTopSellers();
  renderAllProducts();
});
