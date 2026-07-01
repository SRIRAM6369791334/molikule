@extends('layouts.app')


@section('title', 'About | Molikule Green Care')
@section('meta_description', "Molikule Green Care � India's trusted provider of eco-conscious hygiene, laundry care, auto care, and specialty cleaning solutions for homes, institutions, and industries.")
@section('meta_keywords', 'sustainable hygiene solutions India, eco-friendly cleaning products, institutional hygiene products, healthcare disinfectants India, laundry care solutions for hotels, auto care chemicals India, green cleaning company India, specialty chemical company India, commercial cleaning products, facility management hygiene solutions')
@section('meta_description', 'Molikule Green Care — India\'s trusted provider of eco-conscious hygiene, laundry care, auto care, and specialty cleaning solutions for homes, institutions, and industries.')
@section('meta_keywords', 'hygiene training certification India, Molikule NEXUS, specialty chemical R&D India, eco-friendly cleaning formulation research, green chemistry innovation, ZDHC certified cleaning products, GOTS compliant hygiene solutions, OEKO-TEX ECO PASSPORT chemicals India, sustainable hygiene solutions India, eco-friendly cleaning products, institutional hygiene products, healthcare disinfectants India, laundry care solutions for hotels, auto care chemicals India, green cleaning company India, specialty chemical company India, commercial cleaning products, facility management hygiene solutions')

@push('schema')
<script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "Organization",
        "name": "Molikule Green Care",
        "url": "https://molikule.com",
        "logo": "https://molikule.com/assets/images/logo.png",
        "description": "India's trusted provider of eco-conscious hygiene, laundry care, auto care, and specialty cleaning solutions.",
        "contactPoint": {
            "@@type": "ContactPoint",
            "telephone": "+91-8888888888",
            "contactType": "customer service",
            "areaServed": "IN",
            "availableLanguage": "en"
        },
        "areaServed": "India",
        "sameAs": [
            "https://www.facebook.com/molikulegreencare",
            "https://www.instagram.com/molikulegreencare",
            "https://www.linkedin.com/company/molikule"
        ]
    }
</script>
@endpush

@section('content')

{{-- ============================================================
     PREMIUM REDESIGNED ABOUT US PAGE — MOLIKULE GREEN CARE
     Matches the visual system of Home and Careers.
     Combines premium typography, glassmorphic accents, soft
     diffusion shadows, and custom micro-interactions.
     Fully resolves CSS specificity overrides and SEO standards.
============================================================ --}}

