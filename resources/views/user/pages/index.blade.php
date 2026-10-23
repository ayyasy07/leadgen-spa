@extends('user.layouts.main')

@section('title', 'LeadGen — Spa & Salon')
@section('footer_type', 'simple')

@push('styles')
<style>
    /* ============================================
       NEWSLETTER ALERT — floating, ga ngegeser layout
    ============================================ */
    .nl-alert {
        position: fixed;
        top: 20px;
        left: 50%;
        transform: translateX(-50%) translateY(-20px);
        z-index: 9999;
        max-width: 440px;
        width: calc(100% - 40px);
        padding: 13px 16px 13px 16px;
        border-radius: 12px;
        font-size: 13.5px;
        line-height: 1.4;
        display: flex;
        align-items: flex-start;
        gap: 10px;
        box-shadow: 0 12px 30px rgba(15, 23, 42, .15);
        border: 1px solid transparent;
        opacity: 0;
        pointer-events: none;
        transition: opacity .3s ease, transform .35s cubic-bezier(.22, .8, .3, 1);
    }
    .nl-alert.show {
        opacity: 1;
        transform: translateX(-50%) translateY(0);
        pointer-events: auto;
    }
    .nl-alert i { flex: none; margin-top: 2px; font-size: 15px; }
    .nl-alert .nl-msg { flex: 1; min-width: 0; }
    .nl-alert .nl-close {
        flex: none;
        background: transparent;
        border: none;
        cursor: pointer;
        color: currentColor;
        opacity: .55;
        padding: 0;
        margin-left: 4px;
        font-size: 14px;
        transition: opacity .2s;
    }
    .nl-alert .nl-close:hover { opacity: 1; }

    .nl-alert.success {
        background: #f0fdf4;
        color: #15803d;
        border-color: #bbf7d0;
    }
    .nl-alert.info {
        background: #eff6ff;
        color: #1d4ed8;
        border-color: #bfdbfe;
    }
    .nl-alert.error {
        background: #fef2f2;
        color: #b91c1c;
        border-color: #fecaca;
    }

    .dark .nl-alert.success { background: #052e16; color: #4ade80; border-color: #166534; }
    .dark .nl-alert.info    { background: #0c1e3e; color: #60a5fa; border-color: #1e3a8a; }
    .dark .nl-alert.error   { background: #3f0a0a; color: #fca5a5; border-color: #7f1d1d; }

    /* =====================================================
       TESTIMONIALS (index) — disamain sama halaman Testimonials
       di-scope ke #testimonials biar menang dari style layout
    ===================================================== */
    #testimonials .testimonial-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 26px;
        max-width: 1080px;
        margin: 0 auto 40px;
        padding: 0;
        align-items: stretch;
    }

    #testimonials .testimonial-card {
        position: relative;
        display: flex;
        flex-direction: column;
        height: 100%;
        background: #fff;
        border: 1px solid transparent;
        border-radius: 14px;
        padding: 30px 28px 24px;
        text-align: left;
        box-shadow: 0 10px 35px rgba(0, 0, 0, .045);
        overflow: hidden;
        transition: transform .35s ease, box-shadow .35s ease, border-color .35s ease;
    }
    #testimonials .testimonial-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: var(--primary);
        transform: scaleX(0);
        transform-origin: left;
        transition: transform .4s cubic-bezier(.22, .8, .3, 1);
    }
    #testimonials .testimonial-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 18px 40px rgba(0, 0, 0, .09);
        border-color: rgba(247, 108, 108, .18);
    }
    #testimonials .testimonial-card:hover::before { transform: scaleX(1); }

    #testimonials .testimonial-card .stars {
        color: var(--primary);
        font-size: 11px;
        letter-spacing: 2px;
        display: inline-block;
        margin: 0 0 14px;
        line-height: 1;
    }

    #testimonials .testimonial-card > p {
        color: var(--gray);
        font-size: 13.5px;
        line-height: 1.85;
        font-style: italic;
        flex: 1;
        margin: 0 0 18px;
        display: -webkit-box;
        -webkit-line-clamp: 5;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    #testimonials .testimonial-card .user {
        display: flex;
        align-items: center;
        gap: 12px;
        padding-top: 16px;
        margin-top: auto;
        border-top: 1px solid var(--line);
    }
    #testimonials .testimonial-card .user img {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        object-fit: cover;
        flex: none;
        box-shadow: 0 0 0 2px #fff, 0 0 0 3px var(--line);
        transition: box-shadow .3s ease;
    }
    #testimonials .testimonial-card:hover .user img {
        box-shadow: 0 0 0 2px #fff, 0 0 0 3px var(--primary);
    }
    #testimonials .testimonial-card .user > div {
        flex: 1;
        min-width: 0;
    }
    #testimonials .testimonial-card .user h4 {
        font-size: 14px;
        font-weight: 600;
        margin: 0 0 2px;
        color: var(--ink);
        line-height: 1.2;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    #testimonials .testimonial-card .user span {
        display: block;
        font-size: 11.5px;
        color: var(--gray);
        line-height: 1.2;
    }

    #testimonials .testimonial-card { cursor: pointer; }
    #testimonials .testimonial-card .user img { pointer-events: none; }
    #testimonials .testimonial-card .user > .tm-arrow {
        flex: none;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
        font-size: 12px;
        opacity: .45;
        transform: translateX(0);
        transition: transform .3s cubic-bezier(.22, .8, .3, 1), opacity .3s ease;
    }
    #testimonials .testimonial-card:hover .user > .tm-arrow {
        opacity: 1;
        transform: translateX(5px);
    }

    .tm-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, .6);
        backdrop-filter: blur(6px);
        z-index: 10000;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }
    .tm-overlay.active { display: flex; }
    .tm-content {
        background: #fff;
        border-radius: 20px;
        max-width: 500px;
        width: 100%;
        max-height: 90vh;
        overflow-y: auto;
        padding: 34px 32px 28px;
        position: relative;
        text-align: left;
        box-shadow: 0 20px 60px rgba(0, 0, 0, .25);
        animation: tmPopIn .3s ease;
    }
    @keyframes tmPopIn {
        from { transform: scale(.95); opacity: 0; }
        to   { transform: scale(1); opacity: 1; }
    }
    .tm-close {
        position: absolute;
        top: 14px;
        right: 14px;
        background: transparent;
        border: none;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        font-size: 13px;
        cursor: pointer;
        color: var(--gray);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background .25s ease, color .25s ease, transform .3s ease;
    }
    .tm-close:hover {
        background: rgba(247, 108, 108, .1);
        color: var(--primary);
        transform: rotate(90deg);
    }
    .tm-stars {
        color: var(--primary);
        font-size: 11px;
        letter-spacing: 2px;
        margin-bottom: 14px;
        line-height: 1;
    }
    .tm-quote {
        font-size: 15px;
        line-height: 1.9;
        color: var(--gray);
        font-style: italic;
        margin: 0 0 22px;
    }
    .tm-foot {
        display: flex;
        align-items: center;
        gap: 12px;
        padding-top: 18px;
        border-top: 1px solid var(--line);
    }
    .tm-foot img {
        width: 46px;
        height: 46px;
        border-radius: 50%;
        object-fit: cover;
        flex: none;
    }
    .tm-info { flex: 1; min-width: 0; }
    .tm-info h4 {
        font-size: 15px;
        font-weight: 600;
        margin: 0 0 2px;
        color: var(--ink);
        line-height: 1.2;
    }
    .tm-role {
        font-size: 11.5px;
        color: var(--gray);
        line-height: 1.2;
    }
    @media (prefers-reduced-motion: reduce) {
        #testimonials .testimonial-card .user > .tm-arrow,
        .tm-close,
        .tm-content { transition: none; animation: none; }
    }
    @media (max-width: 600px) {
        #testimonials .testimonial-card .user > .tm-arrow { opacity: .8; }
        .tm-content { padding: 30px 22px 24px; }
    }

    #testimonials .see-all-testimonials {
        display: table;
        position: relative;
        margin: 0 auto;
        padding: 6px 2px;
        background: transparent;
        border: none;
        box-shadow: none;
        font-size: 12.5px;
        font-weight: 600;
        letter-spacing: .3px;
        color: var(--ink);
        text-decoration: none;
        transition: color .25s ease;
    }
    #testimonials .see-all-testimonials::after {
        content: '';
        position: absolute;
        left: 0;
        right: 0;
        bottom: 0;
        height: 1px;
        background: var(--primary);
        transform: scaleX(.25);
        transform-origin: center;
        transition: transform .35s cubic-bezier(.22, .8, .3, 1);
    }
    #testimonials .see-all-testimonials:hover { color: var(--primary); }
    #testimonials .see-all-testimonials:hover::after { transform: scaleX(1); }

    @media (prefers-reduced-motion: reduce) {
        #testimonials .testimonial-card,
        #testimonials .testimonial-card::before,
        #testimonials .see-all-testimonials,
        #testimonials .see-all-testimonials::after { transition: none; }
    }
    @media (max-width: 900px) {
        #testimonials .testimonial-grid { grid-template-columns: 1fr 1fr; }
    }
    @media (max-width: 600px) {
        #testimonials .testimonial-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')

    {{-- ===== FLOATING ALERT NEWSLETTER ===== --}}
    @if(session('newsletter_success'))
        <div class="nl-alert success" id="nlAlert">
            <i class="fas fa-check-circle"></i>
            <div class="nl-msg">{{ session('newsletter_success') }}</div>
            <button type="button" class="nl-close" onclick="document.getElementById('nlAlert').classList.remove('show')">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    @if(session('newsletter_info'))
        <div class="nl-alert info" id="nlAlert">
            <i class="fas fa-info-circle"></i>
            <div class="nl-msg">{{ session('newsletter_info') }}</div>
            <button type="button" class="nl-close" onclick="document.getElementById('nlAlert').classList.remove('show')">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    @if(session('newsletter_error') || $errors->has('email'))
        <div class="nl-alert error" id="nlAlert">
            <i class="fas fa-exclamation-circle"></i>
            <div class="nl-msg">{{ session('newsletter_error') ?? $errors->first('email') }}</div>
            <button type="button" class="nl-close" onclick="document.getElementById('nlAlert').classList.remove('show')">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    {{-- HERO --}}
    @php
        $firstSlide   = $heroSliders->first();
        $firstTitle   = $firstSlide ? $formatHeroText($firstSlide->title) : '';
        $firstEyebrow = $firstSlide ? $formatHeroEyebrow($firstSlide->eyebrow) : '';
        $defaultTitle = '<span>SPA SALON</span><br>RELAX AND<br>REFRESH';
    @endphp

    <section class="hero" id="home">
        <img class="hero-bg" id="heroBg"
            src="{{ $firstSlide && $firstSlide->image ? asset('storage/'.$firstSlide->image) : asset('user/assets/lilinbesar.jpe') }}"
            alt="Hero Background">

        <div class="hero-content">
            <div class="eyebrow" id="heroEyebrow">
                {!! $firstEyebrow ?: '<span>ENJOY THE SOOTHING EXPERIENCE</span>' !!}
            </div>
            <h1 id="heroTitle">
                {!! $firstTitle ?: $defaultTitle !!}
            </h1>
        </div>

        <span class="hero-arrow prev" onclick="changeSlide(-1)">←</span>
        <span class="hero-arrow next" onclick="changeSlide(1)">→</span>

        <div class="hero-dots">
            @foreach($heroSliders as $i => $slide)
                <span class="{{ $i === 0 ? 'active' : '' }}" onclick="goToSlide({{ $i }})"></span>
            @endforeach
        </div>
    </section>

    {{-- WELCOME --}}
    <section class="welcome" id="about">
        <div class="container welcome-inner">
            <div class="welcome-text">
                <span class="eyebrow">THE SPA AND SALON HISTORY</span>
                <h2>Welcome to our spa and massage therapy center.</h2>
                <p>Step into a space where relaxation meets personalized care. We're here to help you slow down, recharge, and enjoy a moment dedicated entirely to yourself.</p>
                <p>Whether you're looking to release tension, refresh your body, or simply take a break from your day, discover the treatment that suits what you need.</p>
                <a href="{{ url('about') }}" class="btn">KNOW US MORE <i class="fas fa-arrow-right" style="margin-left: 10px;"></i></a>
            </div>
            <div class="welcome-image">
                <img src="{{ asset('user/assets/wlcm.jpg') }}" alt="Spa Products">
            </div>
        </div>
    </section>

    {{-- TREATMENTS --}}
    <section class="treatments" id="treatments">
        <div class="container">
            <h2>SPA TREATMENTS</h2>
            <p>Discover our carefully curated treatments designed to help you unwind and feel refreshed. From soothing massages to nourishing body treatments, every experience is created with your comfort in mind.</p>

            <div class="tabs" id="tabs">
                @foreach($treatmentCategories as $i => $cat)
                    <button class="{{ $i === 0 ? 'active' : '' }}"
                            onclick="changeTreatment('{{ Str::slug($cat) }}')">
                        {{ strtoupper($cat) }}
                    </button>
                @endforeach
            </div>

                @php
                    $firstCat = $treatmentCategories->first();
                    $catItems = $treatments->where('category', $firstCat)->values();

                    // Prioritas featured, fallback first (semua udah termasuk nonactive)
                    $t1 = $catItems->first(function ($t) {
                        return $t->is_featured;
                    }) ?? $catItems->first();

                    $img1 = $t1 && $t1->image   ? asset('storage/'.$t1->image)   : asset('user/assets/sep.jpe');
                    $img2 = $t1 && $t1->image_2 ? asset('storage/'.$t1->image_2) : asset('user/assets/download.jpe');
                @endphp

                <div class="treatment-detail">
                    <div class="treatment-images">
                        <img id="treatmentImg1"
                            src="{{ $img1 }}"
                            alt="{{ $t1->name ?? 'Treatment' }}"
                            onclick="openTreatmentLightbox(0)">
                        <img id="treatmentImg2"
                            src="{{ $img2 }}"
                            alt="{{ $t1->name ?? 'Treatment' }} — image 2"
                            onclick="openTreatmentLightbox(1)">
                    </div>
                <div class="treatment-text">
                    <h3 id="treatmentTitle">
                        {{ $t1->name ?? 'We use different types of massage for a variety of health and beauty benefit purposes.' }}
                    </h3>
                    <p id="treatmentDesc">
                        {{ $t1->description ?? 'Each treatment is thoughtfully designed to ease tension, promote relaxation, and leave you feeling refreshed from head to toe.' }}
                    </p>
                    <a id="findOutMoreBtn"
                       href="{{ url('treatments?kategori='.Str::slug($firstCat ?? '')) }}"
                       class="btn">
                        FIND OUT MORE <i class="fas fa-arrow-right" style="margin-left: 10px;"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- GALLERY --}}
        <div class="container gallery-section" id="gallery">
            <div class="gallery-header">
                <h2>Gallery</h2>
                <a href="{{ url('gallery') }}" class="see-more-link">See More →</a>
            </div>

            <div class="gallery-grid" id="galleryGrid">
                @forelse($galleries as $i => $gallery)
                    <img src="{{ asset('storage/'.$gallery->image) }}"
                         onclick="openLightbox({{ $i }})"
                         alt="{{ $gallery->title ?? 'Gallery' }}"
                         onerror="this.src='{{ asset('user/assets/wlcm.jpg') }}'">
                @empty
                    <p style="grid-column: 1 / -1; text-align: center; color: #999; padding: 40px;">
                        No gallery photos yet.
                    </p>
                @endforelse
            </div>

            {{-- 3 PACKAGES — Bawah Gallery --}}
            <div class="bottom-cards">
                @forelse($featuredPackages as $pkg)
                    <div class="bottom-card" onclick="window.location='{{ url('packages') }}'">
                        <div class="img-wrap">
                            <img src="{{ $pkg->image ? asset('storage/'.$pkg->image) : asset('user/assets/wlcm.jpg') }}"
                                 alt="{{ $pkg->name }}"
                                 onerror="this.src='{{ asset('user/assets/wlcm.jpg') }}'">
                            <div class="overlay"></div>
                        </div>
                        <h4>{{ $pkg->name }}</h4>
                        <span class="cat-tag">PACKAGE</span>
                        <p>{{ Str::limit($pkg->description ?? '', 120) }}</p>
                    </div>
                @empty
                    <p style="grid-column: 1 / -1; text-align: center; color: #999; padding: 40px;">
                        No featured packages yet.
                    </p>
                @endforelse
            </div>
        </div>
    </section>

    {{-- TESTIMONIALS --}}
    <section class="testimonials" id="testimonials">
        <div class="container">
            <h2>What Our Clients Say</h2>
            <div class="testimonial-grid">
                @forelse($testimonials as $t)
                    <div class="testimonial-card"
                         data-name="{{ $t->name }}"
                         data-role="{{ $t->role ?? 'Customer' }}"
                         data-rating="{{ $t->rating }}"
                         data-message="{{ $t->message }}"
                         data-photo="{{ $t->photo ? asset('storage/'.$t->photo) : 'https://ui-avatars.com/api/?name='.urlencode($t->name).'&background=f76c6c&color=fff' }}"
                         onclick="openTestimonialModal(this)">
                        <div class="stars">
                            @for($i = 0; $i < $t->rating; $i++)
                                <i class="fas fa-star"></i>
                            @endfor
                            @for($i = $t->rating; $i < 5; $i++)
                                <i class="far fa-star"></i>
                            @endfor
                        </div>
                        <p>"{{ $t->message }}"</p>
                        <div class="user">
                            <img src="{{ $t->photo ? asset('storage/'.$t->photo) : 'https://ui-avatars.com/api/?name='.urlencode($t->name).'&background=f76c6c&color=fff' }}"
                                 alt="{{ $t->name }}">
                            <div>
                                <h4>{{ $t->name }}</h4>
                                <span>{{ $t->role ?? 'Customer' }}</span>
                            </div>
                            <div class="tm-arrow"><i class="fas fa-arrow-right"></i></div>
                        </div>
                    </div>
                @empty
                    <p style="grid-column: 1 / -1; text-align: center; color: #999; padding: 40px;">
                        No testimonials yet.
                    </p>
                @endforelse
            </div>
            <a href="{{ url('testimonials') }}" class="see-all-testimonials">See All Testimonials →</a>
        </div>
    </section>

    {{-- TESTIMONIAL MODAL --}}
    <div class="tm-overlay" id="tmOverlay">
        <div class="tm-content">
            <button class="tm-close" onclick="closeTestimonialModal()" aria-label="Close">
                <i class="fas fa-times"></i>
            </button>

            <div class="tm-stars" id="tmStars"></div>
            <p class="tm-quote" id="tmQuote"></p>

            <div class="tm-foot">
                <img id="tmImage" src="" alt="Client Photo">
                <div class="tm-info">
                    <h4 id="tmName"></h4>
                    <div class="tm-role" id="tmRole"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- QUESTIONS --}}
    <section class="questions" id="questions">
        <div class="container">
            <h2>ANY QUESTIONS?</h2>
            <p>We're here to help you find the right treatment and make your spa experience comfortable, relaxing, and enjoyable.</p>

            <div class="info-cards" id="contact">
                <a href="#" class="info-card">
                    <i class="fa-regular fa-map"></i>
                    <h4>CONTACT ADDRESS</h4>
                    <p>{!! nl2br(e(\App\Models\Setting::get('contact_address', 'mispaa, Korea'))) !!}</p>
                </a>
                <a href="tel:{{ \App\Models\Setting::get('contact_phone', '0812 3456 7890') }}" class="info-card">
                    <i class="fas fa-phone-alt"></i>
                    <h4>CALL US TODAY!</h4>
                    <p>{{ \App\Models\Setting::get('contact_phone', '0812 3456 7890') }}</p>
                </a>
                <a href="mailto:{{ \App\Models\Setting::get('contact_email', 'info@spaaa.com') }}" class="info-card">
                    <i class="fa-regular fa-envelope"></i>
                    <h4>EMAIL</h4>
                    <p>{{ \App\Models\Setting::get('contact_email', 'info@spaaa.com') }}</p>
                </a>
                <a href="#" class="info-card">
                    <i class="fa-regular fa-clock"></i>
                    <h4>WORKING HOURS</h4>
                    <p>
                        {{ \App\Models\Setting::get('weekday_label', 'Mon - Sat') }}:
                        {{ \App\Models\Setting::get('weekday_open', '09:00') }} -
                        {{ \App\Models\Setting::get('weekday_close', '21:00') }}<br>
                        {{ \App\Models\Setting::get('weekend_label', 'Sunday') }}:
                        {{ \App\Models\Setting::get('weekend_open', '09:00') }} -
                        {{ \App\Models\Setting::get('weekend_close', '19:00') }}
                    </p>
                </a>
            </div>
        </div>
    </section>

    {{-- NEWSLETTER --}}
    <section class="newsletter" id="newsletter">
        <div class="container newsletter-inner">
            <h3>Subscribe Our Newsletter</h3>

            <form class="newsletter-form" method="POST" action="{{ url('newsletter/subscribe') }}">
                @csrf
                <input type="email" name="email" placeholder="Your email address" required>
                <button type="submit"><i class="fas fa-arrow-right"></i></button>
            </form>

            @php
                $socialIg = \App\Models\Setting::get('social_instagram');
                $socialWa = \App\Models\Setting::get('social_whatsapp');
                $socialFb = \App\Models\Setting::get('social_facebook');
                $socialTw = \App\Models\Setting::get('social_twitter');
                $socialLi = \App\Models\Setting::get('social_linkedin');
            @endphp

            <div class="social-icons">
                @if($socialIg)
                    <a href="{{ $socialIg }}" target="_blank" rel="noopener"><i class="fab fa-instagram"></i></a>
                @endif
                @if($socialWa)
                    <a href="{{ $socialWa }}" target="_blank" rel="noopener"><i class="fab fa-whatsapp"></i></a>
                @endif
                @if($socialFb)
                    <a href="{{ $socialFb }}" target="_blank" rel="noopener"><i class="fab fa-facebook-f"></i></a>
                @endif
                @if($socialTw)
                    <a href="{{ $socialTw }}" target="_blank" rel="noopener"><i class="fab fa-twitter"></i></a>
                @endif
                @if($socialLi)
                    <a href="{{ $socialLi }}" target="_blank" rel="noopener"><i class="fab fa-linkedin-in"></i></a>
                @endif
            </div>
        </div>
    </section>

