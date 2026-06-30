<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Jem Designs & Co</title>
    @php $faviconPath = \App\Models\SiteSetting::get('favicon'); @endphp
    @if($faviconPath && \Illuminate\Support\Facades\Storage::disk('public')->exists($faviconPath))
        <link rel="icon" type="image/{{ pathinfo($faviconPath, PATHINFO_EXTENSION) }}" href="{{ Storage::url($faviconPath) }}">
    @else
        <link rel="icon" type="image/jpeg" href="/images/logo.jpg">
    @endif
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="/css/admin.css">
</head>
<body class="admin-body" id="adminBody">

    {{-- ═══ SIDEBAR (Desktop) ═══ --}}
    <aside class="admin-sidebar" id="adminSidebar">
        <div class="admin-sidebar__header">
            <a href="{{ route('admin.dashboard') }}" class="admin-sidebar__brand">
                <img src="/images/logo.jpg" alt="Jem Designs & Co" class="admin-sidebar__logo">
                <div class="admin-sidebar__brand-text">
                    <span class="admin-sidebar__brand-name">Jem Designs</span>
                    <span class="admin-sidebar__brand-sub">& Co. — Admin</span>
                </div>
            </a>
        </div>

        <nav class="admin-nav">
            <a href="{{ route('admin.dashboard') }}"
               class="admin-nav__item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                Dashboard
            </a>

            <div class="admin-nav__group">
                <span class="admin-nav__group-label">Catalog</span>
                <a href="{{ route('admin.products.index') }}"
                   class="admin-nav__item {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/></svg>
                    Products
                </a>
                <a href="{{ route('admin.attributes.index') }}"
                   class="admin-nav__item admin-nav__item--sub {{ request()->routeIs('admin.attributes.*') ? 'active' : '' }}">
                    Attributes
                </a>
                <a href="{{ route('admin.categories.index') }}"
                   class="admin-nav__item admin-nav__item--sub {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    Categories
                </a>
                <a href="{{ route('admin.collections.index') }}"
                   class="admin-nav__item admin-nav__item--sub {{ request()->routeIs('admin.collections.*') ? 'active' : '' }}">
                    Collections
                </a>
            </div>

            <div class="admin-nav__group">
                <span class="admin-nav__group-label">Sales</span>
                <a href="{{ route('admin.orders.index') }}"
                   class="admin-nav__item {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
                    Orders
                </a>
                <a href="{{ route('admin.inquiries.index') }}"
                   class="admin-nav__item admin-nav__item--sub {{ request()->routeIs('admin.inquiries.*') ? 'active' : '' }}">
                    Inquiries
                </a>
            </div>

            <div class="admin-nav__group">
                <span class="admin-nav__group-label">Content</span>
                <a href="{{ route('admin.homepage.index') }}"
                   class="admin-nav__item {{ request()->routeIs('admin.homepage.*') ? 'active' : '' }}">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                    Homepage
                </a>
                <a href="{{ route('admin.instagram.index') }}"
                   class="admin-nav__item admin-nav__item--sub {{ request()->routeIs('admin.instagram.*') ? 'active' : '' }}">
                    Instagram
                </a>
            </div>

            <div class="admin-nav__group">
                <span class="admin-nav__group-label">Marketing</span>
                <a href="{{ route('admin.marketing.index') }}"
                   class="admin-nav__item {{ request()->routeIs('admin.marketing.*') ? 'active' : '' }}">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                    Sections
                </a>
                <a href="{{ route('admin.testimonials.index') }}"
                   class="admin-nav__item admin-nav__item--sub {{ request()->routeIs('admin.testimonials.*') ? 'active' : '' }}">
                    Testimonials
                </a>
            </div>

            <div class="admin-nav__group">
                <span class="admin-nav__group-label">Settings</span>
                <a href="{{ route('admin.settings.index') }}"
                   class="admin-nav__item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z"/></svg>
                    General
                </a>
            </div>
        </nav>

        <div class="admin-sidebar__footer">
            <a href="/" target="_blank" class="admin-sidebar__view-site">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                View Store
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="admin-sidebar__logout">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                    Sign Out
                </button>
            </form>
            <span class="admin-sidebar__user">{{ Auth::user()->email }}</span>
        </div>
    </aside>

    {{-- ═══ MOBILE OVERLAY ═══ --}}
    <div class="admin-overlay" id="adminOverlay"></div>

    {{-- ═══ MAIN ═══ --}}
    <div class="admin-main">
        <header class="admin-topbar">
            <button class="admin-topbar__toggle" id="sidebarToggle" aria-label="Toggle sidebar">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
            </button>
            <div class="admin-topbar__title">@yield('title', 'Dashboard')</div>
            <div class="admin-topbar__actions">
                {{-- Theme Toggle --}}
                <div class="theme-toggle" id="themeToggle">
                    <button type="button" class="theme-toggle__btn active" id="themeLight" title="Light Mode">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
                    </button>
                    <button type="button" class="theme-toggle__btn" id="themeDark" title="Dark Mode">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>
                    </button>
                </div>
                <a href="/" target="_blank" class="admin-topbar__preview">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                    <span>View Site</span>
                </a>
            </div>
        </header>

        <main class="admin-content">
            @if(session('success'))
                <div class="admin-alert admin-alert--success">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="admin-alert admin-alert--error">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    {{-- ═══ MOBILE BOTTOM NAV ═══ --}}
    <nav class="admin-bottom-nav" id="adminBottomNav">
        <a href="{{ route('admin.dashboard') }}" class="admin-bottom-nav__item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
            <span>Home</span>
        </a>
        <a href="{{ route('admin.products.index') }}" class="admin-bottom-nav__item {{ request()->routeIs('admin.products.*') || request()->routeIs('admin.categories.*') || request()->routeIs('admin.collections.*') ? 'active' : '' }}">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/></svg>
            <span>Products</span>
        </a>
        <a href="{{ route('admin.orders.index') }}" class="admin-bottom-nav__item {{ request()->routeIs('admin.orders.*') || request()->routeIs('admin.inquiries.*') ? 'active' : '' }}">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
            <span>Orders</span>
        </a>
        <a href="{{ route('admin.homepage.index') }}" class="admin-bottom-nav__item {{ request()->routeIs('admin.homepage.*') || request()->routeIs('admin.instagram.*') ? 'active' : '' }}">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            <span>Content</span>
        </a>
        <a href="{{ route('admin.settings.index') }}" class="admin-bottom-nav__item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z"/></svg>
            <span>Settings</span>
        </a>
    </nav>

    <script>
        // Sidebar toggle
        const sidebar = document.getElementById('adminSidebar');
        const overlay = document.getElementById('adminOverlay');
        const toggle  = document.getElementById('sidebarToggle');

        toggle.addEventListener('click', () => {
            sidebar.classList.toggle('open');
            overlay.classList.toggle('visible');
        });

        overlay.addEventListener('click', () => {
            sidebar.classList.remove('open');
            overlay.classList.remove('visible');
        });

        // Theme toggle
        const body = document.getElementById('adminBody');
        const lightBtn = document.getElementById('themeLight');
        const darkBtn  = document.getElementById('themeDark');
        const savedTheme = localStorage.getItem('jemAdminTheme') || 'light';

        function applyTheme(theme) {
            if (theme === 'dark') {
                body.classList.add('admin-dark');
                darkBtn.classList.add('active');
                lightBtn.classList.remove('active');
            } else {
                body.classList.remove('admin-dark');
                lightBtn.classList.add('active');
                darkBtn.classList.remove('active');
            }
            localStorage.setItem('jemAdminTheme', theme);
        }

        applyTheme(savedTheme);

        lightBtn.addEventListener('click', () => applyTheme('light'));
        darkBtn.addEventListener('click', () => applyTheme('dark'));
    </script>
</body>
</html>