<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800;900&family=Roboto:wght@400;500;700&display=swap');

    /* --------------------------------------------------------
       DESIGN SYSTEM & SPECIFICITY OVERRIDES
       -------------------------------------------------------- */
    :root {
        --brand-primary: #bbd700;
        --brand-secondary: #1a9fd4;
        --dark-navy: #0f172a;
        --slate-gray: #64748b;
        --soft-white: #f8fafc;
        --pure-white: #ffffff;
        --lime: #bbd700;
        --lime-dark: #9aaf00;
        --ink: #0f172a;
        --ease-premium: cubic-bezier(0.16, 1, 0.3, 1);
    }

    /* ── Floating ambient blobs (shared with home/careers) ── */
    @keyframes abt-float {

        0%,
        100% {
            transform: translate(0, 0) scale(1);
        }

        50% {
            transform: translate(16px, 24px) scale(1.04);
        }
    }

    .abt-blob {
        position: absolute;
        border-radius: 50%;
        filter: blur(90px);
        z-index: 0;
        pointer-events: none;
        animation: abt-float 22s ease-in-out infinite;
        opacity: 0.12;
    }

    /* Override global internal page specificity issues */
    .internal-page .text-white-force {
        color: var(--pure-white) !important;
    }

    .internal-page .text-dark-force {
        color: var(--dark-navy) !important;
    }

    .internal-page .text-accent-force {
        color: var(--brand-primary) !important;
    }

    .internal-page .text-secondary-force {
        color: var(--brand-secondary) !important;
    }

    .internal-page .text-slate-force {
        color: var(--slate-gray) !important;
    }

    /* Premium Grid & Motion Behaviours */
    .mgc-hero-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 18px;
    }

    @media (max-width: 576px) {
        .mgc-hero-grid {
            grid-template-columns: 1fr !important;
        }
    }

    .mgc-card-hover {
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1) !important;
    }

    .mgc-card-hover:hover {
        transform: translateY(-8px) scale(1.01) !important;
        box-shadow: 0 30px 60px rgba(0, 0, 0, 0.12), 0 10px 20px rgba(0, 0, 0, 0.06) !important;
        border-color: rgba(187, 215, 0, 0.25) !important;
    }

    .mgc-pillar-hover {
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1) !important;
    }

    .mgc-pillar-hover:hover {
        transform: translateY(-10px) !important;
        box-shadow: 0 30px 55px rgba(15, 23, 42, 0.08) !important;
        border-color: rgba(26, 159, 212, 0.2) !important;
    }

    .mgc-cert-hover {
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1) !important;
    }

    .mgc-cert-hover:hover {
        transform: translateY(-8px) scale(1.01) !important;
        box-shadow: 0 30px 60px rgba(0, 0, 0, 0.08), 0 10px 20px rgba(0, 0, 0, 0.03) !important;
    }

    .mgc-cert-hover:hover i,
    .mgc-cert-hover:hover img {
        transform: scale(1.18) rotate(6deg) !important;
    }

    .mgc-cert-hover i,
    .mgc-cert-hover img {
        transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1) !important;
    }

    .mgc-play-btn:hover {
        transform: scale(1.08) !important;
        box-shadow: 0 0 0 20px rgba(187, 215, 0, 0.15) !important;
    }

    /* Roadmap Timeline Styles */
    .hw-roadmap {
        position: relative;
        max-width: 1000px;
        margin: 0 auto;
        padding: 40px 0;
        display: flex;
        flex-direction: column;
    }

    .hw-roadmap__line {
        position: absolute;
        left: 50%;
        top: 0;
        bottom: 0;
        width: 3px;
        background: linear-gradient(to bottom, #bbd700 0%, #1a9fd4 50%, #059669 80%, #ea580c 100%);
        transform: translateX(-50%);
        z-index: 1;
    }

    .hw-roadmap__item {
        position: relative;
        display: flex;
        width: 50%;
        padding: 20px 40px;
        box-sizing: border-box;
        z-index: 2;
    }

    .hw-roadmap__item--left {
        align-self: flex-start;
        justify-content: flex-end;
        text-align: right;
    }

    .hw-roadmap__item--right {
        align-self: flex-end;
        justify-content: flex-start;
        margin-left: 50%;
        text-align: left;
    }

    .hw-roadmap__content {
        background: #ffffff;
        border-radius: 24px;
        padding: 30px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.02), 0 1px 3px rgba(0, 0, 0, 0.01);
        width: 100%;
        max-width: 440px;
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
    }

    .hw-roadmap__content:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 45px rgba(0, 0, 0, 0.07), 0 1px 3px rgba(0, 0, 0, 0.02);
        border-color: rgba(187, 215, 0, 0.35);
    }

    .hw-roadmap__header {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 16px;
    }

    .hw-roadmap__item--left .hw-roadmap__header {
        flex-direction: row-reverse;
    }

    .hw-roadmap__year {
        font-size: 32px;
        font-weight: 900;
        line-height: 1;
    }

    .hw-roadmap__icon {
        width: 46px;
        height: 46px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }

    /* Colors by timeline node */
    .hw-roadmap__item--1 .hw-roadmap__year {
        color: #bbd700;
    }

    .hw-roadmap__item--1 .hw-roadmap__icon {
        background: rgba(187, 215, 0, 0.1);
        color: #9aaf00;
    }

    .hw-roadmap__item--1 .hw-roadmap__content:hover .hw-roadmap__icon {
        background: #bbd700;
        color: #0f172a;
    }

    .hw-roadmap__item--1 .hw-roadmap__dot {
        border: 4px solid #bbd700;
    }

    .hw-roadmap__item--1:hover .hw-roadmap__dot {
        background: #bbd700;
    }

    .hw-roadmap__item--2 .hw-roadmap__year {
        color: #1a9fd4;
    }

    .hw-roadmap__item--2 .hw-roadmap__icon {
        background: rgba(26, 159, 212, 0.1);
        color: #1a9fd4;
    }

    .hw-roadmap__item--2 .hw-roadmap__content:hover .hw-roadmap__icon {
        background: #1a9fd4;
        color: #ffffff;
    }

    .hw-roadmap__item--2 .hw-roadmap__dot {
        border: 4px solid #1a9fd4;
    }

    .hw-roadmap__item--2:hover .hw-roadmap__dot {
        background: #1a9fd4;
    }

    .hw-roadmap__item--3 .hw-roadmap__year {
        color: #059669;
    }

    .hw-roadmap__item--3 .hw-roadmap__icon {
        background: rgba(5, 150, 105, 0.1);
        color: #059669;
    }

    .hw-roadmap__item--3 .hw-roadmap__content:hover .hw-roadmap__icon {
        background: #059669;
        color: #ffffff;
    }

    .hw-roadmap__item--3 .hw-roadmap__dot {
        border: 4px solid #059669;
    }

    .hw-roadmap__item--3:hover .hw-roadmap__dot {
        background: #059669;
    }

    .hw-roadmap__item--4 .hw-roadmap__year {
        color: #ea580c;
    }

    .hw-roadmap__item--4 .hw-roadmap__icon {
        background: rgba(234, 88, 12, 0.1);
        color: #ea580c;
    }

    .hw-roadmap__item--4 .hw-roadmap__content:hover .hw-roadmap__icon {
        background: #ea580c;
        color: #ffffff;
    }

    .hw-roadmap__item--4 .hw-roadmap__dot {
        border: 4px solid #ea580c;
    }

    .hw-roadmap__item--4:hover .hw-roadmap__dot {
        background: #ea580c;
    }

    .hw-roadmap__item--5 .hw-roadmap__year {
        color: #bbd700;
    }

    .hw-roadmap__item--5 .hw-roadmap__icon {
        background: rgba(187, 215, 0, 0.1);
        color: #9aaf00;
    }

    .hw-roadmap__item--5 .hw-roadmap__content:hover .hw-roadmap__icon {
        background: #bbd700;
        color: #0f172a;
    }

    .hw-roadmap__item--5 .hw-roadmap__dot {
        border: 4px solid #bbd700;
    }

    .hw-roadmap__item--5:hover .hw-roadmap__dot {
        background: #bbd700;
    }

    .hw-roadmap__dot {
        position: absolute;
        top: 36px;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background: #ffffff;
        z-index: 3;
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 0 0 4px #ffffff;
    }

    .hw-roadmap__item--left .hw-roadmap__dot {
        right: -9px;
    }

    .hw-roadmap__item--right .hw-roadmap__dot {
        left: -9px;
    }

    .hw-roadmap__item:hover .hw-roadmap__dot {
        transform: scale(1.3);
    }

    .hw-roadmap__body h4 {
        font-size: 18px;
        font-weight: 800;
        margin-bottom: 8px;
    }

    .hw-roadmap__body p {
        font-size: 14px;
        line-height: 1.65;
        margin: 0;
    }

    /* Responsive Roadmap styles */
    @media (max-width: 768px) {
        .hw-roadmap__line {
            left: 20px;
            transform: none;
        }

        .hw-roadmap__item {
            width: 100%;
            padding-left: 55px;
            padding-right: 0;
            text-align: left !important;
        }

        .hw-roadmap__item--left {
            justify-content: flex-start;
        }

        .hw-roadmap__item--right {
            margin-left: 0;
            justify-content: flex-start;
        }

        .hw-roadmap__item--left .hw-roadmap__header {
            flex-direction: row;
        }

        .hw-roadmap__item--left .hw-roadmap__dot,
        .hw-roadmap__item--right .hw-roadmap__dot {
            left: 11px;
            right: auto;
        }

        .hw-roadmap__item:hover .hw-roadmap__dot {
            transform: scale(1.3);
        }
    }

    /* --------------------------------------------------------
       CERTIFICATES MARQUEE — UNIFIED DESIGN SYSTEM
       Matches: careers.blade.php cards, contact.blade.php info cards,
       home.blade.php inner-box pattern.
       -------------------------------------------------------- */
    .cert-section-wrapper {
        position: relative;
        padding: 100px 0;
        background: #f8fafc;
        border-top: 1px solid #e2e8f0;
        border-bottom: 1px solid #e2e8f0;
        overflow: hidden;
        font-family: 'Outfit', sans-serif;
    }

    /* Marquee fade edges */
    .cert-marquee-wrapper {
        display: flex;
        overflow: hidden;
        width: 100%;
        padding: 20px 0;
        mask-image: linear-gradient(to right, transparent, black 8%, black 92%, transparent);
        -webkit-mask-image: linear-gradient(to right, transparent, black 8%, black 92%, transparent);
        white-space: nowrap;
        flex-wrap: nowrap;
    }

    .cert-marquee-track {
        display: flex;
        flex-shrink: 0;
        align-items: stretch;
        width: max-content;
        animation: cert-scroll 40s linear infinite;
        will-change: transform;
    }

    @keyframes cert-scroll {
        0% {
            transform: translateX(0);
        }

        100% {
            transform: translateX(-100%);
        }
    }

    /* Clean unified card — matches .mgc-pillar-hover style */
    .cert-card {
        width: 340px;
        flex-shrink: 0;
        margin: 0 18px;
        background: #ffffff;
        border-radius: 32px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.04), 0 2px 8px rgba(15, 23, 42, 0.02);
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        cursor: default;
        outline: none;
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }

    /* Lime accent bar on top — same as Core Principles pillars */
    .cert-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #bbd700, #9aaf00);
        opacity: 0;
        transition: opacity 0.35s ease;
    }

    .cert-card:hover::before {
        opacity: 1;
    }

    .cert-card:focus-visible {
        outline: 3px solid var(--brand-primary);
        outline-offset: 4px;
    }

    .cert-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 30px 60px rgba(15, 23, 42, 0.08), 0 8px 20px rgba(15, 23, 42, 0.03);
        border-color: rgba(187, 215, 0, 0.30);
    }

    /* Image area */
    .cert-card__img-wrap {
        width: 100%;
        height: 220px;
        background: linear-gradient(135deg, rgba(187, 215, 0, 0.05) 0%, rgba(26, 159, 212, 0.05) 100%);
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 28px;
        transition: background 0.4s ease;
    }

    .cert-card:hover .cert-card__img-wrap {
        background: linear-gradient(135deg, rgba(187, 215, 0, 0.09) 0%, rgba(26, 159, 212, 0.09) 100%);
    }

    .cert-card__img-wrap img {
        max-width: 100%;
        max-height: 160px;
        object-fit: contain;
        transition: transform 0.5s cubic-bezier(0.16, 1, 0.3, 1);
        filter: drop-shadow(0 6px 12px rgba(0, 0, 0, 0.12));
    }

    .cert-card:hover .cert-card__img-wrap img {
        transform: scale(1.1) translateY(-4px);
    }

    /* Text body */
    .cert-card__body {
        padding: 28px 30px 32px;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .cert-card__badge {
        display: inline-block;
        padding: 5px 16px;
        border-radius: 50px;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        width: fit-content;
    }

    .cert-card__name {
        font-size: 17px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.4;
        letter-spacing: -0.3px;
        margin: 0;
        transition: color 0.3s ease;
        white-space: normal;
        word-wrap: break-word;
    }

    .cert-card:hover .cert-card__name {
        color: #9aaf00;
    }
</style>

{{-- =====================================================
         PREMIUM DARK HERO (Matched to careers.blade.php)
    ====================================================== --}}
<section class="page-title" style="padding: 120px 0 0px; background: linear-gradient(135deg, #090d16 0%, #0f172a 100%); position: relative; overflow: hidden;">

    {{-- Glow accents --}}
    <div style="position: absolute; top: -100px; left: 30%; width: 580px; height: 320px;
                    background: radial-gradient(circle, rgba(187,215,0,0.13) 0%, transparent 70%);
                    filter: blur(55px); pointer-events: none;"></div>
    <div style="position: absolute; bottom: -60px; right: 18%; width: 480px; height: 280px;
                    background: radial-gradient(circle, rgba(26,159,212,0.10) 0%, transparent 70%);
                    filter: blur(50px); pointer-events: none;"></div>

    {{-- Subtle dot grid --}}
    <div style="position: absolute; inset: 0; background-image: radial-gradient(rgba(255,255,255,0.04) 1px, transparent 1px); background-size: 28px 28px;"></div>

    <div class="auto-container" style="position: relative; z-index: 10;">
        <div class="content-box" style="text-align: center; max-width: 820px; margin: 0 auto;">

            {{-- Breadcrumb pill --}}
            <ul class="bread-crumb" style="display: inline-flex; gap: 10px; align-items: center; font-weight: 800; font-size: 12px; text-transform: uppercase; letter-spacing: 2px; background: rgba(255,255,255,0.04); padding: 10px 28px; border-radius: 50px; border: 1px solid rgba(255,255,255,0.08); margin-bottom: 28px;">
                <li><a href="{{ route('home') }}" style="text-decoration: none; transition: color 0.3s;">Home</a></li>
                <!-- <li style="color: #bbd700; font-size: 9px;">●</li> -->
                <li style="color: rgba(255,255,255,0.85);">About Us</li>
            </ul>

            {{-- SEO H1 --}}
            <h1 style="font-size: clamp(38px,6vw,64px); font-weight: 900; color: #ffffff; margin-bottom: 22px; letter-spacing: -2px; line-height: 1.08; font-family: 'Outfit', sans-serif;">
                Redefining Hygiene Through <br>
                <span style="background: linear-gradient(90deg, #bbd700, #1a9fd4); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Sustainable Chemistry</span>
            </h1>
            <p style="color: #94a3b8; font-size: 18px; max-width: 620px; margin: 0 auto; line-height: 1.75; font-family: 'Roboto', sans-serif;">
                Advanced cleaning, laundry care, healthcare hygiene, auto care, and institutional solutions — crafted for performance, safety, and a greener planet.
        </div>
    </div>
</section>




{{-- =====================================================
         HERO / MAIN ABOUT SECTION
    ====================================================== --}}
<section class="about-section pt_100 pb_100" style="background: rgba(187,215,0,0.04); overflow: hidden; position: relative; margin-top: 0px; border-radius: 44px 44px 0 0; z-index: 5;">
    {{-- Floating blobs (same as home/careers) --}}
    <div class="abt-blob" style="width:480px;height:480px;top:-80px;right:-80px;background:radial-gradient(circle,#bbd700 0%,transparent 70%);"></div>
    <div class="abt-blob" style="width:360px;height:360px;bottom:-60px;left:-60px;background:radial-gradient(circle,#1a9fd4 0%,transparent 70%);animation-delay:8s;"></div>

    <div class="auto-container">
        <div class="row align-items-center">

            {{-- LEFT: Text --}}
            <div class="col-lg-6 col-md-12 col-sm-12 mb_40">
                <div style=";">
                    <span style="display: inline-block; padding: 8px 22px; background: rgba(187,215,0,0.08); color: #9aaf00; border-radius: 50px; font-weight: 800; font-size: 12px; text-transform: uppercase; margin-bottom: 22px; letter-spacing: 2px; border: 1px solid rgba(187,215,0,0.15);">
                        Our Story
                    </span>
                    <h2 class="text-dark-force" style="font-size: 42px; font-weight: 900; line-height: 1.1; margin-bottom: 28px; letter-spacing: -1px; font-family: 'Outfit', sans-serif;">
                        The Molikule Story — <br><span class="text-accent-force" style="position: relative;">Born from Purpose, Built on Chemistry.
                            <svg style="position: absolute; bottom: -6px; left: 0; width: 100%;" viewBox="0 0 200 8" preserveAspectRatio="none">
                                <path d="M0,6 Q50,0 100,5 Q150,10 200,4" stroke="var(--brand-primary)" stroke-width="2.5" fill="none" opacity="0.5" />
                            </svg>
                        </span>
                    </h2>

                    <p class="text-slate-force" style="font-size: 16px; line-height: 1.8; margin-bottom: 20px; text-align: justify; font-family: 'Roboto', sans-serif;">
                        Molikule Green Care was founded on a singular belief: that chemistry, when applied responsibly, can transform the way the world cleans, cares, and connects with its environment.
                    </p>
                    <p class="text-slate-force" style="font-size: 16px; line-height: 1.8; margin-bottom: 20px; text-align: justify; font-family: 'Roboto', sans-serif;">
                        What began as a purpose-driven initiative has grown into a trusted name in professional hygiene, institutional care, auto care, and specialty chemical formulations. Our journey has been shaped by relentless research, technological advancement, and an unwavering commitment to responsible innovation.
                    </p>
                    <p class="text-slate-force" style="font-size: 16px; line-height: 1.8; margin-bottom: 20px; text-align: justify; font-family: 'Roboto', sans-serif;">
                        We believe chemistry should not just solve industrial challenges — it should contribute to a cleaner, safer, and more sustainable world. Every product in our portfolio reflects this philosophy, combining performance, safety, and sustainability into solutions that industries can rely on every day.
                    </p>
                    <p class="text-dark-force" style="font-size: 16px; line-height: 1.8; margin-bottom: 0; text-align: justify; font-family: 'Roboto', sans-serif; font-weight: 600;">
                        Today, Molikule Green Care continues to expand its footprint across India, delivering advanced hygiene and maintenance solutions that improve operational efficiency, elevate cleanliness standards, and support long-term environmental responsibility.
                    </p>
                </div>
            </div>

            {{-- RIGHT: Image --}}
            <div class="col-lg-6 col-md-12 col-sm-12">
                <div style="position: relative; padding: 20px;">
                    {{-- rotated bg blob --}}
                    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(135deg, var(--brand-primary), #a3c200); border-radius: 40px; transform: rotate(-3deg); z-index: 1; opacity: 0.08;"></div>
                    <div style="position: absolute; top: 8px; left: 8px; width: 100%; height: 100%; background: var(--brand-secondary); border-radius: 40px; transform: rotate(1.5deg); z-index: 0; opacity: 0.05;"></div>

                    <img src="{{ asset('assets/images/banner/banner-2.jpg') }}" alt="Molikule Green Care — sustainable hygiene and cleaning solutions India"
                        style="width: 100%; border-radius: 32px; position: relative; z-index: 2; box-shadow: 0 30px 70px rgba(0,0,0,0.10), 0 10px 20px rgba(0,0,0,0.05); display: block;">

                    {{-- Stats badge --}}
                    <div style="position: absolute; bottom: 48px; right: 8px; background: var(--dark-navy); padding: 22px 28px; border-radius: 22px; color: var(--pure-white); z-index: 3; box-shadow: 0 20px 40px rgba(15,23,42,0.25);">
                        <div class="text-accent-force" style="font-size: 44px; font-weight: 900; line-height: 1;">18+</div>
                        <div style="font-size: 11px; text-transform: uppercase; font-weight: 700; letter-spacing: 2px; color: var(--slate-gray); margin-top: 4px;">Years of Innovation</div>
                    </div>

                    {{-- Clients badge --}}
                    <div style="position: absolute; top: 48px; left: 8px; background: var(--pure-white); padding: 16px 22px; border-radius: 18px; z-index: 3; box-shadow: 0 12px 30px rgba(0,0,0,0.08); border: 1px solid #f1f5f9;">
                        <div class="text-dark-force" style="font-size: 28px; font-weight: 900; line-height: 1;">500+</div>
                        <div class="text-slate-force" style="font-size: 11px; text-transform: uppercase; font-weight: 700; letter-spacing: 1px; margin-top: 3px;">Happy Clients</div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
{{-- =====================================================
         SECTION 2 — WHO WE ARE
    ====================================================== --}}
<section class="about-section pt_100 pb_100" style="background: #ffffff; overflow: hidden; position: relative; margin-top: -80px; border-radius: 44px 44px 0 0; z-index: 5;">
    {{-- Floating blobs --}}
    <div class="abt-blob" style="width:480px;height:480px;top:-80px;right:-80px;background:radial-gradient(circle,#bbd700 0%,transparent 70%);"></div>
    <div class="abt-blob" style="width:360px;height:360px;bottom:-60px;left:-60px;background:radial-gradient(circle,#1a9fd4 0%,transparent 70%);animation-delay:8s;"></div>

    <div class="auto-container">
        <div class="row align-items-center">

            {{-- LEFT: Bold Statement --}}
            <div class="col-lg-5 col-md-12 col-sm-12 mb_40">
                <div style="padding-right: 30px;">
                    <span style="display: inline-block; padding: 8px 22px; background: rgba(187,215,0,0.08); color: #9aaf00; border-radius: 50px; font-weight: 800; font-size: 12px; text-transform: uppercase; margin-bottom: 22px; letter-spacing: 2px; border: 1px solid rgba(187,215,0,0.15);">
                        Who We Are
                    </span>
                    <h2 class="text-dark-force" style="font-size: 42px; font-weight: 900; line-height: 1.15; margin-bottom: 28px; letter-spacing: -1px; font-family: 'Outfit', sans-serif;">
                        "Hygiene is more than cleanliness — it is about creating safer spaces, healthier environments, and a <span class="text-accent-force" style="position: relative;">better future.
                            <svg style="position: absolute; bottom: -6px; left: 0; width: 100%;" viewBox="0 0 200 8" preserveAspectRatio="none">
                                <path d="M0,6 Q50,0 100,5 Q150,10 200,4" stroke="var(--brand-primary)" stroke-width="2.5" fill="none" opacity="0.5" />
                            </svg>
                        </span>"
                    </h2>
                </div>
            </div>

            {{-- RIGHT: Body Copy --}}
            <div class="col-lg-7 col-md-12 col-sm-12">
                <div style="padding: 30px; background: rgba(248,250,252,0.8); border-radius: 32px; border: 1px solid #f1f5f9; box-shadow: 0 20px 40px rgba(0,0,0,0.02);">
                    <p class="text-slate-force" style="font-size: 17px; line-height: 1.8; margin-bottom: 24px; text-align: justify; font-family: 'Roboto', sans-serif;">
                        At <strong class="text-dark-force">Molikule Green Care</strong>, we are a specialty chemistry company dedicated to delivering high-performance, eco-conscious hygiene and cleaning solutions for homes, institutions, and industries across India. Driven by innovation, integrity, and environmental responsibility, every product we create is designed to perform powerfully while treading lightly on the planet.
                    </p>
                    <p class="text-slate-force" style="font-size: 17px; line-height: 1.8; margin-bottom: 24px; text-align: justify; font-family: 'Roboto', sans-serif;">
                        We specialize in advanced cleaning solutions, laundry care, healthcare hygiene, institutional care, and auto care products — engineered for modern industries and evolving lifestyles. Our mission is to help businesses and communities maintain the highest standards of cleanliness while embracing sustainable practices that protect people and the environment.
                    </p>
                    <p class="text-slate-force" style="font-size: 17px; line-height: 1.8; margin: 0; text-align: justify; font-family: 'Roboto', sans-serif;">
                        From hospitals and hotels to automotive care centers, educational institutions, and commercial facilities, Molikule Green Care is the trusted hygiene partner for organizations that demand consistent, reliable, and responsible performance.
                    </p>
                </div>
            </div>

        </div>
    </div>
</section>


{{-- =====================================================
         VISION & MISSION (Side-by-side premium cards)
    ====================================================== --}}
<section class="pt_100 pb_100" style="background: linear-gradient(rgba(247,254,231,0.95),rgba(239,246,255,0.95)); border-top: 1px solid #e2e8f0; border-bottom: 1px solid #e2e8f0; position: relative; overflow: hidden;">
    <div class="abt-blob" style="width:400px;height:400px;top:-120px;left:5%;background:radial-gradient(circle,#bbd700 0%,transparent 70%);opacity:0.08;"></div>
    <div class="abt-blob" style="width:350px;height:350px;bottom:-80px;right:5%;background:radial-gradient(circle,#1a9fd4 0%,transparent 70%);opacity:0.07;animation-delay:6s;"></div>
    <div class="auto-container" style="position:relative;z-index:2;">
        <div class="row g-4">

            {{-- MISSION --}}
            <div class="col-lg-4 col-md-6">
                <div class="mgc-card-hover" style="background: var(--pure-white); padding: 50px 40px; border-radius: 40px;
                                border-shadow: 0 30px 60px rgba(0,0,0,0.04); border: 1px solid #e2e8f0;
                                height: 100%; transition: all 0.4s ease; position: relative; overflow: hidden;">
                    <div style="position: absolute; bottom: -40px; right: -40px; width: 180px; height: 180px;
                                    background: radial-gradient(circle, rgba(187,215,0,0.06) 0%, transparent 70%); border-radius: 50%;"></div>
                    <div style="width: 72px; height: 72px; background: rgba(187,215,0,0.08); border-radius: 22px;
                                    display: flex; align-items: center; justify-content: center; margin-bottom: 28px;
                                    box-shadow: 0 8px 20px rgba(187,215,0,0.12);">
                        <i class="fas fa-rocket" style="font-size: 28px; color: var(--brand-primary);"></i>
                    </div>
                    <span style="display: inline-block; padding: 5px 16px; background: rgba(187,215,0,0.08); color: #9aaf00; border-radius: 50px; font-weight: 800; font-size: 11px; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 16px;">Our Mission</span>
                    <h2 class="text-dark-force" style="font-size: 26px; font-weight: 900; margin-bottom: 18px; line-height: 1.2;">Sustainable Growth Through Innovation.</h2>
                    <p class="text-slate-force" style="font-size: 15px; line-height: 1.85; margin: 0; position: relative; z-index: 1;">
                        To develop high-performance, eco-conscious solutions. To deliver reliable quality through advanced research. To support industries with sustainable, cost-effective systems. To create long-term value through responsible chemistry and continuous innovation.
                    </p>
                </div>
            </div>

            {{-- VISION --}}
            <div class="col-lg-4 col-md-6">
                <div class="mgc-card-hover" style="background: var(--dark-navy); padding: 50px 40px; border-radius: 40px;
                                border: 1px solid rgba(255,255,255,0.05); transition: all 0.4s ease; position: relative; overflow: hidden;
                                box-shadow: 0 30px 60px rgba(15,23,42,0.25); height: 100%;">
                    <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px;
                                    background: radial-gradient(circle, rgba(187,215,0,0.10) 0%, transparent 70%); border-radius: 50%;"></div>
                    <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px;
                                    background: radial-gradient(circle, rgba(26,159,212,0.08) 0%, transparent 70%); border-radius: 50%;"></div>
                    <div style="width: 72px; height: 72px; background: rgba(26,159,212,0.15); border-radius: 22px;
                                    display: flex; align-items: center; justify-content: center; margin-bottom: 28px;
                                    box-shadow: 0 8px 20px rgba(26,159,212,0.15); position: relative; z-index: 1;">
                        <i class="fas fa-lightbulb" style="font-size: 28px; color: var(--brand-secondary);"></i>
                    </div>
                    <span style="display: inline-block; padding: 5px 16px; background: rgba(26,159,212,0.15); color: var(--brand-secondary); border-radius: 50px; font-weight: 800; font-size: 11px; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 16px; position: relative; z-index: 1;">Our Vision</span>
                    <h2 class="text-white-force" style="font-size: 26px; font-weight: 900; margin-bottom: 18px; line-height: 1.2; position: relative; z-index: 1;">Shaping the Future of Hygiene & Specialty Care.</h2>
                    <p class="text-slate-force" style="font-size: 15px; line-height: 1.85; color: #94a3b8 !important; margin: 0; position: relative; z-index: 1;">
                        To become a globally trusted leader in sustainable hygiene, auto care, and specialty cleaning solutions — delivering innovation, safety, and performance across industries and communities.
                    </p>
                </div>
            </div>

            {{-- PURPOSE --}}
            <div class="col-lg-4 col-md-12">
                <div class="mgc-card-hover" style="background: var(--pure-white); padding: 50px 40px; border-radius: 40px;
                                border-shadow: 0 30px 60px rgba(0,0,0,0.04); border: 1px solid #e2e8f0;
                                height: 100%; transition: all 0.4s ease; position: relative; overflow: hidden;">
                    <div style="position: absolute; top: -40px; right: -40px; width: 180px; height: 180px;
                                    background: radial-gradient(circle, rgba(5,150,105,0.06) 0%, transparent 70%); border-radius: 50%;"></div>
                    <div style="width: 72px; height: 72px; background: rgba(5,150,105,0.08); border-radius: 22px;
                                    display: flex; align-items: center; justify-content: center; margin-bottom: 28px;
                                    box-shadow: 0 8px 20px rgba(5,150,105,0.12);">
                        <i class="fas fa-bullseye" style="font-size: 28px; color: #059669;"></i>
                    </div>
                    <span style="display: inline-block; padding: 5px 16px; background: rgba(5,150,105,0.08); color: #059669; border-radius: 50px; font-weight: 800; font-size: 11px; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 16px;">Our Purpose</span>
                    <h2 class="text-dark-force" style="font-size: 26px; font-weight: 900; margin-bottom: 18px; line-height: 1.2;">Empowering Industries Through Innovation.</h2>
                    <p class="text-slate-force" style="font-size: 15px; line-height: 1.85; margin: 0; position: relative; z-index: 1;">
                        To create innovative and sustainable hygiene, maintenance, and specialty chemical solutions that empower industries, improve everyday living, and contribute to a cleaner and healthier future.
                    </p>
                </div>
            </div>

        </div>
    </div>
</section>


{{-- =====================================================
         SECTION 5 — OUR PRODUCT PORTFOLIO
    ====================================================== --}}
<section class="portfolio-section pt_100 pb_100" style="background: #f8fafc; position: relative; overflow: hidden; border-top: 1px solid #e2e8f0; border-bottom: 1px solid #e2e8f0;">
    <div class="abt-blob" style="width:400px;height:400px;top:-120px;left:-100px;background:radial-gradient(circle,#1a9fd4 0%,transparent 70%);opacity:0.06;"></div>
    <div class="abt-blob" style="width:350px;height:350px;bottom:-80px;right:-80px;background:radial-gradient(circle,#bbd700 0%,transparent 70%);opacity:0.06;animation-delay:6s;"></div>

    <div class="auto-container" style="position:relative;z-index:2;">

        {{-- Header --}}
        <div style="text-align: center; max-width: 800px; margin: 0 auto 50px;">
            <span style="display: inline-block; padding: 8px 22px; background: rgba(26,159,212,0.08); color: #1a9fd4; border-radius: 50px; font-weight: 800; font-size: 12px; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 20px; border: 1px solid rgba(26,159,212,0.15);">Our Product Portfolio</span>
            <h2 style="font-size: 38px; font-weight: 900; color: #0f172a; margin-bottom: 20px; font-family: 'Outfit', sans-serif; letter-spacing: -0.5px; line-height: 1.2;">
                Comprehensive Solutions for <br class="d-none d-md-block">
                <span style="color: #1a9fd4; position: relative;">Every Hygiene Challenge
                    <svg style="position: absolute; bottom: -8px; left: 0; width: 100%;" viewBox="0 0 200 8" preserveAspectRatio="none">
                        <path d="M0,6 Q50,0 100,5 Q150,10 200,4" stroke="#1a9fd4" stroke-width="2.5" fill="none" opacity="0.3" />
                    </svg>
                </span>
            </h2>
            <p style="font-size: 17px; line-height: 1.7; color: #475569; font-family: 'Roboto', Arial, sans-serif;">
                Our product portfolio is built around one core promise — solutions that work harder, last longer, and care more. From institutional laundry care to precision auto detailing, each formulation is developed with advanced specialty chemistry to deliver measurable results.
            </p>
        </div>

        {{-- Categories Grid --}}
        <div class="row g-4 mb_60 justify-content-center">
            @foreach($categories as $category)
            @php
            // Map specific descriptions and icons based on category name
            $desc = '';
            $icon = 'fa-pump-soap';
            $catName = strtolower($category->category_name);

            if(strpos($catName, 'laundry') !== false) {
            $desc = 'Professional-grade detergents, fabric softeners, and stain removers engineered for high-volume laundry operations and delicate home fabrics alike.';
            $icon = 'fa-tshirt';
            } elseif(strpos($catName, 'housekeeping') !== false || strpos($catName, 'institutional') !== false) {
            $desc = 'Floor cleaners, surface disinfectants, washroom care, and multi-surface solutions designed for hotels, hospitals, offices, and large commercial spaces.';
            $icon = 'fa-building';
            } elseif(strpos($catName, 'healthcare') !== false || strpos($catName, 'hospital') !== false) {
            $desc = 'Hospital-grade disinfectants, hand sanitisers, and infection-control products formulated to meet the stringent hygiene demands of healthcare environments.';
            $icon = 'fa-hospital-alt';
            } elseif(strpos($catName, 'auto') !== false) {
            $desc = 'Car shampoos, tyre shine, wax polishes, dashboard protectants, and detailing chemicals for professional auto care centres and vehicle enthusiasts.';
            $icon = 'fa-car';
            } elseif(strpos($catName, 'kitchen') !== false || strpos($catName, 'f&b') !== false || strpos($catName, 'food') !== false) {
            $desc = 'Grease-cutting, food-safe cleaners and sanitisers for commercial kitchens, restaurants, and food processing facilities.';
            $icon = 'fa-utensils';
            } elseif(strpos($catName, 'personal') !== false) {
            $desc = 'Gentle, skin-friendly hygiene products developed with the same commitment to safety and eco-conscious chemistry that defines every Molikule formulation.';
            $icon = 'fa-spa';
            } elseif(strpos($catName, 'hygiene') !== false || strpos($catName, 'home') !== false) {
            $desc = 'Complete home hygiene and surface care solutions combining powerful cleaning action with sustainable, non-toxic ingredients.';
            $icon = 'fa-home';
            } else {
            $desc = 'Advanced specialty chemical formulations designed for high performance, safety, and sustainable efficiency.';
            $icon = 'fa-pump-soap';
            }
            @endphp
            <div class="col-lg-4 col-md-6 col-sm-12">
                <a href="{{ $category->shop_url }}" style="display: flex; flex-direction: column; background: #ffffff; padding: 40px 30px; border-radius: 24px; box-shadow: 0 10px 40px rgba(0,0,0,0.04); border: 1px solid #e2e8f0; height: 100%; text-decoration: none; transition: transform 0.3s ease, box-shadow 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 20px 50px rgba(0,0,0,0.08)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 40px rgba(0,0,0,0.04)';">
                    <div style="width: 70px; height: 70px; margin-bottom: 24px; background: rgba(26,159,212,0.08); border-radius: 18px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas {{ $icon }}" style="font-size: 28px; color: #1a9fd4;"></i>
                    </div>
                    <h4 style="font-size: 20px; font-weight: 800; color: #0f172a; margin-bottom: 16px; font-family: 'Outfit', sans-serif;">{{ $category->category_name }}</h4>
                    <p style="font-size: 15px; line-height: 1.7; color: #475569; font-family: 'Roboto', Arial, sans-serif; margin-bottom: 24px; flex-grow: 1;">
                        {{ $desc }}
                    </p>
                    <div style="display: flex; align-items: center; gap: 8px; font-weight: 700; font-size: 14px; color: #1a9fd4; text-transform: uppercase; letter-spacing: 1px;">
                        Explore Products <i class="fas fa-arrow-right" style="font-size: 12px;"></i>
                    </div>
                </a>
            </div>
            @endforeach
        </div>

        {{-- Formulation Promise Badges --}}
        <div style="background: #ffffff; border-radius: 30px; padding: 40px 50px; box-shadow: 0 15px 40px rgba(0,0,0,0.03); border: 1px solid rgba(187,215,0,0.15);">
            <div style="text-align: center; margin-bottom: 30px;">
                <h3 style="font-size: 22px; font-weight: 800; color: #0f172a; font-family: 'Outfit', sans-serif;">The Molikule Formulation Promise</h3>
            </div>
            <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 15px;">
                <div style="padding: 10px 20px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 50px; display: flex; align-items: center; gap: 10px; font-size: 14px; font-weight: 600; color: #334155; box-shadow: 0 4px 10px rgba(0,0,0,0.02);">
                    <i class="fas fa-check-circle" style="color: #9aaf00; font-size: 16px;"></i> Superior cleaning performance
                </div>
                <div style="padding: 10px 20px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 50px; display: flex; align-items: center; gap: 10px; font-size: 14px; font-weight: 600; color: #334155; box-shadow: 0 4px 10px rgba(0,0,0,0.02);">
                    <i class="fas fa-check-circle" style="color: #9aaf00; font-size: 16px;"></i> Long-lasting freshness and hygiene
                </div>
                <div style="padding: 10px 20px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 50px; display: flex; align-items: center; gap: 10px; font-size: 14px; font-weight: 600; color: #334155; box-shadow: 0 4px 10px rgba(0,0,0,0.02);">
                    <i class="fas fa-check-circle" style="color: #9aaf00; font-size: 16px;"></i> Skin-friendly and user-safe application
                </div>
                <div style="padding: 10px 20px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 50px; display: flex; align-items: center; gap: 10px; font-size: 14px; font-weight: 600; color: #334155; box-shadow: 0 4px 10px rgba(0,0,0,0.02);">
                    <i class="fas fa-check-circle" style="color: #9aaf00; font-size: 16px;"></i> Operational efficiency for commercial use
                </div>
                <div style="padding: 10px 20px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 50px; display: flex; align-items: center; gap: 10px; font-size: 14px; font-weight: 600; color: #334155; box-shadow: 0 4px 10px rgba(0,0,0,0.02);">
                    <i class="fas fa-check-circle" style="color: #9aaf00; font-size: 16px;"></i> Sustainable & eco-responsible performance
                </div>
            </div>
        </div>

    </div>
</section>

{{-- =====================================================
         SECTION 6 — INDUSTRIES WE SERVE
    ====================================================== --}}
<section class="pt_100 pb_100" style="background: #ffffff; position: relative; overflow: hidden;">

    <div class="auto-container" style="position:relative; z-index:2;">
        <div style="text-align: center; max-width: 800px; margin: 0 auto 60px;">
            <h2 style="font-size: 36px; font-weight: 700; color: #1368B4; margin-bottom: 18px; font-family: 'Outfit', sans-serif;">
                Trusted Across India's Most Demanding Environments
            </h2>
            <p style="font-size: 16px; line-height: 1.7; color: #2D3748; font-family: 'Roboto', Arial, sans-serif;">
                Molikule Green Care serves a diverse spectrum of industries where hygiene, safety, and reliability are non-negotiable. Our solutions are field-tested in environments that demand consistent, high-performing products day after day.
            </p>
        </div>

        <div class="row g-4 text-center justify-content-center">
            {{-- Industry 1 --}}
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div style="padding: 40px 20px 30px; background: #ffffff; border-radius: 20px; border-top: 3px solid #3b82f6; box-shadow: 0 10px 40px rgba(0,0,0,0.05); height: 100%; transition: transform 0.3s ease;">
                    <div style="width: 90px; height: 90px; margin: 0 auto 15px; display: flex; align-items: center; justify-content: center; background: radial-gradient(circle, rgba(59,130,246,0.15) 0%, transparent 60%);">
                        <i class="fas fa-hospital" style="color: #3b82f6; font-size: 26px;"></i>
                    </div>
                    <h4 style="font-family: 'Outfit', sans-serif; font-weight: 800; font-size: 15px; color: #1a1a1a; margin: 0; line-height: 1.4;">Hospitals &<br>Healthcare</h4>
                </div>
            </div>

            {{-- Industry 2 --}}
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div style="padding: 40px 20px 30px; background: #ffffff; border-radius: 20px; border-top: 3px solid #f59e0b; box-shadow: 0 10px 40px rgba(0,0,0,0.05); height: 100%; transition: transform 0.3s ease;">
                    <div style="width: 90px; height: 90px; margin: 0 auto 15px; display: flex; align-items: center; justify-content: center; background: radial-gradient(circle, rgba(245,158,11,0.15) 0%, transparent 60%);">
                        <i class="fas fa-hotel" style="color: #f59e0b; font-size: 26px;"></i>
                    </div>
                    <h4 style="font-family: 'Outfit', sans-serif; font-weight: 800; font-size: 15px; color: #1a1a1a; margin: 0; line-height: 1.4;">Hotels &<br>Hospitality</h4>
                </div>
            </div>

            {{-- Industry 3 --}}
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div style="padding: 40px 20px 30px; background: #ffffff; border-radius: 20px; border-top: 3px solid #8b5cf6; box-shadow: 0 10px 40px rgba(0,0,0,0.05); height: 100%; transition: transform 0.3s ease;">
                    <div style="width: 90px; height: 90px; margin: 0 auto 15px; display: flex; align-items: center; justify-content: center; background: radial-gradient(circle, rgba(139,92,246,0.15) 0%, transparent 60%);">
                        <i class="fas fa-building" style="color: #8b5cf6; font-size: 26px;"></i>
                    </div>
                    <h4 style="font-family: 'Outfit', sans-serif; font-weight: 800; font-size: 15px; color: #1a1a1a; margin: 0; line-height: 1.4;">Commercial<br>Buildings & Malls</h4>
                </div>
            </div>

            {{-- Industry 4 --}}
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div style="padding: 40px 20px 30px; background: #ffffff; border-radius: 20px; border-top: 3px solid #10b981; box-shadow: 0 10px 40px rgba(0,0,0,0.05); height: 100%; transition: transform 0.3s ease;">
                    <div style="width: 90px; height: 90px; margin: 0 auto 15px; display: flex; align-items: center; justify-content: center; background: radial-gradient(circle, rgba(16,185,129,0.15) 0%, transparent 60%);">
                        <i class="fas fa-graduation-cap" style="color: #10b981; font-size: 26px;"></i>
                    </div>
                    <h4 style="font-family: 'Outfit', sans-serif; font-weight: 800; font-size: 15px; color: #1a1a1a; margin: 0; line-height: 1.4;">Educational<br>Institutions</h4>
                </div>
            </div>

            {{-- Industry 5 --}}
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div style="padding: 40px 20px 30px; background: #ffffff; border-radius: 20px; border-top: 3px solid #f43f5e; box-shadow: 0 10px 40px rgba(0,0,0,0.05); height: 100%; transition: transform 0.3s ease;">
                    <div style="width: 90px; height: 90px; margin: 0 auto 15px; display: flex; align-items: center; justify-content: center; background: radial-gradient(circle, rgba(244,63,94,0.15) 0%, transparent 60%);">
                        <i class="fas fa-industry" style="color: #f43f5e; font-size: 26px;"></i>
                    </div>
                    <h4 style="font-family: 'Outfit', sans-serif; font-weight: 800; font-size: 15px; color: #1a1a1a; margin: 0; line-height: 1.4;">Industrial<br>Facilities</h4>
                </div>
            </div>

            {{-- Industry 6 --}}
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div style="padding: 40px 20px 30px; background: #ffffff; border-radius: 20px; border-top: 3px solid #0ea5e9; box-shadow: 0 10px 40px rgba(0,0,0,0.05); height: 100%; transition: transform 0.3s ease;">
                    <div style="width: 90px; height: 90px; margin: 0 auto 15px; display: flex; align-items: center; justify-content: center; background: radial-gradient(circle, rgba(14,165,233,0.15) 0%, transparent 60%);">
                        <i class="fas fa-tshirt" style="color: #0ea5e9; font-size: 26px;"></i>
                    </div>
                    <h4 style="font-family: 'Outfit', sans-serif; font-weight: 800; font-size: 15px; color: #1a1a1a; margin: 0; line-height: 1.4;">Laundry &<br>Linen Services</h4>
                </div>
            </div>

            {{-- Industry 7 --}}
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div style="padding: 40px 20px 30px; background: #ffffff; border-radius: 20px; border-top: 3px solid #bbd700; box-shadow: 0 10px 40px rgba(0,0,0,0.05); height: 100%; transition: transform 0.3s ease;">
                    <div style="width: 90px; height: 90px; margin: 0 auto 15px; display: flex; align-items: center; justify-content: center; background: radial-gradient(circle, rgba(187,215,0,0.2) 0%, transparent 60%);">
                        <i class="fas fa-car" style="color: #9aaf00; font-size: 26px;"></i>
                    </div>
                    <h4 style="font-family: 'Outfit', sans-serif; font-weight: 800; font-size: 15px; color: #1a1a1a; margin: 0; line-height: 1.4;">Automotive &<br>Auto Detailing</h4>
                </div>
            </div>

            {{-- Industry 8 --}}
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div style="padding: 40px 20px 30px; background: #ffffff; border-radius: 20px; border-top: 3px solid #64748b; box-shadow: 0 10px 40px rgba(0,0,0,0.05); height: 100%; transition: transform 0.3s ease;">
                    <div style="width: 90px; height: 90px; margin: 0 auto 15px; display: flex; align-items: center; justify-content: center; background: radial-gradient(circle, rgba(100,116,139,0.15) 0%, transparent 60%);">
                        <i class="fas fa-briefcase" style="color: #64748b; font-size: 26px;"></i>
                    </div>
                    <h4 style="font-family: 'Outfit', sans-serif; font-weight: 800; font-size: 15px; color: #1a1a1a; margin: 0; line-height: 1.4;">Corporate Offices<br>& FM Companies</h4>
                </div>
            </div>

        </div>
    </div>
</section>


{{-- =====================================================
         SECTION 7 — WHY CHOOSE MOLIKULE GREEN CARE
    ====================================================== --}}
<section class="pt_100 pb_100" style="background: #f8fafc; border-top: 1px solid #e2e8f0; border-bottom: 1px solid #e2e8f0; position: relative; overflow: hidden;">
    <div class="abt-blob" style="width:420px;height:420px;top:-100px;left:-80px;background:radial-gradient(circle,#059669 0%,transparent 70%);opacity:0.06;"></div>
    <div class="abt-blob" style="width:380px;height:380px;bottom:-80px;right:-60px;background:radial-gradient(circle,#bbd700 0%,transparent 70%);opacity:0.06;animation-delay:11s;"></div>
    <div class="auto-container" style="position: relative; z-index: 2;">

        <div style="text-align: center; max-width: 700px; margin: 0 auto 70px;">
            <span style="display: inline-block; padding: 8px 22px; background: rgba(5,150,105,0.08); color: #059669; border-radius: 50px; font-weight: 800; font-size: 12px; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 18px; border: 1px solid rgba(5,150,105,0.15);">Why Choose Us</span>
            <h2 class="text-dark-force" style="font-size: 42px; font-weight: 900; margin-bottom: 18px; letter-spacing: -0.5px; font-family: 'Outfit', sans-serif;">Six Reasons the Best <span style="color: #059669;">Institutions</span> Choose Us</h2>
        </div>

        <div class="row g-4 text-center">

            {{-- Reason 1 --}}
            <div class="col-lg-4 col-md-6">
                <div class="mgc-pillar-hover" style="background: #f7fee7; padding: 40px 30px; border-radius: 36px;
                                border: 1px solid rgba(132,204,22,0.15); height: 100%; transition: all 0.4s ease; position: relative; overflow: hidden;">
                    <div style="width: 72px; height: 72px; background: #ffffff; border-radius: 50%;
                                    display: flex; align-items: center; justify-content: center; margin: 0 auto 24px;
                                    box-shadow: 0 10px 25px rgba(132,204,22,0.15);">
                        <i class="fas fa-flask" style="font-size: 26px; color: #65a30d;"></i>
                    </div>
                    <h4 class="text-dark-force" style="font-size: 20px; font-weight: 900; margin-bottom: 14px; line-height: 1.3;">Innovation-Driven<br>Formulations</h4>
                    <p class="text-slate-force" style="font-size: 15px; line-height: 1.7; margin: 0;">
                        We invest continuously in research and specialty chemistry to develop solutions that address modern hygiene challenges — from drug-resistant pathogens in hospitals to stubborn engine grime in auto care.
                    </p>
                </div>
            </div>

            {{-- Reason 2 --}}
            <div class="col-lg-4 col-md-6">
                <div class="mgc-pillar-hover" style="background: #ecfdf5; padding: 40px 30px; border-radius: 36px;
                                border: 1px solid rgba(16,185,129,0.15); height: 100%; transition: all 0.4s ease; position: relative; overflow: hidden;">
                    <div style="width: 72px; height: 72px; background: #ffffff; border-radius: 50%;
                                    display: flex; align-items: center; justify-content: center; margin: 0 auto 24px;
                                    box-shadow: 0 10px 25px rgba(16,185,129,0.15);">
                        <i class="fas fa-leaf" style="font-size: 26px; color: #059669;"></i>
                    </div>
                    <h4 class="text-dark-force" style="font-size: 20px; font-weight: 900; margin-bottom: 14px; line-height: 1.3;">Sustainable Chemistry<br>at the Core</h4>
                    <p class="text-slate-force" style="font-size: 15px; line-height: 1.7; margin: 0;">
                        Eco-conscious design is not an afterthought at Molikule — it is our starting point. Every formulation is developed to minimise environmental impact without compromising on efficacy.
                    </p>
                </div>
            </div>

            {{-- Reason 3 --}}
            <div class="col-lg-4 col-md-6">
                <div class="mgc-pillar-hover" style="background: #f7fee7; padding: 40px 30px; border-radius: 36px;
                                border: 1px solid rgba(132,204,22,0.15); height: 100%; transition: all 0.4s ease; position: relative; overflow: hidden;">
                    <div style="width: 72px; height: 72px; background: #ffffff; border-radius: 50%;
                                    display: flex; align-items: center; justify-content: center; margin: 0 auto 24px;
                                    box-shadow: 0 10px 25px rgba(132,204,22,0.15);">
                        <i class="fas fa-check-circle" style="font-size: 26px; color: #65a30d;"></i>
                    </div>
                    <h4 class="text-dark-force" style="font-size: 20px; font-weight: 900; margin-bottom: 14px; line-height: 1.3;">Reliable, Consistent<br>Performance</h4>
                    <p class="text-slate-force" style="font-size: 15px; line-height: 1.7; margin: 0;">
                        Industries depend on products that perform the same way every time. Our quality control ensures that every batch of every product meets the same rigorous standards.
                    </p>
                </div>
            </div>

            {{-- Reason 4 --}}
            <div class="col-lg-4 col-md-6">
                <div class="mgc-pillar-hover" style="background: #ecfdf5; padding: 40px 30px; border-radius: 36px;
                                border: 1px solid rgba(16,185,129,0.15); height: 100%; transition: all 0.4s ease; position: relative; overflow: hidden;">
                    <div style="width: 72px; height: 72px; background: #ffffff; border-radius: 50%;
                                    display: flex; align-items: center; justify-content: center; margin: 0 auto 24px;
                                    box-shadow: 0 10px 25px rgba(16,185,129,0.15);">
                        <i class="fas fa-box-open" style="font-size: 26px; color: #059669;"></i>
                    </div>
                    <h4 class="text-dark-force" style="font-size: 20px; font-weight: 900; margin-bottom: 14px; line-height: 1.3;">Comprehensive<br>Product Portfolio</h4>
                    <p class="text-slate-force" style="font-size: 15px; line-height: 1.7; margin: 0;">
                        From a single source, you get solutions for laundry, housekeeping, healthcare hygiene, kitchen care, personal care, and auto detailing — simplifying your procurement.
                    </p>
                </div>
            </div>

            {{-- Reason 5 --}}
            <div class="col-lg-4 col-md-6">
                <div class="mgc-pillar-hover" style="background: #f7fee7; padding: 40px 30px; border-radius: 36px;
                                border: 1px solid rgba(132,204,22,0.15); height: 100%; transition: all 0.4s ease; position: relative; overflow: hidden;">
                    <div style="width: 72px; height: 72px; background: #ffffff; border-radius: 50%;
                                    display: flex; align-items: center; justify-content: center; margin: 0 auto 24px;
                                    box-shadow: 0 10px 25px rgba(132,204,22,0.15);">
                        <i class="fas fa-handshake" style="font-size: 26px; color: #65a30d;"></i>
                    </div>
                    <h4 class="text-dark-force" style="font-size: 20px; font-weight: 900; margin-bottom: 14px; line-height: 1.3;">Customer-Centric<br>Partnership</h4>
                    <p class="text-slate-force" style="font-size: 15px; line-height: 1.7; margin: 0;">
                        We don't just supply products — we work alongside you. Our team provides technical support, dosage guidance, and long-term partnership to help your operations thrive.
                    </p>
                </div>
            </div>

            {{-- Reason 6 --}}
            <div class="col-lg-4 col-md-6">
                <div class="mgc-pillar-hover" style="background: #ecfdf5; padding: 40px 30px; border-radius: 36px;
                                border: 1px solid rgba(16,185,129,0.15); height: 100%; transition: all 0.4s ease; position: relative; overflow: hidden;">
                    <div style="width: 72px; height: 72px; background: #ffffff; border-radius: 50%;
                                    display: flex; align-items: center; justify-content: center; margin: 0 auto 24px;
                                    box-shadow: 0 10px 25px rgba(16,185,129,0.15);">
                        <i class="fas fa-shield-alt" style="font-size: 26px; color: #059669;"></i>
                    </div>
                    <h4 class="text-dark-force" style="font-size: 20px; font-weight: 900; margin-bottom: 14px; line-height: 1.3;">Commitment to<br>Safety & Quality</h4>
                    <p class="text-slate-force" style="font-size: 15px; line-height: 1.7; margin: 0;">
                        Every Molikule product is formulated to be safe for users, surfaces, and the environment. Safety data and compliance documentation come standard.
                    </p>
                </div>
            </div>

        </div>

    </div>
</section>



{{-- =====================================================
         SECTION 8 — RESEARCH & DEVELOPMENT
    ====================================================== --}}
<section class="pt_100 pb_100" style="background: var(--pure-white); position: relative; overflow: hidden;">
    <div class="abt-blob" style="width:500px;height:500px;top:-150px;right:-100px;background:radial-gradient(circle,#1a9fd4 0%,transparent 70%);opacity:0.04;"></div>
    <div class="abt-blob" style="width:400px;height:400px;bottom:-100px;left:-50px;background:radial-gradient(circle,#bbd700 0%,transparent 70%);opacity:0.04;animation-delay:4s;"></div>

    <div class="auto-container" style="position: relative; z-index: 2;">
        <div class="row align-items-center">
            {{-- Left Side: Image and Stat --}}
            <div class="col-lg-5 mb-5 mb-lg-0">
                <div style="position: relative; border-radius: 36px; overflow: hidden; box-shadow: 0 30px 60px rgba(15,23,42,0.08);">
                    <img src="{{ asset('assets/images/Research-Development-Capabilities.webp') }}" alt="Molikule Green Care specialty chemistry manufacturing facility India" style="width: 100%; height: 550px; object-fit: cover; display: block; filter: contrast(1.05) saturate(1.1);">

                    {{-- Subtle Stat Overlay --}}
                    <div style="position: absolute; bottom: 30px; left: -20px; background: rgba(255,255,255,0.95); backdrop-filter: blur(10px); padding: 24px 32px; border-radius: 0 24px 24px 0; border-left: 6px solid #1a9fd4; box-shadow: 0 15px 35px rgba(0,0,0,0.1);">
                        <div style="display: flex; align-items: center; gap: 16px;">
                            <div style="font-size: 48px; font-weight: 900; color: #1a9fd4; line-height: 1; font-family: 'Outfit', sans-serif;">17<span style="font-size: 32px;">+</span></div>
                            <div style="font-size: 15px; font-weight: 700; color: var(--dark-navy); line-height: 1.3; text-transform: uppercase; letter-spacing: 1px;">Years of Formulation<br>Expertise</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Side: Text & Focus Areas --}}
            <div class="col-lg-6 offset-lg-1">
                <span style="display: inline-block; padding: 8px 22px; background: rgba(26,159,212,0.08); color: #1a9fd4; border-radius: 50px; font-weight: 800; font-size: 12px; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 18px; border: 1px solid rgba(26,159,212,0.15);">Research & Development</span>
                <h2 class="text-dark-force" style="font-size: 42px; font-weight: 900; margin-bottom: 24px; letter-spacing: -0.5px; font-family: 'Outfit', sans-serif;">Innovation Rooted in <span style="color: #1a9fd4;">Science</span></h2>

                <p class="text-slate-force" style="font-size: 16px; line-height: 1.8; font-family: 'Roboto', sans-serif; margin-bottom: 20px;">
                    Since our founding in 2008, research and development has been the backbone of everything we create at Molikule Green Care. We operate a dedicated, in-house R&D function — led by a BTech-qualified director from the Madras Institute of Technology — that drives the formulation of every product in our portfolio. This is not contract chemistry. Every solution we bring to market is the result of original, purpose-built research conducted by our own team.
                </p>
                <p class="text-slate-force" style="font-size: 16px; line-height: 1.8; font-family: 'Roboto', sans-serif; margin-bottom: 30px;">
                    Our R&D philosophy is built around three non-negotiables: performance that works in the real world, safety that protects users and surfaces, and sustainability that respects the environment. Over more than 17 years, this approach has produced a growing portfolio of specialty cleaning, hygiene, laundry care, auto care, and institutional solutions — each one engineered to meet the evolving demands of modern industries.
                </p>

                <div style="margin-bottom: 30px;">
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        <li style="display: flex; align-items: flex-start; margin-bottom: 16px;">
                            <div style="min-width: 32px; height: 32px; background: #f0f9ff; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 16px; margin-top: 2px;">
                                <i class="fas fa-flask" style="color: #0ea5e9; font-size: 14px;"></i>
                            </div>
                            <div style="font-size: 15px; line-height: 1.6; color: var(--slate-gray);"><strong style="color: var(--dark-navy);">Specialty Chemical Formulation</strong> — Developing high-performance cleaning and hygiene chemistries from the ground up</div>
                        </li>
                        <li style="display: flex; align-items: flex-start; margin-bottom: 16px;">
                            <div style="min-width: 32px; height: 32px; background: #ecfdf5; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 16px; margin-top: 2px;">
                                <i class="fas fa-leaf" style="color: #10b981; font-size: 14px;"></i>
                            </div>
                            <div style="font-size: 15px; line-height: 1.6; color: var(--slate-gray);"><strong style="color: var(--dark-navy);">Green Chemistry</strong> — Replacing harmful ingredients with safer, biodegradable, eco-conscious alternatives</div>
                        </li>
                        <li style="display: flex; align-items: flex-start; margin-bottom: 16px;">
                            <div style="min-width: 32px; height: 32px; background: #f5f3ff; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 16px; margin-top: 2px;">
                                <i class="fas fa-vial" style="color: #8b5cf6; font-size: 14px;"></i>
                            </div>
                            <div style="font-size: 15px; line-height: 1.6; color: var(--slate-gray);"><strong style="color: var(--dark-navy);">Application-Specific R&D</strong> — Tailoring formulations to the unique demands of healthcare, hospitality, laundry, automotive, and industrial environments</div>
                        </li>
                        <li style="display: flex; align-items: flex-start; margin-bottom: 16px;">
                            <div style="min-width: 32px; height: 32px; background: #fffbeb; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 16px; margin-top: 2px;">
                                <i class="fas fa-chart-bar" style="color: #f59e0b; font-size: 14px;"></i>
                            </div>
                            <div style="font-size: 15px; line-height: 1.6; color: var(--slate-gray);"><strong style="color: var(--dark-navy);">Quality & Stability Testing</strong> — Rigorous testing protocols to ensure every batch meets consistency, safety, and performance standards</div>
                        </li>
                        <li style="display: flex; align-items: flex-start;">
                            <div style="min-width: 32px; height: 32px; background: #fdf4ff; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 16px; margin-top: 2px;">
                                <i class="fas fa-recycle" style="color: #d946ef; font-size: 14px;"></i>
                            </div>
                            <div style="font-size: 15px; line-height: 1.6; color: var(--slate-gray);"><strong style="color: var(--dark-navy);">Sustainable Innovation</strong> — Continuously reducing chemical load, improving biodegradability, and advancing responsible formulation practices</div>
                        </li>
                    </ul>
                </div>

                <div style="padding: 20px 24px; background: #f8fafc; border-left: 4px solid #bbd700; border-radius: 0 16px 16px 0;">
                    <p style="margin: 0; font-size: 16px; font-weight: 700; color: var(--dark-navy); font-style: italic;">At Molikule, science is not a department — it is the foundation on which every product is built.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- =====================================================
         SECTION 8.5 — NEXUS TRAINING & CERTIFICATION
    ====================================================== --}}
