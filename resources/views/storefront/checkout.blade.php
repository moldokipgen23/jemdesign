@extends('layouts.storefront')
@section('meta_title', 'Checkout — Jem Designs & Co.')

@section('content')
<section class="section" style="padding-top:120px;min-height:100vh">
  <div class="container" style="max-width:800px">

    <span class="section__eyebrow anim-reveal">Secure Checkout</span>
    <h1 class="shop-header__title anim-reveal">Checkout</h1>
    <div class="section__divider anim-reveal"></div>

    {{-- Empty cart notice --}}
    <div id="checkoutEmpty" style="text-align:center;padding:60px 0;display:none">
      <p style="font-family:var(--serif);font-size:24px;color:var(--white-dim);margin-bottom:12px">Your bag is empty</p>
      <p style="font-size:13px;color:var(--gray);margin-bottom:24px">Add some pieces before checking out.</p>
      <a href="{{ route('storefront.shop') }}" class="btn btn--gold">Start Shopping</a>
    </div>

    {{-- Checkout form --}}
    <div id="checkoutForm" style="display:none">

      {{-- Order summary --}}
      <div class="admin-card" style="margin-bottom:24px;background:var(--black-card);border:1px solid var(--border)">
        <div class="admin-card__header" style="border-bottom:1px solid var(--border)">
          <span class="admin-card__title" style="color:var(--white-dim)">Order Summary</span>
          <span id="checkoutItemCount" style="font-size:12px;color:var(--gray)"></span>
        </div>
        <div id="checkoutItems" style="padding:16px 20px"></div>
        <div style="padding:16px 20px;border-top:1px solid var(--border);display:flex;justify-content:space-between;align-items:center">
          <span style="font-size:14px;color:var(--white-dim);font-weight:500">Total</span>
          <span id="checkoutTotal" style="font-size:18px;font-weight:600;color:var(--gold)"></span>
        </div>
      </div>

      {{-- Customer details --}}
      <div class="admin-card" style="margin-bottom:24px;background:var(--black-card);border:1px solid var(--border)">
        <div class="admin-card__header" style="border-bottom:1px solid var(--border)">
          <span class="admin-card__title" style="color:var(--white-dim)">Your Details</span>
        </div>
        <div style="padding:20px;display:flex;flex-direction:column;gap:16px">
          <div>
            <label style="display:block;font-size:10px;font-weight:600;letter-spacing:.12em;text-transform:uppercase;color:var(--gray);margin-bottom:6px">Full Name *</label>
            <input type="text" id="coName" class="admin-input" placeholder="Your full name" required style="width:100%;background:var(--bg-input);border:1px solid var(--border);color:var(--white-dim);padding:10px 14px;border-radius:4px">
          </div>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
            <div>
              <label style="display:block;font-size:10px;font-weight:600;letter-spacing:.12em;text-transform:uppercase;color:var(--gray);margin-bottom:6px">Email *</label>
              <input type="email" id="coEmail" class="admin-input" placeholder="you@example.com" required style="width:100%;background:var(--bg-input);border:1px solid var(--border);color:var(--white-dim);padding:10px 14px;border-radius:4px">
            </div>
            <div>
              <label style="display:block;font-size:10px;font-weight:600;letter-spacing:.12em;text-transform:uppercase;color:var(--gray);margin-bottom:6px">Phone</label>
              <input type="tel" id="coPhone" class="admin-input" placeholder="+91 XXXXX XXXXX" style="width:100%;background:var(--bg-input);border:1px solid var(--border);color:var(--white-dim);padding:10px 14px;border-radius:4px">
            </div>
          </div>
          <div>
            <label style="display:block;font-size:10px;font-weight:600;letter-spacing:.12em;text-transform:uppercase;color:var(--gray);margin-bottom:6px">Shipping Address *</label>
            <textarea id="coAddress" rows="3" placeholder="House/Flat, Street, City, State, PIN code" required style="width:100%;background:var(--bg-input);border:1px solid var(--border);color:var(--white-dim);padding:10px 14px;border-radius:4px;resize:vertical"></textarea>
          </div>
          <div>
            <label style="display:block;font-size:10px;font-weight:600;letter-spacing:.12em;text-transform:uppercase;color:var(--gray);margin-bottom:6px">Order Notes (optional)</label>
            <input type="text" id="coNotes" placeholder="Any special requests" style="width:100%;background:var(--bg-input);border:1px solid var(--border);color:var(--white-dim);padding:10px 14px;border-radius:4px">
          </div>
        </div>
      </div>

      {{-- Payment --}}
      <div id="checkoutError" style="display:none;padding:12px 16px;background:rgba(220,38,38,0.1);border:1px solid rgba(220,38,38,0.3);border-radius:4px;color:#fca5a5;font-size:13px;margin-bottom:16px"></div>

      @php
        $waEnabled = \App\Models\SiteSetting::get('payment_whatsapp', '1') == '1';
        $rzpEnabled = \App\Models\SiteSetting::get('payment_razorpay', '0') == '1';
      @endphp

      @if($waEnabled)
      <button id="waCheckoutBtn" class="btn btn--full" style="font-size:15px;padding:16px;background:#25D366;color:#fff;display:flex;align-items:center;justify-content:center;gap:10px">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="#fff"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
        Order via WhatsApp
      </button>
      @endif

      @if($waEnabled && $rzpEnabled)
      <div style="text-align:center;margin:16px 0;color:var(--gray);font-size:12px">— or —</div>
      @endif

      @if($rzpEnabled)
      <button id="payBtn" class="btn btn--gold btn--full" style="font-size:15px;padding:16px">
        Pay with Razorpay
      </button>
      <p style="text-align:center;font-size:11px;color:var(--gray);margin-top:12px">
        Secure payment powered by Razorpay. UPI, Cards, Netbanking.
      </p>
      @endif

      @if($waEnabled)
      <p style="text-align:center;font-size:11px;color:var(--gray);margin-top:12px">
        Send your order details via WhatsApp — no online payment required.
      </p>
      @endif
    </div>

  </div>
