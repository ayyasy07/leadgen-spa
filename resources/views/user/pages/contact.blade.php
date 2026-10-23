@extends('user.layouts.main')

@section('title', 'Contact Us — LeadGen Spa')
@section('meta_description', 'We are here to answer any questions and help you book your perfect wellness experience.')
@section('footer_type', 'grid')

@php
    $contactAddress = \App\Models\Setting::get('contact_address', '301 The Greenhouse, Custard Factory, London, E2 8DY');
    $contactPhone   = \App\Models\Setting::get('contact_phone', '+44 (0) 123 456 7890');
    $contactEmail   = \App\Models\Setting::get('contact_email', 'hello@leadgenspa.com');

    $mapInput = trim(\App\Models\Setting::get('map_embed_url', ''));
    if (empty($mapInput)) {
        $mapInput = $contactAddress;
    }

    // ==========================================
    // AUTO-CONVERT MAP INPUT
    // ==========================================
    $mapEmbed = '';

    // 1. Embed URL (dari Google Maps "Embed a map" → Copy HTML)
    if (str_contains($mapInput, '/maps/embed')) {
        $mapEmbed = $mapInput;
    }
    // 2. Format simple dengan q= (e.g. maps.google.com/maps?q=...)
    elseif (preg_match('/[?&]q=([^&]+)/', $mapInput, $m)) {
        $mapEmbed = 'https://maps.google.com/maps?q=' . $m[1] . '&t=&z=15&ie=UTF8&iwloc=&output=embed';
    }
    // 3. Format google.com/maps/place/NAMA
    elseif (preg_match('#google\.com/maps/place/([^/@]+)#', $mapInput, $m)) {
        $placeName = urldecode(str_replace('+', ' ', $m[1]));
        $mapEmbed = 'https://maps.google.com/maps?q=' . urlencode($placeName) . '&t=&z=15&ie=UTF8&iwloc=&output=embed';
    }
    // 4. Link pendek goo.gl (gak ada info) → fallback contact_address
    elseif (str_contains($mapInput, 'goo.gl')) {
        $mapEmbed = 'https://maps.google.com/maps?q=' . urlencode($contactAddress) . '&t=&z=15&ie=UTF8&iwloc=&output=embed';
    }
    // 5. Bukan URL (alamat biasa) → convert ke query
    else {
        $mapEmbed = 'https://maps.google.com/maps?q=' . urlencode($mapInput) . '&t=&z=15&ie=UTF8&iwloc=&output=embed';
    }

    $mapDirections = 'https://www.google.com/maps/dir/?api=1&destination=' . urlencode($mapInput);
@endphp