<section class="pt_100 pb_100" style="background: #ffffff; position: relative;">
    <div class="auto-container">
        <div class="row align-items-center" style="row-gap: 40px;">

            {{-- Left Side: Text Content --}}
            <div class="col-lg-6 mb-5 mb-lg-0 order-2 order-lg-1" style="padding-right: 32px;">
                <span style="display: inline-block; font-weight: 700; font-size: 14px; text-transform: uppercase; letter-spacing: 2px; color: #1368B4; margin-bottom: 15px;">
                    Molikule's Training & Certification Division
                </span>
                <h2 style="font-size: 36px; font-weight: 700; color: #1368B4; margin-bottom: 24px; font-family: 'Outfit', sans-serif; line-height: 1.2;">
                    NEXUS — Nurturing Excellence, <br>Xploring Unique Solutions
                </h2>

                <p style="font-size: 16px; line-height: 1.8; color: #2D3748; font-family: 'Roboto', Arial, sans-serif; margin-bottom: 20px;">
                    Great products deserve great people behind them. NEXUS is Molikule Green Care's official training and certification division — built to equip our partners, dealers, application technicians, and service providers with the knowledge, skills, and credentials to deliver exceptional results in the field.
                </p>
                <p style="font-size: 16px; line-height: 1.8; color: #2D3748; font-family: 'Roboto', Arial, sans-serif; margin-bottom: 30px;">
                    Through graded certification programmes, hands-on workshops, technical guides, and industry-specific consulting, NEXUS ensures that everyone in the Molikule ecosystem represents our brand with expertise and confidence. From ISO and GOTS/ZDHC compliance consulting to laundry process audits and healthcare hygiene training — NEXUS bridges the gap between product innovation and service excellence.
                </p>

                <h4 style="color: #1368B4; font-size: 22px; font-weight: 700; margin-bottom: 20px; font-family: 'Outfit', sans-serif;">Who It's For:</h4>
                <ul style="list-style: none; padding: 0; margin-bottom: 35px; display: flex; flex-direction: column; gap: 16px;">
                    <li style="display: flex; align-items: flex-start; gap: 12px;">
                        <i class="fas fa-check" style="color: #1368B4; font-size: 16px; margin-top: 4px;"></i>
                        <span style="font-size: 16px; color: #2D3748; font-family: 'Roboto', Arial, sans-serif; line-height: 1.6;"><strong>Dealers & Distributors</strong> — Sell with authority backed by certified product knowledge</span>
                    </li>
                    <li style="display: flex; align-items: flex-start; gap: 12px;">
                        <i class="fas fa-check" style="color: #1368B4; font-size: 16px; margin-top: 4px;"></i>
                        <span style="font-size: 16px; color: #2D3748; font-family: 'Roboto', Arial, sans-serif; line-height: 1.6;"><strong>Application Technicians</strong> — Master correct usage, dosing, and safety protocols</span>
                    </li>
                    <li style="display: flex; align-items: flex-start; gap: 12px;">
                        <i class="fas fa-check" style="color: #1368B4; font-size: 16px; margin-top: 4px;"></i>
                        <span style="font-size: 16px; color: #2D3748; font-family: 'Roboto', Arial, sans-serif; line-height: 1.6;"><strong>Service & Facility Providers</strong> — Deliver consistent, Molikule-standard results every time</span>
                    </li>
                    <li style="display: flex; align-items: flex-start; gap: 12px;">
                        <i class="fas fa-check" style="color: #1368B4; font-size: 16px; margin-top: 4px;"></i>
                        <span style="font-size: 16px; color: #2D3748; font-family: 'Roboto', Arial, sans-serif; line-height: 1.6;"><strong>Laundry & Auto Care Professionals</strong> — Specialist training tailored to your sector</span>
                    </li>
                </ul>

                <div style="margin-bottom: 35px;">
                    <p style="margin: 0; font-size: 16px; font-weight: 600; color: #1368B4; font-style: italic; font-family: 'Roboto', Arial, sans-serif;">Together, through NEXUS, we grow stronger — empowering excellence, standardising quality, and elevating the Molikule brand.</p>
                </div>

                <button type="button" id="nexusCertificationOpen" class="theme-btn" style="background: #1368B4; color: #ffffff; border-radius: 4px; padding: 14px 32px; font-weight: 700; font-size: 16px; display: inline-flex; align-items: center; gap: 10px; transition: all 0.3s ease; text-decoration: none; border: none; box-shadow: 0 2px 13px rgba(0,0,0,0.1); cursor: pointer;">
                    Get NEXUS Certified <i class="fas fa-arrow-right"></i>
                </button>
            </div>

            {{-- Right Side: Visual --}}
            <div class="col-lg-5 offset-lg-1 order-1 order-lg-2 mb-5 mb-lg-0">
                <img src="{{ asset('assets/images/nexus-training.webp') }}" alt="Molikule NEXUS hygiene training certification India" style="width: 100%; max-height: 600px; object-fit: cover; border-radius: 10px; box-shadow: 0 2px 13px rgba(0,0,0,0.1); display: block;">
            </div>

        </div>
    </div>