@endsection

@push('scripts')
<script>
    const heroSlides    = {!! json_encode($heroSlidesJson) !!};
    const treatmentData = {!! json_encode($treatmentDataJson) !!};
    const galleryImages = {!! json_encode($galleryImagesJson) !!};

    // ============================================
    // HELPERS
    // ============================================
    function escapeHtml(str) {
        if (str === null || str === undefined) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function formatTitle(text) {
        if (!text) return '';
        const lines = String(text).split(/\r\n|\r|\n/).map(s => s.trim()).filter(Boolean);
        if (!lines.length) return '';
        const first = lines.shift();
        let html = '<span>' + escapeHtml(first) + '</span>';
        lines.forEach(line => html += '<br>' + escapeHtml(line));
        return html;
    }

    // ============================================
    // HERO SLIDER
    // ============================================
    let currentSlide = 0;
    const heroBg      = document.getElementById('heroBg');
    const heroEyebrow = document.getElementById('heroEyebrow');
    const heroTitle   = document.getElementById('heroTitle');
    const heroDots    = document.querySelectorAll('.hero-dots span');

    function updateSlide() {
        if (!heroSlides.length) return;

        const slide = heroSlides[currentSlide];
        if (!slide) return;

        if (slide.image) {
            heroBg.src = slide.image;
        }

        if (slide.eyebrow && String(slide.eyebrow).trim() !== '') {
            heroEyebrow.innerHTML = '<span>' + escapeHtml(slide.eyebrow) + '</span>';
        } else {
            heroEyebrow.innerHTML = '<span>ENJOY THE SOOTHING EXPERIENCE</span>';
        }

        const titleHtml = formatTitle(slide.title);
        heroTitle.innerHTML = titleHtml || '<span>SPA SALON</span><br>RELAX AND<br>REFRESH';

        heroDots.forEach((dot, i) => dot.classList.toggle('active', i === currentSlide));
    }

    function changeSlide(dir) {
        if (!heroSlides.length) return;
        currentSlide = (currentSlide + dir + heroSlides.length) % heroSlides.length;
        updateSlide();
    }

    function goToSlide(i) {
        currentSlide = i;
        updateSlide();
    }

    if (heroSlides.length > 1) {
        setInterval(() => changeSlide(1), 4000);
    }

    // ============================================
    // TREATMENT TABS
    // ============================================
    const treatmentTitle = document.getElementById('treatmentTitle');
    const treatmentDesc  = document.getElementById('treatmentDesc');
    const treatmentImg1  = document.getElementById('treatmentImg1');
    const treatmentImg2  = document.getElementById('treatmentImg2');
    const findOutMoreBtn = document.getElementById('findOutMoreBtn');
    const tabBtns        = document.querySelectorAll('.tabs button');

    window.treatmentImages = [treatmentImg1.src, treatmentImg2.src];

    function changeTreatment(key) {
        tabBtns.forEach(b => b.classList.remove('active'));
        if (window.event && window.event.target) window.event.target.classList.add('active');

        const data = treatmentData[key];
        if (!data) return;

        treatmentTitle.innerText = data.title;
        treatmentDesc.innerText  = data.desc;
        treatmentImg1.src        = data.img1;
        treatmentImg2.src        = data.img2;
        findOutMoreBtn.href      = '{{ url('treatments') }}?kategori=' + data.kategori;

        window.treatmentImages = [data.img1, data.img2];
    }

    // ============================================
    // TREATMENT LIGHTBOX
    // ============================================
    let treatmentIndex = 0;

    function openTreatmentLightbox(i) {
        treatmentIndex = i;
        document.getElementById('treatmentLightboxImage').src = window.treatmentImages[treatmentIndex];
        document.getElementById('treatmentLightbox').classList.add('active');
    }

    function closeTreatmentLightbox() {
        document.getElementById('treatmentLightbox').classList.remove('active');
    }

    function changeTreatmentLightbox(dir) {
        treatmentIndex = (treatmentIndex + dir + window.treatmentImages.length) % window.treatmentImages.length;
        document.getElementById('treatmentLightboxImage').src = window.treatmentImages[treatmentIndex];
    }

    // ============================================
    // GALLERY LIGHTBOX
    // ============================================
    let galleryIndex = 0;

    function openLightbox(i) {
        galleryIndex = i;
        document.getElementById('galleryLightboxImage').src = galleryImages[galleryIndex];
        document.getElementById('galleryLightbox').classList.add('active');
    }

    function closeGalleryLightbox() {
        document.getElementById('galleryLightbox').classList.remove('active');
    }

    function changeGalleryLightbox(dir) {
        galleryIndex = (galleryIndex + dir + galleryImages.length) % galleryImages.length;
        document.getElementById('galleryLightboxImage').src = galleryImages[galleryIndex];
    }

    // ============================================
    // TESTIMONIAL MODAL
    // ============================================
    function openTestimonialModal(card) {
        const rating = parseInt(card.dataset.rating, 10) || 0;
        let stars = '';
        for (let i = 0; i < 5; i++) {
            stars += '<i class="' + (i < rating ? 'fas' : 'far') + ' fa-star"></i>';
        }

        document.getElementById('tmStars').innerHTML = stars;
        document.getElementById('tmQuote').textContent = '"' + card.dataset.message + '"';
        document.getElementById('tmImage').src = card.dataset.photo;
        document.getElementById('tmName').textContent = card.dataset.name;
        document.getElementById('tmRole').textContent = card.dataset.role;

        document.getElementById('tmOverlay').classList.add('active');
    }

    function closeTestimonialModal() {
        document.getElementById('tmOverlay').classList.remove('active');
    }

    document.getElementById('tmOverlay').addEventListener('click', function (e) {
        if (e.target === this) closeTestimonialModal();
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeTestimonialModal();
            closeTreatmentLightbox();
            closeGalleryLightbox();
        }
    });

    updateSlide();

    // ============================================
    // NEWSLETTER ALERT — auto-show + auto-dismiss
    // ============================================
    (function () {
        var alert = document.getElementById('nlAlert');
        if (!alert) return;

        requestAnimationFrame(function () {
            requestAnimationFrame(function () {
                alert.classList.add('show');
            });
        });

        var t = setTimeout(function () {
            alert.classList.remove('show');
            setTimeout(function () { if (alert.parentNode) alert.remove(); }, 400);
        }, 6000);

        alert.addEventListener('mouseenter', function () { clearTimeout(t); });
        alert.addEventListener('mouseleave', function () {
            t = setTimeout(function () {
                alert.classList.remove('show');
                setTimeout(function () { if (alert.parentNode) alert.remove(); }, 400);
            }, 3000);
        });
    })();
</script>
@endpush