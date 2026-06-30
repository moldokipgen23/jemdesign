@extends('layouts.admin')
@section('title', 'Settings')

@section('content')
<div class="admin-page-header">
    <h1 class="admin-page-header__title">Settings</h1>
</div>

{{-- Tabs --}}
<div style="display:flex;gap:4px;margin-bottom:24px;flex-wrap:wrap">
    @foreach($groups as $group)
    <a href="{{ route('admin.settings.index', ['tab' => $group]) }}"
       class="btn-admin {{ $activeGroup === $group ? 'btn-admin--gold' : 'btn-admin--outline' }} btn-admin--sm"
       style="text-transform:capitalize">
        {{ $group }}
    </a>
    @endforeach
</div>

<div class="admin-card" style="max-width:720px">
    <div class="admin-card__body">
        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="group" value="{{ $activeGroup }}">

            @if($activeGroup === 'general')
                <x-admin-setting-field :settings="$settings" key="brand_name" label="Brand Name" />
                <x-admin-setting-field :settings="$settings" key="tagline" label="Tagline" />
                <x-admin-setting-field :settings="$settings" key="logo" label="Logo" type="file" />
                <x-admin-setting-field :settings="$settings" key="favicon" label="Favicon" type="file" />
                <p style="font-size:11px;color:var(--text-muted);margin-top:-12px;margin-bottom:16px">Square icon shown in browser tabs. Recommended: 32×32px or 64×64px ICO/PNG.</p>

            @elseif($activeGroup === 'content')
                <p style="font-size:12px;color:var(--text-muted);margin-bottom:20px">Manage the images shown on your public homepage and story pages. These update instantly on the live site.</p>
                <x-admin-setting-field :settings="$settings" key="hero_image" label="Hero Banner Image" type="file" />
                <p style="font-size:11px;color:var(--text-muted);margin-top:-12px;margin-bottom:16px">Full-width hero image on the homepage. Recommended: 1920×1080px or larger.</p>
                <x-admin-setting-field :settings="$settings" key="story_image" label="Story Section Image" type="file" />
                <p style="font-size:11px;color:var(--text-muted);margin-top:-12px;margin-bottom:16px">Image shown on the homepage "Our Craft" strip. Recommended: 800×1000px portrait.</p>
                <x-admin-setting-field :settings="$settings" key="founder_image" label="Founder Section Image" type="file" />
                <p style="font-size:11px;color:var(--text-muted);margin-top:-12px;margin-bottom:16px">Image shown in the founder section on the homepage. Also used on the /founder page if founder_photo is empty.</p>

            @elseif($activeGroup === 'contact')
                <x-admin-setting-field :settings="$settings" key="whatsapp_number" label="WhatsApp Number" placeholder="918368873736 (no + or spaces)" />
                <x-admin-setting-field :settings="$settings" key="email" label="Email Address" />

            @elseif($activeGroup === 'social')
                <x-admin-setting-field :settings="$settings" key="instagram_url" label="Instagram URL" />
                <x-admin-setting-field :settings="$settings" key="facebook_url" label="Facebook URL" />

            @elseif($activeGroup === 'founder')
                <x-admin-setting-field :settings="$settings" key="founder_name" label="Founder Name" />
                <x-admin-setting-field :settings="$settings" key="founder_title" label="Title / Role" />
                <x-admin-setting-field :settings="$settings" key="founder_quote" label="Founder Quote" type="textarea" />
                <x-admin-setting-field :settings="$settings" key="founder_photo" label="Founder Photo" type="file" />

            @elseif($activeGroup === 'about')
                <x-admin-setting-field :settings="$settings" key="brand_story" label="Brand Story" type="textarea" />

            @elseif($activeGroup === 'seo')
                <x-admin-setting-field :settings="$settings" key="meta_title" label="Meta Title" />
                <x-admin-setting-field :settings="$settings" key="meta_description" label="Meta Description" type="textarea" />
                <x-admin-setting-field :settings="$settings" key="og_image" label="OG Image" type="file" />

            @elseif($activeGroup === 'checkout')
                <p style="font-size:12px;color:var(--text-muted);margin-bottom:20px">Toggle which payment methods are available to customers at checkout.</p>

                {{-- WhatsApp Toggle --}}
                <div style="padding:16px;background:var(--bg-input);border:1px solid var(--gold-border);border-radius:8px;margin-bottom:12px;display:flex;align-items:center;gap:14px">
                    <div style="width:40px;height:40px;border-radius:8px;background:#25D366;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="#fff"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    </div>
                    <div style="flex:1">
                        <div style="font-size:13px;font-weight:600;color:var(--text)">WhatsApp Checkout</div>
                        <div style="font-size:11px;color:var(--text-muted)">Customers send order via WhatsApp — no online payment needed</div>
                    </div>
                    <label style="position:relative;display:inline-block;width:44px;height:24px;flex-shrink:0">
                        <input type="checkbox" name="payment_whatsapp" value="1" {{ ($settings['payment_whatsapp'] ?? '1') == '1' ? 'checked' : '' }} onchange="this.closest('form').submit()" style="opacity:0;width:0;height:0">
                        <span style="position:absolute;cursor:pointer;inset:0;background:var(--border);border-radius:24px;transition:.3s"></span>
                        <span style="position:absolute;height:18px;width:18px;left:3px;bottom:3px;background:#fff;border-radius:50%;transition:.3s;pointer-events:none"></span>
                    </label>
                </div>

                {{-- Razorpay Toggle --}}
                <div style="padding:16px;background:var(--bg-input);border:1px solid var(--border);border-radius:8px;margin-bottom:12px;display:flex;align-items:center;gap:14px;opacity:0.6">
                    <div style="width:40px;height:40px;border-radius:8px;background:var(--gold);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="#fff"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10" stroke="#fff" stroke-width="1.5"/></svg>
                    </div>
                    <div style="flex:1">
                        <div style="font-size:13px;font-weight:600;color:var(--text)">Razorpay Online Payment</div>
                        <div style="font-size:11px;color:var(--text-muted)">UPI, Cards, Netbanking, Wallets — {{ config('razorpay.key_id') ? 'Ready' : 'Keys not set in .env' }}</div>
                    </div>
                    <label style="position:relative;display:inline-block;width:44px;height:24px;flex-shrink:0">
                        <input type="checkbox" name="payment_razorpay" value="1" {{ ($settings['payment_razorpay'] ?? '0') == '1' ? 'checked' : '' }} onchange="this.closest('form').submit()" style="opacity:0;width:0;height:0" {{ !config('razorpay.key_id') ? 'disabled' : '' }}>
                        <span style="position:absolute;cursor:pointer;inset:0;background:var(--border);border-radius:24px;transition:.3s"></span>
                        <span style="position:absolute;height:18px;width:18px;left:3px;bottom:3px;background:#fff;border-radius:50%;transition:.3s;pointer-events:none"></span>
                    </label>
                </div>

                @if(!config('razorpay.key_id'))
                <div style="padding:12px 16px;background:rgba(201,160,78,0.1);border:1px solid var(--gold-border);border-radius:8px;margin-top:8px">
                    <p style="font-size:12px;color:var(--gold);margin-bottom:4px"><strong>🔧 Setup Razorpay (Coming Soon)</strong></p>
                    <p style="font-size:11px;color:var(--text-muted)">Add your Razorpay API keys to the <code>.env</code> file to enable online payments:<br>
                    <code style="color:var(--text-dim)">RAZORPAY_KEY_ID=rzp_test_xxxxx</code><br>
                    <code style="color:var(--text-dim)">RAZORPAY_KEY_SECRET=xxxxx</code><br>
                    Get keys from <a href="https://dashboard.razorpay.com/app/keys" target="_blank" style="color:var(--gold)">dashboard.razorpay.com</a></p>
                </div>
                @endif

            @elseif($activeGroup === 'whatsapp')
                <p style="font-size:12px;color:var(--text-muted);margin-bottom:20px">Configure how your WhatsApp order messages appear to customers. These settings control the order details template sent when customers checkout via WhatsApp.</p>
                <x-admin-setting-field :settings="$settings" key="wa_business_name" label="Business Name" placeholder="Jem Designs & Co." />
                <x-admin-setting-field :settings="$settings" key="wa_phone" label="WhatsApp Number" placeholder="918368873736 (with country code, no + or spaces)" />
                <p style="font-size:11px;color:var(--text-muted);margin-top:-12px;margin-bottom:16px">Include country code. Example: 918368873736 for India (+91).</p>
                <x-admin-setting-field :settings="$settings" key="wa_greeting" label="Greeting Message" placeholder="Hello! 🙏" />
                <x-admin-setting-field :settings="$settings" key="wa_footer" label="Footer Message" type="textarea" placeholder="Thank you for choosing..." />

                <div style="margin-top:20px;padding:16px;background:var(--bg-input);border:1px solid var(--gold-border);border-radius:4px">
                    <p style="font-size:12px;font-weight:600;color:var(--text);margin-bottom:8px">📋 Order Message Preview</p>
                    <div id="waPreview" style="background:#075e54;color:#fff;padding:16px;border-radius:8px;font-size:13px;line-height:1.6;white-space:pre-wrap;font-family:inherit"></div>
                    <p style="font-size:11px;color:var(--text-muted);margin-top:8px">This is how the order message will appear to you on WhatsApp.</p>
                </div>
                @if(!config('razorpay.key_id'))
                <div style="padding:12px 16px;background:rgba(201,160,78,0.1);border:1px solid var(--gold-border);border-radius:4px;margin-top:8px">
                    <p style="font-size:12px;color:var(--gold);margin-bottom:4px"><strong>Setup Razorpay</strong></p>
                    <p style="font-size:11px;color:var(--text-muted)">Add your Razorpay API keys to the <code>.env</code> file:<br>
                    <code style="color:var(--text-dim)">RAZORPAY_KEY_ID=rzp_test_xxxxx</code><br>
                    <code style="color:var(--text-dim)">RAZORPAY_KEY_SECRET=xxxxx</code><br>
                    Get keys from <a href="https://dashboard.razorpay.com/app/keys" target="_blank" style="color:var(--gold)">dashboard.razorpay.com</a></p>
                </div>
                @endif
            @endif

            @if($activeGroup !== 'checkout')
            <div style="margin-top:8px">
                <button type="submit" class="btn-admin btn-admin--gold">Save {{ ucfirst($activeGroup) }} Settings</button>
            </div>
            @endif
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const group = '{{ $activeGroup }}';
    if (group !== 'whatsapp') return;

    const nameEl = document.querySelector('[name="wa_business_name"]');
    const phoneEl = document.querySelector('[name="wa_phone"]');
    const greetEl = document.querySelector('[name="wa_greeting"]');
    const footerEl = document.querySelector('[name="wa_footer"]');
    const preview = document.getElementById('waPreview');
    if (!preview) return;

    function updatePreview() {
        const name = nameEl?.value || 'Jem Designs & Co.';
        const phone = phoneEl?.value || '918368873736';
        const greet = greetEl?.value || 'Hello! 🙏';
        const footer = footerEl?.value || '';

        const msg = `${greet}

*${name} — Order Details*
━━━━━━━━━━━━━━━━━

*📦 Order #JEM-DEMO1234*

1. Heritage Weave Shirt — Indigo, Size M
   Qty: 1 × ₹2,499 = ₹2,499

2. Tribal Motif Tote Bag — Natural
   Qty: 2 × ₹1,299 = ₹2,598

━━━━━━━━━━━━━━━━━
💰 *Subtotal: ₹5,097*
🚚 *Shipping: Free*
━━━━━━━━━━━━━━━━━
🧾 *TOTAL: ₹5,097*

📍 *Delivery Address:*
John Doe
123 Heritage Lane, Imphal
Manipur — 795001

📞 *Phone:* +91 98765 43210
📧 *Email:* john@example.com

📝 *Notes:* Please gift wrap if possible.

━━━━━━━━━━━━━━━━━
${footer}

🔗 Pay via UPI: upi://pay?pa=${phone}@upi&pn=${encodeURIComponent(name)}&am=5097`;

        preview.textContent = msg;
    }

    [nameEl, phoneEl, greetEl, footerEl].forEach(el => {
        if (el) el.addEventListener('input', updatePreview);
    });
    updatePreview();
});
</script>
@endpush