</section>

{{-- =====================================================
         CERTIFICATES (SCROLLING MARQUEE) — UNIFIED DESIGN
    ====================================================== --}}
<section class="cert-section-wrapper" style="background-color: #f8fafc;">
    {{-- Ambient blobs matching other sections --}}
    <div class="abt-blob" style="width:420px;height:420px;top:-100px;left:10%;background:radial-gradient(circle,#bbd700 0%,transparent 70%);opacity:0.07;"></div>
    <div class="abt-blob" style="width:360px;height:360px;bottom:-80px;right:8%;background:radial-gradient(circle,#1a9fd4 0%,transparent 70%);opacity:0.06;animation-delay:10s;"></div>

    <div class="auto-container" style="position:relative;z-index:10;">
        <div style="text-align: center; max-width: 900px; margin: 0 auto 60px;">
            <span style="display: inline-block; padding: 8px 22px; background: rgba(19,104,180,0.08); color: #1368B4; border-radius: 50px; font-weight: 800; font-size: 12px; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 20px; border: 1px solid rgba(19,104,180,0.15);">
                Certifications & Compliance
            </span>
            <h2 style="font-size: 42px; font-weight: 900; color: #0f172a; margin-bottom: 24px; font-family: 'Outfit', sans-serif; letter-spacing: -0.5px; line-height: 1.2;">
                Certified Quality. <br class="d-none d-md-block">
                <span style="color: #1368B4; position: relative;">Globally Recognised Standards.
                    <svg style="position: absolute; bottom: -8px; left: 0; width: 100%;" viewBox="0 0 200 8" preserveAspectRatio="none">
                        <path d="M0,6 Q50,0 100,5 Q150,10 200,4" stroke="#1368B4" stroke-width="2.5" fill="none" opacity="0.3" />
                    </svg>
                </span>
            </h2>
            <p style="font-size: 17px; line-height: 1.8; color: #475569; font-family: 'Roboto', Arial, sans-serif; margin-bottom: 20px;">
                At Molikule Green Care, our commitment to sustainability and safety is not just a promise — it is verified. Our products and processes are aligned with internationally recognised certification standards that set the benchmark for responsible chemistry, environmental performance, and human safety across global supply chains.
            </p>
            <p style="font-size: 17px; line-height: 1.8; color: #475569; font-family: 'Roboto', Arial, sans-serif;">
                These certifications are our assurance to every customer, partner, and institution that what they use from Molikule meets — and exceeds — the highest standards of quality, transparency, and ecological responsibility.
            </p>
        </div>
    </div>{{-- /auto-container --}}

    {{-- Full-width marquee (no container) --}}
    <div class="cert-marquee-wrapper" role="region" aria-label="Our Certificates Marquee">
        @for ($i = 0; $i < 6; $i++)
            <div class="cert-marquee-track" aria-hidden="{{ $i === 0 ? 'false' : 'true' }}">
            @foreach ($certificates as $cert)
            @php
            // Determine outbound link based on title for SEO/Trust
            $link = '#';
            $title = strtolower($cert->title);
            if(strpos($title, 'zdhc') !== false) $link = 'https://www.roadmaptozero.com/';
            elseif(strpos($title, 'gots') !== false) $link = 'https://global-standard.org/';
            elseif(strpos($title, 'oeko-tex') !== false) $link = 'https://www.oeko-tex.com/';
            @endphp
            <div class="cert-card" tabindex="{{ $i === 0 ? '0' : '-1' }}">
                {{-- Image area: Opens full screen --}}
                <a href="{{ $cert->image_url }}" class="cert-card__img-wrap lightbox-image" data-fancybox="certificates" style="text-decoration: none; cursor: pointer;" title="Click to view full certificate">
                    <img src="{{ $cert->image_url }}" alt="{{ $cert->title }} logo">
                </a>

                {{-- Text body: Links to official website --}}
                <div class="cert-card__body">
                    <span class="cert-card__badge" style="background-color: rgba(187,215,0,0.10); color: #9aaf00;">
                        CERTIFIED
                    </span>
                    @if($link !== '#')
                    <a href="{{ $link }}" target="_blank" rel="noopener noreferrer" style="text-decoration: none; color: inherit;">
                        <h3 class="cert-card__name" style="text-decoration: underline; text-decoration-color: rgba(19,104,180,0.3); text-underline-offset: 4px;">{{ $cert->title }} <i class="fas fa-external-link-alt" style="font-size: 12px; margin-left: 4px; color: #1368B4;"></i></h3>
                    </a>
                    @else
                    <h3 class="cert-card__name">{{ $cert->title }}</h3>
                    @endif
                </div>
            </div>
            @endforeach
    </div>
    @endfor
    </div>

    <div class="auto-container" style="position:relative;z-index:10;">
        <div style="text-align: center; max-width: 800px; margin: 60px auto 0;">
            <p style="font-weight: 700; font-size: 18px; color: #1368B4; font-family: 'Outfit', sans-serif; font-style: italic;">
                Every certification we hold is a commitment we keep — to safer products, cleaner processes, and a more responsible industry.
            </p>
        </div>
    </div>
