<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Gallery;
use App\Models\HeroSlider;
use App\Models\Package;
use App\Models\Testimonial;
use App\Models\Treatment;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    public function index()
    {
        $heroSliders = HeroSlider::where('is_active', 1)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        // ==========================================
        // TREATMENTS — FILTER is_active
        // Cuma treatment aktif yang dipake di homepage
        // ==========================================
        $treatments = Treatment::with('prices')
            ->where('is_active', 1)
            ->orderByDesc('is_featured')
            ->orderBy('name')
            ->get();

        // ==========================================
        // KATEGORI — prioritas: kategori yang punya featured
        // Kategori yang semua treatment-nya inactive OTOMATIS ga muncul
        // (karena $treatments udah di-filter is_active = 1)
        // ==========================================
        $featuredCats = $treatments
            ->where('is_featured', true)
            ->pluck('category')
            ->unique()
            ->values();

        $otherCats = $treatments
            ->pluck('category')
            ->unique()
            ->reject(function ($cat) use ($featuredCats) {
                return $featuredCats->contains($cat);
            })
            ->values();

        $treatmentCategories = $featuredCats
            ->merge($otherCats)
            ->take(4)
            ->values();

        // ==========================================
        // GALLERIES — Featured dulu (FIFO), terus fallback latest active
        // Max = Gallery::maxFeatured() (default 10 dari config)
        // ==========================================
        $maxFeatured = Gallery::maxFeatured();

        $galleries = Gallery::where('is_active', 1)
            ->where('is_featured', 1)
            ->orderBy('featured_at')        // FIFO — paling lama dulu
            ->orderByDesc('created_at')     // fallback
            ->take($maxFeatured)
            ->get();

        // Kalo featured < max → fallback latest active (non-featured)
        if ($galleries->count() < $maxFeatured) {
            $needed     = $maxFeatured - $galleries->count();
            $excludeIds = $galleries->pluck('id')->all();

            $fallback = Gallery::where('is_active', 1)
                ->where('is_featured', 0)
                ->when(!empty($excludeIds), function ($q) use ($excludeIds) {
                    $q->whereNotIn('id', $excludeIds);
                })
                ->orderBy('sort_order')
                ->orderByDesc('created_at')
                ->take($needed)
                ->get();

            $galleries = $galleries->merge($fallback);
        }

        // ==========================================
        // PACKAGES — Featured (max 3, FIFO) + fallback latest active
        // ==========================================
        $featuredPackages = Package::where('is_active', 1)
            ->where('is_featured', 1)
            ->orderBy('featured_at')
            ->take(3)
            ->get();

        if ($featuredPackages->count() < 3) {
            $needed = 3 - $featuredPackages->count();
            $excludeIds = $featuredPackages->pluck('id')->all();

            $fallback = Package::where('is_active', 1)
                ->where('is_featured', 0)
                ->when(!empty($excludeIds), function ($q) use ($excludeIds) {
                    $q->whereNotIn('id', $excludeIds);
                })
                ->latest()
                ->take($needed)
                ->get();

            $featuredPackages = $featuredPackages->merge($fallback);
        }

        $testimonials = Testimonial::where('is_active', 1)
            ->latest()
            ->take(3)
            ->get();

        $blogs = Blog::where('is_published', 1)
            ->orderByDesc('published_at')
            ->take(3)
            ->get();

        $formatHeroText = function ($text) {
            if (empty($text)) return '';
            $lines = preg_split('/\r\n|\r|\n/', trim($text));
            $lines = array_values(array_filter(array_map('trim', $lines)));
            if (empty($lines)) return '';
            $first = array_shift($lines);
            $html  = '<span>' . e($first) . '</span>';
            foreach ($lines as $line) {
                $html .= '<br>' . e($line);
            }
            return $html;
        };

        $formatHeroEyebrow = function ($text) {
            if (empty($text)) return '';
            return '<span>' . e(trim($text)) . '</span>';
        };

        $heroSlidesJson = $heroSliders->map(function ($s) {
            return [
                'image'   => $s->image ? asset('storage/'.$s->image) : asset('user/assets/lilinbesar.jpe'),
                'eyebrow' => trim((string) $s->eyebrow),
                'title'   => trim((string) $s->title),
            ];
        })->values()->all();

        // ==========================================
        // TREATMENT DATA JSON — featured dulu, fallback aktif pertama
        // ==========================================
        $treatmentDataJson = [];
        foreach ($treatmentCategories as $cat) {
            $catItems = $treatments->where('category', $cat)->values();

            // Prioritas: featured AKTIF → fallback treatment aktif pertama
            $ta = $catItems->first(function ($t) {
                return $t->is_featured;
            }) ?? $catItems->first();

            if (!$ta) continue;

            $img1 = $ta->image
                ? asset('storage/'.$ta->image)
                : asset('user/assets/sep.jpe');

            $img2 = $ta->image_2
                ? asset('storage/'.$ta->image_2)
                : asset('user/assets/download.jpe');

            $treatmentDataJson[Str::slug($cat)] = [
                'title'    => $ta->name ?? '',
                'desc'     => $ta->description ?? '',
                'img1'     => $img1,
                'img2'     => $img2,
                'kategori' => Str::slug($cat),
            ];
        }

        $galleryImagesJson = $galleries->map(function ($g) {
            return asset('storage/'.$g->image);
        })->values()->all();

        return view('user.pages.index', compact(
            'heroSliders',
            'treatments',
            'treatmentCategories',
            'galleries',
            'featuredPackages',
            'testimonials',
            'blogs',
            'heroSlidesJson',
            'treatmentDataJson',
            'galleryImagesJson',
            'formatHeroText',
            'formatHeroEyebrow'
        ));
    }
}