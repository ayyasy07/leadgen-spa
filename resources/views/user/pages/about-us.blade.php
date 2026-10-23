@extends('user.layouts.main')

@section('title', 'About Us — LeadGen Spa')
@section('meta_description', 'Discover the journey of LeadGen Spa, a sanctuary designed for absolute relaxation and holistic wellness.')
@section('footer_type', 'grid')

@push('styles')
<style>
    /* ===== HERO ===== */
    .about-hero {
        position: relative;
        height: 400px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        background:
            linear-gradient(180deg, rgba(10, 7, 6, 0.55), rgba(8, 6, 5, 0.62)),
            url('{{ asset("user/assets/lilinbesar.jpe") }}') center 35% / cover no-repeat;
        color: #fff;
    }
    .about-hero-content { max-width: 640px; padding: 0 24px; }
    .about-hero .eyebrow {
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: var(--primary);
        margin-bottom: 16px;
    }
    .about-hero h1 {
        font-size: 3rem;
        font-weight: 800;
        margin: 0 0 16px;
        letter-spacing: -0.5px;
    }
    .about-hero p {
        font-size: 16px;
        color: rgba(255, 255, 255, 0.9);
        line-height: 1.6;
    }

    /* ===== WELCOME ===== */
    .about-welcome {
        padding: 90px 0;
        background: var(--bg-cream);
    }
    .about-welcome-inner {
        display: flex;
        align-items: center;
        gap: 60px;
        max-width: 1180px;
        margin: 0 auto;
        padding: 0 32px;
    }
    .about-welcome-text { flex: 1; }
    .about-welcome-text .eyebrow {
        color: #c98f8c;
        font-size: 12px;
        font-weight: 600;
        letter-spacing: 1px;
        text-transform: uppercase;
        margin-bottom: 15px;
        display: block;
    }
    .about-welcome-text h2 {
        font-size: 30px;
        font-weight: 700;
        line-height: 1.3;
        margin-bottom: 25px;
    }
    .about-welcome-text p {
        color: var(--gray);
        font-size: 14px;
        line-height: 1.8;
        margin-bottom: 20px;
        max-width: 450px;
    }
    .about-welcome-text .btn {
        display: inline-block;
        background: var(--primary);
        color: #fff;
        padding: 12px 28px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 600;
        letter-spacing: 1px;
        text-transform: uppercase;
        border: none;
        cursor: pointer;
        transition: all 0.4s ease;
        text-decoration: none;
    }
    .about-welcome-text .btn:hover {
        background: var(--primary-dark);
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(247, 108, 108, 0.3);
    }
    .about-collage {
        flex: 1;
        position: relative;
        width: 100%;
        max-width: 420px;
        height: 330px;
    }
    .about-collage img {
        position: absolute;
        border-radius: 12px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        object-fit: cover;
    }
    .about-collage .img-main {
        width: 85%;
        height: 240px;
        top: 0;
        right: 0;
        z-index: 1;
    }
    .about-collage .img-sub {
        width: 52%;
        height: 175px;
        bottom: 0;
        left: 0;
        border: 6px solid var(--bg-cream);
        z-index: 2;
    }

    /* ===== THERAPISTS ===== */
    .about-therapists {
        background: #fff;
        padding: 90px 0;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    .about-therapists .leaf-deco {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -52%);
        z-index: 0;
        opacity: 0.08;
        pointer-events: none;
        width: 600px;
        height: 600px;
    }
    .about-therapists .wrap {
        position: relative;
        z-index: 1;
        max-width: 1180px;
        margin: 0 auto;
        padding: 0 32px;
    }
    .about-therapists .eyebrow {
        color: var(--primary);
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
        margin-bottom: 16px;
    }
    .about-therapists h2 {
        font-size: 30px;
        font-weight: 800;
        text-transform: uppercase;
        margin-bottom: 20px;
    }
    .about-therapists .intro {
        max-width: 560px;
        margin: 0 auto 60px;
        color: var(--gray);
        font-size: 14px;
        line-height: 1.8;
    }

    /* ===== THERAPIST CARD (shared) ===== */
    .therapist-card {
        background: #fff;
        border: 1px solid var(--line);
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        transition: 0.3s ease;
    }
    .therapist-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
    }
    .therapist-card img {
        width: 100%;
        height: 350px;
        object-fit: cover;
    }
    .therapist-card .info {
        padding: 24px;
    }
    .therapist-card h4 {
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 8px;
    }
    .therapist-card .role {
        color: var(--primary-dark);
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.05em;
    }

    /* ============================================
       THERAPIST GRID — mode DIEM (≤ 3 therapist)
    ============================================ */
    .therapist-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 24px;
        max-width: 960px;
        margin: 0 auto;
    }

    /* ============================================
       THERAPIST MARQUEE — mode AUTO-SCROLL (≥ 4 therapist)
    ============================================ */
    .therapist-marquee-wrap {
        --marquee-speed: 30s; /* ← UBAH ANGKA INI BUAT ATUR KECEPATAN */
        --card-width: 300px;
        --card-gap: 24px;

        position: relative;
        overflow: hidden;
        padding: 10px 0 30px;

        /* Fade kiri kanan biar smooth */
        -webkit-mask-image: linear-gradient(
            90deg,
            transparent 0,
            #000 60px,
            #000 calc(100% - 60px),
            transparent 100%
        );
        mask-image: linear-gradient(
            90deg,
            transparent 0,
            #000 60px,
            #000 calc(100% - 60px),
            transparent 100%
        );
    }

    .therapist-marquee {
        display: flex;
        gap: var(--card-gap);
        width: max-content;
        animation: therapistScroll var(--marquee-speed) linear infinite;
        will-change: transform;
    }

    /* Pause pas user hover */
    .therapist-marquee-wrap:hover .therapist-marquee {
        animation-play-state: paused;
    }

    .therapist-marquee .therapist-card {
        flex: 0 0 var(--card-width);
        width: var(--card-width);
    }

    @keyframes therapistScroll {
        from { transform: translateX(0); }
        to   { transform: translateX(-50%); }
    }

    /* Respect user yang prefer reduced motion */
    @media (prefers-reduced-motion: reduce) {
        .therapist-marquee {
            animation: none;
        }
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 900px) {
        .about-welcome-inner { flex-direction: column; gap: 40px; }
        .about-collage { max-width: 340px; height: 280px; }
        .about-collage .img-main { height: 200px; width: 82%; }
        .about-collage .img-sub { height: 160px; width: 50%; }
        .therapist-grid { grid-template-columns: 1fr; max-width: 320px; }

        .therapist-marquee-wrap {
            --card-width: 260px;
            --card-gap: 18px;
        }
        .therapist-marquee .therapist-card img {
            height: 300px;
        }
    }

    @media (max-width: 600px) {
        .therapist-marquee-wrap {
            --card-width: 240px;
            --card-gap: 16px;
        }
    }
