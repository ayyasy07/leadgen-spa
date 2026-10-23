<footer>
    <div class="container footer-inner">
        <a href="{{ route('home') }}" class="footer-logo"><i class="fas fa-spa"></i> LeadGen</a>
        <div class="footer-copyright">&copy; {{ date('Y') }} LeadGen Spa &amp; Salon. All rights reserved.</div>
    </div>
</footer>

{{-- Lightbox Treatment --}}
<div class="lightbox" id="treatmentLightbox">
    <div class="lightbox-content">
        <img id="treatmentLightboxImage" src="" alt="Treatment Image">
        <button class="lightbox-close" onclick="closeTreatmentLightbox()"><i class="fas fa-times"></i></button>
        <button class="lightbox-prev" onclick="changeTreatmentLightbox(-1)">‹</button>
        <button class="lightbox-next" onclick="changeTreatmentLightbox(1)">›</button>
    </div>
</div>

{{-- Lightbox Gallery --}}
<div class="lightbox" id="galleryLightbox">
    <div class="lightbox-content">
        <img id="galleryLightboxImage" src="" alt="Gallery Image">
        <button class="lightbox-close" onclick="closeGalleryLightbox()"><i class="fas fa-times"></i></button>
        <button class="lightbox-prev" onclick="changeGalleryLightbox(-1)">‹</button>
        <button class="lightbox-next" onclick="changeGalleryLightbox(1)">›</button>
    </div>
</div>