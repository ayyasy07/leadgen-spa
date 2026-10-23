@extends('user.layouts.main')

@section('title', 'Spa Packages — LeadGen Spa')
@section('meta_description', 'Indulge in our curated spa packages designed for ultimate relaxation and rejuvenation.')
@section('footer_type', 'grid')

@push('styles')
<style>
    .pkg-hero {
        position: relative;
        height: 52vw;
        max-height: 460px;
        min-height: 320px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        background: linear-gradient(rgba(20, 14, 10, .35), rgba(20, 14, 10, .55)),
            url('{{ asset("user/assets/lilinbesar.jpe") }}') center/cover;
        color: #fff;
    }
    .pkg-hero h1 {
        color: var(--primary);
        font-size: clamp(36px, 6vw, 58px);
        font-weight: 800;
        margin-bottom: 18px;
        letter-spacing: -.5px;
    }
    .pkg-hero p {
        color: #f2f2f2;
        font-size: 14.5px;
        max-width: 560px;
        margin: 0 auto;
        line-height: 1.75;
        padding: 0 20px;
    }

    .curated {
        background: var(--bg-cream);
        padding: 100px 0;
    }
    .curated .head {
        text-align: center;
        margin-bottom: 60px;
    }
    .curated h2 {
        font-size: 32px;
        font-weight: 700;
        margin-bottom: 14px;
    }
    .curated .head p {
        color: var(--gray);
        font-size: 14px;
    }

    .pkg-filters {
        display: flex;
        justify-content: center;
        gap: 30px;
        margin-bottom: 44px;
        flex-wrap: wrap;
    }
    .filter-btn {
        border: none;
        background: transparent;
        font-family: 'Montserrat', sans-serif;
        font-size: 12.5px;
        font-weight: 700;
        letter-spacing: .6px;
        text-transform: uppercase;
        color: #332f2e;
        padding-bottom: 10px;
        position: relative;
        cursor: pointer;
        transition: color .25s;
    }
    .filter-btn:hover { color: var(--primary); }
    .filter-btn.active { color: var(--primary-dark); }
    .filter-btn.active::after {
        content: '';
        position: absolute;
        left: 0; right: 0; bottom: -1px;
        height: 2px;
        background: var(--primary-dark);
    }

    .pkg-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 30px;
        max-width: 1000px;
        margin: 0 auto;
    }
    .pkg-card {
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
        text-align: center;
        box-shadow: 0 5px 20px rgba(0, 0, 0, .05);
        border: 1px solid var(--line);
        display: flex;
        flex-direction: column;
        transition: all .3s ease;
    }
    .pkg-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, .1);
        border-color: var(--primary);
    }
    .pkg-card.popular { border-color: var(--primary); }

    .pkg-media {
        position: relative;
        cursor: pointer;
        overflow: hidden;
    }
    .pkg-media img {
        width: 100%;
        height: 180px;
        object-fit: cover;
        transition: transform .5s ease;
    }
    .pkg-media:hover img { transform: scale(1.05); }

    .popular-badge {
        position: absolute;
        top: 14px;
        right: 0;
        background: var(--primary);
        color: #fff;
        font-size: 11px;
        font-weight: 600;
        padding: 6px 16px;
        border-radius: 20px 0 0 20px;
        z-index: 2;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .pkg-body {
        padding: 32px 26px 30px;
        display: flex;
        flex-direction: column;
        flex: 1;
    }
    .pkg-body h3 {
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 10px;
    }
    .pkg-price {
        color: var(--maroon);
        font-size: 30px;
        font-weight: 700;
        margin-bottom: 6px;
    }
    .pkg-unit {
        font-size: 13px;
        font-weight: 600;
        color: var(--gray);
    }
    .pkg-saving {
        display: inline-block;
        background: rgba(247,108,108,0.1);
        color: var(--primary-dark);
        font-size: 11px;
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 20px;
        margin-bottom: 18px;
    }
    .pkg-features {
        margin-bottom: 26px;
        flex: 1;
        text-align: left;
    }
    .pkg-features li {
        color: var(--gray);
        font-size: 13.5px;
        line-height: 2.1;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .pkg-features li i {
        color: var(--primary);
        font-size: 11px;
        flex: none;
    }

    .pkg-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        background: var(--primary);
        color: #fff;
        padding: 13px;
        border-radius: 30px;
        font-size: 12.5px;
        font-weight: 600;
        letter-spacing: .3px;
        border: none;
        cursor: pointer;
        transition: all .3s ease;
        font-family: 'Montserrat', sans-serif;
    }
    .pkg-btn:hover {
        background: var(--primary-dark);
        transform: translateY(-2px);
    }

    .empty-state {
        grid-column: 1 / -1;
        text-align: center;
        padding: 60px 20px;
        color: var(--gray);
        font-size: 14px;
    }

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
        position: relative;
        background: #fff;
        border-radius: 20px;
        width: 100%;
        max-width: 800px;
        max-height: calc(100vh - 40px);
        overflow-y: auto;
        display: flex;
        flex-direction: row;
        box-shadow: 0 20px 60px rgba(0, 0, 0, .25);
        animation: popIn .3s ease;
    }
    @keyframes popIn {
        from { transform: scale(.95); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }

    .modal-close {
        position: absolute;
        top: 16px;
        right: 16px;
        background: #fff;
        border: 1px solid var(--line);
        width: 34px;
        height: 34px;
        border-radius: 50%;
        font-size: 14px;
        cursor: pointer;
        color: var(--ink);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: .25s;
        z-index: 10;
        box-shadow: 0 4px 12px rgba(0, 0, 0, .1);
    }
    .modal-close:hover {
        background: var(--primary);
        border-color: var(--primary);
        color: #fff;
        transform: rotate(90deg);
    }

    .modal-left {
        width: 40%;
        background: var(--bg-cream);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        flex: none;
    }
    .modal-left img {
        width: 100%;
        height: 100%;
        max-height: 300px;
        object-fit: cover;
        border-radius: 12px;
        cursor: zoom-in;
    }

    .modal-right {
        width: 60%;
        padding: 34px 32px;
        display: flex;
        flex-direction: column;
    }
    .modal-category {
        font-size: 11px;
        font-weight: 700;
        color: var(--primary);
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 10px;
        display: block;
    }
    .modal-name {
        font-size: 24px;
        font-weight: 800;
        margin-bottom: 10px;
        line-height: 1.25;
    }
    .modal-desc {
        font-size: 13px;
        color: var(--gray);
        line-height: 1.65;
        margin-bottom: 20px;
    }
    .modal-features {
        list-style: none;
        padding: 0;
        margin: 0 0 20px;
    }
    .modal-features li {
        font-size: 13px;
        color: var(--gray);
        line-height: 1.9;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .modal-features li i {
        color: var(--primary);
        font-size: 11px;
        flex: none;
    }

    .modal-qty {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        margin-bottom: 22px;
        padding-bottom: 22px;
        border-bottom: 1px solid var(--line);
    }
    .modal-qty h4 {
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .4px;
        color: var(--ink);
    }
    .modal-qty-control {
        display: flex;
        align-items: center;
        gap: 4px;
        border: 1px solid var(--line);
        border-radius: 30px;
        background: var(--bg-cream);
        padding: 4px;
        width: fit-content;
    }
    .modal-qty-control button {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        border: none;
        background: #fff;
        font-size: 15px;
        font-weight: 700;
        cursor: pointer;
        color: var(--ink);
        transition: .2s;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 1px 3px rgba(0, 0, 0, .08);
    }
    .modal-qty-control button:hover {
        background: var(--primary);
        color: #fff;
    }
    .modal-qty-control input {
        border: none;
        outline: none;
        background: transparent;
        text-align: center;
        font-size: 14px;
        font-weight: 700;
        font-family: 'Montserrat', sans-serif;
        color: var(--ink);
        width: 34px;
    }

    .modal-price {
        font-size: 20px;
        font-weight: 800;
        color: var(--primary);
        margin-bottom: 22px;
        display: block;
    }
    .modal-price small {
        font-size: 12px;
        font-weight: 500;
        color: var(--gray);
    }

    .modal-buttons {
        display: flex;
        gap: 12px;
        margin-top: auto;
    }
    .btn-add-cart,
    .btn-book-now {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 13px;
        border-radius: 30px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        transition: all .3s ease;
        font-family: 'Montserrat', sans-serif;
    }
    .btn-add-cart {
        background: transparent;
        border: 1.5px solid var(--primary);
        color: var(--primary);
    }
    .btn-add-cart:hover {
        background: rgba(247, 108, 108, .08);
        transform: translateY(-2px);
    }
    .btn-book-now {
        background: var(--primary);
        color: #fff;
        border: 1.5px solid var(--primary);
        text-transform: uppercase;
    }
    .btn-book-now:hover {
        background: var(--primary-dark);
        border-color: var(--primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 8px 15px rgba(247, 108, 108, .3);
    }

    .lightbox {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, .92);
        z-index: 10001;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 40px;
    }
    .lightbox.active { display: flex; }
    .lightbox img {
        max-width: 100%;
        max-height: 80vh;
        object-fit: contain;
        border-radius: 8px;
    }
    .lightbox-close {
        position: absolute;
        top: 20px;
        right: 20px;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: var(--primary);
        color: #fff;
        border: none;
        font-size: 18px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: .25s;
    }
    .lightbox-close:hover {
        background: var(--primary-dark);
        transform: rotate(90deg);
    }

    @media (max-width: 900px) {
        .pkg-grid { grid-template-columns: 1fr; }
        .modal-content { flex-direction: column; }
        .modal-left, .modal-right { width: 100%; }
        .modal-left { padding: 24px 24px 0; }
        .modal-left img { max-height: 220px; }
        .modal-right { padding: 22px 24px 26px; }
    }
</style>
@endpush

@section('content')

    <section class="pkg-hero">
        <div>
            <h1>Spa Packages</h1>
            <p>Indulge in our curated packages designed for ultimate relaxation and rejuvenation.</p>
        </div>
    </section>

    <section class="curated">
        <div class="wrap">
            <div class="head">
                <h2>Curated Experiences</h2>
                <p>Select a package that perfectly aligns with your wellness journey.</p>
            </div>

            <div class="pkg-filters" id="pkgFilters">
                <button type="button" class="filter-btn active" data-filter="all">All</button>
                <button type="button" class="filter-btn" data-filter="couple">Couple</button>
            </div>

            <div class="pkg-grid" id="pkgGrid">
                @forelse($packages as $pkg)
                @php
                    $isPopular = $pkg->is_popular;
                    $hasSaving = $pkg->saving_percent > 0;
                @endphp
                <div class="pkg-card {{ $isPopular ? 'popular' : '' }}"
                    data-category="{{ $pkg->unit }}">
                        <div class="pkg-media"
                             onclick="openLightbox('{{ $pkg->image ? asset('storage/'.$pkg->image) : asset('user/assets/wlcm.jpg') }}')">
                            @if($isPopular)
                                <span class="popular-badge"><i class="fas fa-star"></i> Popular</span>
                            @endif
                            <img src="{{ $pkg->image ? asset('storage/'.$pkg->image) : asset('user/assets/wlcm.jpg') }}"
                                 alt="{{ $pkg->name }}"
                                 onerror="this.src='{{ asset('user/assets/wlcm.jpg') }}'">
                        </div>
                        <div class="pkg-body">
                            <h3>{{ $pkg->name }}</h3>
                            <div class="pkg-price">
                                Rp {{ number_format($pkg->price, 0, ',', '.') }}
                                <span class="pkg-unit">/ {{ $pkg->unit }}</span>
                            </div>

                            @if($hasSaving)
                                <span class="pkg-saving">Hemat {{ $pkg->saving_percent }}%</span>
                            @endif

                            <ul class="pkg-features">
                                @foreach($pkg->items->take(4) as $item)
                                    <li>
                                        <i class="fas fa-check"></i>
                                        {{ $item->name }}
                                        @if($item->duration) ({{ $item->duration }} min) @endif
                                    </li>
                                @endforeach
                            </ul>

                            <button type="button"
                                    class="pkg-btn"
                                    onclick="openPackageModal({{ $pkg->id }})">
                                Choose Package <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">No packages are available yet.</div>
                @endforelse
            </div>

            <p id="pkgEmptyState" style="display:none;text-align:center;color:var(--gray);font-size:13.5px;margin-top:20px;">
                No packages in this category yet.
            </p>
        </div>
    </section>

    {{-- MODAL --}}
    <div class="modal-overlay" id="modalOverlay">
        <div class="modal-content">
            <button class="modal-close" onclick="closeModal()" aria-label="Close">
                <i class="fas fa-times"></i>
            </button>
            <div class="modal-left">
                <img id="modalImage" src="" alt="Package Image"
                     onclick="openLightbox(document.getElementById('modalImage').src)">
            </div>
            <div class="modal-right">
                <span class="modal-category" id="modalCategory"></span>
                <h3 class="modal-name" id="modalName"></h3>
                <p class="modal-desc" id="modalDesc"></p>
                <ul class="modal-features" id="modalFeatures"></ul>

                <div class="modal-qty">
                    <h4 id="modalQtyLabel">Number of Guests</h4>
                    <div class="modal-qty-control">
                        <button type="button" onclick="decreaseGuests()" aria-label="Decrease">
                            <i class="fas fa-minus"></i>
                        </button>
                        <input type="text" id="guests" value="1" readonly>
                        <button type="button" onclick="increaseGuests()" aria-label="Increase">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>

                <span class="modal-price" id="modalTotal"></span>

                <div class="modal-buttons">
                    <button class="btn-add-cart" onclick="addToCartFromModal()">
                        <i class="fas fa-cart-plus"></i> Add to Cart
                    </button>
                    <button class="btn-book-now" onclick="bookNowFromModal()">
                        <i class="fas fa-calendar-check"></i> Book Now
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="lightbox" id="lightbox">
        <button class="lightbox-close" onclick="closeLightbox()" aria-label="Close">
            <i class="fas fa-times"></i>
        </button>
        <img id="lightboxImage" src="" alt="Fullscreen Image">
    </div>

@endsection

@push('scripts')
<script>
    const packagesData = {!! json_encode($packages->map(function($p) {
        return [
            'id' => $p->id,
            'name' => $p->name,
            'description' => $p->description ?? '',
            'price' => $p->price,
            'unit' => $p->unit,
            'image' => $p->image ? asset('storage/'.$p->image) : asset('user/assets/wlcm.jpg'),
            'is_popular' => (bool) $p->is_popular,    // ← TAMBAH
            'items' => $p->items->map(function($i) {
                return ['name' => $i->name, 'duration' => $i->duration];
            })->toArray(),
        ];
    })) !!};

    let modalItem = null;
    let modalGuests = 1;

    const unitQtyLabels = {
        person: 'Number of Guests',
        couple: 'Number of Couples'
    };

    function openPackageModal(packageId) {
        modalItem = packagesData.find(p => p.id === packageId);
        if (!modalItem) return;

        modalGuests = 1;
        document.getElementById('guests').value = 1;

        document.getElementById('modalCategory').textContent = modalItem.unit.toUpperCase();
        document.getElementById('modalName').textContent = modalItem.name;
        document.getElementById('modalDesc').textContent = modalItem.description;
        document.getElementById('modalImage').src = modalItem.image;

        const featuresHtml = modalItem.items.map(i =>
            `<li><i class="fas fa-check"></i> ${i.name} ${i.duration ? '('+i.duration+' min)' : ''}</li>`
        ).join('');
        document.getElementById('modalFeatures').innerHTML = featuresHtml;

        document.getElementById('modalQtyLabel').textContent = unitQtyLabels[modalItem.unit] || 'Number of Guests';

        updateModalTotal();
        document.getElementById('modalOverlay').classList.add('active');
    }

    function increaseGuests() {
        modalGuests++;
        document.getElementById('guests').value = modalGuests;
        updateModalTotal();
    }

    function decreaseGuests() {
        if (modalGuests > 1) {
            modalGuests--;
            document.getElementById('guests').value = modalGuests;
            updateModalTotal();
        }
    }

    function updateModalTotal() {
        if (!modalItem) return;
        const total = modalItem.price * modalGuests;
        document.getElementById('modalTotal').innerHTML =
            `Rp ${total.toLocaleString('id-ID')} <small>/ ${modalItem.unit}</small>`;
    }

    function closeModal() {
        document.getElementById('modalOverlay').classList.remove('active');
    }

    // ===== ADD TO CART — FIXED: kirim harga per unit =====
    function addToCartFromModal() {
        if (!modalItem) return;

        addToCart({
            name: modalItem.name,
            duration: 'Package',
            unit: modalItem.unit,
            qty: modalGuests,
            price: modalItem.price,
            image: modalItem.image
        });

        closeModal();
    }

    // ===== BOOK NOW — FIXED: kirim harga per unit =====
    function bookNowFromModal() {
        if (!modalItem) return;

        addToCart({
            name: modalItem.name,
            duration: 'Package',
            unit: modalItem.unit,
            qty: modalGuests,
            price: modalItem.price,
            image: modalItem.image
        });

        window.location.href = '{{ url("booking") }}';
    }

    function openLightbox(src) {
        document.getElementById('lightboxImage').src = src;
        document.getElementById('lightbox').classList.add('active');
    }
    function closeLightbox() {
        document.getElementById('lightbox').classList.remove('active');
    }

    const filterButtons = document.querySelectorAll('.filter-btn');
    const pkgCards = document.querySelectorAll('.pkg-card');
    const pkgEmptyState = document.getElementById('pkgEmptyState');

    filterButtons.forEach(function (btn) {
        btn.addEventListener('click', function () {
            filterButtons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            const filter = btn.dataset.filter;
            let visible = 0;

            pkgCards.forEach(function (card) {
                const matches = filter === 'all' || card.dataset.category === filter;
                card.style.display = matches ? '' : 'none';
                if (matches) visible++;
            });

            pkgEmptyState.style.display = visible === 0 ? 'block' : 'none';
        });
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal();
            closeLightbox();
        }
    });

    document.getElementById('modalOverlay').addEventListener('click', function(e) {
        if (e.target === this) closeModal();
    });
</script>
@endpush