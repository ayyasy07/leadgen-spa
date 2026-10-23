@extends('user.layouts.main')

@section('title', $treatment->meta_title ?? $treatment->name . ' — LeadGen Spa')
@section('meta_description', $treatment->meta_description ?? Str::limit($treatment->description, 160))
@section('footer_type', 'grid')

@section('header')
    @include('user.partials.header-detail', ['backUrl' => url('treatments')])
@endsection

@push('styles')
<style>
    .detail-hero {
        position: relative;
        height: 340px;
        background:
            linear-gradient(180deg, rgba(10, 7, 6, 0.66), rgba(8, 6, 5, 0.72)),
            url('{{ $treatment->image ? asset("storage/".$treatment->image) : asset("user/assets/wlcm.jpg") }}') center 45% / cover no-repeat;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        color: #fff;
    }
    .detail-hero h1 {
        font-size: 3rem; font-weight: 700;
        color: var(--primary);
        margin: 0 0 14px; letter-spacing: -0.5px;
    }
    .detail-hero .sub {
        font-size: 0.78rem; font-weight: 700;
        letter-spacing: 2px; text-transform: uppercase; color: #fff;
    }

    .content-wrap {
        max-width: 1180px;
        margin: 0 auto;
        padding: 70px 32px 96px;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 60px;
        align-items: stretch; /* galeri & info sama tinggi -> bawahnya sejajar */
    }

    /* ===== GALLERY ===== */
    .gallery-col {
        width: 100%;
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    /* 1 gambar */
    .gallery-single {
        width: 100%;
        aspect-ratio: 4 / 3;
        overflow: hidden;
        border-radius: 12px;
    }
    .gallery-single img {
        width: 100%; height: 100%;
        object-fit: cover;
        cursor: pointer;
        transition: transform 0.4s ease;
        display: block;
    }
    .gallery-single img:hover { transform: scale(1.04); }

    /* 2 gambar side-by-side */
    .gallery-two {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        aspect-ratio: 4 / 3;
    }
    .gallery-two img {
        width: 100%; height: 100%;
        object-fit: cover;
        border-radius: 12px;
        cursor: pointer;
        transition: transform 0.4s ease;
        display: block;
    }
    .gallery-two img:hover { transform: scale(1.04); }

    /* 3 gambar: 1 besar + 2 kecil di bawah */
    .gallery-main {
        width: 100%;
        aspect-ratio: 16 / 10;
        overflow: hidden;
        border-radius: 12px;
    }
    .gallery-main img {
        width: 100%; height: 100%;
        object-fit: cover;
        cursor: pointer;
        transition: transform 0.4s ease;
        display: block;
    }
    .gallery-main img:hover { transform: scale(1.04); }

    .gallery-sub {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        aspect-ratio: 16 / 6;
    }
    .gallery-sub img {
        width: 100%; height: 100%;
        object-fit: cover;
        border-radius: 12px;
        cursor: pointer;
        transition: transform 0.4s ease;
        display: block;
    }
    .gallery-sub img:hover { transform: scale(1.04); }

    /* ===== INFO ===== */
    .info-col { display: flex; flex-direction: column; }
    .info-col .eyebrow {
        font-size: 0.72rem; font-weight: 700;
        letter-spacing: 1px; text-transform: uppercase;
        color: var(--primary-dark); margin-bottom: 12px;
    }
    .info-col h2 {
        font-size: 2rem; font-weight: 700;
        margin: 0 0 18px; color: var(--ink);
        letter-spacing: -0.5px;
    }
    .info-col p {
        font-size: 0.95rem; color: #5b5654;
        line-height: 1.75; margin: 0 0 24px;
    }
    .divider {
        border: none; border-top: 1px solid var(--line);
        margin: 0 0 28px;
    }

    .price-row {
        display: flex; align-items: baseline;
        gap: 12px; margin-bottom: 6px;
    }
    .price {
        font-size: 2.4rem; font-weight: 800;
        color: var(--primary); line-height: 1.1;
    }
    .price-unit {
        font-size: 0.95rem; color: var(--gray);
        font-weight: 500; background: var(--bg-cream);
        padding: 3px 10px; border-radius: 20px;
    }
    .price-total {
        font-size: 1.05rem; color: #5b5654;
        margin-bottom: 28px; font-style: italic;
    }
    .price-total strong {
        color: var(--ink); font-weight: 800; font-style: normal;
    }

    .duration-options { margin-bottom: 26px; }
    .duration-options h4 {
        font-size: 0.85rem; font-weight: 700;
        text-transform: uppercase; margin: 0 0 14px;
        color: var(--ink);
    }
    .duration-btn {
        display: inline-block;
        border: 1px solid var(--line);
        background: #fff;
        border-radius: 30px;
        padding: 10px 20px;
        font-size: 13px;
        font-weight: 600;
        font-family: 'Montserrat', sans-serif;
        color: var(--gray);
        cursor: pointer;
        margin-right: 8px;
        margin-bottom: 8px;
        transition: all 0.3s ease;
    }
    .duration-btn:hover {
        border-color: var(--primary);
        color: var(--primary-dark);
    }
    .duration-btn.active {
        border-color: var(--primary);
        color: var(--primary-dark);
        background: rgba(247, 108, 108, 0.05);
    }

    .booking-row {
        display: flex; align-items: center;
        gap: 20px; margin-bottom: 32px;
    }
    .guests { display: flex; align-items: center; gap: 14px; }
    .guests h4 {
        font-size: 0.85rem; font-weight: 700;
        text-transform: uppercase; color: var(--ink);
        margin: 0;
    }
    .guests-control {
        display: flex; align-items: center;
        border: 1px solid var(--line);
        border-radius: 30px; background: #fff;
        overflow: hidden; width: 104px;
    }
    .guests-control button {
        border: none; background: transparent;
        font-size: 16px; font-weight: 700;
        padding: 10px 15px; cursor: pointer;
        color: var(--gray); transition: 0.3s;
    }
    .guests-control button:hover { color: var(--primary-dark); }
    .guests-control input {
        flex: 1; text-align: center;
        border: none; outline: none; background: transparent;
        font-size: 14px; font-weight: 700;
        font-family: 'Montserrat', sans-serif;
        color: var(--ink); width: 30px;
    }

    /* tombol selalu nempel di bawah kolom -> sejajar dengan bawah galeri */
    .action-row {
        display: flex;
        align-items: stretch;
        gap: 12px;
        margin-top: auto;
    }
    .btn-cart-icon {
        width: 58px; height: 58px; flex: none;
        background: #fff; color: var(--primary);
        border: 2px solid var(--primary);
        border-radius: 12px;
        font-size: 20px;
        cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        transition: all 0.3s ease;
    }
    .btn-cart-icon:hover {
        background: var(--primary); color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(247, 108, 108, 0.3);
    }
    .btn-book-now {
        flex: 1;
        height: 58px;
        display: flex; align-items: center; justify-content: center;
        gap: 10px;
        background: var(--primary); color: #fff;
        border: none;
        padding: 0 20px;
        border-radius: 12px;
        font-family: 'Montserrat', sans-serif;
        font-weight: 700; font-size: 0.95rem;
        letter-spacing: 0.5px;
        cursor: pointer;
        transition: all 0.3s ease;
        text-transform: uppercase;
    }
    .btn-book-now i { transition: transform 0.3s ease; }
    .btn-book-now:hover {
        background: var(--primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(247, 108, 108, 0.3);
    }
    .btn-book-now:hover i { transform: translateX(4px); }

    .related-section { background: var(--bg-cream); padding: 70px 0; }
    .related-section h2 {
        text-align: center;
        font-size: 1.6rem; font-weight: 700;
        margin-bottom: 40px;
    }
    .related-grid {
        display: grid; grid-template-columns: repeat(3, 1fr);
        gap: 24px; max-width: 1180px;
        margin: 0 auto; padding: 0 32px;
    }
    .related-card {
        background: #fff; border-radius: 14px;
        overflow: hidden; text-decoration: none;
        color: inherit;
        transition: transform 0.3s, box-shadow 0.3s;
    }
    .related-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    }
    .related-card img { width: 100%; height: 180px; object-fit: cover; }
    .related-card-body { padding: 20px 22px; }
    .related-card .cat {
        font-size: 0.72rem; font-weight: 700;
        letter-spacing: 0.6px; text-transform: uppercase;
        color: var(--maroon); display: block; margin-bottom: 8px;
    }
    .related-card h3 { font-size: 1.1rem; font-weight: 700; margin: 0 0 8px; }
    .related-card .price {
        font-size: 0.95rem; font-weight: 700; color: var(--primary-dark);
    }

    .lightbox {
        position: fixed; inset: 0;
        background: rgba(0, 0, 0, 0.92);
        backdrop-filter: blur(8px);
        z-index: 10000; display: none;
        align-items: center; justify-content: center;
        padding: 40px;
    }
    .lightbox.active { display: flex; }
    .lightbox-content { position: relative; max-width: 85%; max-height: 85%; }
    .lightbox-content img {
        max-width: 100%; max-height: 70vh;
        object-fit: contain; border-radius: 8px;
    }
    .lightbox-close {
        position: absolute; top: -60px; right: 0;
        background: transparent; border: none;
        cursor: pointer; color: #fff; font-size: 28px;
    }
    .lightbox-close:hover { color: var(--primary); }

    /* Biar fotonya ngikutin sejajarnya button book now */
    @media (min-width: 901px) {
        .gallery-col {
            display: grid;
            grid-template-rows: minmax(0, 1fr);
            gap: 16px;
            contain: size;          /* tinggi galeri ga ikut nentuin tinggi baris */
            min-height: 440px;
        }
        .gallery-col:has(.gallery-main) {
            grid-template-rows: minmax(0, 1.55fr) minmax(0, 1fr);
        }
        .gallery-single,
        .gallery-two,
        .gallery-main,
        .gallery-sub {
            aspect-ratio: auto;
            min-height: 0;
            height: 100%;
        }
        .gallery-two,
        .gallery-sub { grid-template-rows: minmax(0, 1fr); }
        .gallery-single img,
        .gallery-main img,
        .gallery-two img,
        .gallery-sub img { min-height: 0; }
        .gallery-main { overflow: hidden; }
    }

    @media (max-width: 900px) {
        .content-wrap { grid-template-columns: 1fr; gap: 36px; padding: 40px 20px 60px; }
        .related-grid { grid-template-columns: 1fr; }
        .detail-hero { height: 260px; }
        .detail-hero h1 { font-size: 2rem; }

        /* Galeri mobile: aspect-ratio lebih mini */
        .gallery-single { aspect-ratio: 3 / 2; }
        .gallery-two    { aspect-ratio: 3 / 2; }
        .gallery-main   { aspect-ratio: 4 / 3; }
        .gallery-sub    { aspect-ratio: 4 / 2; }

        .action-row { margin-top: 0; }
    }
</style>
@endpush

@section('content')

    <section class="detail-hero">
        <h1>{{ $treatment->name }}</h1>
        <div class="sub">{{ strtoupper($treatment->category) }}</div>
    </section>

    @php
        $images = $treatment->images;
        $imageCount = count($images);
        $firstPrice = $treatment->prices->sortBy('price')->first();
    @endphp

    <div class="content-wrap">
        <div class="gallery-col">
            @if($imageCount === 0)
                <div class="gallery-single">
                    <img src="{{ asset('user/assets/wlcm.jpg') }}" alt="{{ $treatment->name }}">
                </div>
            @elseif($imageCount === 1)
                <div class="gallery-single">
                    <img src="{{ asset('storage/'.$images[0]) }}"
                         alt="{{ $treatment->name }}"
                         onclick="openLightbox(0)">
                </div>
            @elseif($imageCount === 2)
                <div class="gallery-two">
                    @foreach($images as $i => $img)
                        <img src="{{ asset('storage/'.$img) }}"
                             alt="{{ $treatment->name }}"
                             onclick="openLightbox({{ $i }})">
                    @endforeach
                </div>
            @else
                <div class="gallery-main">
                    <img src="{{ asset('storage/'.$images[0]) }}"
                         alt="{{ $treatment->name }}"
                         onclick="openLightbox(0)">
                </div>
                <div class="gallery-sub">
                    <img src="{{ asset('storage/'.$images[1]) }}"
                         alt="{{ $treatment->name }}"
                         onclick="openLightbox(1)">
                    <img src="{{ asset('storage/'.$images[2]) }}"
                         alt="{{ $treatment->name }}"
                         onclick="openLightbox(2)">
                </div>
            @endif
        </div>

        <div class="info-col">
            <div class="eyebrow">{{ strtoupper($treatment->category) }}</div>
            <h2>{{ $treatment->name }}</h2>
            <p>{{ $treatment->description }}</p>

            <hr class="divider">

            @if($treatment->prices->count())
                <div class="price-row">
                    <span class="price" id="unitPriceDisplay">
                        Rp {{ number_format($firstPrice->price, 0, ',', '.') }}
                    </span>
                    <span class="price-unit">/ {{ $treatment->unit ?? 'person' }}</span>
                </div>

                <div class="price-total">
                    Total: <strong id="totalPriceDisplay">Rp {{ number_format($firstPrice->price, 0, ',', '.') }}</strong>
                    <span id="guestsInfo">(1 guest)</span>
                </div>

                <div class="duration-options">
                    <h4>Select Duration</h4>
                    <div id="durationOptions">
                        @foreach($treatment->prices->sortBy('duration') as $i => $price)
                            <button type="button"
                                    class="duration-btn {{ $i === 0 ? 'active' : '' }}"
                                    data-price="{{ $price->price }}"
                                    data-duration="{{ $price->duration }}"
                                    onclick="selectDuration(this)">
                                {{ $price->duration }} Mins
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="booking-row">
                <div class="guests">
                    <h4>Guests</h4>
                    <div class="guests-control">
                        <button type="button" onclick="decreaseGuests()">-</button>
                        <input type="text" id="guests" value="1" readonly>
                        <button type="button" onclick="increaseGuests()">+</button>
                    </div>
                </div>
            </div>

            <div class="action-row">
                <button type="button" class="btn-cart-icon" onclick="addToCartAction()" title="Add to Cart">
                    <i class="fas fa-shopping-bag"></i>
                </button>
                <button type="button" class="btn-book-now" onclick="bookNowAction()">
                    Book Now <i class="fas fa-arrow-right"></i>
                </button>
            </div>
        </div>
    </div>

    @if($related->count())
        <section class="related-section">
            <h2>You May Also Like</h2>
            <div class="related-grid">
                @foreach($related as $r)
                    <a href="{{ route('treatments.show', $r->slug) }}" class="related-card">
                        <img src="{{ $r->image ? asset('storage/'.$r->image) : asset('user/assets/wlcm.jpg') }}"
                             alt="{{ $r->name }}">
                        <div class="related-card-body">
                            <span class="cat">{{ strtoupper($r->category) }}</span>
                            <h3>{{ $r->name }}</h3>
                            @if($r->prices->count())
                                <span class="price">
                                    From Rp {{ number_format($r->prices->min('price'), 0, ',', '.') }}
                                </span>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    <div class="lightbox" id="lightbox">
        <div class="lightbox-content">
            <img id="lightboxImage" src="" alt="Full Image">
            <button class="lightbox-close" onclick="closeLightbox()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    const images = {!! json_encode($images) !!};
    let currentIndex = 0;

    const treatmentUnit = '{{ $treatment->unit ?? "person" }}';
    let unitPrice = {{ $firstPrice->price ?? 0 }};
    let currentDuration = '{{ $firstPrice->duration ?? 60 }}';
    let guests = 1;

    function selectDuration(btn) {
        document.querySelectorAll('.duration-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        unitPrice = parseInt(btn.dataset.price);
        currentDuration = btn.dataset.duration;
        updatePriceDisplay();
    }

    function increaseGuests() {
        guests++;
        document.getElementById('guests').value = guests;
        updatePriceDisplay();
    }
    function decreaseGuests() {
        if (guests > 1) {
            guests--;
            document.getElementById('guests').value = guests;
            updatePriceDisplay();
        }
    }

    function updatePriceDisplay() {
        document.getElementById('unitPriceDisplay').textContent =
            'Rp ' + unitPrice.toLocaleString('id-ID');

        const total = unitPrice * guests;
        document.getElementById('totalPriceDisplay').textContent =
            'Rp ' + total.toLocaleString('id-ID');

        const unitLabel = guests === 1 ? 'guest' : 'guests';
        document.getElementById('guestsInfo').textContent =
            '(' + guests + ' ' + unitLabel + ')';
    }

    function addToCartAction() {
        const total = unitPrice * guests;
        addToCart({
            name: '{{ $treatment->name }}',
            duration: currentDuration + ' Mins',
            unit: treatmentUnit,
            qty: guests,
            price: total,
            image: '{{ $treatment->image ? asset("storage/".$treatment->image) : asset("user/assets/wlcm.jpg") }}'
        });
    }

    function bookNowAction() {
        addToCartAction();
        window.location.href = '{{ url("booking") }}';
    }

    function openLightbox(index) {
        currentIndex = index;
        document.getElementById('lightboxImage').src = '{{ asset("storage") }}/' + images[index];
        document.getElementById('lightbox').classList.add('active');
    }
    function closeLightbox() {
        document.getElementById('lightbox').classList.remove('active');
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeLightbox();
    });
</script>
@endpush