</section>

@if($rzpEnabled)
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
@endif
<script>
(function() {
  const cart = JSON.parse(localStorage.getItem('jemCart')) || [];
  const emptyEl = document.getElementById('checkoutEmpty');
  const formEl  = document.getElementById('checkoutForm');
  const rzpEnabled = {{ $rzpEnabled ? 'true' : 'false' }};

  if (!cart.length) {
    emptyEl.style.display = 'block';
    return;
  }
  formEl.style.display = 'block';

  // Render summary
  const itemsEl = document.getElementById('checkoutItems');
  const total = cart.reduce((s, i) => s + ((i.price || 0) * (i.qty || 1)), 0);

  itemsEl.innerHTML = cart.map(i => `
    <div style="display:flex;align-items:center;gap:14px;padding:12px 0;border-bottom:1px solid rgba(255,255,255,0.06)">
      <img src="${i.image || ''}" style="width:56px;height:72px;object-fit:cover;border-radius:2px;background:var(--bg-input)">
      <div style="flex:1">
        <div style="font-size:13px;color:var(--white-dim);font-weight:500">${i.name}</div>
        <div style="font-size:11px;color:var(--gray);margin-top:2px">${i.color || ''} ${i.size ? '/ ' + i.size : ''} × ${i.qty}</div>
      </div>
      <div style="font-size:13px;font-weight:500;color:var(--gold)">₹${((i.price || 0) * (i.qty || 1)).toLocaleString('en-IN')}</div>
    </div>
  `).join('');

  document.getElementById('checkoutItemCount').textContent = cart.reduce((s, i) => s + (i.qty || 1), 0) + ' item(s)';
  document.getElementById('checkoutTotal').textContent = '₹' + total.toLocaleString('en-IN');

  // WhatsApp Checkout
  document.getElementById('waCheckoutBtn').addEventListener('click', async function() {
    const errorEl = document.getElementById('checkoutError');
    errorEl.style.display = 'none';

    const name    = document.getElementById('coName').value.trim();
    const email   = document.getElementById('coEmail').value.trim();
    const phone   = document.getElementById('coPhone').value.trim();
    const address = document.getElementById('coAddress').value.trim();
    const notes   = document.getElementById('coNotes').value.trim();

    if (!name || !address) {
      errorEl.textContent = 'Please fill in your name and shipping address.';
      errorEl.style.display = 'block';
      return;
    }

    const customerInfo = { name, phone, email, address, notes };
    const message = buildWhatsAppMsg(cart, customerInfo);
    const waNum = '{{ \App\Models\SiteSetting::get("whatsapp_number", "918368873736") }}';

    // Save inquiry
    try {
      await fetch('{{ route("inquiry.store") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
        body: JSON.stringify({ name, phone, cart, notes }),
      });
    } catch (_) { /* proceed even on network failure */ }

    window.open('https://wa.me/' + waNum + '?text=' + encodeURIComponent(message), '_blank');
  });

  // Razorpay (only if enabled)
  const payBtn = document.getElementById('payBtn');
  if (rzpEnabled && payBtn) {
  payBtn.addEventListener('click', async function() {
    const btn = this;
    const errorEl = document.getElementById('checkoutError');
    errorEl.style.display = 'none';

    // Validate form
    const name    = document.getElementById('coName').value.trim();
    const email   = document.getElementById('coEmail').value.trim();
    const phone   = document.getElementById('coPhone').value.trim();
    const address = document.getElementById('coAddress').value.trim();
    const notes   = document.getElementById('coNotes').value.trim();

    if (!name || !email || !address) {
      errorEl.textContent = 'Please fill in your name, email, and shipping address.';
      errorEl.style.display = 'block';
      return;
    }

    btn.disabled = true;
    btn.textContent = 'Processing...';

    try {
      // Create Razorpay order on server
      const res = await fetch('/api/razorpay/create-order', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Accept': 'application/json'
        },
        body: JSON.stringify({ amount: total })
      });

      if (!res.ok) throw new Error('Failed to create payment order');
      const orderData = await res.json();

      const options = {
        key: '{{ $razorpayKey }}',
        amount: orderData.amount,
        currency: orderData.currency,
        name: 'Jem Designs & Co.',
        description: 'Order Payment',
        order_id: orderData.id,
        handler: async function(response) {
          // Payment successful — create order
          try {
            const orderRes = await fetch('{{ route("storefront.checkout.process") }}', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
              },
              body: JSON.stringify({
                razorpay_payment_id:   response.razorpay_payment_id,
                razorpay_order_id:     response.razorpay_order_id,
                razorpay_signature:    response.razorpay_signature,
                cart:                  cart,
                customer_name:         name,
                customer_email:        email,
                customer_phone:        phone,
                shipping_address:      address,
                notes:                 notes,
              })
            });

            const result = await orderRes.json();
            if (result.success) {
              localStorage.removeItem('jemCart');
              window.location.href = result.redirect;
            } else {
              throw new Error(result.error || 'Order failed');
            }
          } catch (e) {
            errorEl.textContent = 'Payment received but order processing failed. Please contact us with your payment ID: ' + response.razorpay_payment_id;
            errorEl.style.display = 'block';
            btn.disabled = false;
            btn.textContent = 'Pay with Razorpay';
          }
        },
        prefill: {
          name:    name,
          email:   email,
          contact: phone,
        },
        theme: {
          color: '#C9A04E',
        },
        modal: {
          ondismiss: function() {
            btn.disabled = false;
            btn.textContent = 'Pay with Razorpay';
          }
        }
      };

      const rzp = new Razorpay(options);
      rzp.on('payment.failed', function(response) {
        errorEl.textContent = 'Payment failed: ' + (response.error?.description || 'Unknown error');
        errorEl.style.display = 'block';
        btn.disabled = false;
        btn.textContent = 'Pay with Razorpay';
      });
      rzp.open();
    } catch (e) {
      errorEl.textContent = e.message || 'Something went wrong. Please try again.';
      errorEl.style.display = 'block';
      btn.disabled = false;
      btn.textContent = 'Pay with Razorpay';
    }
  });
  } // end if(rzpEnabled)
})();
</script>
@endsection