</style>
@endpush

@section('content')

    {{-- HERO --}}
    <section class="about-hero">
        <div class="about-hero-content">
            <div class="eyebrow">About Us</div>
            <h1>Our Story</h1>
            <p>Discover the journey of LeadGen Spa, a sanctuary designed for absolute relaxation and holistic wellness.</p>
        </div>
    </section>

    {{-- WELCOME --}}
    <section class="about-welcome">
        <div class="about-welcome-inner">
            <div class="about-welcome-text">
                <span class="eyebrow">The Spa and Salon Story</span>
                <h2>Welcome to LeadGen Spa</h2>
                <p>Founded on the principle of holistic well-being, LeadGen Spa has been a sanctuary for those seeking respite from the bustling world. Our philosophy intertwines ancient healing traditions with modern therapeutic practices.</p>
                <p>We believe that true beauty and relaxation stem from a balance of mind, body, and spirit. Every element of our spa — from the ambient lighting to the organic essential oils — is meticulously chosen to transport you to a state of profound tranquility.</p>
                <a href="{{ route('treatments.index') }}" class="btn">Discover Our Treatments</a>
            </div>

            <div class="about-collage">
                <img class="img-main"
                    src="{{ asset('user/assets/towelspaaboutus.jpe') }}"
                    alt="Luxury Spa Massage Experience"
                    onerror="this.src='{{ asset('user/assets/wlcm.jpg') }}'">
                <img class="img-sub"
                    src="{{ asset('user/assets/soapabt.jpg') }}"
                    alt="Spa ambiance"
                    onerror="this.src='{{ asset('user/assets/sep.jpe') }}'">
            </div>
        </div>
    </section>

    {{-- THERAPISTS --}}
    <section class="about-therapists">
        <svg class="leaf-deco" viewBox="0 0 400 400" xmlns="http://www.w3.org/2000/svg">
            <g transform="translate(200, 200)">
                <path fill="#d4c9c4" d="M0,-150 C50,-140 100,-90 120,-30 C140,30 120,100 80,140 C40,180 -30,180 -80,150 C-130,120 -160,60 -150,-10 C-140,-80 -80,-140 0,-150 Z"/>
            </g>
        </svg>

        <div class="wrap">
            <div class="eyebrow">Our Experts</div>
            <h2>Meet The Therapists</h2>
            <p class="intro">Our team of certified professionals is dedicated to providing you with a personalized and transformative wellness experience.</p>

            @php
                $therapistCount = $therapists->count();
                $useMarquee = $therapistCount >= 4; // ≥4 → auto-scroll
            @endphp

            @if($therapists->isEmpty())
                {{-- EMPTY STATE --}}
                <p style="text-align: center; color: #999; padding: 40px;">
                    There are no therapists yet.
                </p>

            @elseif($useMarquee)
                {{-- MARQUEE MODE (≥ 4 therapist) — auto-scroll --}}
                <div class="therapist-marquee-wrap">
                    <div class="therapist-marquee">
                        {{-- Loop 1 --}}
                        @foreach($therapists as $t)
                            <div class="therapist-card">
                                <img src="{{ $t->photo ? asset('storage/'.$t->photo) : asset('user/assets/wlcm.jpg') }}"
                                     alt="{{ $t->name }}"
                                     onerror="this.src='{{ asset('user/assets/wlcm.jpg') }}'">
                                <div class="info">
                                    <h4>{{ $t->name }}</h4>
                                    <div class="role">{{ $t->role ?? 'Therapist' }}</div>
                                </div>
                            </div>
                        @endforeach

                        {{-- Loop 2 (duplikat) — biar seamless loop --}}
                        @foreach($therapists as $t)
                            <div class="therapist-card" aria-hidden="true">
                                <img src="{{ $t->photo ? asset('storage/'.$t->photo) : asset('user/assets/wlcm.jpg') }}"
                                     alt="{{ $t->name }}"
                                     onerror="this.src='{{ asset('user/assets/wlcm.jpg') }}'">
                                <div class="info">
                                    <h4>{{ $t->name }}</h4>
                                    <div class="role">{{ $t->role ?? 'Therapist' }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            @else
                {{-- GRID MODE (≤ 3 therapist) — diem, ga scroll --}}
                <div class="therapist-grid">
                    @foreach($therapists as $t)
                        <div class="therapist-card">
                            <img src="{{ $t->photo ? asset('storage/'.$t->photo) : asset('user/assets/wlcm.jpg') }}"
                                 alt="{{ $t->name }}"
                                 onerror="this.src='{{ asset('user/assets/wlcm.jpg') }}'">
                            <div class="info">
                                <h4>{{ $t->name }}</h4>
                                <div class="role">{{ $t->role ?? 'Therapist' }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

@endsection