@push('styles')
<style>
    .ct-hero {
        position: relative;
        height: 420px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        background: linear-gradient(180deg, rgba(15, 10, 9, .45), rgba(10, 7, 6, .55)),
            url('{{ asset("user/assets/lilinbesar.jpe") }}') center 30% / cover no-repeat;
        color: #fff;
    }
    .ct-hero-content { max-width: 640px; padding: 0 24px; }
    .ct-hero .eyebrow {
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: var(--primary);
        margin-bottom: 16px;
    }
    .ct-hero h1 {
        font-size: 2.9rem;
        font-weight: 700;
        margin: 0 0 16px;
        letter-spacing: -.5px;
    }
    .ct-hero p {
        font-size: 16px;
        color: rgba(255, 255, 255, .9);
        line-height: 1.6;
    }

    .ct-content-wrap {
        max-width: 1180px;
        margin: 0 auto;
        padding: 70px 32px 90px;
        display: grid;
        grid-template-columns: 1.5fr 1fr;
        gap: 28px;
        align-items: stretch;
    }

    .form-card {
        background: #fff;
        border-radius: 16px;
        padding: 44px 48px 48px;
        flex: 1;
    }
    .form-card h2 {
        font-size: 1.15rem;
        font-weight: 700;
        margin: 0 0 30px;
        color: var(--ink);
    }

    .alert-success {
        background: rgba(40, 167, 69, .1);
        border-left: 4px solid #28a745;
        color: #155724;
        padding: 14px 18px;
        border-radius: 8px;
        margin-bottom: 24px;
        font-size: 13.5px;
    }
    .alert-danger {
        background: rgba(231, 76, 60, .1);
        border-left: 4px solid #e74c3c;
        color: #721c24;
        padding: 14px 18px;
        border-radius: 8px;
        margin-bottom: 24px;
        font-size: 13.5px;
    }
    .alert-danger ul { list-style: disc; padding-left: 20px; margin: 0; }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 28px;
        margin-bottom: 26px;
    }

    .field label {
        display: block;
        font-size: 12px;
        font-weight: 700;
        color: var(--ink);
        margin-bottom: 10px;
    }
    .field input,
    .field select,
    .field textarea {
        width: 100%;
        border: none;
        border-bottom: 1.5px solid #ddd7d3;
        background: transparent;
        padding: 6px 2px 10px;
        font-size: 14px;
        font-family: 'Montserrat', sans-serif;
        color: var(--ink);
        outline: none;
        transition: border-color .25s;
    }
    .field input:focus,
    .field select:focus,
    .field textarea:focus { border-bottom-color: var(--primary); }
    .field input::placeholder,
    .field textarea::placeholder { color: #a8a29f; }

    .field.select-wrap { position: relative; }
    .field.select-wrap::after {
        content: "";
        position: absolute;
        right: 4px; top: 34px;
        width: 8px; height: 8px;
        border-right: 2px solid #6f6a68;
        border-bottom: 2px solid #6f6a68;
        transform: rotate(45deg);
        pointer-events: none;
    }

    .field textarea { resize: none; min-height: 96px; line-height: 1.5; }

    .btn-send {
        align-self: flex-start;
        background: var(--primary);
        color: #fff;
        border: none;
        padding: 14px 30px;
        border-radius: 30px;
        font-size: 13px;
        font-weight: 700;
        letter-spacing: .5px;
        cursor: pointer;
        transition: all .3s ease;
        font-family: 'Montserrat', sans-serif;
    }
    .btn-send:hover {
        background: var(--primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(247, 108, 108, .3);
    }

    .info-col { display: flex; flex-direction: column; gap: 22px; }

    .info-panel {
        background: var(--bg-cream);
        border-radius: 16px;
        padding: 30px 32px 34px;
    }
    .info-panel h2 {
        font-size: 1.1rem;
        font-weight: 700;
        margin: 0 0 22px;
        color: var(--ink);
    }
    .info-item {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        margin-bottom: 18px;
    }
    .info-item:last-child { margin-bottom: 0; }
    .info-icon {
        color: var(--primary);
        width: 20px; flex: none;
        display: flex;
        justify-content: center;
        padding-top: 2px;
    }
    .info-icon i { font-size: 16px; }
    .info-text h3 {
        font-size: 11px;
        font-weight: 700;
        margin: 0 0 4px;
        color: var(--ink);
        text-transform: uppercase;
        letter-spacing: .4px;
    }
    .info-text p {
        margin: 0;
        font-size: 13px;
        color: #615c5a;
        line-height: 1.5;
    }

    .map-card {
        background: #fff;
        border-radius: 14px;
        overflow: hidden;
        border: 1px solid var(--line);
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    .map-topbar {
        padding: 18px 20px;
        border-bottom: 1px solid var(--line);
    }
    .map-topbar h3 {
        font-size: 16px;
        font-weight: 700;
        color: var(--ink);
        margin: 0;
    }
    .map-body {
        padding: 16px 20px 20px;
        display: flex;
        flex-direction: column;
        flex: 1;
        gap: 12px;
    }
    .map-embed {
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid var(--line);
        flex: 1;
        min-height: 220px;
    }
    .map-embed iframe {
        width: 100%;
        height: 100%;
        min-height: 220px;
        border: 0;
        display: block;
    }
    .btn-directions {
        align-self: flex-start;
        background: var(--primary);
        color: #fff;
        font-size: 11px;
        font-weight: 700;
        padding: 9px 14px;
        border-radius: 5px;
        text-decoration: none;
        transition: all .3s ease;
    }
    .btn-directions:hover { background: var(--primary-dark); }

    .btn-open-map {
        align-self: flex-start;
        margin-top: 4px;
        font-size: 12px;
        font-weight: 700;
        color: var(--primary-dark);
        background: none;
        border: none;
        cursor: pointer;
        transition: .3s;
        font-family: 'Montserrat', sans-serif;
    }
    .btn-open-map:hover { color: var(--maroon); text-decoration: underline; }

    .map-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, .85);
        z-index: 10000;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }
    .map-overlay.active { display: flex; }

    .fullscreen-map-container {
        position: relative;
        width: 100%;
        height: 100%;
        max-width: 1200px;
        max-height: 800px;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0, 0, 0, .3);
    }
    .fullscreen-map-container iframe {
        width: 100%;
        height: 100%;
        border: 0;
    }
    .close-map-btn {
        position: absolute;
        top: 15px; right: 15px;
        width: 40px; height: 40px;
        border-radius: 50%;
        background: var(--primary);
        color: #fff;
        border: none;
        font-size: 20px;
        cursor: pointer;
        z-index: 10;
        transition: .3s;
    }
    .close-map-btn:hover { background: var(--primary-dark); }

    @media (max-width: 900px) {
        .ct-content-wrap { grid-template-columns: 1fr; }
    }
    @media (max-width: 700px) {
        .form-row { grid-template-columns: 1fr; gap: 20px; }
        .form-card { padding: 30px 24px 34px; }
        .ct-content-wrap { padding: 50px 20px 60px; }
    }