</section>


{{-- =====================================================
         VIDEO BANNER
    ====================================================== 
    <section style="margin: 0 40px 100px; position: relative; height: 520px; border-radius: 44px; overflow: hidden; box-shadow: 0 50px 100px rgba(15,23,42,0.12);">
        <div style="position: absolute; inset: 0; background: url(https://images.unsplash.com/photo-1584622650111-993a426fbf0a?q=80&w=2070) center/cover; transform: scale(1.05); transition: transform 6s ease;"></div>
        <div style="position: absolute; inset: 0; background: linear-gradient(135deg, rgba(15,23,42,0.55) 0%, rgba(15,23,42,0.75) 100%);"></div>

        <div style="position: absolute; top: -80px; left: -80px; width: 300px; height: 300px; border: 2px solid rgba(187,215,0,0.12); border-radius: 50%;"></div>
        <div style="position: absolute; bottom: -60px; right: -60px; width: 250px; height: 250px; border: 2px solid rgba(26,159,212,0.12); border-radius: 50%;"></div>

        <div style="position: absolute; inset: 0; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; padding: 30px; z-index: 10;">
            <a href="https://www.youtube.com/watch?v=nfP5N9Yc72A" class="lightbox-image mgc-play-btn"
               style="width: 110px; height: 110px; background: var(--brand-primary); border-radius: 50%;
                      display: flex; align-items: center; justify-content: center; margin-bottom: 32px;
                      box-shadow: 0 0 0 16px rgba(187,215,0,0.12), 0 20px 40px rgba(187,215,0,0.30);
                      transition: all 0.4s ease; text-decoration: none;">
                <i class="fas fa-play" style="font-size: 34px; color: var(--dark-navy); margin-left: 6px;"></i>
            </a>
            <h2 class="text-white-force" style="font-size: 44px; font-weight: 900; letter-spacing: -1.5px; margin-bottom: 14px; line-height: 1.1; font-family: 'Outfit', sans-serif;">
                See the Science in Action
            </h2>
            <p class="text-white-force" style="opacity: 0.75; font-size: 17px; max-width: 480px; line-height: 1.7; margin: 0; text-align: justify; font-family: 'Roboto', sans-serif;">
                A behind-the-scenes look at our molecular engineering and the green chemistry that powers it.
            </p>
        </div>
    </section>--}}

