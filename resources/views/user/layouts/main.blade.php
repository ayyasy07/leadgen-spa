<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'LeadGen Spa & Salon')</title>
    <meta name="description" content="@yield('meta_description', 'Premium spa & salon experience — relax, refresh, rejuvenate.')">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @include('user.partials.styles')

    @stack('styles')
</head>
<body>

    {{-- Header --}}
    @section('header')
        @include('user.partials.header')
    @show

    {{-- Content --}}
    @yield('content')

    {{-- Footer --}}
    @if(trim($__env->yieldContent('footer_type')) === 'simple')
        @include('user.partials.footer-simple')
    @else
        @include('user.partials.footer-grid')
    @endif

    {{-- Lightbox Global --}}
    <div class="lightbox" id="treatmentLightbox">
        <div class="lightbox-content">
            <img id="treatmentLightboxImage" src="" alt="Treatment Image">
            <button class="lightbox-close" onclick="closeTreatmentLightbox()"><i class="fas fa-times"></i></button>
            <button class="lightbox-prev" onclick="changeTreatmentLightbox(-1)">‹</button>
            <button class="lightbox-next" onclick="changeTreatmentLightbox(1)">›</button>
        </div>
    </div>

    <div class="lightbox" id="galleryLightbox">
        <div class="lightbox-content">
            <img id="galleryLightboxImage" src="" alt="Gallery Image">
            <button class="lightbox-close" onclick="closeGalleryLightbox()"><i class="fas fa-times"></i></button>
            <button class="lightbox-prev" onclick="changeGalleryLightbox(-1)">‹</button>
            <button class="lightbox-next" onclick="changeGalleryLightbox(1)">›</button>
        </div>
    </div>

    {{-- Feather Icons --}}
    <script src="https://unpkg.com/feather-icons"></script>
    <script>
        (function() {
            function renderFeather() {
                if (typeof feather !== 'undefined' && feather.replace) {
                    try { feather.replace({ 'stroke-width': 2 }); } catch (e) {}
                }
            }
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', renderFeather);
            } else {
                renderFeather();
            }
            setTimeout(renderFeather, 300);
            window.addEventListener('load', renderFeather);
        })();
    </script>

    @include('user.partials.scripts')

    @stack('scripts')
    {{-- Chatbot --}}
    @include('user.partials.chatbot')
</body>
</html>