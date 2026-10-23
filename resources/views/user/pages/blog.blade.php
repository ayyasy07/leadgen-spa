@extends('user.layouts.main')

@section('title', 'Wellness Blog — LeadGen Spa')
@section('meta_description', 'Insights, tips, and stories for a balanced life.')
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
    .page-hero-content {
        max-width: 640px;
        padding: 0 24px;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }
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
        text-align: center;
    }
    .page-hero p {
        font-size: 16px;
        color: rgba(255, 255, 255, 0.9);
        line-height: 1.6;
        text-align: center;
    }

    /* CATEGORY FILTER */
    .cat-filter-wrap {
        max-width: 1180px;
        margin: 0 auto;
        padding: 52px 32px 0;
    }
    .cat-filter {
        display: flex;
        justify-content: center;
        gap: 24px;
        flex-wrap: wrap;
    }
    .cat-item {
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
    .cat-item:hover { color: var(--primary-dark); }
    .cat-item.active { color: var(--maroon); }
    .cat-item.active::after {
        content: "";
        position: absolute;
        left: 0; right: 0; bottom: -1px;
        height: 2px;
        background: var(--maroon);
    }

    .blog-section { padding: 44px 0 80px; }
    .blog-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 24px;
        max-width: 1180px;
        margin: 0 auto;
        padding: 0 32px;
        align-items: stretch;   
    }
    .blog-card {
        background: #fff;
        border-radius: 14px;
        overflow: hidden;
        border: 1px solid var(--line);
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        transition: transform 0.3s, box-shadow 0.3s;
        text-decoration: none;
        color: inherit;
        display: flex;              
        flex-direction: column;
        height: 100%;               
    }
    .blog-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
    }
    .blog-card .img-wrap {
        height: 200px;
        flex: 0 0 200px;            
        overflow: hidden;
    }
    .blog-card .img-wrap img {
        width: 100%; height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    .blog-card:hover .img-wrap img { transform: scale(1.05); }

    .blog-card .content {
        padding: 24px 24px 28px;
        display: flex;              
        flex-direction: column;
        flex: 1;                    
    }
    .blog-card .category {
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.6px;
        text-transform: uppercase;
        color: var(--primary-dark);
        margin-bottom: 10px;
        display: block;
        flex: 0 0 auto;             
    }
    .blog-card .headline-md {
        font-size: 18px;
        font-weight: 700;
        line-height: 1.4;
        margin-bottom: 12px;
        color: var(--ink);
        flex: 0 0 auto;             
        display: -webkit-box;
        -webkit-line-clamp: 2;      
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .blog-card .body-sm {
        font-size: 14px;
        line-height: 1.7;
        color: var(--gray);
        margin-bottom: 18px;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        flex: 1;                    
    }
    .blog-card .footer-meta {
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-top: 1px solid var(--line);
        padding-top: 14px;
        margin-top: auto;           
        flex: 0 0 auto;
    }
    .blog-card .footer-meta .date {
        font-size: 12px;
        font-weight: 500;
        color: var(--gray);
    }
    .blog-card .footer-meta .date i {
        margin-right: 4px;
        font-size: 11px;
    }
    .blog-card .read-more {
        font-size: 12px;
        font-weight: 700;
        color: var(--primary-dark);
        letter-spacing: 0.05em;
        text-transform: uppercase;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: 0.3s ease;
        text-decoration: none;
    }
    .blog-card .read-more:hover {
        gap: 12px;
        color: var(--maroon);
    }
    .blog-card .read-more i { font-size: 11px; }

    .empty-state {
        grid-column: 1 / -1;
        text-align: center;
        padding: 80px 20px;
        color: var(--gray);
        font-size: 14px;
    }
    .empty-state i {
        font-size: 48px;
        color: #ddd;
        margin-bottom: 16px;
        display: block;
    }

    .pagination-wrap {
        margin-top: 40px;
        display: flex;
        justify-content: center;
    }

    @media (max-width: 900px) {
        .blog-grid { grid-template-columns: 1fr 1fr; padding: 0 24px; }
    }
    @media (max-width: 600px) {
        .blog-grid { grid-template-columns: 1fr; padding: 0 20px; }
    }
</style>
@endpush

@section('content')

    {{-- HERO --}}
    <section class="page-hero">
        <div class="page-hero-content">
            <div class="eyebrow">Wellness Blog</div>
            <h1>Insights, tips, and stories</h1>
            <p>for a balanced life.</p>
        </div>
    </section>

    {{-- CATEGORY FILTER --}}
    @if($categories->count())
        <div class="cat-filter-wrap">
            <div class="cat-filter">
                <a href="{{ route('blog.index') }}"
                   class="cat-item {{ !request('kategori') ? 'active' : '' }}">
                    All
                </a>
                @foreach($categories as $cat)
                    <a href="{{ route('blog.index', ['kategori' => $cat->slug]) }}"
                       class="cat-item {{ request('kategori') === $cat->slug ? 'active' : '' }}">
                        {{ strtoupper($cat->name) }}
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    {{-- BLOG GRID --}}
    <section class="blog-section">
        <div class="blog-grid">

            @forelse($blogs as $b)
                <a href="{{ route('blog.show', $b->slug) }}" class="blog-card">
                    <div class="img-wrap">
                        <img src="{{ $b->image ? asset('storage/'.$b->image) : asset('user/assets/wlcm.jpg') }}"
                             alt="{{ $b->title }}"
                             onerror="this.src='{{ asset('user/assets/wlcm.jpg') }}'">
                    </div>
                    <div class="content">
                        <span class="category">{{ $b->category->name ?? 'Uncategorized' }}</span>
                        <h3 class="headline-md">{{ $b->title }}</h3>
                        <p class="body-sm">
                            {{ $b->excerpt ?: Str::limit(strip_tags($b->content), 150) }}
                        </p>
                        <div class="footer-meta">
                            <span class="date">
                                <i class="far fa-calendar-alt"></i>
                                {{ $b->published_at ? $b->published_at->format('M d, Y') : $b->created_at->format('M d, Y') }}
                            </span>
                            <span class="read-more">
                                Read More <i class="fas fa-arrow-right"></i>
                            </span>
                        </div>
                    </div>
                </a>
            @empty
                <div class="empty-state">
                    <i class="fas fa-file-alt"></i>
                    There are no blog posts yet.
                </div>
            @endforelse

        </div>

        @if($blogs->hasPages())
            <div class="pagination-wrap">
                {{ $blogs->appends(request()->query())->links() }}
            </div>
        @endif
    </section>

@endsection