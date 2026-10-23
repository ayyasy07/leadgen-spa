@extends('user.layouts.main')

@section('title', $blog->meta_title ?? $blog->title . ' — LeadGen Spa')
@section('meta_description', $blog->meta_description ?? Str::limit(strip_tags($blog->content), 160))
@section('footer_type', 'grid')

@section('header')
    @include('user.partials.header-detail', ['backUrl' => url('blog')])
@endsection

@push('styles')
<style>
    /* HERO */
    .hero {
        position: relative;
        height: 440px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        background: linear-gradient(180deg, rgba(20,14,12,.15), rgba(15,10,9,.25)),
            url('{{ $blog->image ? asset("storage/".$blog->image) : asset("user/assets/lilinbesar.jpe") }}') center/cover no-repeat;
        color: #fff;
    }
    .hero-content {
        max-width: 720px;
        padding: 0 24px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        margin: 0 auto;
    }
    .hero .eyebrow {
        color: var(--coral, #f76c6c);
        font-weight: 700;
        font-size: .75rem;
        letter-spacing: 2px;
        text-transform: uppercase;
        margin-bottom: 14px;
        text-align: center;
    }
    .hero h1 {
        font-size: 2.6rem;
        line-height: 1.15;
        margin: 0 0 18px;
        font-weight: 700;
        letter-spacing: -.5px;
        text-align: center;
        max-width: 680px;
}
    .hero p {
        font-size: 1rem;
        font-weight: 400;
        color: rgba(255,255,255,.88);
        line-height: 1.6;
        margin: 0 0 20px;
        text-align: center;
        max-width: 600px;
    }
    .hero-meta {
        font-size: .8rem;
        font-weight: 500;
        letter-spacing: .5px;
        color: #fff;
        text-align: center;
    }
    .hero-meta-sep {
        display: inline-block;
        margin: 0 8px;
        opacity: .7;
    }
    /* ARTICLE */
    .article-shell {
        max-width: 800px;
        margin: 0 auto;
        padding: 0 24px;
        position: relative;
    }
    .article-card {
        background: #fff;
        border-radius: 22px 22px 0 0;
        margin-top: -90px;
        position: relative;
        z-index: 2;
        padding: 52px 56px 40px;
        box-shadow: 0 -6px 30px rgba(20,15,12,.06);
    }

    /* CONTENT */
    .article-card .content-body {
        color: #5b5654;
        font-size: .95rem;
        line-height: 2;
        overflow-wrap: break-word;
    }
    .article-card .content-body > *:first-child { margin-top: 0 !important; }
    .article-card .content-body > *:last-child  { margin-bottom: 0 !important; }

    /* H1 OTOMATIS */
    .article-card .content-body .article-title {
        color: var(--ink, #232020);
        font-weight: 700;
        font-size: 2rem;
        line-height: 1.3;
        letter-spacing: -.5px;
        margin: 0 0 16px;
        padding-bottom: 12px;
        border-bottom: 1px solid rgba(148,163,184,.2);
    }

    .article-card .content-body p {
        margin: 0 0 14px;
        line-height: 2;
    }
    .article-card .content-body h1,
    .article-card .content-body h2,
    .article-card .content-body h3,
    .article-card .content-body h4 {
        color: var(--ink, #232020);
        font-weight: 700;
        letter-spacing: -.3px;
        line-height: 1.5;
    }
    .article-card .content-body h1 { font-size: 1.7rem; margin: 22px 0 10px; }
    .article-card .content-body h2 { font-size: 1.4rem; margin: 36px 0 16px; }
    .article-card .content-body h3 { font-size: 1.15rem; margin: 20px 0 8px; }
    .article-card .content-body h4 { font-size: 1rem; margin: 18px 0 6px; }

    .article-card .content-body blockquote {
        background: #f6f3f8;
        border-left: 3px solid var(--coral, #f76c6c);
        border-radius: 6px;
        margin: 16px 0;
        padding: 16px 22px;
    }
    .article-card .content-body blockquote p {
        font-style: italic;
        color: #4d4948;
        font-size: .95rem;
        line-height: 1.7;
        margin: 0;
    }

    /* IMG */
    .article-card .content-body img {
        max-width: 100%;
        height: auto;
        border-radius: 12px;
        margin: 16px 0;
        display: block;
    }

    /* GALLERY — full width, di bawah H1 */
    .article-card .content-body .article-gallery {
        margin: 24px 0 20px;
    }
    .article-card .content-body .article-gallery img {
        border-radius: 12px;
        width: 100%;
        height: auto;
        max-height: 500px;
        object-fit: cover;
        margin: 0 0 16px;
        display: block;
        cursor: pointer;
        transition: transform .25s;
    }
    .article-card .content-body .article-gallery img:last-child {
        margin-bottom: 0;
    }
    .article-card .content-body .article-gallery img:hover {
        transform: scale(1.01);
    }

    /* FIGURE + FIGCAPTION */
    .article-card .content-body figure {
        margin: 28px 0 10px;
        display: block;
    }
    .article-card .content-body figure img {
        border-radius: 12px;
        width: 100%;
        height: auto;
        margin: 0;
        display: block;
    }
    .article-card .content-body figcaption {
        text-align: center;
        font-style: italic;
        color: var(--gray, #777);
        font-size: .85rem;
        margin-top: 12px;
    }

    /* LIST */
    .article-card .content-body ul,
    .article-card .content-body ol {
        margin: 0 0 14px 24px;
        padding: 0;
    }
    .article-card .content-body li {
        margin-bottom: 4px;
        line-height: 2;
    }
    .article-card .content-body a {
        color: var(--primary-dark, #8a0f1a);
        text-decoration: underline;
    }

    /* EMOJI */
    .article-card .content-body .np-emoji {
        font-size: 1.15em;
        line-height: 1;
        margin-right: 4px;
        vertical-align: middle;
        display: inline-block;
    }

    /* FOOTER */
    .article-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-top: 1px solid var(--line, #e7e2df);
        margin-top: 30px;
        padding-top: 22px;
        flex-wrap: wrap;
        gap: 16px;
    }
    .tags { display: flex; gap: 10px; flex-wrap: wrap; }
    .tag {
        background: var(--cream, #f4f1ef);
        color: #4a4544;
        font-size: .75rem;
        font-weight: 600;
        padding: 7px 14px;
        border-radius: 20px;
        letter-spacing: .3px;
    }
    .share {
        display: flex;
        align-items: center;
        gap: 14px;
        color: #4a4544;
        font-size: .8rem;
        font-weight: 600;
    }
    .share .icons { display: flex; gap: 12px; }
    .share button {
        background: none;
        border: none;
        padding: 0;
        color: #4a4544;
        cursor: pointer;
        font-size: 16px;
        transition: .25s;
    }
    .share button:hover { color: var(--coral, #f76c6c); }
    .share button svg {
        width: 16px;
        height: 16px;
        stroke: currentColor;
        fill: none;
        stroke-width: 2;
        stroke-linecap: round;
        stroke-linejoin: round;
        display: block;
    }
    .share button.copied { color: #28a745; }

    /* SOFT CTA */
    .soft-cta {
        text-align: center;
        padding: 50px 0;
        max-width: 800px;
        margin: 0 auto;
    }
    .soft-cta p {
        font-size: 15px;
        font-weight: 500;
        color: var(--gray, #777);
        line-height: 1.6;
    }
    .soft-cta a {
        display: inline-block;
        margin-top: 15px;
        font-size: 13px;
        font-weight: 700;
        color: var(--primary-dark, #8a0f1a);
        text-transform: uppercase;
        letter-spacing: .5px;
        border-bottom: 2px solid transparent;
        transition: .3s;
        text-decoration: none;
    }
    .soft-cta a:hover {
        border-bottom-color: var(--primary-dark, #8a0f1a);
        color: var(--primary, #a3141f);
    }

    /* MORE TO READ */
    .more-section {
        background: var(--cream, #f4f1ef);
        padding: 70px 0 80px;
    }
    .more-heading { text-align: center; margin-bottom: 44px; }
    .more-heading h2 {
        font-size: 1.7rem;
        font-weight: 700;
        margin: 0 0 12px;
        color: var(--ink, #232020);
    }
    .more-heading .underline {
        width: 46px;
        height: 3px;
        background: var(--coral, #f76c6c);
        margin: 0 auto;
        border-radius: 3px;
    }
    .more-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 24px;
        max-width: 1180px;
        margin: 0 auto;
        padding: 0 32px;
    }
    .more-card {
        background: #fff;
        border-radius: 14px;
        overflow: hidden;
        transition: .3s ease;
        text-decoration: none;
        color: inherit;
        display: block;
    }
    .more-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,.05);
    }
    .more-card img {
        width: 100%;
        height: 160px;
        object-fit: cover;
    }
    .more-card-body { padding: 18px 20px 22px; }
    .more-card .cat {
        color: var(--coral-dark, #ea5252);
        font-size: .72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .5px;
        margin-bottom: 6px;
        display: block;
    }
    .more-card h3 {
        font-size: 1rem;
        font-weight: 700;
        margin: 0 0 8px;
        line-height: 1.4;
        color: var(--ink, #232020);
    }
    .more-card p {
        margin: 0;
        font-size: .85rem;
        color: #8a8482;
        line-height: 1.6;
    }

    /* LIGHTBOX */
    .np-lightbox {
        position: fixed; inset: 0;
        background: rgba(0,0,0,.92);
        z-index: 9999;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 40px;
        cursor: zoom-out;
        backdrop-filter: blur(4px);
    }
    .np-lightbox.show { display: flex; }
    .np-lightbox img {
        max-width: 90vw;
        max-height: 90vh;
        border-radius: 10px;
        box-shadow: 0 30px 60px rgba(0,0,0,.5);
    }
    .np-lightbox-close {
        position: absolute;
        top: 20px;
        right: 24px;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: rgba(255,255,255,.15);
        border: none;
        color: #fff;
        font-size: 20px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .np-lightbox-close:hover { background: rgba(255,255,255,.25); }

    /* TOAST */
    .copy-toast {
        position: fixed;
        bottom: 30px;
        left: 50%;
        transform: translateX(-50%) translateY(20px);
        background: #1e293b;
        color: #fff;
        padding: 12px 20px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        box-shadow: 0 10px 30px rgba(0,0,0,.3);
        opacity: 0;
        pointer-events: none;
        transition: all .3s ease;
        z-index: 9999;
    }
    .copy-toast.show {
        opacity: 1;
        transform: translateX(-50%) translateY(0);
    }

    @media (max-width: 900px) {
        .hero { height: 380px; }
        .hero h1 { font-size: 1.9rem; }
        .article-card { padding: 36px 28px 32px; }
        .more-grid { grid-template-columns: 1fr; padding: 0 24px; }
    }
</style>
@endpush

@section('content')

    {{-- HERO --}}
    <section class="hero">
        <div class="hero-content">
            <div class="eyebrow">{{ $blog->category->name ?? 'Wellness Guide' }}</div>
            <h1>{{ $blog->title }}</h1>
            @if($blog->excerpt)
                <p>{{ $blog->excerpt }}</p>
            @endif
            <div class="hero-meta">
                {{ $blog->published_at ? $blog->published_at->format('M d, Y') : $blog->created_at->format('M d, Y') }}
                @if($blog->show_author && $blog->author_name)
                    <span class="hero-meta-sep">•</span>
                    By {{ $blog->author_name }}
                @endif
            </div>
        </div>
    </section>

    {{-- ARTICLE --}}
    <div class="article-shell">
        <article class="article-card">

            {{-- CONTENT: H1 AUTO + GALLERY + CONTENT --}}
            <div class="content-body">

                {{-- H1 OTOMATIS dari judul --}}
                <h1 class="article-title">{{ $blog->title }}</h1>

                {{-- GALLERY (di bawah H1) --}}
                @if($blog->gallery && is_array($blog->gallery) && count($blog->gallery))
                    <div class="article-gallery">
                        @foreach($blog->gallery as $img)
                            <img src="{{ asset('storage/'.$img) }}"
                                 alt="{{ $blog->title }}"
                                 onclick="npOpenLightbox(this.src)">
                        @endforeach
                    </div>
                @endif

                {{-- CONTENT CKEditor (H1 di-strip jadi H2 kalo ada) --}}
                @php
                    $content = $blog->content;
                    $content = preg_replace('/<h1([^>]*)>(.*?)<\/h1>/i', '<h2$1>$2</h2>', $content);
                @endphp
                {!! $content !!}

            </div>

            {{-- FOOTER --}}
            <div class="article-footer">
                <div class="tags">
                    @if($blog->category)
                        <span class="tag">{{ $blog->category->name }}</span>
                    @endif
                    @if($blog->keywords)
                        @foreach(array_filter(array_map('trim', explode(',', $blog->keywords))) as $kw)
                            <span class="tag">{{ $kw }}</span>
                        @endforeach
                    @endif
                </div>
                <div class="share">
                    <span>Share:</span>
                    <div class="icons">
                        <button type="button" id="copyLinkBtn" title="Copy Link" aria-label="Copy Link">
                            <svg viewBox="0 0 24 24"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                        </button>
                        <button type="button" id="copyTextBtn" title="Copy Text" aria-label="Copy Text">
                            <svg viewBox="0 0 24 24"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                        </button>
                    </div>
                </div>
            </div>

        </article>
    </div>

    {{-- SOFT CTA --}}
    <div class="soft-cta">
        <p>Ready to experience total relaxation?</p>
        <a href="{{ route('treatments.index') }}">Reserve Your Treatment →</a>
    </div>

    {{-- MORE TO READ --}}
    @if($related->count())
        <section class="more-section">
            <div class="more-heading">
                <h2>More to Read</h2>
                <div class="underline"></div>
            </div>
            <div class="more-grid">
                @foreach($related as $r)
                    <a href="{{ route('blog.show', $r->slug) }}" class="more-card">
                        <img src="{{ $r->image ? asset('storage/'.$r->image) : asset('user/assets/wlcm.jpg') }}"
                             alt="{{ $r->title }}">
                        <div class="more-card-body">
                            <span class="cat">{{ $r->category->name ?? 'Article' }}</span>
                            <h3>{{ $r->title }}</h3>
                            <p>{{ Str::limit($r->excerpt ?: strip_tags($r->content), 100) }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    {{-- LIGHTBOX --}}
    <div class="np-lightbox" id="npLightbox" onclick="npCloseLightbox()">
        <button type="button" class="np-lightbox-close" onclick="event.stopPropagation();npCloseLightbox();">×</button>
        <img src="" alt="preview" id="npLightboxImg" onclick="event.stopPropagation();">
    </div>

    {{-- TOAST --}}
    <div class="copy-toast" id="copyToast">Link copied!</div>

@endsection

@push('scripts')
<script>
(function () {
    'use strict';

    window.npOpenLightbox = function (src) {
        var lb = document.getElementById('npLightbox');
        var img = document.getElementById('npLightboxImg');
        if (!lb || !img) return;
        img.src = src;
        lb.classList.add('show');
        document.body.style.overflow = 'hidden';
    };
    window.npCloseLightbox = function () {
        var lb = document.getElementById('npLightbox');
        if (!lb) return;
        lb.classList.remove('show');
        document.body.style.overflow = '';
    };
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') window.npCloseLightbox();
    });

    var toast = document.getElementById('copyToast');
    var toastTimer = null;
    function showToast(msg) {
        if (!toast) return;
        toast.textContent = msg;
        toast.classList.add('show');
        clearTimeout(toastTimer);
        toastTimer = setTimeout(function () { toast.classList.remove('show'); }, 2000);
    }

    function copyToClipboard(text) {
        if (navigator.clipboard && window.isSecureContext) {
            return navigator.clipboard.writeText(text);
        }
        return new Promise(function (resolve, reject) {
            var ta = document.createElement('textarea');
            ta.value = text;
            ta.style.position = 'fixed';
            ta.style.left = '-9999px';
            document.body.appendChild(ta);
            ta.focus();
            ta.select();
            try {
                var ok = document.execCommand('copy');
                document.body.removeChild(ta);
                ok ? resolve() : reject(new Error('execCommand failed'));
            } catch (err) {
                document.body.removeChild(ta);
                reject(err);
            }
        });
    }

    function bindCopy(btnId, getText, successMsg, failMsg) {
        var btn = document.getElementById(btnId);
        if (!btn) return;
        btn.addEventListener('click', function () {
            copyToClipboard(getText())
                .then(function () {
                    showToast(successMsg);
                    btn.classList.add('copied');
                    setTimeout(function () { btn.classList.remove('copied'); }, 2000);
                })
                .catch(function (err) {
                    console.error('Copy Failed:', err);
                    showToast(failMsg);
                });
        });
    }

    bindCopy('copyLinkBtn', function () { return window.location.href; }, 'Link Successfully Copied!', 'Failed to Copy Link');
    bindCopy('copyTextBtn', function () {
        var el = document.querySelector('.content-body');
        return el ? el.innerText.trim() : '';
    }, 'Text Successfully Copied!', 'Failed to Copy Text');
})();
</script>
@endpush