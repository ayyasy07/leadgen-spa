<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;

class BlogController extends Controller
{
    /**
     * List blog published + filter kategori.
     */
    public function index()
    {
        $categories = BlogCategory::orderBy('sort_order')->orderBy('name')->get();

        $kategori = request('kategori');

        $blogs = Blog::with('category')
            ->where('is_published', 1)
            ->when($kategori, function ($q) use ($kategori) {
                $q->whereHas('category', function ($sq) use ($kategori) {
                    $sq->where('slug', $kategori);
                });
            })
            ->orderBy('sort_order')
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->paginate(9)
            ->appends(request()->query());

        return view('user.pages.blog', compact('blogs', 'categories'));
    }

    /**
     * Detail blog by slug.
     */
    public function show($slug)
    {
        $blog = Blog::with('category')
            ->where('slug', $slug)
            ->where('is_published', 1)
            ->firstOrFail();

        // Related — dari kategori sama
        $related = Blog::where('is_published', 1)
            ->where('id', '!=', $blog->id)
            ->when($blog->category_id, fn ($q) => $q->where('category_id', $blog->category_id))
            ->orderByDesc('published_at')
            ->take(3)
            ->get();

        // Fallback: kalo related kosong
        if ($related->count() < 3) {
            $related = Blog::where('is_published', 1)
                ->where('id', '!=', $blog->id)
                ->orderByDesc('published_at')
                ->take(3)
                ->get();
        }

        return view('user.pages.blog-detail', compact('blog', 'related'));
    }
}