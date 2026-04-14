<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'SIMABIMA') }} — Sistem Manajemen Arsip</title>
        <meta name="description" content="SIMABIMA — Sistem Manajemen Arsip Digital untuk pengelolaan dokumen yang efisien dan terorganisir.">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700|plus-jakarta-sans:700,800" rel="stylesheet" />

        <style>
            *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
            html { scroll-behavior: smooth; }
            body {
                font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
                background: #f4f6ff;
                color: #1a1a2e;
                min-height: 100vh;
                overflow-x: hidden;
            }

            /* ─── Navbar ──────────────────────────────────── */
            .lp-nav {
                position: fixed; top: 0; left: 0; right: 0; z-index: 100;
                background: rgba(255,255,255,.88);
                backdrop-filter: blur(16px);
                border-bottom: 1px solid rgba(42,56,144,.10);
                padding: .9rem 2rem;
                display: flex; align-items: center; justify-content: space-between;
                gap: 1rem;
            }
            .lp-nav-brand {
                display: flex; align-items: center; gap: .65rem; text-decoration: none;
            }
            .lp-nav-logo {
                width: 2.1rem; height: 2.1rem;
                display: flex; align-items: center; justify-content: center; flex-shrink: 0;
            }
            .lp-logo-img {
                width: 100%; height: 100%; object-fit: contain; display: block;
            }
            .lp-nav-name {
                font-family: 'Plus Jakarta Sans', 'Instrument Sans', sans-serif;
                font-weight: 800; font-size: 1.15rem; letter-spacing: .04em; color: #1e2a6e;
            }
            .lp-nav-actions { display: flex; align-items: center; gap: .75rem; }
            .btn-outline {
                display: inline-flex; align-items: center; gap: .4rem;
                padding: .5rem 1.25rem; border-radius: .5rem; font-size: .875rem; font-weight: 600;
                text-decoration: none; border: 1.5px solid #2A3890; color: #2A3890;
                transition: background .15s, color .15s;
            }
            .btn-outline:hover { background: #2A3890; color: #fff; }
            .btn-primary {
                display: inline-flex; align-items: center; gap: .4rem;
                padding: .5rem 1.4rem; border-radius: .5rem; font-size: .875rem; font-weight: 600;
                text-decoration: none; background: linear-gradient(135deg, #2A3890, #3d50b7);
                color: #fff; border: none; box-shadow: 0 2px 8px rgba(42,56,144,.3);
                transition: opacity .15s, transform .1s;
            }
            .btn-primary:hover { opacity: .88; transform: translateY(-1px); }
            .btn-primary svg, .btn-outline svg { width: 1rem; height: 1rem; flex-shrink: 0; }

            /* ─── Hero ────────────────────────────────────── */
            .lp-hero {
                min-height: 100vh;
                display: flex; align-items: center; justify-content: center;
                padding: 6rem 2rem 4rem;
                position: relative; overflow: hidden;
            }
            /* Decorative background blobs */
            .lp-blob {
                position: absolute; border-radius: 50%; filter: blur(80px);
                pointer-events: none; z-index: 0;
            }
            .lp-blob-1 {
                width: 38rem; height: 38rem;
                background: rgba(42,56,144,.13);
                top: -10rem; right: -8rem;
            }
            .lp-blob-2 {
                width: 28rem; height: 28rem;
                background: rgba(205,171,47,.15);
                bottom: -6rem; left: -6rem;
            }
            .lp-blob-3 {
                width: 20rem; height: 20rem;
                background: rgba(42,56,144,.07);
                top: 30%; left: 5%;
            }

            /* Hero grid layout */
            .lp-hero-inner {
                position: relative; z-index: 1;
                max-width: 72rem; width: 100%;
                display: grid; grid-template-columns: 1fr 1fr; gap: 4rem; align-items: center;
            }
            @media (max-width: 900px) {
                .lp-hero-inner { grid-template-columns: 1fr; gap: 2.5rem; text-align: center; }
                .lp-hero-btns { justify-content: center; }
                .lp-hero-visual { display: none; }
            }

            .lp-pill {
                display: inline-flex; align-items: center; gap: .45rem;
                background: rgba(42,56,144,.09); color: #2A3890;
                border: 1px solid rgba(42,56,144,.2); border-radius: 9999px;
                padding: .3rem .9rem; font-size: .78rem; font-weight: 600;
                letter-spacing: .04em; margin-bottom: 1.2rem;
            }
            .lp-pill span.dot {
                width: .45rem; height: .45rem; border-radius: 50%;
                background: #CDAB2F; display: inline-block;
            }

            .lp-hero-title {
                font-family: 'Plus Jakarta Sans', 'Instrument Sans', sans-serif;
                font-weight: 800; font-size: clamp(2.4rem, 5vw, 3.6rem);
                line-height: 1.15; color: #1e2a6e; margin-bottom: 1.2rem;
            }
            .lp-hero-title em {
                font-style: normal; color: #CDAB2F;
                text-decoration: underline; text-decoration-style: wavy;
                text-decoration-color: rgba(205,171,47,.5);
                text-underline-offset: 5px;
            }
            .lp-hero-desc {
                font-size: 1.05rem; line-height: 1.7; color: #4a5568; margin-bottom: 2rem;
            }
            .lp-hero-btns { display: flex; align-items: center; gap: .9rem; flex-wrap: wrap; }
            .btn-hero-primary {
                display: inline-flex; align-items: center; gap: .55rem;
                padding: .85rem 2rem; border-radius: .65rem; font-size: 1rem; font-weight: 700;
                text-decoration: none; background: linear-gradient(135deg, #2A3890, #3d50b7);
                color: #fff; border: none;
                box-shadow: 0 4px 20px rgba(42,56,144,.35);
                transition: opacity .15s, transform .15s, box-shadow .15s;
            }
            .btn-hero-primary:hover { opacity: .88; transform: translateY(-2px); box-shadow: 0 8px 28px rgba(42,56,144,.4); }
            .btn-hero-primary svg { width: 1.1rem; height: 1.1rem; }
            .btn-hero-secondary {
                display: inline-flex; align-items: center; gap: .5rem;
                padding: .85rem 1.8rem; border-radius: .65rem; font-size: 1rem; font-weight: 600;
                text-decoration: none; border: 2px solid rgba(42,56,144,.25); color: #2A3890;
                transition: border-color .15s, background .15s;
            }
            .btn-hero-secondary:hover { border-color: #2A3890; background: rgba(42,56,144,.05); }
            .btn-hero-secondary svg { width: 1rem; height: 1rem; }

            /* ─── Hero visual (archive illustration) ─────── */
            .lp-hero-visual {
                display: flex; justify-content: flex-end; align-items: center;
            }
            .lp-card-stack {
                position: relative; width: 20rem; height: 20rem;
            }
            .lp-card-stack .stack-card {
                position: absolute; border-radius: 1.2rem;
                box-shadow: 0 8px 32px rgba(42,56,144,.18);
                overflow: hidden;
            }
            .stack-card-back {
                width: 16rem; bottom: 2rem; right: 0;
                background: linear-gradient(135deg, #c9d4ff, #dce3ff);
                height: 14rem; transform: rotate(6deg);
                border: 1.5px solid rgba(42,56,144,.12);
            }
            .stack-card-mid {
                width: 17rem; bottom: .5rem; left: .5rem;
                background: #fff; height: 15rem; transform: rotate(-3deg);
                border: 1.5px solid rgba(42,56,144,.08);
            }
            .stack-card-front {
                width: 17.5rem; bottom: 0; left: 0;
                background: #fff; height: 15.5rem;
                border: 1.5px solid rgba(42,56,144,.12);
            }
            .sc-header {
                background: linear-gradient(90deg, #2A3890, #3d50b7);
                padding: .9rem 1.2rem;
                display: flex; align-items: center; gap: .6rem;
            }
            .sc-header-dot { width: .5rem; height: .5rem; border-radius: 50%; background: rgba(255,255,255,.45); }
            .sc-header-title { font-size: .72rem; font-weight: 700; color: rgba(255,255,255,.85); letter-spacing: .06em; text-transform: uppercase; }
            .sc-body { padding: .9rem 1.2rem; display: flex; flex-direction: column; gap: .55rem; }
            .sc-row {
                display: flex; align-items: center; gap: .6rem;
                padding: .5rem .7rem; border-radius: .5rem;
                background: rgba(42,56,144,.04);
            }
            .sc-row-icon { width: 1.6rem; height: 1.6rem; border-radius: .35rem; flex-shrink: 0; display: flex; align-items: center; justify-content: center; }
            .sc-row-icon svg { width: .85rem; height: .85rem; }
            .sc-row-text { flex: 1; }
            .sc-row-label { font-size: .7rem; font-weight: 600; color: #1e2a6e; line-height: 1.3; }
            .sc-row-meta { font-size: .62rem; color: #9ca3af; margin-top: .05rem; }
            .sc-badge { font-size: .6rem; font-weight: 700; padding: .15rem .5rem; border-radius: 9999px; background: rgba(205,171,47,.15); color: #6b5200; border: 1px solid rgba(205,171,47,.3); flex-shrink: 0; }

            /* floating tags around card */
            .lp-float-tag {
                position: absolute; background: #fff;
                border-radius: .65rem; padding: .5rem .85rem;
                box-shadow: 0 4px 16px rgba(0,0,0,.12);
                display: flex; align-items: center; gap: .45rem;
                font-size: .75rem; font-weight: 600; color: #2A3890;
                border: 1px solid rgba(42,56,144,.12);
                animation: float 3s ease-in-out infinite;
            }
            .lp-float-tag svg { width: .9rem; height: .9rem; flex-shrink: 0; }
            .lp-float-tag-1 { top: -1.2rem; right: -1rem; animation-delay: 0s; }
            .lp-float-tag-2 { bottom: 3rem; right: -2rem; animation-delay: 1s; }
            .lp-float-tag-3 { bottom: 0; left: -2rem; animation-delay: 1.8s; color: #6b5200; border-color: rgba(205,171,47,.2); }
            @keyframes float {
                0%, 100% { transform: translateY(0); }
                50% { transform: translateY(-6px); }
            }

            /* ─── Stats bar ───────────────────────────────── */
            .lp-stats {
                display: flex; align-items: center; gap: 1.5rem; flex-wrap: wrap;
                margin-top: 2.5rem; padding-top: 2rem;
                border-top: 1px solid rgba(42,56,144,.1);
            }
            .lp-stat { display: flex; flex-direction: column; }
            .lp-stat-num {
                font-family: 'Plus Jakarta Sans', sans-serif;
                font-weight: 800; font-size: 1.5rem; color: #2A3890; line-height: 1;
            }
            .lp-stat-label { font-size: .75rem; color: #6b7280; margin-top: .2rem; }
            .lp-stat-divider { width: 1px; height: 2rem; background: rgba(42,56,144,.12); }

            /* ─── Features section ────────────────────────── */
            .lp-features {
                padding: 5rem 2rem;
                background: #fff;
                position: relative; overflow: hidden;
            }
            .lp-features::before {
                content: ''; position: absolute; top: 0; left: 0; right: 0;
                height: 3px; background: linear-gradient(90deg, #2A3890, #CDAB2F, #2A3890);
            }
            .lp-section-center { text-align: center; margin-bottom: 3rem; }
            .lp-section-tag {
                display: inline-block; font-size: .75rem; font-weight: 700; letter-spacing: .08em;
                text-transform: uppercase; color: #2A3890; margin-bottom: .75rem;
                padding: .3rem .9rem; background: rgba(42,56,144,.07); border-radius: 9999px;
            }
            .lp-section-title {
                font-family: 'Plus Jakarta Sans', sans-serif;
                font-weight: 800; font-size: clamp(1.6rem, 3vw, 2.2rem); color: #1e2a6e;
                line-height: 1.25; margin-bottom: .75rem;
            }
            .lp-section-sub { font-size: .95rem; color: #6b7280; max-width: 36rem; margin: 0 auto; line-height: 1.7; }

            .lp-features-grid {
                max-width: 64rem; margin: 0 auto;
                display: grid; grid-template-columns: repeat(auto-fit, minmax(17rem, 1fr)); gap: 1.5rem;
            }
            .lp-feature-card {
                border-radius: 1.1rem; padding: 1.75rem;
                border: 1.5px solid rgba(42,56,144,.1);
                transition: box-shadow .2s, transform .2s, border-color .2s;
                background: #fff;
            }
            .lp-feature-card:hover {
                box-shadow: 0 8px 32px rgba(42,56,144,.12);
                border-color: rgba(42,56,144,.22); transform: translateY(-3px);
            }
            .lp-feature-icon {
                width: 3rem; height: 3rem; border-radius: .75rem; margin-bottom: 1.1rem;
                display: flex; align-items: center; justify-content: center;
            }
            .lp-feature-icon svg { width: 1.4rem; height: 1.4rem; }
            .icon-blue { background: rgba(42,56,144,.1); color: #2A3890; }
            .icon-gold { background: rgba(205,171,47,.14); color: #6b5200; }
            .icon-green { background: rgba(34,197,94,.12); color: rgb(21,128,61); }
            .lp-feature-title { font-weight: 700; font-size: 1.05rem; color: #1e2a6e; margin-bottom: .45rem; }
            .lp-feature-desc { font-size: .88rem; line-height: 1.65; color: #6b7280; }

            /* ─── CTA banner ──────────────────────────────── */
            .lp-cta {
                padding: 5rem 2rem;
                background:
                    linear-gradient(135deg, rgba(30,42,110,.92) 0%, rgba(42,56,144,.9) 60%, rgba(61,80,183,.86) 100%),
                    url('{{ asset('img/bg.jpeg') }}') center / cover no-repeat;
                position: relative; overflow: hidden; text-align: center;
            }
            .lp-cta::after {
                content: '';
                position: absolute; top: -50%; right: -10%; width: 30rem; height: 30rem;
                border-radius: 50%; background: rgba(205,171,47,.1);
                pointer-events: none;
            }
            .lp-cta-inner { position: relative; z-index: 1; max-width: 44rem; margin: 0 auto; }
            .lp-cta-brand {
                display: inline-flex; align-items: center; gap: .65rem;
                margin-bottom: 1.1rem;
                padding: .4rem .9rem;
                border-radius: 9999px;
                background: rgba(255,255,255,.12);
                border: 1px solid rgba(255,255,255,.2);
            }
            .lp-cta-brand-logo {
                width: 1.55rem; height: 1.55rem;
            }
            .lp-cta-brand-name {
                font-size: .8rem;
                font-weight: 700;
                letter-spacing: .08em;
                color: rgba(255,255,255,.9);
            }
            .lp-cta-title {
                font-family: 'Plus Jakarta Sans', sans-serif;
                font-weight: 800; font-size: clamp(1.6rem, 3vw, 2.2rem); color: #fff;
                margin-bottom: .85rem; line-height: 1.25;
            }
            .lp-cta-desc { font-size: .95rem; color: rgba(255,255,255,.72); line-height: 1.7; margin-bottom: 2rem; }
            .btn-cta {
                display: inline-flex; align-items: center; gap: .55rem;
                padding: .95rem 2.2rem; border-radius: .7rem; font-size: 1rem; font-weight: 700;
                text-decoration: none; background: #CDAB2F; color: #1e2a6e;
                box-shadow: 0 4px 20px rgba(205,171,47,.4); border: none;
                transition: opacity .15s, transform .15s;
            }
            .btn-cta:hover { opacity: .88; transform: translateY(-2px); }
            .btn-cta svg { width: 1.1rem; height: 1.1rem; }

            /* ─── Footer ──────────────────────────────────── */
            .lp-footer {
                background: #1e2a6e; color: rgba(255,255,255,.5);
                padding: 1.5rem 2rem; text-align: center; font-size: .8rem;
            }
            .lp-footer-brand {
                display: inline-flex;
                align-items: center;
                gap: .55rem;
                margin-right: .35rem;
                vertical-align: middle;
            }
            .lp-footer-logo {
                width: 1.25rem;
                height: 1.25rem;
            }
            .lp-footer strong { color: rgba(255,255,255,.85); }
        </style>
    </head>
    <body>

        {{-- ── NAVBAR ──────────────────────────────────────────── --}}
        <nav class="lp-nav">
            <a href="#" class="lp-nav-brand">
                <span class="lp-nav-logo">
                    <img src="{{ asset('img/logo.png') }}" alt="Logo SIMABIMA" class="lp-logo-img">
                </span>
                <span class="lp-nav-name">SIMABIMA</span>
            </a>
            <div class="lp-nav-actions">
                @auth
                    <a href="{{ url('/admin') }}" class="btn-primary">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                        Dashboard
                    </a>
                @else
                    <a href="{{ url('/admin/login') }}" class="btn-outline">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                        Login
                    </a>
                @endauth
            </div>
        </nav>

        {{-- ── HERO ─────────────────────────────────────────────── --}}
        <section class="lp-hero">
            <span class="lp-blob lp-blob-1"></span>
            <span class="lp-blob lp-blob-2"></span>
            <span class="lp-blob lp-blob-3"></span>

            <div class="lp-hero-inner">
                {{-- Left: Text content --}}
                <div>
                    <div class="lp-pill">
                        <span class="dot"></span>
                        Sistem Manajemen Arsip Digital
                    </div>

                    <h1 class="lp-hero-title">
                        Kelola <em>Arsip</em><br>
                        Lebih Cerdas<br>
                        &amp; Efisien
                    </h1>

                    <p class="lp-hero-desc">
                        SIMABIMA memudahkan pengelolaan dokumen dan arsip organisasi Anda secara terpusat,
                        terstruktur, dan dapat diakses kapan saja — dari mana saja.
                    </p>

                    <div class="lp-hero-btns">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/admin') }}" class="btn-hero-primary">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                                    Buka Dashboard
                                </a>
                            @else
                                <a href="{{ url('/admin/login') }}" class="btn-hero-primary">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                                    Masuk ke Sistem
                                </a>
                                <a href="#fitur" class="btn-hero-secondary">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 8 12 12 14 14"/></svg>
                                    Pelajari Fitur
                                </a>
                            @endauth
                        @endif
                    </div>

                    <div class="lp-stats">
                        <div class="lp-stat">
                            <span class="lp-stat-num">Multi</span>
                            <span class="lp-stat-label">Unit Organisasi</span>
                        </div>
                        <span class="lp-stat-divider"></span>
                        <div class="lp-stat">
                            <span class="lp-stat-num">100%</span>
                            <span class="lp-stat-label">Berbasis Web</span>
                        </div>
                        <span class="lp-stat-divider"></span>
                        <div class="lp-stat">
                            <span class="lp-stat-num">Aman</span>
                            <span class="lp-stat-label">Berbasis Peran</span>
                        </div>
                    </div>
                </div>

                {{-- Right: Archive card illustration --}}
                <div class="lp-hero-visual">
                    <div style="position:relative; padding: 2.5rem 2.5rem 1rem 1rem;">

                        {{-- Floating tags --}}
                        <div class="lp-float-tag lp-float-tag-1">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                            Terverifikasi
                        </div>
                        <div class="lp-float-tag lp-float-tag-2">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                            Unduh Dokumen
                        </div>
                        <div class="lp-float-tag lp-float-tag-3" style="color:#6b5200">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
                            Eksplorasi Arsip
                        </div>

                        <div class="lp-card-stack">
                            <div class="stack-card stack-card-back"></div>
                            <div class="stack-card stack-card-mid"></div>
                            <div class="stack-card stack-card-front">
                                <div class="sc-header">
                                    <span class="sc-header-dot"></span>
                                    <span class="sc-header-dot"></span>
                                    <span class="sc-header-title">Daftar Arsip</span>
                                </div>
                                <div class="sc-body">
                                    <div class="sc-row">
                                        <div class="sc-row-icon icon-blue">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                                        </div>
                                        <div class="sc-row-text">
                                            <div class="sc-row-label">Surat Keputusan 001</div>
                                            <div class="sc-row-meta">12 Jan 2025 · Bag. Umum</div>
                                        </div>
                                        <span class="sc-badge">SK</span>
                                    </div>
                                    <div class="sc-row">
                                        <div class="sc-row-icon icon-gold">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
                                        </div>
                                        <div class="sc-row-text">
                                            <div class="sc-row-label">Laporan Keuangan Q1</div>
                                            <div class="sc-row-meta">3 Mar 2025 · Keuangan</div>
                                        </div>
                                        <span class="sc-badge">LK</span>
                                    </div>
                                    <div class="sc-row">
                                        <div class="sc-row-icon" style="background:rgba(34,197,94,.1); color:rgb(21,128,61);">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                                        </div>
                                        <div class="sc-row-text">
                                            <div class="sc-row-label">Notulensi Rapat Mei</div>
                                            <div class="sc-row-meta">20 Mei 2025 · Sekretariat</div>
                                        </div>
                                        <span class="sc-badge">NR</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ── CTA BANNER ───────────────────────────────────────── --}}
        <section class="lp-cta">
            <div class="lp-cta-inner">
                <div class="lp-cta-brand">
                    <img src="{{ asset('img/logo.png') }}" alt="Logo SIMABIMA" class="lp-logo-img lp-cta-brand-logo">
                    <span class="lp-cta-brand-name">SIMABIMA</span>
                </div>
                <h2 class="lp-cta-title">Siap Memulai?</h2>
                <p class="lp-cta-desc">
                    Masuk ke sistem SIMABIMA dan mulai kelola arsip organisasi Anda dengan lebih terstruktur dan efisien.
                </p>
            </div>
        </section>

        {{-- ── FOOTER ───────────────────────────────────────────── --}}
        <footer class="lp-footer">
            <span class="lp-footer-brand">
                <img src="{{ asset('img/logo.png') }}" alt="Logo SIMABIMA" class="lp-logo-img lp-footer-logo">
                <strong>SIMABIMA</strong>
            </span>
            &copy; {{ date('Y') }} — Sistem Manajemen Arsip Digital. All rights reserved.
        </footer>

    </body>
</html>
