<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\GalleryCategory;

class GalleryController extends Controller
{
    public function index()
    {
        // Semua kategori (buat filter tabs)
        $categories = GalleryCategory::orderBy('sort_order')
            ->orderBy('name')
            ->get();

        // Semua gallery aktif
        $galleries = Gallery::where('is_active', 1)
            ->with('category')
            ->orderBy('sort_order')
            ->orderByDesc('created_at')
            ->get();

        // Siapin data buat JS (mapping di controller, bukan di blade)
        $galleriesJson = $galleries->map(function ($g) {
            return [
                'src'      => $g->image ? asset('storage/'.$g->image) : asset('user/assets/wlcm.jpg'),
                'category' => $g->category->slug ?? '',
                'title'    => $g->title ?? 'Gallery',
            ];
        })->values()->all();

        return view('user.pages.gallery', compact('galleries', 'categories', 'galleriesJson'));
    }
}