</style>
@endpush

@section('content')

    <section class="ct-hero">
        <div class="ct-hero-content">
            <div class="eyebrow">Get in Touch</div>
            <h1>Contact Us</h1>
            <p>We are here to answer any questions and help you book your perfect wellness experience.</p>
        </div>
    </section>

    <div class="ct-content-wrap">

        <div class="form-card">
            <h2>Send Us a Message</h2>

            @if(session('contact_success'))
                <div class="alert-success">
                    <i class="fas fa-check-circle"></i> {{ session('contact_success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert-danger">
                    <ul>
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('contact.store') }}" method="POST">
                @csrf

                <div class="form-row">
                    <div class="field">
                        <label for="name">Full Name</label>
                        <input id="name" type="text" name="name"
                               value="{{ old('name') }}"
                               placeholder="Jane Doe" required>
                    </div>
                    <div class="field">
                        <label for="email">Email Address</label>
                        <input id="email" type="email" name="email"
                               value="{{ old('email') }}"
                               placeholder="jane@example.com" required>
                    </div>
                </div>

                <div class="field select-wrap" style="margin-bottom:26px;">
                    <label for="subject">Subject</label>
                    <select id="subject" name="subject">
                        <option value="">-- Choose Subject --</option>
                        <option value="General Inquiry" {{ old('subject') == 'General Inquiry' ? 'selected' : '' }}>General Inquiry</option>
                        <option value="Book a Treatment" {{ old('subject') == 'Book a Treatment' ? 'selected' : '' }}>Book a Treatment</option>
                        <option value="Packages & Pricing" {{ old('subject') == 'Packages & Pricing' ? 'selected' : '' }}>Packages & Pricing</option>
                        <option value="Feedback" {{ old('subject') == 'Feedback' ? 'selected' : '' }}>Feedback</option>
                    </select>
                </div>

                <div class="field" style="margin-bottom:32px;">
                    <label for="message">Message</label>
                    <textarea id="message" name="message"
                              placeholder="How can we help you relax today?"
                              required>{{ old('message') }}</textarea>
                </div>

                <button class="btn-send" type="submit">Send Message</button>
            </form>
        </div>

        <div class="info-col">

            <div class="info-panel">
                <h2>Contact Information</h2>

                <div class="info-item">
                    <div class="info-icon"><i class="fa-solid fa-location-dot"></i></div>
                    <div class="info-text">
                        <h3>Address</h3>
                        <p>{!! nl2br(e($contactAddress)) !!}</p>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-icon"><i class="fa-solid fa-phone"></i></div>
                    <div class="info-text">
                        <h3>Phone</h3>
                        <p>{{ $contactPhone }}</p>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-icon"><i class="fa-solid fa-envelope"></i></div>
                    <div class="info-text">
                        <h3>Email</h3>
                        <p>{{ $contactEmail }}</p>
                    </div>
                </div>
            </div>

            <div class="map-card">
                <div class="map-topbar">
                    <h3>Visit Us</h3>
                </div>
                <div class="map-body">
                    <div class="map-embed">
                        <iframe
                            src="{{ $mapEmbed }}"
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                            allowfullscreen>
                        </iframe>
                    </div>

                    <a class="btn-directions"
                       href="{{ $mapDirections }}"
                       target="_blank" rel="noopener">Get Directions</a>

                    <button class="btn-open-map" onclick="openMapPopup()">
                        <i class="fas fa-expand"></i> Open Map
                    </button>
                </div>
            </div>

        </div>
    </div>

    <div class="map-overlay" id="mapOverlay">
        <div class="fullscreen-map-container">
            <button class="close-map-btn" onclick="closeMapPopup()">
                <i class="fas fa-times"></i>
            </button>
            <iframe
                src="{{ $mapEmbed }}"
                allowfullscreen>
            </iframe>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    function openMapPopup() {
        document.getElementById('mapOverlay').classList.add('active');
    }
    function closeMapPopup() {
        document.getElementById('mapOverlay').classList.remove('active');
    }
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeMapPopup();
    });
</script>
@endpush