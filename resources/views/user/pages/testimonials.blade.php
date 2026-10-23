@extends('user.layouts.main')

@section('title', 'Testimonials — LeadGen Spa')
@section('meta_description', 'Discover the transformative experiences of our guests. Real stories of relaxation, rejuvenation, and holistic wellness.')
@section('footer_type', 'grid')

@push('styles')
<style>
    /* ===== HERO ===== */
    .tst-hero {
        position: relative;
        padding: 110px 20px;
        text-align: center;
        background: linear-gradient(rgba(10, 8, 7, .68), rgba(10, 8, 7, .68)),
            url('{{ asset("user/assets/lilinbesar.jpe") }}') center/cover;
        overflow: hidden;
        color: #fff;
    }
    .tst-hero .eyebrow {
        color: var(--primary);
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 2.5px;
        margin-bottom: 18px;
        text-transform: uppercase;
    }
    .tst-hero h1 {
        font-size: clamp(34px, 5.5vw, 54px);
        font-weight: 800;
        line-height: 1.2;
        margin-bottom: 22px;
        letter-spacing: -.5px;
    }
    .tst-hero p {
        color: #e6e6e6;
        font-size: 14px;
        max-width: 600px;
        margin: 0 auto;
        line-height: 1.8;
    }

    /* ===== GRID ===== */
    .testimonials {
        padding: 80px 0 70px;
        text-align: left; /* ← FIX: paksa rata kiri */
    }
    .testimonial-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 26px;
        max-width: 1080px;
        margin: 0 auto 44px;
        padding: 0 32px;
        align-items: stretch;
        text-align: left; /* ← FIX */
    }

    /* ===== CARD ===== */
    .t-card {
        background: #fff;
        border: 1px solid transparent;
        border-radius: 14px;
        padding: 30px 28px 24px;
        box-shadow: 0 10px 35px rgba(0, 0, 0, .045);
        transition: transform .35s ease, box-shadow .35s ease, border-color .35s ease;
        cursor: pointer;
        animation: fadeInUp .6s ease both;
        animation-delay: var(--reveal-delay, 0s);
        overflow: hidden;

        display: flex;
        flex-direction: column;
        height: 100%;
        position: relative;

        text-align: left; /* ← FIX: paksa rata kiri di card */
    }
    /* garis aksen tipis di atas card, muncul saat hover */
    .t-card::before {
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
    .t-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 18px 40px rgba(0, 0, 0, .09);
        border-color: rgba(247, 108, 108, .18);
    }
    .t-card:hover::before { transform: scaleX(1); }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(26px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* ===== STARS (di atas) ===== */
    .t-card .t-stars,
    .t-stars {
        color: var(--primary);
        font-size: 11px;
        letter-spacing: 2px;
        display: block;           /* ← FIX: dari inline-block → block */
        text-align: left;         /* ← FIX: paksa rata kiri */
        margin-bottom: 14px;
        line-height: 1;
    }

    /* ===== QUOTE (tengah, flex-grow) ===== */
    .t-card .t-quote,
    .t-quote {
        color: var(--gray);
        font-size: 13.5px;
        line-height: 1.85;
        font-style: italic;
        flex: 1;
        margin: 0 0 18px;
        word-break: break-word;

        display: -webkit-box;
        -webkit-line-clamp: 5;
        -webkit-box-orient: vertical;
        overflow: hidden;

        text-align: left;         /* ← FIX: paksa rata kiri */
    }

    /* ===== FOOTER: AVATAR + NAMA (di bawah) ===== */
    .t-card .t-foot,
    .t-foot {
        display: flex;
        align-items: center;
        justify-content: flex-start;  /* ← FIX: dari center → flex-start */
        gap: 12px;
        padding-top: 16px;
        border-top: 1px solid var(--line);
        margin-top: auto;
        text-align: left;             /* ← FIX */
    }
    .t-foot img {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        object-fit: cover;
        flex: none;
        cursor: default;
        pointer-events: none;
        box-shadow: 0 0 0 2px #fff, 0 0 0 3px var(--line);
        transition: box-shadow .3s ease;
    }
    .t-card:hover .t-foot img {
        box-shadow: 0 0 0 2px #fff, 0 0 0 3px var(--primary);
    }
    .t-foot-info {
        flex: 1;
        min-width: 0;
        text-align: left;        /* ← FIX */
    }
    .t-foot-info h4 {
        font-size: 14px;
        font-weight: 600;
        margin: 0 0 2px;
        color: var(--ink);
        line-height: 1.2;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        text-align: left;        /* ← FIX */
    }
    .t-foot-info .role {
        font-size: 11.5px;
        color: var(--gray);
        line-height: 1.2;
        text-align: left;        /* ← FIX */
    }

    /* ===== ARROW — simpel, tanpa lingkaran/background ===== */
    .t-arrow {
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
    .t-card:hover .t-arrow {
        opacity: 1;
        transform: translateX(5px);
    }

    /* ===== LOAD MORE ===== */
    .load-more {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        margin: 0 auto;
        padding: 6px 2px;
        background: transparent;
        border: none;
        font-size: 12.5px;
        font-weight: 600;
        letter-spacing: .3px;
        color: var(--ink);
        cursor: pointer;
        font-family: 'Montserrat', sans-serif;
        position: relative;
        transition: color .25s ease;
    }
    .load-more::after {
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
    .load-more i {
        font-size: 10px;
        transition: transform .3s ease;
    }
    .load-more:hover { color: var(--primary); }
    .load-more:hover::after { transform: scaleX(1); }
    .load-more:hover i { transform: translateY(3px); }
    .load-more.hidden { display: none; }

    .empty-state {
        grid-column: 1 / -1;
        text-align: center;
        padding: 60px 20px;
        color: var(--gray);
        font-size: 14px;
    }

    /* ===== MODAL ===== */
    .modal-overlay {
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
    .modal-overlay.active { display: flex; }

    .modal-content {
        background: #fff;
        border-radius: 20px;
        max-width: 500px;
        width: 100%;
        padding: 34px 32px 28px;
        position: relative;
        box-shadow: 0 20px 60px rgba(0, 0, 0, .25);
        animation: popIn .3s ease;
        text-align: left;        /* ← FIX: modal juga rata kiri */
    }
    @keyframes popIn {
        from { transform: scale(.95); opacity: 0; }
        to   { transform: scale(1); opacity: 1; }
    }

    .modal-close {
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
    .modal-close:hover {
        background: rgba(247, 108, 108, .1);
        color: var(--primary);
        transform: rotate(90deg);
    }

    .modal-content .t-stars { margin-bottom: 14px; }
    .modal-content .t-quote {
        font-size: 15px;
        line-height: 1.9;
        color: var(--gray);
        font-style: italic;
        margin-bottom: 22px;
        display: block;
        -webkit-line-clamp: unset;
        -webkit-box-orient: unset;
        overflow: visible;
        flex: none;
    }
    .modal-content .t-foot {
        padding-top: 18px;
        margin-top: 0;
    }
    .modal-content .t-foot img {
        width: 46px;
        height: 46px;
    }
    .modal-content .t-foot-info h4 { font-size: 15px; }
    .modal-content .t-arrow { display: none; }

    @media (prefers-reduced-motion: reduce) {
        .t-card, .t-card::before, .t-arrow, .load-more, .load-more::after, .load-more i, .modal-content {
            animation: none;
            transition: none;
        }
    }
    @media (max-width: 900px) {
        .testimonial-grid { grid-template-columns: 1fr 1fr; }
    }
    @media (max-width: 600px) {
        .testimonial-grid {
            grid-template-columns: 1fr;
            padding: 0 20px;
            gap: 18px;
        }
        .t-card {
            padding: 26px 22px 20px;
        }
        .t-arrow { opacity: .8; }
    }
</style>
@endpush

@section('content')

    {{-- HERO --}}
    <section class="tst-hero">
        <div class="eyebrow">KIND WORDS</div>
        <h1>What Our Clients Say</h1>
        <p>Discover the transformative experiences of our guests. Real stories of relaxation, rejuvenation, and holistic wellness from those who have found their sanctuary with us.</p>
    </section>

    {{-- TESTIMONIALS --}}
    <section class="testimonials">
        <div class="testimonial-grid" id="testimonialGrid"></div>

        <button class="load-more" id="loadMoreBtn" onclick="loadMore()">
            Load More Stories <i class="fas fa-chevron-down"></i>
        </button>
    </section>

    {{-- MODAL --}}
    <div class="modal-overlay" id="modalOverlay">
        <div class="modal-content">
            <button class="modal-close" onclick="closeModal()" aria-label="Close">
                <i class="fas fa-times"></i>
            </button>

            <div class="t-stars" id="modalStars"></div>
            <p class="t-quote" id="modalQuote"></p>

            <div class="t-foot">
                <img id="modalImage" src="" alt="Client Photo">
                <div class="t-foot-info">
                    <h4 id="modalName"></h4>
                    <div class="role" id="modalRole">Customers</div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    // ===== DATA dari controller =====
    const testimonialsData = {!! json_encode($testimonialsJson) !!};

    // ===== STATE =====
    let visibleCount = 9;

    const grid = document.getElementById('testimonialGrid');
    const loadMoreBtn = document.getElementById('loadMoreBtn');

    // ===== STAR MARKUP =====
    function starsMarkup(count) {
        let out = '';
        for (let i = 0; i < 5; i++) {
            out += `<i class="fa-solid fa-star" style="opacity:${i < count ? 1 : .25}"></i>`;
        }
        return out;
    }

    // ===== FALLBACK PHOTO =====
    function getPhoto(item) {
        if (item.photo && String(item.photo).trim() !== '') {
            return item.photo;
        }
        return 'https://ui-avatars.com/api/?name=' + encodeURIComponent(item.name || 'User') + '&background=f76c6c&color=fff&size=100';
    }

    // ===== RENDER =====
    function renderTestimonials() {
        grid.innerHTML = '';

        if (testimonialsData.length === 0) {
            grid.innerHTML = '<div class="empty-state">No testimonials yet.</div>';
            loadMoreBtn.classList.add('hidden');
            return;
        }

        const items = testimonialsData.slice(0, visibleCount);
        items.forEach((item, index) => {
            const card = document.createElement('div');
            card.className = 't-card';
            card.style.setProperty('--reveal-delay', (index % 3) * 0.12 + 's');
            card.onclick = function() { openModal(index); };

            card.innerHTML = `
                <div class="t-stars">${starsMarkup(item.rating)}</div>
                <p class="t-quote">"${item.message}"</p>
                <div class="t-foot">
                    <img src="${getPhoto(item)}" alt="${item.name}">
                    <div class="t-foot-info">
                        <h4>${item.name}</h4>
                        <div class="role">${item.role || 'Customers'}</div>
                    </div>
                    <div class="t-arrow">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </div>
            `;
            grid.appendChild(card);
        });

        if (testimonialsData.length <= 9) {
            loadMoreBtn.classList.add('hidden');
        } else if (visibleCount >= testimonialsData.length) {
            loadMoreBtn.classList.add('hidden');
        } else {
            loadMoreBtn.classList.remove('hidden');
        }
    }

    // ===== LOAD MORE =====
    function loadMore() {
        visibleCount += 3;
        renderTestimonials();
    }

    // ===== MODAL =====
    function openModal(index) {
        const item = testimonialsData[index];
        if (!item) return;

        document.getElementById('modalImage').src = getPhoto(item);
        document.getElementById('modalName').textContent = item.name;
        document.getElementById('modalRole').textContent = item.role || 'Customers';
        document.getElementById('modalStars').innerHTML = starsMarkup(item.rating);
        document.getElementById('modalQuote').textContent = '"' + item.message + '"';

        document.getElementById('modalOverlay').classList.add('active');
    }

    function closeModal() {
        document.getElementById('modalOverlay').classList.remove('active');
    }

    // Close modal kalo klik overlay
    document.getElementById('modalOverlay').addEventListener('click', function(e) {
        if (e.target === this) closeModal();
    });

    // ESC close
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeModal();
    });

    // ===== INIT =====
    renderTestimonials();
</script>
@endpush