{{-- =====================================================
         SECTION 9 — CLOSING BANNER / CTA
    ====================================================== --}}
<section class="cta-closing-section" style="padding: 100px 20px; background: linear-gradient(135deg, #059669 0%, #1a9fd4 100%); position: relative; overflow: hidden; margin: 40px auto 80px; max-width: 1300px; border-radius: 40px; box-shadow: 0 30px 60px rgba(5,150,105,0.2);">
    {{-- Glassmorphic/ambient decorations --}}
    <div style="position: absolute; top: -50px; left: -50px; width: 300px; height: 300px; background: rgba(255,255,255,0.1); filter: blur(50px); border-radius: 50%;"></div>
    <div style="position: absolute; bottom: -100px; right: -50px; width: 400px; height: 400px; background: rgba(187,215,0,0.2); filter: blur(60px); border-radius: 50%;"></div>

    {{-- Floating Icons --}}
    <style>
        @keyframes floatIcon {
            0% {
                transform: translateY(0) rotate(0deg);
            }

            50% {
                transform: translateY(-20px) rotate(15deg);
            }

            100% {
                transform: translateY(0) rotate(0deg);
            }
        }

        .cta-float-icon {
            position: absolute;
            color: rgba(255, 255, 255, 0.12);
            animation: floatIcon 6s ease-in-out infinite;
            z-index: 1;
        }
    </style>
    <i class="fas fa-leaf cta-float-icon" style="font-size: 80px; top: 15%; left: 8%;"></i>
    <i class="fas fa-flask cta-float-icon" style="font-size: 100px; bottom: 10%; left: 20%; animation-delay: 1.5s;"></i>
    <i class="fas fa-shield-alt cta-float-icon" style="font-size: 60px; top: 20%; right: 12%; animation-delay: 3s;"></i>
    <i class="fas fa-recycle cta-float-icon" style="font-size: 90px; bottom: 15%; right: 8%; animation-delay: 2s;"></i>
    <i class="fas fa-vial cta-float-icon" style="font-size: 50px; top: 50%; left: 5%; animation-delay: 0.5s;"></i>
    <i class="fas fa-globe cta-float-icon" style="font-size: 120px; top: 40%; right: 25%; color: rgba(255,255,255,0.06); animation-delay: 2.5s;"></i>

    <div class="auto-container" style="position: relative; z-index: 2; text-align: center;">
        <div style="max-width: 850px; margin: 0 auto;">
            <h2 style="font-size: 46px; font-weight: 900; color: #ffffff !important; margin-bottom: 24px; letter-spacing: -1px; font-family: 'Outfit', sans-serif; line-height: 1.2;">
                Building Cleaner Spaces. <br>Protecting a Greener Tomorrow.
            </h2>
            <p style="font-size: 18px; line-height: 1.8; color: rgba(255,255,255,0.9) !important; margin-bottom: 40px; font-family: 'Roboto', sans-serif; max-width: 700px; margin-left: auto; margin-right: auto;">
                At Molikule Green Care, we are not just manufacturing cleaning products — we are engineering hygiene and maintenance solutions that improve lives, strengthen industries, and support a more sustainable future for India and beyond.<br><br>
                <span style="display:inline-block; padding: 10px 20px; background: rgba(255,255,255,0.1); border-radius: 8px; font-weight: 700; color: #ffffff; border: 1px solid rgba(255,255,255,0.2);">Because cleanliness is not just our business — it is our commitment to a healthier world.</span>
            </p>

            <div style="display: flex; gap: 20px; justify-content: center; flex-wrap: wrap;">
                <a href="/shop" style="background: #ffffff; color: #059669; border-radius: 50px; padding: 16px 40px; font-weight: 800; font-size: 16px; text-transform: uppercase; letter-spacing: 1px; display: inline-flex; align-items: center; gap: 10px; transition: all 0.3s ease; box-shadow: 0 10px 25px rgba(0,0,0,0.15); text-decoration: none;">
                    Explore Our Products <i class="fas fa-arrow-right"></i>
                </a>
                <a href="/contact" style="background: transparent; border: 2px solid #ffffff; color: #ffffff; border-radius: 50px; padding: 14px 40px; font-weight: 800; font-size: 16px; text-transform: uppercase; letter-spacing: 1px; display: inline-flex; align-items: center; gap: 10px; transition: all 0.3s ease; text-decoration: none;">
                    Get In Touch <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</section>

