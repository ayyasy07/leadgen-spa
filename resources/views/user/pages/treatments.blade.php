@extends('user.layouts.main')

@section('title', 'Treatments & Pricing — LeadGen Spa')
@section('meta_description', 'Explore our curated spa treatments — massage, face therapy, body care, grooming, and packages.')
@section('footer_type', 'grid')

@push('styles')
<style>
    .page-hero {
        position: relative;
        height: 380px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        background:
            linear-gradient(180deg, rgba(10, 7, 6, 0.55), rgba(8, 6, 5, 0.62)),
            url('{{ asset("user/assets/lilinbesar.jpe") }}') center 35% / cover no-repeat;
        color: #fff;
    }
    .page-hero-content { max-width: 640px; padding: 0 24px; }
    .page-hero .eyebrow {
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: var(--primary);
        margin-bottom: 16px;
    }
    .page-hero h1 {
        font-size: 2.9rem;
        font-weight: 700;
        margin: 0 0 16px;
        letter-spacing: -0.5px;
    }
    .page-hero p {
        font-size: 16px;
        color: rgba(255, 255, 255, 0.9);
        line-height: 1.6;
    }

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
        font-size: 0.78rem;
        font-weight: 700;
        letter-spacing: 0.6px;
        text-transform: uppercase;
        color: #332f2e;
        padding-bottom: 10px;
        position: relative;
        text-decoration: none;
        transition: color .25s;
    }
    .filter:hover { color: var(--primary-dark); }
    .filter.active { color: var(--maroon); }
    .filter.active::after {
        content: "";
        position: absolute;
        left: 0; right: 0; bottom: -1px;
        height: 2px;
        background: var(--maroon);
    }

    .grid {
        max-width: 1180px;
        margin: 0 auto;
        padding: 44px 32px 90px;
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 26px;
    }
    .card {
        background: #fff;
        border-radius: 14px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        box-shadow: 0 2px 10px rgba(0,0,0,0.03);
        transition: transform 0.3s, box-shadow 0.3s;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    }
    .card-media { position: relative; }
    .card-media img { width: 100%; height: 230px; object-fit: cover; }
    .card-body {
        padding: 24px 28px 0;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    .card-cat {
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.6px;
        text-transform: uppercase;
        color: var(--maroon);
        margin-bottom: 10px;
    }
    .card-body h3 {
        font-size: 1.3rem;
        font-weight: 700;
        margin: 0 0 14px;
        color: var(--ink);
    }
    .card-body p {
        font-size: 0.9rem;
        color: #847e7c;
        line-height: 1.65;
        margin: 0 0 16px;
    }
    .price-list {
        list-style: none;
        margin: 0 0 20px;
        padding: 0;
    }
    .price-list li {
        display: flex;
        justify-content: space-between;
        font-size: 0.9rem;
        padding: 8px 0;
        border-bottom: 1px dashed var(--line);
    }
    .price-list li:last-child { border-bottom: none; }
    .price-list span:last-child { font-weight: 800; color: var(--ink); }

    .card-actions {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: auto;
        padding: 0 28px 24px;
        gap: 10px;
    }
    .link-detail {
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--gray);
        text-transform: uppercase;
        letter-spacing: 0.6px;
        text-decoration: none;
        transition: 0.3s;
        flex: 1;
    }
    .link-detail:hover { color: var(--primary-dark); }

    .btn-add-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: var(--primary);
        color: #fff;
        border: none;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        flex: none;
    }
    .btn-add-circle:hover {
        background: var(--primary-dark);
        transform: scale(1.1);
    }

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

    .modal-durations { margin-bottom: 22px; }
    .modal-durations h4 {
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .4px;
        color: var(--ink);
        margin-bottom: 10px;
    }
    .duration-opt {
        display: inline-block;
        border: 1px solid var(--line);
        background: #fff;
        border-radius: 30px;
        padding: 8px 16px;
        font-size: 12.5px;
        font-weight: 600;
        font-family: 'Montserrat', sans-serif;
        color: var(--gray);
        cursor: pointer;
        margin-right: 8px;
        margin-bottom: 8px;
        transition: .25s;
    }
    .duration-opt.active {
        border-color: var(--primary);
        color: var(--primary-dark);
        background: rgba(247, 108, 108, 0.05);
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

    .modal-price-row {
        display: flex;
        align-items: baseline;
        gap: 8px;
        margin-bottom: 4px;
    }
    .modal-price-unit {
        font-size: 22px;
        font-weight: 800;
        color: var(--primary);
    }
    .modal-price-unit-label {
        font-size: 12px;
        color: var(--gray);
        font-weight: 500;
    }
    .modal-price-total {
        font-size: 13px;
        color: #5b5654;
        margin-bottom: 22px;
    }
    .modal-price-total strong {
        color: var(--ink);
        font-weight: 700;
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

    @media (max-width: 900px) {
        .grid { grid-template-columns: 1fr 1fr; }
        .modal-content { flex-direction: column; }
        .modal-left, .modal-right { width: 100%; }
        .modal-left { padding: 24px 24px 0; }
        .modal-left img { max-height: 220px; }
        .modal-right { padding: 22px 24px 26px; }
    }
    @media (max-width: 600px) {
        .grid { grid-template-columns: 1fr; padding: 34px 20px 60px; }
        .page-hero h1 { font-size: 2rem; }
    }
</style>
@endpush

@section('content')

    {{-- HERO --}}
    <section class="page-hero">
        <div class="page-hero-content">
            <div class="eyebrow">Our Treatments</div>
            <h1>Explore Our Services</h1>
            <p>Discover a sanctuary of rejuvenation. Explore our curated selection of therapies designed to restore balance to your body and tranquility to your mind.</p>
        </div>
    </section>

    {{-- FILTER --}}
    <div class="filters-wrap">
        <div class="filters">
            <a href="{{ route('treatments.index') }}"
               class="filter {{ !$kategori ? 'active' : '' }}">
                All
            </a>
            @foreach($categories as $cat)
                <a href="{{ route('treatments.index', ['kategori' => Str::slug($cat)]) }}"
                   class="filter {{ $kategori === Str::slug($cat) ? 'active' : '' }}">
                    {{ strtoupper($cat) }}
                </a>
            @endforeach
        </div>
    </div>

    {{-- GRID --}}
    <div class="grid">
        @forelse($treatments as $t)
            <div class="card">
                <div class="card-media">
                    <img src="{{ $t->image ? asset('storage/'.$t->image) : asset('user/assets/wlcm.jpg') }}"
                         alt="{{ $t->name }}"
                         onerror="this.src='{{ asset('user/assets/wlcm.jpg') }}'">
                </div>
                <div class="card-body">
                    <span class="card-cat">{{ strtoupper($t->category) }}</span>
                    <h3>{{ $t->name }}</h3>
                    <p>{{ Str::limit($t->description, 120) }}</p>

                    @if($t->prices->count())
                        <ul class="price-list">
                            @foreach($t->prices->sortBy('duration') as $price)
                                <li>
                                    <span>{{ $price->duration }} Mins</span>
                                    <span>Rp {{ number_format($price->price, 0, ',', '.') }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
                <div class="card-actions">
                    <a href="{{ route('treatments.show', $t->slug) }}" class="link-detail">
                        View Detail →
                    </a>
                    <button type="button"
                            class="btn-add-circle"
                            title="Add to Cart"
                            onclick="openTreatmentModal({{ $t->id }})">
                        <i class="fas fa-cart-plus"></i>
                    </button>
                </div>
            </div>
        @empty
            <div class="empty-state">
                No treatments in this category yet.
            </div>
        @endforelse
    </div>

    {{-- MODAL --}}
    <div class="modal-overlay" id="modalOverlay">
        <div class="modal-content">
            <button class="modal-close" onclick="closeModal()" aria-label="Close">
                <i class="fas fa-times"></i>
            </button>
            <div class="modal-left">
                <img id="modalImage" src="" alt="Treatment Image">
            </div>
            <div class="modal-right">
                <span class="modal-category" id="modalCategory"></span>
                <h3 class="modal-name" id="modalName"></h3>
                <p class="modal-desc" id="modalDesc"></p>

                <div class="modal-durations">
                    <h4>Select Duration</h4>
                    <div id="modalDurations"></div>
                </div>

                <div class="modal-qty">
                    <h4>Number of Guests</h4>
                    <div class="modal-qty-control">
                        <button type="button" onclick="decreaseGuests()">
                            <i class="fas fa-minus"></i>
                        </button>
                        <input type="text" id="modalGuests" value="1" readonly>
                        <button type="button" onclick="increaseGuests()">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>

                <div class="modal-price-row">
                    <span class="modal-price-unit" id="modalUnitPrice">Rp 0</span>
                    <span class="modal-price-unit-label">/ <span id="modalUnitLabel">person</span></span>
                </div>
                <div class="modal-price-total">
                    Total: <strong id="modalTotalPrice">Rp 0</strong>
                    <span id="modalGuestsInfo">(1 guest)</span>
                </div>

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

@endsection

@push('scripts')
<script>
    // ===== DATA TREATMENTS =====
    const treatmentsData = {!! json_encode($treatments->map(function($t) {
        return [
            'id' => $t->id,
            'name' => $t->name,
            'category' => $t->category,
            'description' => $t->description ?? '',
            'unit' => $t->unit ?? 'person',
            'image' => $t->image ? asset('storage/'.$t->image) : asset('user/assets/wlcm.jpg'),
            'prices' => $t->prices->sortBy('duration')->map(function($p) {
                return ['duration' => $p->duration, 'price' => $p->price];
            })->values()->toArray(),
        ];
    })) !!};

    // ===== MODAL STATE =====
    let modalItem = null;
    let modalGuests = 1;
    let modalCurrentPrice = 0;
    let modalCurrentDuration = '';

    function openTreatmentModal(treatmentId) {
        modalItem = treatmentsData.find(t => t.id === treatmentId);
        if (!modalItem) return;

        modalGuests = 1;
        document.getElementById('modalGuests').value = 1;

        document.getElementById('modalCategory').textContent = modalItem.category.toUpperCase();
        document.getElementById('modalName').textContent = modalItem.name;
        document.getElementById('modalDesc').textContent = modalItem.description;
        document.getElementById('modalImage').src = modalItem.image;
        document.getElementById('modalUnitLabel').textContent = modalItem.unit;

        const durBox = document.getElementById('modalDurations');
        durBox.innerHTML = '';
        modalCurrentPrice = modalItem.prices[0]?.price ?? 0;
        modalCurrentDuration = modalItem.prices[0]?.duration ?? 60;

        modalItem.prices.forEach((p, i) => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'duration-opt' + (i === 0 ? ' active' : '');
            btn.textContent = p.duration + ' Mins';
            btn.setAttribute('data-price', p.price);
            btn.setAttribute('data-duration', p.duration);
            btn.onclick = function() { selectModalDuration(this); };
            durBox.appendChild(btn);
        });

        updateModalPrice();
        document.getElementById('modalOverlay').classList.add('active');
    }

    function selectModalDuration(btn) {
        document.querySelectorAll('#modalDurations .duration-opt').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        modalCurrentPrice = parseInt(btn.dataset.price);
        modalCurrentDuration = btn.dataset.duration;
        updateModalPrice();
    }

    function increaseGuests() {
        modalGuests++;
        document.getElementById('modalGuests').value = modalGuests;
        updateModalPrice();
    }
    function decreaseGuests() {
        if (modalGuests > 1) {
            modalGuests--;
            document.getElementById('modalGuests').value = modalGuests;
            updateModalPrice();
        }
    }

    function updateModalPrice() {
        if (!modalItem) return;

        document.getElementById('modalUnitPrice').textContent =
            'Rp ' + modalCurrentPrice.toLocaleString('id-ID');

        const total = modalCurrentPrice * modalGuests;
        document.getElementById('modalTotalPrice').textContent =
            'Rp ' + total.toLocaleString('id-ID');

        const unitLabel = modalGuests === 1 ? 'guest' : 'guests';
        document.getElementById('modalGuestsInfo').textContent =
            '(' + modalGuests + ' ' + unitLabel + ')';
    }

    function closeModal() {
        document.getElementById('modalOverlay').classList.remove('active');
    }

    // ===== ADD TO CART — FIXED: kirim harga per unit =====
    function addToCartFromModal() {
        if (!modalItem) return;

        addToCart({
            name: modalItem.name,
            duration: modalCurrentDuration + ' Mins',
            unit: modalItem.unit,
            qty: modalGuests,
            price: modalCurrentPrice,
            image: modalItem.image
        });

        closeModal();
    }

    // ===== BOOK NOW — FIXED: kirim harga per unit =====
    function bookNowFromModal() {
        if (!modalItem) return;

        addToCart({
            name: modalItem.name,
            duration: modalCurrentDuration + ' Mins',
            unit: modalItem.unit,
            qty: modalGuests,
            price: modalCurrentPrice,
            image: modalItem.image
        });

        window.location.href = '{{ url("booking") }}';
    }

    // ===== ESC + KLIK LUAR =====
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeModal();
    });
    document.getElementById('modalOverlay').addEventListener('click', function(e) {
        if (e.target === this) closeModal();
    });
</script>
@endpush