@extends('user.layouts.main')

@section('title', 'Gallery — LeadGen Spa')
@section('meta_description', 'Immerse yourself in tranquility. A glimpse into our peaceful spaces designed for your ultimate relaxation.')
@section('footer_type', 'grid')

@push('styles')
<style>
    /* ===== HERO ===== */
    .gal-hero {
        position: relative;
        height: 380px;
        background:
            linear-gradient(180deg, rgba(10, 7, 6, 0.55), rgba(8, 6, 5, 0.62)),
            url('{{ asset("user/assets/lilinbesar.jpe") }}') center 35% / cover no-repeat;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        color: #fff;
    }
    .gal-hero-content { max-width: 640px; padding: 0 24px; }
    .gal-hero h1 {
        font-size: 2.9rem;
        font-weight: 700;
        margin: 0 0 18px;
        letter-spacing: -0.5px;
    }
    .gal-hero p {
        font-size: 1rem;
        color: rgba(255, 255, 255, 0.92);
        line-height: 1.6;
        margin: 0;
    }

    /* ===== FILTERS ===== */
    .filters-wrap {
        max-width: 1180px;
        margin: 0 auto;
        padding: 52px 32px 0;
    }
    .filters {
        display: flex;
        justify-content: center;
        gap: 30px;
        flex-wrap: wrap;
    }
    .filter {
        font-family: 'Montserrat', sans-serif;
        font-size: 0.78rem;
        font-weight: 700;
        letter-spacing: 0.6px;
        text-transform: uppercase;
        color: #332f2e;
        padding-bottom: 10px;
        position: relative;
        cursor: pointer;
        background: none;
        border: none;
        transition: color .25s;
    }
    .filter:hover { color: var(--primary); }
    .filter.active { color: var(--maroon); }
    .filter.active::after {
        content: "";
        position: absolute;
        left: 0; right: 0; bottom: -1px;
        height: 2px;
        background: var(--maroon);
    }

    /* ===== GRID ===== */
    .grid {
        max-width: 1180px;
        margin: 0 auto;
        padding: 34px 32px 12px;
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 22px;
    }
    .grid-item {
        border-radius: 10px;
        overflow: hidden;
        position: relative;
        transition: transform 0.3s, box-shadow 0.3s;
        cursor: pointer;
    }
    .grid-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    .grid-item img {
        width: 100%;
        height: 225px;
        object-fit: cover;
        transition: transform 0.5s ease;
        display: block;
    }
    .grid-item:hover img {
        transform: scale(1.05);
    }
    .empty-state {
        grid-column: 1 / -1;
        text-align: center;
        padding: 80px 20px;
        color: var(--gray);
        font-size: 14px;
    }

    /* ===== LOAD MORE ===== */
    .load-more-wrap {
        text-align: center;
        padding: 44px 32px 100px;
    }
    .btn-outline {
        display: inline-block;
        border: 1px solid #c9c0bc;
        color: var(--ink);
        font-family: 'Montserrat', sans-serif;
        font-weight: 600;
        font-size: 0.85rem;
        padding: 12px 30px;
        border-radius: 6px;
        background: transparent;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .btn-outline:hover {
        border-color: var(--primary);
        color: var(--primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(247, 108, 108, 0.1);
    }
    .btn-outline.hidden { display: none; }

    /* ===== LIGHTBOX ===== */
    .lightbox {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.92);
        backdrop-filter: blur(8px);
        z-index: 10000;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 40px;
    }
    .lightbox.active { display: flex; }
    .lightbox-content {
        position: relative;
        max-width: 85%;
        max-height: 85%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .lightbox-content img {
        max-width: 100%;
        max-height: 70vh;
        object-fit: contain;
        border-radius: 8px;
    }
    .lightbox-close {
        position: absolute;
        top: -80px;
        right: -80px;
        width: 30px;
        height: 30px;
        background: transparent;
        border: none;
        cursor: pointer;
        color: #fff;
        font-size: 28px;
        font-weight: 300;
        transition: all 0.3s ease;
        line-height: 1;
    }
    .lightbox-close:hover { color: var(--primary); transform: rotate(90deg); }
    .lightbox-prev, .lightbox-next {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: transparent;
        border: none;
        color: #fff;
        font-size: 36px;
        font-weight: 300;
        cursor: pointer;
        transition: all 0.3s ease;
        opacity: 0.6;
    }
    .lightbox-prev:hover, .lightbox-next:hover { opacity: 1; color: var(--primary); }
    .lightbox-prev { left: -120px; }
    .lightbox-next { right: -120px; }

    @media (max-width: 900px) {
        .grid { grid-template-columns: 1fr 1fr; }
        .lightbox-prev { left: 10px; }
        .lightbox-next { right: 10px; }
        .lightbox-close { top: -50px; right: 0; }
    }
    @media (max-width: 600px) {
        .grid { grid-template-columns: 1fr; }
        .gal-hero h1 { font-size: 2rem; }
    }
</style>
@endpush

@section('content')

    {{-- HERO --}}
    <section class="gal-hero">
        <div class="gal-hero-content">
            <h1>Our Sanctuary</h1>
            <p>Immerse yourself in tranquility. A glimpse into our peaceful spaces designed for your ultimate relaxation.</p>
        </div>
    </section>

    {{-- FILTERS --}}
    <div class="filters-wrap">
        <div class="filters" id="galleryFilters">
            <button type="button" class="filter active" data-filter="all">All</button>
            @foreach($categories as $cat)
                <button type="button"
                        class="filter"
                        data-filter="{{ $cat->slug }}">
                    {{ $cat->name }}
                </button>
            @endforeach
        </div>
    </div>

    {{-- GRID --}}
    <div class="grid" id="galleryGrid"></div>

    {{-- LOAD MORE --}}
    <div class="load-more-wrap">
        <button type="button" class="btn-outline" id="loadMoreBtn" onclick="loadMore()">
            Load More
        </button>
    </div>

    {{-- LIGHTBOX --}}
    <div class="lightbox" id="galleryLightbox">
        <div class="lightbox-content">
            <img id="galleryLightboxImage" src="" alt="Gallery Image">
            <button class="lightbox-close" onclick="closeGalleryLightbox()">
                <i class="fas fa-times"></i>
            </button>
            <button class="lightbox-prev" onclick="changeGalleryLightbox(-1)">‹</button>
            <button class="lightbox-next" onclick="changeGalleryLightbox(1)">›</button>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    // ===== DATA dari controller =====
    const allGallery = {!! json_encode($galleriesJson) !!};

    const FALLBACK_IMG = '{{ asset("user/assets/wlcm.jpg") }}';

    // ===== STATE =====
    let currentFilter = 'all';
    let visibleCount = 9;
    let lightboxIndex = 0;
    let filteredImages = [...allGallery];

    // ===== RENDER =====
    function renderGallery() {
        const grid = document.getElementById('galleryGrid');
        grid.innerHTML = '';

        // Filter by category
        filteredImages = currentFilter === 'all'
            ? [...allGallery]
            : allGallery.filter(item => item.category === currentFilter);

        const items = filteredImages.slice(0, visibleCount);

        if (items.length === 0) {
            grid.innerHTML = '<div class="empty-state">No photos in this category yet.</div>';
            document.getElementById('loadMoreBtn').classList.add('hidden');
            return;
        }

        items.forEach((item, index) => {
            const div = document.createElement('div');
            div.className = 'grid-item';
            div.setAttribute('data-index', index);
            div.setAttribute('onclick', `openLightbox(${index})`);
            div.innerHTML = `
                <img src="${item.src}" alt="${item.title}" onerror="this.src='${FALLBACK_IMG}'">
            `;
            grid.appendChild(div);
        });

        // Toggle load more
        const btn = document.getElementById('loadMoreBtn');
        if (visibleCount >= filteredImages.length) {
            btn.classList.add('hidden');
        } else {
            btn.classList.remove('hidden');
        }
    }

    // ===== FILTER =====
    document.querySelectorAll('#galleryFilters .filter').forEach(btn => {
        btn.addEventListener('click', function () {
            document.querySelectorAll('#galleryFilters .filter').forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            currentFilter = this.dataset.filter;
            visibleCount = 9;
            renderGallery();
        });
    });

    // ===== LOAD MORE =====
    function loadMore() {
        visibleCount += 3;
        renderGallery();
    }

    // ===== LIGHTBOX =====
    function openLightbox(index) {
        lightboxIndex = index;
        document.getElementById('galleryLightboxImage').src = filteredImages[lightboxIndex].src;
        document.getElementById('galleryLightbox').classList.add('active');
    }

    function closeGalleryLightbox() {
        document.getElementById('galleryLightbox').classList.remove('active');
    }

    function changeGalleryLightbox(dir) {
        lightboxIndex += dir;
        if (lightboxIndex < 0) lightboxIndex = filteredImages.length - 1;
        if (lightboxIndex >= filteredImages.length) lightboxIndex = 0;
        document.getElementById('galleryLightboxImage').src = filteredImages[lightboxIndex].src;
    }

    // ESC close
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeGalleryLightbox();
    });

    // ===== INIT =====
    renderGallery();
</script>
@endpush