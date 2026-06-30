<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Admin Sign In — Jem Designs &amp; Co.</title>
  <link rel="icon" type="image/jpeg" href="{{ asset('images/logo.jpg') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400&family=Montserrat:wght@300;400;500;600&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

    :root {
      --black:      #0B0B0C;
      --card:       #111112;
      --card-2:     #16161A;
      --gold:       #C9A04E;
      --gold-dim:   rgba(201,160,78,0.18);
      --gold-glow:  rgba(201,160,78,0.07);
      --white:      #F2EFE9;
      --white-dim:  rgba(242,239,233,0.75);
      --gray:       #8A857E;
      --gray-dim:   rgba(138,133,126,0.25);
      --border:     rgba(255,255,255,0.07);
      --serif:      'Cormorant Garamond', Georgia, serif;
      --sans:       'Montserrat', 'Helvetica Neue', sans-serif;
      --ease-out:   cubic-bezier(0.22, 1, 0.36, 1);
      --ease-lux:   cubic-bezier(0.16, 1, 0.3, 1);
    }

    html {
      height: 100%;
    }
    body {
      min-height: 100%;
      font-family: var(--sans);
      background: var(--black);
      color: var(--white);
      -webkit-font-smoothing: antialiased;
    }

    /* ── BACKGROUND DIAMONDS ───────────────────────────── */
    .bg-layer {
      position: fixed;
      inset: 0;
      pointer-events: none;
      overflow: hidden;
    }

    /* Radial glow behind card */
    .bg-glow {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      width: 700px;
      height: 700px;
      background: radial-gradient(circle at center,
        rgba(201,160,78,0.09) 0%,
        rgba(201,160,78,0.03) 40%,
        transparent 70%);
      animation: glowPulse 5s ease-in-out infinite;
    }
    @keyframes glowPulse {
      0%, 100% { transform: translate(-50%, -50%) scale(1);   opacity: 0.8; }
      50%       { transform: translate(-50%, -50%) scale(1.12); opacity: 1;   }
    }

    /* Floating diamond shapes */
    .diamond {
      position: absolute;
      fill: none;
      stroke: var(--gold);
      stroke-width: 1.5;
    }

    .d1  { width: 70px;  top: 6%;  left: 4%;   opacity: 0.08; animation: float1 22s ease-in-out infinite; }
    .d2  { width: 32px;  top: 12%; left: 88%;  opacity: 0.06; animation: float2 30s ease-in-out infinite 3s; }
    .d3  { width: 90px;  top: 72%; left: 2%;   opacity: 0.05; animation: float3 26s ease-in-out infinite 1s; }
    .d4  { width: 48px;  top: 82%; left: 90%;  opacity: 0.09; animation: float4 20s ease-in-out infinite 5s; }
    .d5  { width: 22px;  top: 38%; left: 92%;  opacity: 0.07; animation: float1 18s ease-in-out infinite 7s; }
    .d6  { width: 55px;  top: 54%; left: 8%;   opacity: 0.06; animation: float2 34s ease-in-out infinite 2s; }
    .d7  { width: 38px;  top: 25%; left: 55%;  opacity: 0.04; animation: float3 28s ease-in-out infinite 9s; }
    .d8  { width: 18px;  top: 88%; left: 45%;  opacity: 0.07; animation: float4 16s ease-in-out infinite 4s; }
    .d9  { width: 62px;  top: 3%;  left: 70%;  opacity: 0.05; animation: float1 25s ease-in-out infinite 6s; }
    .d10 { width: 26px;  top: 65%; left: 72%;  opacity: 0.08; animation: float2 21s ease-in-out infinite 11s; }

    @keyframes float1 {
      0%,100% { transform: translateY(0)    rotate(0deg);  }
      33%      { transform: translateY(-28px) rotate(20deg); }
      66%      { transform: translateY(-14px) rotate(-10deg); }
    }
    @keyframes float2 {
      0%,100% { transform: translateY(0)    rotate(45deg); }
      40%      { transform: translateY(-36px) rotate(80deg); }
      70%      { transform: translateY(-18px) rotate(30deg); }
    }
    @keyframes float3 {
      0%,100% { transform: translateY(0)    rotate(-15deg); }
      50%      { transform: translateY(-22px) rotate(15deg); }
    }
    @keyframes float4 {
      0%,100% { transform: translateY(0) translateX(0) rotate(0deg); }
      25%      { transform: translateY(-20px) translateX(12px) rotate(35deg); }
      75%      { transform: translateY(-10px) translateX(-8px) rotate(-20deg); }
    }

    /* Thin horizontal lines for depth */
    .bg-line {
      position: absolute;
      left: 0; right: 0;
      height: 1px;
      background: linear-gradient(to right, transparent, var(--gold-glow), var(--gold-dim), var(--gold-glow), transparent);
      opacity: 0;
      animation: lineFade 8s ease-in-out infinite;
    }
    .bg-line--top    { top: 25%; animation-delay: 0s;  }
    .bg-line--bottom { top: 75%; animation-delay: 4s;  }
    @keyframes lineFade {
      0%,100% { opacity: 0; }
      50%      { opacity: 1; }
    }

    /* ── LOGIN SHELL ──────────────────────────────────── */
    .login-wrap {
      position: relative;
      z-index: 10;
      min-height: 100dvh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 24px 16px;
      overflow-y: auto;
    }

    /* ── CARD ─────────────────────────────────────────── */
    .login-card {
      width: 100%;
      max-width: 420px;
      background: var(--card);
      border: 1px solid rgba(201,160,78,0.15);
      border-radius: 4px;
      padding: 48px 44px 44px;
      box-shadow:
        0 0 0 1px rgba(255,255,255,0.03),
        0 20px 60px rgba(0,0,0,0.6),
        0 0 80px rgba(201,160,78,0.04);
      animation: cardIn 0.7s var(--ease-out) both;
    }
    @keyframes cardIn {
      from { opacity: 0; transform: translateY(24px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    /* ── LOGO ─────────────────────────────────────────── */
    .login-logo {
      text-align: center;
      margin-bottom: 28px;
    }
    .login-logo__mark {
      display: inline-block;
      margin-bottom: 4px;
    }
    .login-logo__mark svg {
      width: 140px;
      height: 52px;
    }

    /* ── DIVIDER RULE ─────────────────────────────────── */
    .login-rule {
      display: flex;
      align-items: center;
      gap: 12px;
      margin: 0 0 28px;
      color: var(--gray);
      font-size: 9px;
      font-weight: 500;
      letter-spacing: 0.3em;
      text-transform: uppercase;
    }
    .login-rule::before,
    .login-rule::after {
      content: '';
      flex: 1;
      height: 1px;
      background: var(--border);
    }

    /* ── STATUS MESSAGE ───────────────────────────────── */
    .login-status {
      background: var(--gold-glow);
      border: 1px solid var(--gold-dim);
      border-radius: 2px;
      padding: 10px 14px;
      font-size: 12px;
      color: var(--gold);
      letter-spacing: 0.04em;
      margin-bottom: 20px;
      text-align: center;
    }

    /* ── FORM ─────────────────────────────────────────── */
    .field { margin-bottom: 20px; }
    .field label {
      display: block;
      font-size: 9px;
      font-weight: 500;
      letter-spacing: 0.2em;
      text-transform: uppercase;
      color: var(--gray);
      margin-bottom: 8px;
    }
    .field input {
      width: 100%;
      background: rgba(255,255,255,0.04);
      border: 1px solid rgba(255,255,255,0.1);
      border-radius: 2px;
      padding: 12px 16px;
      font-family: var(--sans);
      font-size: 13px;
      font-weight: 400;
      color: var(--white);
      outline: none;
      transition: border-color 0.25s, box-shadow 0.25s;
      min-height: 46px;
    }
    .field input::placeholder { color: rgba(138,133,126,0.5); }
    .field input:focus {
      border-color: var(--gold);
      box-shadow: 0 0 0 3px rgba(201,160,78,0.08);
    }
    .field-error {
      margin-top: 6px;
      font-size: 11px;
      color: #e57373;
      letter-spacing: 0.03em;
    }

    /* ── REMEMBER ─────────────────────────────────────── */
    .remember {
      margin-bottom: 28px;
    }
    .remember label {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      cursor: pointer;
      font-size: 11px;
      font-weight: 400;
      color: var(--gray);
      letter-spacing: 0.05em;
      min-height: 24px;
    }
    .remember input[type="checkbox"] {
      width: 14px;
      height: 14px;
      accent-color: var(--gold);
      cursor: pointer;
      flex-shrink: 0;
    }

    /* ── SIGN IN BUTTON ───────────────────────────────── */
    .btn-signin {
      width: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      background: var(--gold);
      color: var(--black);
      border: none;
      border-radius: 2px;
      padding: 14px 28px;
      font-family: var(--sans);
      font-size: 11px;
      font-weight: 600;
      letter-spacing: 0.22em;
      text-transform: uppercase;
      cursor: pointer;
      min-height: 50px;
      transition: background 0.3s, transform 0.2s, box-shadow 0.3s;
      position: relative;
      overflow: hidden;
    }
    .btn-signin::after {
      content: '';
      position: absolute;
      inset: 0;
      background: linear-gradient(90deg, transparent 0%, rgba(255,255,255,0.12) 50%, transparent 100%);
      transform: translateX(-100%);
      transition: transform 0.5s;
    }
    .btn-signin:hover { background: #D4AF5E; transform: translateY(-1px); box-shadow: 0 6px 24px rgba(201,160,78,0.3); }
    .btn-signin:hover::after { transform: translateX(100%); }
    .btn-signin:active { transform: translateY(0); }
    .btn-signin svg { flex-shrink: 0; transition: transform 0.3s; }
    .btn-signin:hover svg { transform: translateX(4px); }

    /* ── FOOTER ───────────────────────────────────────── */
    .login-footer {
      margin-top: 24px;
      text-align: center;
    }
    .login-footer a {
      font-size: 11px;
      color: var(--gray);
      letter-spacing: 0.05em;
      text-decoration: none;
      transition: color 0.3s;
    }
    .login-footer a:hover { color: var(--gold); }

    .login-brand-note {
      margin-top: 36px;
      padding-top: 20px;
      border-top: 1px solid var(--border);
      text-align: center;
      font-size: 9px;
      letter-spacing: 0.2em;
      text-transform: uppercase;
      color: rgba(138,133,126,0.45);
    }

    /* ── RESPONSIVE ───────────────────────────────────── */
    @media (max-width: 480px) {
      .login-card { padding: 36px 28px 32px; }
    }
  </style>
</head>
<body>

{{-- ── ANIMATED BACKGROUND ─────────────────── --}}
<div class="bg-layer">
  <div class="bg-glow"></div>

  {{-- Floating diamond shapes —— Kuki-Zo motif --}}
  <svg class="diamond d1"  viewBox="0 0 40 40"><polygon points="20,2 38,20 20,38 2,20"/></svg>
  <svg class="diamond d2"  viewBox="0 0 40 40"><polygon points="20,2 38,20 20,38 2,20"/></svg>
  <svg class="diamond d3"  viewBox="0 0 40 40"><polygon points="20,2 38,20 20,38 2,20"/></svg>
  <svg class="diamond d4"  viewBox="0 0 40 40"><polygon points="20,2 38,20 20,38 2,20"/></svg>
  <svg class="diamond d5"  viewBox="0 0 40 40"><polygon points="20,2 38,20 20,38 2,20"/></svg>
  <svg class="diamond d6"  viewBox="0 0 40 40"><polygon points="20,2 38,20 20,38 2,20"/></svg>
  <svg class="diamond d7"  viewBox="0 0 40 40"><polygon points="20,2 38,20 20,38 2,20"/></svg>
  <svg class="diamond d8"  viewBox="0 0 40 40"><polygon points="20,2 38,20 20,38 2,20"/></svg>
  <svg class="diamond d9"  viewBox="0 0 40 40"><polygon points="20,2 38,20 20,38 2,20"/></svg>
  <svg class="diamond d10" viewBox="0 0 40 40"><polygon points="20,2 38,20 20,38 2,20"/></svg>

  {{-- Horizontal shimmer lines --}}
  <div class="bg-line bg-line--top"></div>
  <div class="bg-line bg-line--bottom"></div>
</div>

{{-- ── LOGIN CARD ───────────────────────────── --}}
<div class="login-wrap">
  <div class="login-card">

    {{-- Logo --}}
    <div class="login-logo">
      <div class="login-logo__mark">
        <svg viewBox="0 0 140 52" xmlns="http://www.w3.org/2000/svg">
          <path d="M38 8 L44 18 L38 28 L32 18 Z" fill="none" stroke="#C9A04E" stroke-width="1.2" opacity="0.8"/>
          <path d="M38 12 L42 18 L38 24 L34 18 Z" fill="#C9A04E" opacity="0.35"/>
          <text x="52" y="38" fill="#F2EFE9" font-family="'Cormorant Garamond', serif" font-size="36" font-weight="600" font-style="italic" letter-spacing="-1">jem</text>
          <text x="53" y="49" fill="#8A857E" font-family="'Montserrat', sans-serif" font-size="6.5" font-weight="500" letter-spacing="3.5">DESIGNS &amp; CO.</text>
        </svg>
      </div>
    </div>

    {{-- Section label --}}
    <div class="login-rule">Admin Portal</div>

    {{-- Session status --}}
    @if (session('status'))
    <div class="login-status">{{ session('status') }}</div>
    @endif

    {{-- Form --}}
    <form method="POST" action="{{ route('login') }}" novalidate>
      @csrf

      <div class="field">
        <label for="email">Email Address</label>
        <input id="email"
               type="email"
               name="email"
               value="{{ old('email') }}"
               placeholder="admin@jemdesigns.com"
               required
               autofocus
               autocomplete="username">
        @error('email')
        <p class="field-error">{{ $message }}</p>
        @enderror
      </div>

      <div class="field">
        <label for="password">Password</label>
        <input id="password"
               type="password"
               name="password"
               placeholder="••••••••••••"
               required
               autocomplete="current-password">
        @error('password')
        <p class="field-error">{{ $message }}</p>
        @enderror
      </div>

      <div class="remember">
        <label>
          <input type="checkbox" name="remember" id="remember_me">
          Keep me signed in
        </label>
      </div>

      <button type="submit" class="btn-signin">
        Sign In
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <line x1="5" y1="12" x2="19" y2="12"/>
          <polyline points="12 5 19 12 12 19"/>
        </svg>
      </button>
    </form>

    @if (Route::has('password.request'))
    <div class="login-footer">
      <a href="{{ route('password.request') }}">Forgot your password?</a>
    </div>
    @endif

    <p class="login-brand-note">Kuki-Zo Heritage Textiles &nbsp;·&nbsp; Northeast India</p>

  </div>
</div>

</body>
</html>