@php
$nexusSegments = [
'Laundry Care',
'Housekeeping',
'Kitchen Hygiene',
'Healthcare',
'Auto Care',
'Industrial Cleaning',
'Facility Management',
'Other',
];
@endphp

<style>
    .nexus-modal-overlay {
        position: fixed;
        inset: 0;
        z-index: 9999;
        display: none;
        align-items: flex-start;
        justify-content: center;
        padding: 24px;
        background: rgba(15, 23, 42, 0.78);
        backdrop-filter: blur(10px);
        overflow-y: auto;
    }

    .nexus-modal-overlay.is-open {
        display: flex;
    }

    .nexus-modal {
        width: 100%;
        max-width: 980px;
        margin: auto;
        background: #ffffff;
        border-radius: 28px;
        box-shadow: 0 36px 110px rgba(15, 23, 42, 0.34);
        border: 1px solid rgba(226, 232, 240, 0.9);
        position: relative;
        display: grid;
        grid-template-columns: minmax(280px, 0.88fr) minmax(0, 1.45fr);
        overflow-x: hidden;
        overflow-y: auto;
    }

    .nexus-modal__header {
        padding: 42px 34px;
        background: linear-gradient(145deg, #090d16 0%, #0f172a 100%);
        color: #ffffff;
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 100%;
    }

    .nexus-modal__header::before,
    .nexus-modal__header::after {
        content: '';
        position: absolute;
        border-radius: 50%;
        pointer-events: none;
    }

    .nexus-modal__header::before {
        width: 260px;
        height: 260px;
        right: -120px;
        top: -90px;
        background: rgba(187, 215, 0, 0.16);
        filter: blur(8px);
    }

    .nexus-modal__header::after {
        width: 220px;
        height: 220px;
        left: -110px;
        bottom: -90px;
        background: rgba(26, 159, 212, 0.18);
        filter: blur(8px);
    }

    .nexus-modal__close {
        position: absolute;
        top: 18px;
        right: 18px;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border: 1px solid rgba(226, 232, 240, 0.9);
        background: #ffffff;
        color: #0f172a;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 4;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.12);
        transition: all 0.25s ease;
    }

    .nexus-modal__close:hover {
        transform: rotate(90deg);
        background: #0f172a;
        color: #ffffff;
    }

    .nexus-modal__content {
        position: relative;
        z-index: 2;
    }

    .nexus-kicker {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 16px;
        background: rgba(187, 215, 0, 0.1);
        border: 1px solid rgba(187, 215, 0, 0.2);
        border-radius: 50px;
        color: #bbd700;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-bottom: 22px;
    }

    .nexus-kicker::before {
        content: '';
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #bbd700;
        box-shadow: 0 0 12px rgba(187, 215, 0, 0.85);
    }

    .nexus-modal__title {
        font-size: 38px;
        font-weight: 900;
        color: #ffffff !important;
        margin-bottom: 16px;
        letter-spacing: -1px;
        line-height: 1.1;
        font-family: 'Outfit', sans-serif;
    }

    .nexus-modal__lead {
        color: rgba(255, 255, 255, 0.76) !important;
        font-size: 15px;
        line-height: 1.75;
        margin: 0;
    }

    .nexus-benefits {
        margin: 34px 0 0;
        padding: 0;
        list-style: none;
        display: grid;
        gap: 14px;
    }

    .nexus-benefits li {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        color: rgba(255, 255, 255, 0.84) !important;
        font-size: 13.5px;
        line-height: 1.55;
    }

    .nexus-benefits i {
        width: 28px;
        height: 28px;
        border-radius: 9px;
        background: rgba(255, 255, 255, 0.08);
        color: #bbd700;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 28px;
        margin-top: 1px;
    }

    .nexus-modal__footer-note {
        position: relative;
        z-index: 2;
        margin-top: 38px;
        padding-top: 22px;
        border-top: 1px solid rgba(255, 255, 255, 0.12);
        color: rgba(255, 255, 255, 0.68) !important;
        font-size: 12.5px;
        line-height: 1.6;
    }

    .nexus-input-group {
        margin-bottom: 20px;
    }

    .nexus-input-label {
        font-size: 11px;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        margin-bottom: 8px;
        display: block;
        transition: color 0.3s ease;
    }

    .nexus-input-group:focus-within .nexus-input-label {
        color: #0f172a;
    }

    .nexus-input-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }

    .nexus-input-icon {
        position: absolute;
        left: 20px;
        color: #cbd5e1;
        font-size: 16px;
        pointer-events: none;
        transition: all 0.3s ease;
    }

    .nexus-input-wrapper:focus-within .nexus-input-icon {
        color: #0f172a;
        transform: scale(1.1);
    }

    .nexus-form-input {
        width: 100%;
        box-sizing: border-box;
        padding: 16px 20px 16px 54px;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
        color: #0f172a;
        font-weight: 600;
        font-size: 15px;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        font-family: 'Roboto', sans-serif;
    }

    .nexus-form-input::placeholder {
        color: #94a3b8;
        font-weight: 400;
    }

    .nexus-form-input:hover {
        border-color: #cbd5e1;
        background: #ffffff;
    }

    .nexus-form-input:focus {
        background: #ffffff;
        outline: none;
        border-color: #bbd700;
        box-shadow: 0 10px 25px rgba(187, 215, 0, 0.15), 0 0 0 4px rgba(187, 215, 0, 0.1);
        transform: translateY(-2px);
    }

    textarea.nexus-form-input {
        padding-left: 18px;
        resize: vertical;
        min-height: 120px;
    }

    .nexus-submit-btn {
        width: 100%;
        background: linear-gradient(135deg, #bbd700 0%, #9aaf00 100%);
        color: #0f172a;
        padding: 20px;
        border-radius: 16px;
        font-weight: 900;
        font-size: 17px;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        box-shadow: 0 12px 26px rgba(187, 215, 0, 0.25);
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
        overflow: hidden;
        font-family: 'Outfit', sans-serif;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .nexus-submit-btn::after {
        content: '';
        position: absolute;
        inset: 0;
        transform: translateX(-100%);
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
        transition: transform 0.6s ease;
    }

    .nexus-submit-btn:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 40px rgba(187, 215, 0, 0.35);
    }

    .nexus-submit-btn:hover::after {
        transform: translateX(100%);
    }

    .nexus-form-note {
        text-align: center;
        font-size: 12.5px;
        color: #94a3b8;
        margin: 16px 0 0;
        font-weight: 600;
    }

    .nexus-modal__body {
        padding: 46px 48px;
        background: #ffffff;
        overflow-y: auto;
    }

    @media (max-width: 575px) {
        .nexus-modal-overlay {
            padding: 14px;
        }

        .nexus-modal {
            max-height: calc(100vh - 28px);
            border-radius: 22px;
        }

        .nexus-modal__header,
        .nexus-modal__body {
            padding-left: 22px !important;
            padding-right: 22px !important;
        }

        .nexus-modal__title {
            font-size: 28px;
        }
    }

    @media (max-width: 860px) {
        .nexus-modal {
            grid-template-columns: 1fr;
        }

        .nexus-modal__header {
            min-height: auto;
            padding: 34px 28px 28px;
        }

        .nexus-benefits {
            grid-template-columns: 1fr;
            margin-top: 24px;
        }

        .nexus-modal__footer-note {
            display: none;
        }
    }

    /* NEXUS Form Nice Select Overrides */
    .nexus-input-wrapper .nice-select.nexus-form-input {
        height: auto;
        line-height: 1.5;
        float: none;
        display: flex;
        align-items: center;
    }
    .nexus-input-wrapper .nice-select.nexus-form-input::after {
        right: 22px;
        height: 8px;
        width: 8px;
        margin-top: -6px;
    }
    .nexus-input-wrapper .nice-select.nexus-form-input .list {
        width: 100%;
        border-radius: 16px;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.1);
        border: 1px solid #e2e8f0;
        margin-top: 8px;
        padding: 8px 0;
        max-height: 280px;
        overflow-y: auto;
    }
    .nexus-input-wrapper .nice-select.nexus-form-input .option {
        padding: 12px 24px;
        font-size: 15px;
        font-weight: 500;
        color: #475569;
        line-height: 1.5;
        min-height: auto;
        transition: all 0.2s ease;
    }
    .nexus-input-wrapper .nice-select.nexus-form-input .option:hover, 
    .nexus-input-wrapper .nice-select.nexus-form-input .option.focus {
        background: #f8fafc;
        color: #1a9fd4;
    }
    .nexus-input-wrapper .nice-select.nexus-form-input .option.selected {
        font-weight: 700;
        color: #0f172a;
        background: rgba(187, 215, 0, 0.15);
    }
    .nexus-input-wrapper .nice-select.nexus-form-input .current {
        display: block;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        margin-right: 20px;
    }
