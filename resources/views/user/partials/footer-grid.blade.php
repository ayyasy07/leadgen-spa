<footer>
    @php
        // Ambil sosmed dari Setting
        $socialLinks = [
            'instagram' => \App\Models\Setting::get('social_instagram'),
            'whatsapp'  => \App\Models\Setting::get('social_whatsapp'),
            'facebook'  => \App\Models\Setting::get('social_facebook'),
            'twitter'   => \App\Models\Setting::get('social_twitter'),
            'linkedin'  => \App\Models\Setting::get('social_linkedin'),
        ];
        $socialLinks = array_filter($socialLinks);

        $socialIcons = [
            'instagram' => 'fa-brands fa-instagram',
            'whatsapp'  => 'fa-brands fa-whatsapp',
            'facebook'  => 'fa-brands fa-facebook-f',
            'twitter'   => 'fa-brands fa-x-twitter',
            'linkedin'  => 'fa-brands fa-linkedin-in',
        ];
        $socialLabels = [
            'instagram' => 'Instagram',
            'whatsapp'  => 'WhatsApp',
            'facebook'  => 'Facebook',
            'twitter'   => 'Twitter / X',
            'linkedin'  => 'LinkedIn',
        ];

        $contactEmail = \App\Models\Setting::get('contact_email');
    @endphp

    <div class="footer-grid">
        <div class="footer-brand">
            <div class="logo">
                <i class="fas fa-spa"></i> LeadGen
            </div>
            <p>Your sanctuary for relaxation and rejuvenation. Experience breathable luxury.</p>
        </div>
        <div>
            <h4>Explore</h4>
            <ul>
                <li><a href="{{ route('home') }}">Home</a></li>
                <li><a href="{{ url('about') }}">About</a></li>
                <li><a href="{{ route('treatments.index') }}">Treatments</a></li>
                <li><a href="{{ url('packages') }}">Packages</a></li>
                <li><a href="{{ url('gallery') }}">Gallery</a></li>
                <li><a href="{{ url('blog') }}">Blog</a></li>
                <li><a href="{{ url('contact') }}">Contact</a></li>
            </ul>
        </div>
        <div>
            <h4>Legal</h4>
            <ul>
                <li><a href="https://share.google/lI8ncPoJ4fvZMXWBT">Privacy Policy</a></li>
                <li><a href="https://share.google/KDigzvKEetS6i7XP2">Terms of Service</a></li>
            </ul>
        </div>
        <div>
            <h4>Connect</h4>
            <ul>
                {{-- Newsletter → section di homepage --}}
                <li><a href="{{ route('home') }}#newsletter">Newsletter</a></li>
            </ul>

            <div class="connect-icons">
                {{-- Email clickable --}}
                @if($contactEmail)
                    <a href="mailto:{{ $contactEmail }}" title="Email: {{ $contactEmail }}" class="ci-mail">
                        <i class="fa-regular fa-envelope"></i>
                    </a>
                @endif

                {{-- Share button → dropdown ke atas --}}
                @if(count($socialLinks) > 0)
                    <div class="share-wrap">
                        <button type="button" class="ci-share" id="footerShareBtn" aria-label="Share" aria-expanded="false">
                            <i class="fa-solid fa-arrow-up-right-from-square"></i>
                        </button>

                        <div class="share-dropdown" id="footerShareDropdown">
                            <div class="share-title">Follow Us</div>
                            @foreach($socialLinks as $key => $url)
                                <a href="{{ $url }}" target="_blank" rel="noopener" class="share-item share-{{ $key }}">
                                    <span class="share-icon">
                                        <i class="{{ $socialIcons[$key] ?? 'fa-solid fa-link' }}"></i>
                                    </span>
                                    <span class="share-label">{{ $socialLabels[$key] ?? ucfirst($key) }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <hr class="footer-divider">
    <div class="footer-bottom">&copy; {{ date('Y') }} LeadGen Spa &amp; Salon. All rights reserved.</div>
</footer>