</style>

<div class="nexus-modal-overlay" id="nexusCertificationModal" aria-hidden="true">
    <div class="nexus-modal" role="dialog" aria-modal="true" aria-labelledby="nexusCertificationTitle">
        <button type="button" class="nexus-modal__close" id="nexusCertificationClose" aria-label="Close NEXUS certification form">
            <i class="fas fa-times"></i>
        </button>

        <div class="nexus-modal__header">
            <div class="nexus-modal__content">
                <span class="nexus-kicker">NEXUS Certification</span>
                <h3 id="nexusCertificationTitle" class="nexus-modal__title" style="color: white !important;">Get NEXUS Certified</h3>
                <p class="nexus-modal__lead">Share your details and our team will contact you about the right NEXUS certification path for your business segment.</p>

                <ul class="nexus-benefits">
                    <li><i class="fas fa-check"></i><span>Guided certification path for dealers, service teams, and facility partners.</span></li>
                    <li><i class="fas fa-graduation-cap"></i><span>Hands-on product usage, dosing, safety, and compliance training.</span></li>
                    <li><i class="fas fa-certificate"></i><span>Structured credentials that help teams represent Molikule standards confidently.</span></li>
                </ul>
            </div>

            <!-- <p class="nexus-modal__footer-note">Your enquiry is sent directly to the Molikule admin team. Required fields are marked with an asterisk.</p> -->
        </div>

        <div class="nexus-modal__body">
            <form action="{{ route('nexus-certification.store') }}" method="POST">
                @csrf
                <input type="hidden" name="nexus_form" value="1">

                <div class="row">
                    <div class="col-md-6">
                        <div class="nexus-input-group">
                            <label class="nexus-input-label" for="nexus_name">Name <span style="color: #ef4444;">*</span></label>
                            <div class="nexus-input-wrapper">
                                <i class="far fa-user nexus-input-icon"></i>
                                <input type="text" id="nexus_name" name="name" value="{{ old('name') }}" class="nexus-form-input" placeholder="Your name" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="nexus-input-group">
                            <label class="nexus-input-label" for="nexus_contact_no">Contact No. <span style="color: #ef4444;">*</span></label>
                            <div class="nexus-input-wrapper">
                                <i class="fas fa-mobile-alt nexus-input-icon"></i>
                                <input type="tel" id="nexus_contact_no" name="contact_no" value="{{ old('contact_no') }}" class="nexus-form-input" placeholder="+91 00000 00000" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="nexus-input-group">
                            <label class="nexus-input-label" for="nexus_email">Email ID <span style="color: #ef4444;">*</span></label>
                            <div class="nexus-input-wrapper">
                                <i class="far fa-envelope nexus-input-icon"></i>
                                <input type="email" id="nexus_email" name="email" value="{{ old('email') }}" class="nexus-form-input" placeholder="you@example.com" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="nexus-input-group">
                            <label class="nexus-input-label" for="nexus_company_name">Company Name <span style="color: #ef4444;">*</span></label>
                            <div class="nexus-input-wrapper">
                                <i class="far fa-building nexus-input-icon"></i>
                                <input type="text" id="nexus_company_name" name="company_name" value="{{ old('company_name') }}" class="nexus-form-input" placeholder="Company name" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="nexus-input-group">
                    <label class="nexus-input-label" for="nexus_segment">Which Segment Are You In? <span style="color: #ef4444;">*</span></label>
                    <div class="nexus-input-wrapper">
                        <i class="fas fa-layer-group nexus-input-icon"></i>
                        <select id="nexus_segment" name="segment" class="nexus-form-input" required style="appearance: none; background-image: url('data:image/svg+xml;charset=utf-8,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 width=%2724%27 height=%2724%27 viewBox=%270 0 24 24%27 fill=%27none%27 stroke=%27%230f172a%27 stroke-width=%272.5%27 stroke-linecap=%27round%27 stroke-linejoin=%27round%27%3E%3Cpolyline points=%276 9 12 15 18 9%27/%3E%3C/svg%3E'); background-repeat: no-repeat; background-position: right 20px center; background-size: 14px; cursor: pointer;">
                            <option value="" disabled {{ old('segment') ? '' : 'selected' }}>Select your segment...</option>
                            @foreach($nexusSegments as $segment)
                            <option value="{{ $segment }}" {{ old('segment') === $segment ? 'selected' : '' }}>{{ $segment }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="nexus-input-group">
                    <label class="nexus-input-label" for="nexus_thoughts">Describe Your Thoughts <span style="color: #ef4444;">*</span></label>
                    <textarea id="nexus_thoughts" name="thoughts" rows="4" class="nexus-form-input" placeholder="Tell us about your requirement..." required>{{ old('thoughts') }}</textarea>
                </div>

                <button type="submit" class="nexus-submit-btn">
                    Submit Enquiry <i class="fas fa-paper-plane" style="font-size: 14px;"></i>
                </button>
                <p class="nexus-form-note">Our team will review your enquiry and contact you shortly.</p>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('nexusCertificationModal');
        const openBtn = document.getElementById('nexusCertificationOpen');
        const closeBtn = document.getElementById('nexusCertificationClose');

        if (!modal || !openBtn || !closeBtn) return;

        function openModal() {
            modal.classList.add('is-open');
            modal.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            modal.classList.remove('is-open');
            modal.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
        }

        openBtn.addEventListener('click', openModal);
        closeBtn.addEventListener('click', closeModal);

        modal.addEventListener('click', function(event) {
            if (event.target === modal) {
                closeModal();
            }
        });

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && modal.classList.contains('is-open')) {
                closeModal();
            }
        });

        @if(isset($errors) && $errors->any() && old('nexus_form'))
        openModal();
        @endif
    });
</script>
@endpush
